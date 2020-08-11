<?php
namespace Boxalino\RealTimeUserExperience\Block\LayeredNavigation\Layer;

use Boxalino\RealTimeUserExperience\Api\ApiSelectedFacetListBlockAccessorInterface;
use Boxalino\RealTimeUserExperience\Block\ApiBlockTrait;
use Boxalino\RealTimeUserExperience\Service\Api\Response\Accessor\Facet;
use Boxalino\RealTimeUserExperience\Service\Api\Response\Accessor\FacetValue;

/**
 * Layered navigation state
 */
class State extends \Magento\Framework\View\Element\Template
    implements ApiSelectedFacetListBlockAccessorInterface
{

    use ApiBlockTrait;

    /**
     * @var \ArrayIterator
     */
    protected $activeFilters;

    /**
     * @var array | null
     */
    protected $urlParameters = null;

    /**
     * Retrieve active filters
     *
     * @return \ArrayIterator
     */
    public function getActiveFilters() : \ArrayIterator
    {
        if($this->activeFilters)
        {
            return $this->activeFilters;
        }

        return new \ArrayIterator();
    }

    /**
     * @param \ArrayIterator $filters
     * @return $this|mixed
     */
    public function setActiveFilters(\ArrayIterator $filters)
    {
        $this->activeFilters = $filters;
        return $this;
    }

    /**
     * Retrieve Clear Filters URL
     *
     * @return string
     */
    public function getClearUrl()
    {
        $filterState = [];
        /** @var Facet $filter */
        foreach ($this->getActiveFilters() as $filter)
        {
            /** in case of a selected range facet - the range-from & range-to fields must be reset */
            if($filter->isRange())
            {
                /** @var FacetValue $facetValue */
                $facetValue = $filter->getSelectedValues()[0];
                if($facetValue->getMinSelectedValue())
                {
                    $filterState[$filter->getRangeFromField()] = $filter->getCleanValue();
                }

                if($facetValue->getMaxSelectedValue())
                {
                    $filterState[$filter->getRangeToField()] = $filter->getCleanValue();
                }
                continue;
            }

            $filterState[$filter->getRequestField()] = $filter->getCleanValue();
        }

        $params['_current'] = true;
        $params['_use_rewrite'] = true;
        $params['_query'] = $filterState;
        $params['_escape'] = true;

        return $this->_urlBuilder->getUrl('*/*/*', $params);
    }


}
