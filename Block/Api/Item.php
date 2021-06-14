<?php
namespace Boxalino\RealTimeUserExperience\Block\Api;

use Boxalino\RealTimeUserExperience\Api\ApiItemBlockAccessorInterface;
use Boxalino\RealTimeUserExperience\Api\ApiRendererInterface;
use Boxalino\RealTimeUserExperience\Block\ApiBlockTrait;
use Boxalino\RealTimeUserExperience\Block\FrameworkBlockTrait;
use Boxalino\RealTimeUserExperienceApi\Service\Api\Response\Accessor\AccessorInterface;

/**
 * Class Item
 *
 * @package Boxalino\RealTimeUserExperience\Block\Catalog\Product
 */
class Item extends \Magento\Framework\View\Element\Template
    implements ApiRendererInterface, ApiItemBlockAccessorInterface
{
    use ApiBlockTrait;
    use FrameworkBlockTrait;

    /**
     * @var null | AccessorInterface
     */
    protected $_apiItem;

    /**
     * @return AccessorInterface|null
     */
    public function getApiItem(): ?AccessorInterface
    {
        if($this->_apiItem)
        {
            return $this->_apiItem;
        }

        /** use getProduct() because that is the accessor-setter for bx-hit */
        $this->setApiItem($this->getBlock()->getBxHit());
        return $this->_apiItem;
    }

    /**
     * @param AccessorInterface $item
     */
    public function setApiItem(AccessorInterface $item): void
    {
        $this->_apiItem = $item;
    }


}
