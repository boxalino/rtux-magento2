<?php
namespace Boxalino\RealTimeUserExperience\Api;

/**
 * @package Boxalino\RealTimeUserExperience\Api
 */
interface ApiFacetListBlockAccessorInterface extends ApiRendererInterface
{
    /**
     */
    public function getFacets();

    /**
     * @param $filters
     * @return mixed
     */
    public function setFilters($filters);

}
