<?php
namespace Boxalino\RealTimeUserExperience\Service\Api\Response\Accessor;

use Boxalino\RealTimeUserExperienceApi\Service\Api\Response\Accessor\AccessorFacetModelInterface;
use Boxalino\RealTimeUserExperienceApi\Service\Api\Response\Accessor\Facet as ApiFacet;

/**
 * Class Facet
 *
 * Designed as the M2 facet accessor
 * Will append with properties that can be used by the templates/blocks
 *
 * @package Boxalino\RealTimeUserExperience\Service\Api\Response\Accessor
 */
class Facet extends ApiFacet
{

    /**
     * @var null | string
     */
    protected $cleanValue = null;

    /**
     * Retrieve filter value for Clear All Items filter state
     *
     * @return string | null
     */
    public function getCleanValue() : ?string
    {
        return $this->cleanValue;
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setCleanValue(string $value) : self
    {
        $this->cleanValue = $value;
        return $this;
    }

    /**
     * Check to be removed when old DI is deprecated
     *
     * @param string $field
     * @return \Boxalino\RealTimeUserExperienceApi\Service\Api\Response\Accessor\Facet
     */
    public function setField(string $field): \Boxalino\RealTimeUserExperienceApi\Service\Api\Response\Accessor\Facet
    {
        if(strpos($field, AccessorFacetModelInterface::BOXALINO_STORE_FACET_PREFIX) === 0)
        {
            $field = substr($field, strlen(AccessorFacetModelInterface::BOXALINO_STORE_FACET_PREFIX), strlen($field));
        }

        parent::setField($field);
        return $this;
    }


}
