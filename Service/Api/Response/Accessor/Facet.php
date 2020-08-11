<?php
namespace Boxalino\RealTimeUserExperience\Service\Api\Response\Accessor;

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
     * @var string | null
     */
    protected $requestField = null;

    /**
     * @var null | string
     */
    protected $removeUrl = null;

    /**
     * @var array
     */
    protected $urlParams = [];

    /**
     * @var null | string
     */
    protected $cleanValue = null;

    /**
     * @var null
     */
    protected $allowMultiselect = false;

    /**
     * @var string
     */
    protected $rangeFromField;

    /**
     * @var string
     */
    protected $rangeToField;

    /**
     * Get request variable name which is used for apply filter
     * For ex: "products_" can be removed, fields renamed, etc
     *
     * @return string
     */
    public function getRequestField() : string
    {
        if(is_null($this->requestField))
        {
            //$this->requestField = substr($this->getField(), strlen(AccessorFacetModelInterface::BOXALINO_STORE_FACET_PREFIX), strlen($this->getField()));
            $this->requestField = $this->getField();
        }

        return $this->requestField ;
    }

    /**
     * Set the name of the facet as is to appear in the URL
     *
     * @param string $field
     */
    public function setRequestField(string $field) : self
    {
        $this->requestField = $field;
        return $this;
    }

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
     * Flag if the facet is configured to allow multiple selected values
     *
     * @return bool
     */
    public function allowMultiselect() : bool
    {
        return $this->allowMultiselect;
    }

    /**
     * @param string $allow
     * @return $this
     */
    public function setAllowMultiselect(string $allow) : self
    {
        $this->allowMultiselect = $allow == 'true';
        return $this;
    }

    /**
     * In case of range facets, range-from field can be configured
     * (further used for facetValue remove/select URLs)
     *
     * @return string
     */
    public function getRangeFromField(): string
    {
        return $this->rangeFromField;
    }

    /**
     * @param string $rangeFromField
     * @return Facet
     */
    public function setRangeFromField(string $rangeFromField): Facet
    {
        $this->rangeFromField = $rangeFromField;
        return $this;
    }

    /**
     * In case of range facets, range-to field can be configured
     * (further used for facetValue remove/select URLs)
     *
     * @return string
     */
    public function getRangeToField(): string
    {
        return $this->rangeToField;
    }

    /**
     * @param string $rangeToField
     * @return Facet
     */
    public function setRangeToField(string $rangeToField): Facet
    {
        $this->rangeToField = $rangeToField;
        return $this;
    }

}
