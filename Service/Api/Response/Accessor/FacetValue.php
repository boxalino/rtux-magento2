<?php
namespace Boxalino\RealTimeUserExperience\Service\Api\Response\Accessor;

use Boxalino\RealTimeUserExperienceApi\Service\Api\Response\Accessor\FacetValue as ApiFacetValue;

/**
 * Class FacetValue
 *
 * Item refers to any data model/logic that is desired to be rendered/displayed
 * The integrator can decide to either use all data as provided by the Narrative API,
 * or to design custom data layers to represent the fetched content
 *
 * For the purpose of a Magento 2 integration, other properties are set on the facet values (similar to \Magento\Catalog\Model\Layer\Filter\Item)
 * These properties are not part of the API but they are set on model generation
 * (check Boxalino\RealTimeUserExperience\Model\Response\Content\ApiFacet)
 *
 * @package Boxalino\RealTimeUserExperience\Service\Api\Response\Accessor
 */
class FacetValue extends ApiFacetValue
{

    /**
     * @var string
     */
    protected $removeUrl;

    /**
     * @var string
     */
    protected $selectUrl;

    /**
     * @var string
     */
    protected $clearUrl;

    /**
     * @return string
     */
    public function getClearUrl(): string
    {
        return $this->clearUrl;
    }

    /**
     * @param string $clearUrl
     * @return FacetValue
     */
    public function setClearUrl(string $clearUrl): FacetValue
    {
        $this->clearUrl = $clearUrl;
        return $this;
    }

    /**
     * @return string
     */
    public function getRemoveUrl(): string
    {
        return $this->removeUrl;
    }

    /**
     * @param string $removeUrl
     * @return FacetValue
     */
    public function setRemoveUrl(string $removeUrl): FacetValue
    {
        $this->removeUrl = $removeUrl;
        return $this;
    }

    /**
     * @return string
     */
    public function getSelectUrl(): string
    {
        return $this->selectUrl;
    }

    /**
     * @param string $selectUrl
     * @return FacetValue
     */
    public function setSelectUrl(string $selectUrl): FacetValue
    {
        $this->selectUrl = $selectUrl;
        return $this;
    }


}
