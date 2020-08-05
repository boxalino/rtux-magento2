<?php
namespace Boxalino\RealTimeUserExperience\Api;

/**
 * The selected facets are handled (displayed) by the "State" element of the Layered Navigation
 *
 * @package Boxalino\RealTimeUserExperience\Api
 */
interface ApiSelectedFacetListBlockAccessorInterface extends ApiRendererInterface
{
    /**
     * List of active/selected filters
     */
    public function getActiveFilters();

    /**
     * @param \ArrayIterator $filters
     * @return mixed
     */
    public function setActiveFilters(\ArrayIterator $filters);

}
