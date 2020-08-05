<?php
namespace Boxalino\RealTimeUserExperience\Api;

use Boxalino\RealTimeUserExperienceApi\Service\Api\Response\Accessor\Facet;

/**
 * @package Boxalino\RealTimeUserExperience\Api
 */
interface ApiFacetBlockAccessorInterface extends ApiRendererInterface
{
    /**
     */
    public function getFilter();

    /**
     * @param $filter
     * @return mixed
     */
    public function setFilter(Facet $filter);

}
