<?php
namespace Boxalino\RealTimeUserExperience\Block\Api;

use Boxalino\RealTimeUserExperience\Api\ApiListingBlockAccessorInterface;
use Boxalino\RealTimeUserExperience\Api\ApiRendererInterface;
use Boxalino\RealTimeUserExperience\Block\ApiBlockTrait;
use Boxalino\RealTimeUserExperience\Block\FrameworkBlockTrait;
use Boxalino\RealTimeUserExperienceApi\Framework\Content\Listing\ApiEntityCollectionInterface;

/**
 * Class ListApi
 * Manages a content collection from the response
 * Besides the obvious speed performance, it is also desired for the pages/blocks to be non-cacheable
 *
 * @package Boxalino\RealTimeUserExperience\Block\Catalog\Product
 */
class ListApi extends \Magento\Framework\View\Element\Template
    implements ApiRendererInterface, ApiListingBlockAccessorInterface
{
    use ApiBlockTrait;
    use FrameworkBlockTrait;

    /**
     * @var null | \ArrayIterator
     */
    protected $_apiCollection = null;

    /**
     * @return \ArrayIterator|null
     */
    public function getApiCollection(): ?\ArrayIterator
    {
        if (is_null($this->_apiCollection))
        {
            /** @var ApiEntityCollectionInterface $apiCollectionModel */
            $apiCollectionModel = $this->getBlock()->getModel();
            $contentCollection = $apiCollectionModel->getApiCollection();

            $this->_eventManager->dispatch(
                'rtux_api_block_api_list_collection',
                ['collection' => $contentCollection]
            );

            $this->_apiCollection = $contentCollection;
        }

        return $this->_apiCollection;
    }


    /**
     * @return bool
     */
    public function hasHits() : bool
    {
        return (bool) $this->getApiCollection()->count();
    }


}
