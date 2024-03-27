<?php
namespace Boxalino\RealTimeUserExperience\Block\LayeredNavigation\Layer;

use Boxalino\RealTimeUserExperience\Api\ApiRendererInterface;
use Boxalino\RealTimeUserExperienceApi\Service\ErrorHandler\UndefinedPropertyError;

/**
 * Class Navigation
 *
 * This is a wrapper over the facets view (state & list & filter item renderer)
 *
 * Per Magento2 expectation, the following elements are available:
 * 1. active filters (also known as the "state")
 * 2. filters (rendered individually)
 *
 * As it is expected to have facets on a position - the facets configured with a position will be used
 * @package Boxalino\RealTimeUserExperience\Block\LayeredNavigation
 */
class NavigationPosition extends AbstractNavigation
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
            try{
                if($this->getBlock()->getPosition())
                {
                    $apiFacetsList = $this->getApiFacetModel()->getByPosition($this->getBlock()->getPosition());
                }
            } catch(UndefinedPropertyError $exception) {}
            $this->_eventManager->dispatch(
                'rtux_api_block_facet_list_collection',
                ['collection' => $apiFacetsList]
            );

            $this->filtersList = $apiFacetsList;
        }

        return $this->filtersList;
    }


}
