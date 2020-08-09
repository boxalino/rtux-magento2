<?php
namespace Boxalino\RealTimeUserExperience\Block\LayeredNavigation\Layer;

use Boxalino\RealTimeUserExperience\Api\ApiSelectedFacetListBlockAccessorInterface;
use Boxalino\RealTimeUserExperience\Block\ApiBlockTrait;

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
        /** @var \Boxalino\RealTimeUserExperienceApi\Service\Api\Response\Accessor\Facet $facet */
        foreach ($this->getActiveFilters() as $facet) {
            foreach($facet->getSelectedValues() as $value) {
                #$filterState[$facet->getField()] = $value;
            }
        }
        $params['_current'] = true;
        $params['_use_rewrite'] = true;
        $params['_query'] = $filterState;
        $params['_escape'] = true;
        return $this->_urlBuilder->getUrl('*/*/*', $params);
    }

}
