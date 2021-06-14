<?php
namespace Boxalino\RealTimeUserExperience\Api;

use Boxalino\RealTimeUserExperienceApi\Service\Api\Response\Accessor\AccessorInterface;

/**
 * @package Boxalino\RealTimeUserExperience\Api
 */
interface ApiItemBlockAccessorInterface extends ApiRendererInterface
{
    /**
     * @return AccessorInterface | null
     */
    public function getApiItem() : ?AccessorInterface;

    /**
     * @param AccessorInterface $item
     */
    public function setApiItem(AccessorInterface $item) : void;

}
