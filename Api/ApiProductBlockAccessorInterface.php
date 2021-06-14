<?php
namespace Boxalino\RealTimeUserExperience\Api;

use Magento\Catalog\Api\Data\ProductInterface;

/**
 * @package Boxalino\RealTimeUserExperience\Api
 */
interface ApiProductBlockAccessorInterface extends ApiItemBlockAccessorInterface
{

    /**
     * @return mixed | ProductInterface
     */
    public function getProduct();

    /**
     * @param ProductInterface $product
     * @return mixed
     */
    public function setProduct(ProductInterface $product);


}
