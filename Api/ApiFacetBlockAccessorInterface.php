<?php
namespace Boxalino\RealTimeUserExperience\Api;

use Boxalino\RealTimeUserExperienceApi\Service\Api\Response\Accessor\AccessorInterface;
use Boxalino\RealTimeUserExperienceApi\Service\Api\Response\Accessor\BlockInterface;

/**
 * @package Boxalino\RealTimeUserExperience\Api
 */
interface ApiFacetBlockAccessorInterface extends ApiRendererInterface
{
    /**
     * @return AccessorInterface|null
     */
    public function getFacetsCollection() : ?AccessorInterface;

}
