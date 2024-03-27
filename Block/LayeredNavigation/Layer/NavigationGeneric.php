<?php
namespace Boxalino\RealTimeUserExperience\Block\LayeredNavigation\Layer;

use Boxalino\RealTimeUserExperience\Api\ApiRendererInterface;

/**
 * Class Navigation
 *
 * This is a wrapper over the facets view (state & list & filter item renderer)
 *
 * Per Magento2 expectation, the following elements are available:
 * 1. active filters (also known as the "state")
 * 2. filters (rendered individually)
 *
 * @package Boxalino\RealTimeUserExperience\Block\LayeredNavigation
 */
class NavigationGeneric extends AbstractNavigation
    implements ApiRendererInterface
{

    /**
     * The filters are set based on the position property defined
     *
     * @return \ArrayIterator
     */
    public function getFilters() : \ArrayIterator
    {
        if (is_null($this->filtersList))
        {
            $apiFacetsList = $this->getApiFacetModel()->getFacets();
            $this->_eventManager->dispatch(
                'rtux_api_block_facet_list_collection',
                ['collection' => $apiFacetsList]
            );

            $this->filtersList = $apiFacetsList;
        }

        return $this->filtersList;
    }


}
