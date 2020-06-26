<?php
namespace Boxalino\RealTimeUserExperience\Api;

/**
 * @package Boxalino\RealTimeUserExperience\Api
 */
interface ApiListingBlockAccessorInterface extends ApiRendererInterface
{
    /**
     * As expected by a default Magento block managing product collections
     *
     * @return \Magento\Eav\Model\Entity\Collection\AbstractCollection|null
     */
    public function getLoadedProductCollection() : ?\Magento\Eav\Model\Entity\Collection\AbstractCollection;

}
