<?php
namespace Boxalino\RealTimeUserExperience\Helper\Api;

use Boxalino\RealTimeUserExperienceApi\Service\Api\Util\ConfigurationInterface;
use Boxalino\RealTimeUserExperienceApi\Service\ErrorHandler\MissingDependencyException;

/**
 * Class Configuration
 * Configurations defined for the REST API requests
 *
 * @package Boxalino\RealTimeUserExperience\Helper\Api
 */
class Configuration implements ConfigurationInterface
{

    /** @var bool  */
    protected $proxy = false;

    /**
     * @var string
     */
    protected $contextId = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    public function __construct(\Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig)
    {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * @return bool
     */
    public function isEnabled() : bool
    {
        $value = $this->scopeConfig->getValue('rtux/general/status', $this->contextId);
        if(empty($value))
        {
            return false;
        }

        return (bool)$value;
    }

    /**
     * The API endpoint depends on the testing conditionals and on the data index
     *
     * @return string
     */
    public function getRestApiEndpoint() : string
    {
        $value = $this->scopeConfig->getValue('rtux/api/url', $this->contextId);
        if(empty($value))
        {
            if($this->getIsDev() || $this->getIsTest())
            {
                return str_replace("%%account%%", $this->getUsername(), $this->getDomainEndpoint(ConfigurationInterface::RTUX_API_ENDPOINT_STAGE));
            }

            return str_replace("%%account%%", $this->getUsername(), $this->getDomainEndpoint(ConfigurationInterface::RTUX_API_ENDPOINT_PRODUCTION));
        }

        return $value;
    }

    /**
     * @return string
     */
    public function getUsername() : string
    {
        $value = $this->scopeConfig->getValue('rtux/general/account', $this->contextId);
        if(empty($value))
        {
            throw new MissingDependencyException("BoxalinoAPI: BOXALINO ACCOUNT NAME has not been configured.");
        }

        return $value;
    }

    /**
     * @return string
     */
    public function getApiKey() : string
    {
        $value = $this->scopeConfig->getValue('rtux/general/api_key', $this->contextId);
        if(empty($value))
        {
            throw new MissingDependencyException("BoxalinoAPI: API KEY has not been configured.");
        }

        return $value;
    }

    /**
     * @return string
     */
    public function getApiSecret() : string
    {
        $value = $this->scopeConfig->getValue('rtux/general/api_secret', $this->contextId);
        if(empty($value))
        {
            throw new MissingDependencyException("BoxalinoAPI: API SECRET has not been configured.");
        }

        return $value;
    }

    /**
     * @return bool
     */
    public function getIsDev() : bool
    {
        $value = $this->scopeConfig->getValue('rtux/api/dev', $this->contextId);
        if(empty($value))
        {
            return false;
        }

        return (bool)$value;
    }

    /**
     * @return bool
     */
    public function getIsTest() : bool
    {
        $value = $this->scopeConfig->getValue('rtux/api/test', $this->contextId);
        if(empty($value))
        {
            return false;
        }

        return (bool)$value;
    }

    /**
     * @param string $endpoint
     * @return string
     */
    public function getDomainEndpoint(string $endpoint) : string
    {
        if($this->isProxy())
        {
            return str_replace("%%domain%%", ConfigurationInterface::RTUX_API_DOMAIN_ALTERNATIVE, $endpoint);
        }

        return str_replace("%%domain%%", ConfigurationInterface::RTUX_API_DOMAIN_MAIN, $endpoint);
    }

    /**
     * @return bool
     */
    public function isProxy(): bool
    {
        return $this->proxy;
    }

    /**
     * @param bool $proxy
     * @return Configuration
     */
    public function setProxy(bool $proxy): ConfigurationInterface
    {
        $this->proxy = $proxy;
        return $this;
    }


}
