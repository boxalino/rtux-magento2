<?php
namespace Boxalino\RealTimeUserExperience\Api;

/**
 * @package Boxalino\RealTimeUserExperience\Api
 */
interface ApiListingBlockAccessorInterface extends ApiRendererInterface
{

    /**
     * Optionally, the generic ArrayIterator for the bx-hits element must be available
     *
     * @return \ArrayIterator|null
     */
    public function getApiCollection() : ?\ArrayIterator;

}
