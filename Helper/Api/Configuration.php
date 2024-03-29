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
            return str_replace("%%account%%", $this->getUsername(), $this->getEndpointByDomain());
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
     * On server-side requests, only the bx-cloud is used
     * On frontend-side requests, only the alternative domain is used
     *
     * @param string|null $domain
     * @return string
     */
    public function getEndpointByDomain(?string $domain = ConfigurationInterface::RTUX_API_DOMAIN_MAIN) : string
    {
        if($this->getIsDev() || $this->getIsTest())
        {
            return str_replace("%%domain%%", $domain,ConfigurationInterface::RTUX_API_ENDPOINT_STAGE);
        }

        return str_replace("%%domain%%", $domain, ConfigurationInterface::RTUX_API_ENDPOINT_PRODUCTION);
    }


}
