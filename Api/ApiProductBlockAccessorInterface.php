<?php
namespace Boxalino\RealTimeUserExperience\Api;

use Boxalino\RealTimeUserExperienceApi\Service\Api\Response\Accessor\AccessorInterface;
use Boxalino\RealTimeUserExperienceApi\Service\Api\Response\Accessor\BlockInterface;
use Magento\Catalog\Api\Data\ProductInterface;

/**
 * @package Boxalino\RealTimeUserExperience\Api
 */
interface ApiProductBlockAccessorInterface extends ApiRendererInterface
{
    /**
     * @return AccessorInterface|null
     */
    public function getApiProduct();

    /**
     * @return mixed | ProductInterface
     */
    public function getProduct();

    /**
     * @param $product
     * @return mixed
     */
    public function setProduct(ProductInterface $product);

}
