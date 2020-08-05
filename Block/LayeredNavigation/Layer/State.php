<?php
namespace Boxalino\RealTimeUserExperience\Block\LayeredNavigation\Layer;

use Boxalino\RealTimeUserExperience\Api\ApiSelectedFacetListBlockAccessorInterface;
use Boxalino\RealTimeUserExperience\Block\ApiBlockTrait;
use Magento\LayeredNavigation\Block\Navigation\State as MagentoState;

/**
 * Layered navigation state
 */
class State extends MagentoState
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
     * @return array | \ArrayIterator
     */
    public function getActiveFilters()
    {
        if($this->activeFilters)
        {
            return $this->activeFilters;
        }

        return parent::getActiveFilters();
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
