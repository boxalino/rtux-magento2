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
    const RTUX_API_ENDPOINT_PRODUCTION="https://main.bx-cloud.com/narrative/%%account%%/api/1";
    const RTUX_API_ENDPOINT_STAGE="https://r-st.bx-cloud.com/narrative/%%account%%/api/1";

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
     * The API endpoint depends on the testing conditionals and on the data index
     *
     * @return string
     */
    public function getRestApiEndpoint() : string
    {
        $value = $this->scopeConfig->getValue('rtux/api/url', $this->contextId);
        if(empty($value))
        {
            if($this->getIsDev())
            {
                return str_replace("%%account%%", $this->getUsername(), self::RTUX_API_ENDPOINT_STAGE);
            }

            return str_replace("%%account%%", $this->getUsername(), self::RTUX_API_ENDPOINT_PRODUCTION);
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
        $value = $this->scopeConfig->getValue('rtux/general/apiKey', $this->contextId);
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
        $value = $this->scopeConfig->getValue('rtux/general/apiSecret', $this->contextId);
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
        $value = $this->scopeConfig->getValue('rtux/general/dev', $this->contextId);
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

}
