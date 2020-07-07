<?php
namespace Boxalino\RealTimeUserExperience\Block\Catalog\Product\ProductList;

use Boxalino\RealTimeUserExperience\Api\ApiRendererInterface;
use Boxalino\RealTimeUserExperience\Block\ApiBlockTrait;
use Boxalino\RealTimeUserExperience\Block\FrameworkBlockTrait;
use Boxalino\RealTimeUserExperienceApi\Service\Api\Response\Accessor\Pagination;
use Magento\Catalog\Helper\Product\ProductList;
use Magento\Framework\View\Element\Template;

/**
 * Class Pager
 * Re-usable with the templates as provided by Magento2
 *
 * @package Boxalino\RealTimeUserExperience\Block\Catalog\Product\ProductList
 */
class Pager extends \Magento\Catalog\Block\Product\Widget\Html\Pager
    implements ApiRendererInterface
{
    use ApiBlockTrait;
    use FrameworkBlockTrait;

    /**
     * @var ProductList
     */
    protected $productListHelper;

    public function __construct(
        ProductList $productListHelper,
        Template\Context $context,
        array $data = []
    ){
        parent::__construct($context, $data);
        $this->productListHelper = $productListHelper;
    }

    /**
     * Accesses the pagination model of the Boxalino API response
     * (as configured in the accessor definitions)
     *
     * @return Pagination
     */
    public function getPagination() : Pagination
    {
        return $this->getBlock()->getPagination();
    }

    /**
     * @return int
     */
    public function getLimit() : int
    {
        return $this->getPagination()->getPageSize();
    }

    /**
     * Return position number in collection for first item on current page
     *
     * @return int
     */
    public function getFirstNum() : int
    {
        return $this->getLimit() * ($this->getCurrentPage() - 1) + 1;
    }

    /**
     * Return position number in collection for last item on current page
     *
     * @return int
     */
    public function getLastNum() : int
    {
        return $this->getLimit() * ($this->getCurrentPage() - 1) + $this->getLimit();
    }

    /**
     * Return total number of collection
     *
     * @return int
     */
    public function getTotalNum() : int
    {
        return $this->getPagination()->getTotalHitCount();
    }

    /**
     * Return number of last page
     *
     * @SuppressWarnings(PHPMD.BooleanGetMethodName)
     */
    public function getLastPageNum() : int
    {
        return $this->getPagination()->getLastPage();
    }


    /**
     * Return number of current page
     *
     * @return int
     */
    public function getCurrentPage() : int
    {
        return $this->getPagination()->getCurrentPage();
    }

    /**
     * Retrieve url for previous page
     *
     * @return string
     */
    public function getPreviousPageUrl() : string
    {
        return $this->getPageUrl($this->getPagination()->getCurrentPage() - 1);
    }

    /**
     * Retrieve url for next page
     *
     * @return string
     */
    public function getNextPageUrl() : string
    {
        return $this->getPageUrl($this->getPagination()->getCurrentPage() + 1);
    }

    /**
     * Retrieve url for last page
     *
     * @return string
     */
    public function getLastPageUrl() : string
    {
        return $this->getPageUrl($this->getLastPageNum());
    }

    /**
     * @duplicate as set in Magento\Catalog\Block\Product\ProductList\Toolbar
     * @return int
     */
    public function getJump() : int
    {
        return $this->_scopeConfig->getValue(
            'design/pagination/pagination_frame_skip',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        ) ?? $this->_jump;
    }

    /**
     * @duplicate as set in Magento\Catalog\Block\Product\ProductList\Toolbar
     * @return int
     */
    public function getFrameLength() : int
    {
        return $this->_scopeConfig->getValue(
            'design/pagination/pagination_frame',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        ) ?? $this->_frameLength;
    }

    /**
     * @duplicate as set in Magento\Catalog\Block\Product\ProductList\Toolbar
     * @return array
     */
    public function getAvailableLimit() : array
    {
        return $this->productListHelper->getAvailableLimit($this->getMode());
    }

    /**
     * Property used in template & defined in Layout Block
     * @return bool
     */
    public function getShowAmounts() : bool
    {
        try{
            return $this->getBlock()->getShowAmounts()[0] === "true";
        } catch(\Throwable $exception)
        {
            return $this->getData("show_amounts");
        }
    }

    /**
     * Property used in template & defined in Layout Block
     * @return bool
     */
    public function getUseContainer() : bool
    {
        try{
            return $this->getBlock()->getUseContainer()[0] === "true";
        } catch (\Throwable $exception)
        {
            return $this->getData("use_container");
        }
    }

    /**
     * Property used in template & defined in Layout Block
     * @return bool
     */
    public function isShowPerPage() : bool
    {
        try{
            $value = $this->getBlock()->getShowPerPage()[0] === "true";
            $this->setShowPerPage($value);
        } catch (\Throwable $exception){}

        return parent::isShowPerPage();
    }

    /**
     * Property used in template & defined in Layout Block
     * @return bool
     */
    public function showPager() : bool
    {
        try{
            return $this->getBlock()->getShowPager()[0] === "true";
        } catch (\Throwable $exception)
        {
            return false;
        }
    }

}
