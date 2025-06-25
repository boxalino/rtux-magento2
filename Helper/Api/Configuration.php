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

    /**
     * @var \Magento\Cookie\Helper\Cookie
     */
    protected $cookieHelper;

    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $customerSession;

    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Cookie\Helper\Cookie $cookieHelper,
        \Magento\Customer\Model\Session $customerSession
    ){
        $this->scopeConfig = $scopeConfig;
        $this->cookieHelper = $cookieHelper;
        $this->customerSession = $customerSession;
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

    /**
     * @return int|null
     */
    public function getSessionCustomerId() : ?int
    {
        if($this->customerSession->getCustomerId())
        {
            return $this->customerSession->getCustomerId();
        }

        return null;
    }

    /**
     * @return bool
     */
    public function isCookieRestrictionModeEnabled() : bool
    {
        return $this->cookieHelper->isCookieRestrictionModeEnabled();
    }

    /**
     * https://boxalino.atlassian.net/wiki/spaces/BPKB/pages/848887816/Data+Privacy+Guidelines+for+Tracking
     * @return bool
     */
    public function isUserNotAllowSaveCookie() : bool
    {
        return $this->cookieHelper->isUserNotAllowSaveCookie();
    }

    /**
     * Sets a generic attribute in the customer's session.
     * Use a unique key to prevent conflicts (e.g., 'vendor_module_consent_status').
     *
     * @param string $key
     * @param string $value
     * @return string
     */
    public function setSessionAttribute(string $key, string $value): string
    {
        $this->customerSession->setData($key, $value);
        return $value;
    }

    /**
     * Retrieves a generic attribute from the customer's session.
     *
     * @param string $key
     * @return mixed|null
     */
    public function getSessionAttribute(string $key)
    {
        return $this->customerSession->getData($key);
    }

    /**
     * Checks if a specific attribute exists in the customer's session.
     *
     * @param string $key
     * @return bool
     */
    public function hasSessionAttribute(string $key): bool
    {
        return $this->customerSession->hasData($key);
    }


}
