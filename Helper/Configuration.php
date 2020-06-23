<?php
namespace Boxalino\RealTimeUserExperience\Helper;

/**
 * Class Configuration
 * Accesses platform (Magento2) data
 *
 * @package Boxalino\RealTimeUserExperience\Helper
 */
class Configuration
{

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $customerSession;

    /**
     * @var \Magento\Search\Helper\Data
     */
    protected $searchHelper;

    public function __construct(
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Search\Helper\Data $searchHelper
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->storeManager = $storeManager;
        $this->customerSession = $customerSession;
        $this->searchHelper = $searchHelper;
    }

    /**
     * @return string
     */
    public function getLanguage() : string
    {
        return substr($this->scopeConfig->getValue('general/locale/code', \Magento\Store\Model\ScopeInterface::SCOPE_STORE), 0, 2);
    }

    /**
     * We may get grid-list, list-grid, grid or list
     * @return int
     */
    public function getMagentoStoreConfigPageSize() : int
    {
        $storeConfig = $this->getMagentoFrontendStoreConfig();
        $storeMainMode = explode('-', $storeConfig['list_mode']);

        return $storeConfig[$storeMainMode[0] . '_per_page'];
    }

    /**
     * @return string
     */
    public function getMagentoStoreConfigListOrder() : string
    {
        return $this->getMagentoFrontendStoreConfig()['default_sort_by'];
    }

    /**
     * @return array
     */
    public function getMagentoFrontendStoreConfig() : array
    {
        return $this->scopeConfig->getValue('catalog/frontend');
    }

    /**
     * @return int
     */
    public function getMagentoRootCategoryId() : int
    {
        return $this->storeManager->getStore()->getRootCategoryId();
    }

    /**
     * @return int
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getMagentoStoreId() : int
    {
        return $this->storeManager->getStore()->getId();
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
     * @return string
     */
    public function getSearchQueryParameter() : string
    {
        return $this->searchHelper->getQueryParamName();
    }

}
