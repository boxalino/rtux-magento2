<?php
namespace Boxalino\RealTimeUserExperience\Helper\Js;

use Boxalino\RealTimeUserExperience\Helper\Api\Configuration as ApiConfiguration;
use Boxalino\RealTimeUserExperience\Helper\Configuration as GenericConfiguration;
use Boxalino\RealTimeUserExperienceApi\Service\Api\ApiCookieSubscriber;
use Boxalino\RealTimeUserExperienceApi\Service\Api\Util\ConfigurationInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use Ramsey\Uuid\Uuid;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\Stdlib\CookieManagerInterface;

/**
 * Class Configuration
 * Configurations defined for the REST API JS requests (tracker, autocomplete, etc)
 *
 * @package Boxalino\RealTimeUserExperience\Helper\Js
 */
class Configuration extends \Magento\Framework\App\Helper\AbstractHelper
{

    /**
     * Application json header.
     */
    const APPLICATION_TRACKER_HEADER = ['Content-Type' => 'text/plain'];

    /**
     * @var \Boxalino\RealTimeUserExperience\Helper\Api\Configuration
     */
    protected $apiConfiguration;

    /**
     * @var \Boxalino\RealTimeUserExperience\Helper\Configuration
     */
    protected $genericConfiguration;

    /**
     * @var Client
     */
    private $trackClient;

    /**
     * @var CookieManagerInterface
     */
    protected $cookieManager;

    public function __construct(
        GenericConfiguration $genericConfiguration,
        ApiConfiguration $apiConfiguration,
        CookieManagerInterface $cookieManager,
        Context $context
    ) {
        parent::__construct($context);
        $this->apiConfiguration = $apiConfiguration;
        $this->genericConfiguration = $genericConfiguration;
        $this->cookieManager = $cookieManager;
        $this->trackClient = new Client(
            [
                'base_uri'=>$this->_httpHeader->getRequestUri(),
                'headers'=>[
                    'user-agent'=>$this->_httpHeader->getHttpUserAgent(),
                    'user-language'=>$this->_httpHeader->getHttpAcceptLanguage()
                ]
            ]
        );
    }

    /**
     * Function that submits an api track request via the guzzle client
     *
     * @param string $event
     * @param array $params
     * @return void
     */
    public function track(string $event, array $params) : void
    {
        $params['_a'] = $this->getAccount();
        $params['_ev'] = $event;
        $params['_t'] = round(microtime(true) * 1000);
        $params['_ln'] = $this->getLanguage();
        $params['_bxs'] = $this->cookieManager->getCookie(ApiCookieSubscriber::BOXALINO_API_COOKIE_SESSION);
        $params['_bxv'] = $this->cookieManager->getCookie(ApiCookieSubscriber::BOXALINO_API_COOKIE_VISITOR);

        try {
            if($this->isTest())
            {
                $this->_logger->info(json_encode($params));
            }
            $profileId = $params['_bxv'];
            if(is_null($profileId))
            {
                $this->_logger->notice("[Boxalino][track] missing _bxv detail for $event: " . json_encode($params));
                $profileId = Uuid::uuid4()->toString();
            }

            $this->trackClient->send(
                new Request(
                    'POST',
                    $this->getServerUrl($profileId),
                    self::APPLICATION_TRACKER_HEADER,
                    json_encode(['events' => [$params]])
                )
            );
        } catch (\Throwable $exception) {
            $this->_logger->warning("Boxalino API Tracker $event not delivered: " . $exception->getMessage());
        }
    }

    /**
     * Tracker URL for frontend/JS requests
     * (the full endpoint construct is part of the JS RtuxApiHelper element https://github.com/boxalino/rtux-magento2/blob/master/view/frontend/web/js/rtuxApiHelper.js)
     *
     * @return string
     */
    public function getTrackerUrl() : string
    {
        if($this->isDev() || $this->isTest())
        {
            return ConfigurationInterface::BOXALINO_API_TRACKING_STAGE;
        }

        return ConfigurationInterface::BOXALINO_API_TRACKING_PRODUCTION;
    }

    /**
     * @return string
     */
    public function getRtiUrl() : string
    {
        if($this->isDev() || $this->isTest())
        {
            return ConfigurationInterface::BOXALINO_API_RTI_STAGE;
        }

        return ConfigurationInterface::BOXALINO_API_RTI_PRODUCTION;
    }

    /**
     * Tracker URL for server-side requests
     *
     * @param string $session
     * @return string
     */
    public function getServerUrl(string $session) : string
    {
        if($this->isDev() || $this->isTest())
        {
            return ConfigurationInterface::BOXALINO_API_SERVER_STAGE . "?_bxv=" . $session;
        }

        return ConfigurationInterface::BOXALINO_API_SERVER_PRODUCTION . "?_bxv=" . $session;
    }

    /**
     * Check if tracker is active
     *
     * @return bool
     */
    public function isTrackerActive() : bool
    {
        $value = $this->scopeConfig->getValue('rtux/tracker/status',\Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        if(empty($value))
        {
            return false;
        }

        return (bool)$value;
    }

    /**
     * Check if rti is active
     *
     * @return bool
     */
    public function isRtiActive() : bool
    {
        $value = $this->scopeConfig->getValue('rtux/rti/status',\Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        if(empty($value))
        {
            return false;
        }

        return (bool)$value;
    }

    /**
     * Server-side API key to allow access without a secret
     * (only allowed for autocomplete requests)
     *
     * @return string
     */
    public function getKey() : ?string
    {
        $value = $this->scopeConfig->getValue('rtux/api/api_server_key', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        if(empty($value))
        {
            return null;
        }

        return $value;
    }

    /**
     * @return string
     */
    public function getAccount() : string
    {
        return $this->apiConfiguration->getUsername();
    }

    /**
     * @return string
     */
    public function getApiKey() : string
    {
        return $this->apiConfiguration->getApiKey();
    }

    /**
     * @return string
     */
    public function getApiSecret() : string
    {
        return $this->apiConfiguration->getApiSecret();
    }

    /**
     * @return string
     */
    public function getEndpoint() : string
    {
        return str_replace("%%account%%", $this->getAccount(), $this->apiConfiguration->getEndpointByDomain(ConfigurationInterface::RTUX_API_DOMAIN_ALTERNATIVE));
    }

    /**
     * @return bool
     */
    public function isDev() : bool
    {
        return $this->apiConfiguration->getIsDev();
    }

    /**
     * @return bool
     */
    public function isTest() : bool
    {
        return $this->apiConfiguration->getIsTest();
    }

    /**
     * @return string
     */
    public function getLanguage() : string
    {
        return $this->genericConfiguration->getLanguage();
    }

    /**
     * @return string
     */
    public function getCurrencyCode() : string
    {
        return $this->genericConfiguration->getCurrencyCode();
    }

    /**
     * Per Magento2 logic (as seen on GA)
     *
     * @return bool
     */
    public function isTrackingRestricted() : bool
    {
        return $this->genericConfiguration->isCookieRestrictionModeEnabled();
    }

}
