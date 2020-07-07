<?php
namespace Boxalino\RealTimeUserExperience\Block;

use Boxalino\RealTimeUserExperience\Service\Api\Util\RequestParametersTrait;

/**
 * Trait FrameworkBlockTrait
 * Generic Magento2 functions reusable within Template blocks
 *
 * @package Boxalino\RealTimeUserExperience\Block
 */
trait FrameworkBlockTrait
{
    use RequestParametersTrait;

    /**
     * @var \Magento\Framework\App\RequestInterface
     */
    protected $_request;

    /**
     * Block view mode to switch from list view to grid view (Magento)
     * Use the general configuration for product list mode from config path catalog/frontend/list_mode as default value
     *
     * @duplicate from Toolbar block
     * @return string
     */
    public function getMode() : string
    {
        return $this->getRequest()->getParam($this->getBlockViewModeParameter(), "grid");
    }

    /**
     * Get request
     *
     * @return \Magento\Framework\App\RequestInterface
     */
    public function getRequest() : \Magento\Framework\App\RequestInterface
    {
        return $this->_request;
    }
}
