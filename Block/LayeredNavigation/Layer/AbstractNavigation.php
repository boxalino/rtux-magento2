<?php
namespace Boxalino\RealTimeUserExperience\Block\LayeredNavigation\Layer;

use Boxalino\RealTimeUserExperience\Api\ApiBlockAccessorInterface;
use Boxalino\RealTimeUserExperience\Api\ApiFacetListBlockAccessorInterface;
use Boxalino\RealTimeUserExperience\Api\ApiRendererInterface;
use Boxalino\RealTimeUserExperience\Api\ApiSelectedFacetListBlockAccessorInterface;
use Boxalino\RealTimeUserExperience\Block\ApiBlockTrait;
use Boxalino\RealTimeUserExperience\Block\FrameworkBlockTrait;
use Boxalino\RealTimeUserExperience\Model\Response\Content\ApiFacet;
use Boxalino\RealTimeUserExperienceApi\Service\Api\Response\Accessor\BlockInterface;
use Boxalino\RealTimeUserExperienceApi\Service\ErrorHandler\MissingDependencyException;
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
 * @package Boxalino\RealTimeUserExperience\Block\LayeredNavigation
 */
abstract class AbstractNavigation extends \Magento\Framework\View\Element\Template
    implements ApiRendererInterface
{

    use ApiBlockTrait;
    use FrameworkBlockTrait;

    /**
     * @var ApiFacet
     */
    protected $apiFacetModel;

    /**
     * @var \ArrayIterator
     */
    protected $filtersList;

    /**
     * @var \ArrayIterator
     */
    protected $activeFiltersList;

    /**
     * Similar to the Layer element of the Layered Navigation
     *
     * @return \Boxalino\RealTimeUserExperienceApi\Service\Api\Response\Accessor\AccessorModelInterface| ApiFacet
     */
    public function getApiFacetModel() : ApiFacet
    {
        if(is_null($this->apiFacetModel))
        {
            $this->apiFacetModel = $this->getBlock()->getModel();
        }

        return $this->apiFacetModel;
    }

    /**
     * The filters are set based on the position property defined
     *
     * @return \ArrayIterator
     */
    abstract public function getFilters() : \ArrayIterator;

    /**
     * @return \ArrayIterator
     */
    public function getActiveFilters() : \ArrayIterator
    {
        if (is_null($this->activeFiltersList))
        {
            $apiFacetsList = $this->getApiFacetModel()->getSelectedFacets();
            $this->_eventManager->dispatch(
                'rtux_api_block_active_facet_list_collection',
                ['collection' => $apiFacetsList]
            );

            $this->activeFiltersList = $apiFacetsList;
        }

        return $this->activeFiltersList;
    }

    /**
     * Checks if there are selected filters
     * @return bool
     */
    public function hasActiveFilters() : bool
    {
        return (bool) $this->getActiveFilters()->count();
    }

    /**
     * URL to clean page view (un-selected all filters)
     *
     * @return string
     */
    public function getClearUrl() : string
    {
        return "/";
    }

    /**
     * Checks which facet is to be expanded
     * (for the accordion widget)
     *
     * @return string
     */
    public function getExpandedFiltersConfiguration() : string
    {
        $configuration = [];
        foreach($this->getFilters() as $index=>$filter)
        {
            if ($filter->getValues()->count() && $filter->getDisplay() === 'expanded') {
                $configuration[] = $index;
            }
        }

        return json_encode($configuration);
    }

    /**
     * The block can be aware of the expected children elements (state&renderer)
     *
     * @param BlockInterface $block
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getApiBlock(ApiBlockAccessorInterface $block) : ?ApiRendererInterface
    {
        if(!$block->getTemplate())
        {
            throw new MissingDependencyException("BoxalinoAPI RenderBlock Error: the block template is missing: " . json_encode($block));
        }

        try{
            $apiBlock = $this->createUniqIdApiBlock($block);
            if($apiBlock instanceof ApiRendererInterface)
            {
                $apiBlock->setBlock($block);
            }

            /** the facet list block is the "renderer" child */
            if($apiBlock instanceof ApiFacetListBlockAccessorInterface)
            {
                $apiBlock->setFilters($this->getFilters());
            }

            /** the selected facet list is the "state" child */
            if($apiBlock instanceof ApiSelectedFacetListBlockAccessorInterface)
            {
                $apiBlock->setActiveFilters($this->getActiveFilters());
            }

            return $apiBlock;
        } catch (\Throwable $exception) {
            $this->_logger->warning("BoxalinoAPI Facets Navigation ERROR: " . $exception->getMessage());
            $this->_logger->warning("BoxalinoAPI Facets Navigation ERROR: " . $exception->getTraceAsString());
            return null;
        }
    }

    /**
     * This will allow not to cache the block
     *
     * @return bool
     */
    public function isScopePrivate()
    {
        return true;
    }

}
