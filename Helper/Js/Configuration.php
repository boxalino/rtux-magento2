<?php
namespace Boxalino\RealTimeUserExperience\Helper\Js;

use Boxalino\RealTimeUserExperience\Helper\Api\Configuration as ApiConfiguration;
use Boxalino\RealTimeUserExperience\Helper\Configuration as GenericConfiguration;
use Magento\Framework\App\Helper\Context;

/**
 * Class Configuration
 * Configurations defined for the REST API JS requests (tracker, autocomplete, etc)
 *
 * @package Boxalino\RealTimeUserExperience\Helper\Js
 */
class Configuration extends \Magento\Framework\App\Helper\AbstractHelper
{
    const BOXALINO_API_TRACKING_PRODUCTION="//track.bx-cloud.com/static/bav2.min.js";
    const BOXALINO_API_TRACKING_STAGE="//r-st.bx-cloud.com/static/bav2.min.js";
    const BOXALINO_API_SERVER_PRODUCTION="//track.bx-cloud.com/track/v2";
    const BOXALINO_API_SERVER_STAGE="//r-st.bx-cloud.com/track/v2";

    /**
     * @var \Boxalino\RealTimeUserExperience\Helper\Api\Configuration
     */
    protected $apiConfiguration;

    /**
     * @var \Boxalino\RealTimeUserExperience\Helper\Configuration
     */
    protected $genericConfiguration;

    public function __construct(
        GenericConfiguration $genericConfiguration,
        ApiConfiguration $apiConfiguration,
        Context $context
    ) {
        parent::__construct($context);
        $this->apiConfiguration = $apiConfiguration;
        $this->genericConfiguration = $genericConfiguration;
    }

    /**
     * @return string
     */
    public function getAccount() : string
    {
        return $this->apiConfiguration->getUsername();
    }

    /**
     * Because it is displayed in a template, the current customer data will be encoded
     *
     * @return string|null
     */
    public function getProfile() : ?string
    {
        $profile = $this->genericConfiguration->getSessionCustomerId();
        if(is_null($profile))
        {
            return null;
        }

        return base64_encode($profile);
    }

    /**
     * Server-side API key to allow access without a secret
     * (only allowed for autocomplete requests)
     *
     * @return string
     */
    public function getKey() : ?string
    {
        $value = $this->scopeConfig->getValue('rtux/api/apiServerKey', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        if(empty($value))
        {
            return null;
        }

        return $value;
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
        return $this->apiConfiguration->getRestApiEndpoint();
    }

    /**
     * @param bool $isDev
     * @return string
     */
    public function getTrackerUrl(bool $isDev=false) : string
    {
        if($isDev)
        {
            return self::BOXALINO_API_TRACKING_STAGE;
        }

        return self::BOXALINO_API_TRACKING_PRODUCTION;
    }

    /**
     * @param bool $isDev
     * @param string $session
     * @return string
     */
    public function getServerUrl(string $session, bool $isDev=false) : string
    {
        if($isDev)
        {
            return self::BOXALINO_API_SERVER_STAGE . "?_bxv=" . $session;
        }

        return self::BOXALINO_API_SERVER_PRODUCTION . "?_bxv=" . $session;
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


}
