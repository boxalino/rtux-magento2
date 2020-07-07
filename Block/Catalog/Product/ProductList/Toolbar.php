<?php
namespace Boxalino\RealTimeUserExperience\Block\Catalog\Product\ProductList;

use Boxalino\RealTimeUserExperience\Api\ApiRendererInterface;
use Boxalino\RealTimeUserExperience\Block\ApiBlockTrait;
use Boxalino\RealTimeUserExperience\Block\FrameworkBlockTrait;
use Magento\Catalog\Helper\Product\ProductList;
use Magento\Framework\View\Element\Template;

/**
 * Class Toolbar
 * Due to the atomic design of the narrative, the toolbar itself is a wrapper for the pagination/sorter and other elements
 *
 * @package Boxalino\RealTimeUserExperience\Block\Catalog\Product\ProductList
 */
class Toolbar extends \Magento\Framework\View\Element\Template
    implements ApiRendererInterface
{
    use ApiBlockTrait;
    use FrameworkBlockTrait;

    /**
     * @var \Magento\Catalog\Block\Product\ProductList\Toolbar
     */
    protected $toolbar;

    /**
     * @var \Magento\Framework\Serialize\Serializer\Json
     */
    protected $jsonHelper;

    public function __construct(
        \Magento\Catalog\Block\Product\ProductList\Toolbar $toolbar,
        \Magento\Framework\Serialize\Serializer\Json $jsonHelper,
        Template\Context $context,
        array $data = []
    ){
        parent::__construct($context, $data);
        $this->toolbar = $toolbar;
        $this->jsonHelper = $jsonHelper;
        /** set contextual toolbar options */
        $this->toolbar->setDefaultDirection(
            $this->_request->getParam($this->getDirectionParameter(), ProductList::DEFAULT_SORT_DIRECTION)
        );
    }

    /**
     * The template logic with the use of deprecated JSON encoder is migrated to the block
     * (in default Magento2 template, the configurations are retrieved in template)
     *
     * @inherited
     * @return string
     */
    public function getWidgetOptionsJson() : string
    {
        $widget = $this->toolbar->getWidgetOptionsJson();
        $widgetOptions = $this->jsonHelper->unserialize($widget);

        return $this->jsonHelper->serialize($widgetOptions['productListToolbarForm']);
    }

}
