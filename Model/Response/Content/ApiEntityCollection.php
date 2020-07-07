<?php declare(strict_types=1);
namespace Boxalino\RealTimeUserExperience\Model\Response\Content;

use Boxalino\RealTimeUserExperienceApi\Service\Api\Response\Accessor\AccessorInterface;
use Boxalino\RealTimeUserExperienceApi\Service\Api\Response\Accessor\AccessorModelInterface;
use Boxalino\RealTimeUserExperienceApi\Framework\Content\Listing\ApiEntityCollectionModelAbstract;
use Magento\Catalog\Api\Data\ProductSearchResultsInterface;
use Magento\Catalog\Model\ProductRepository;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Catalog\Model\ResourceModel\Product\Collection;


/**
 * Class ApiEntityCollection
 *
 * Item refers to any data model/logic that is desired to be rendered/displayed
 * The integrator can decide to either use all data as provided by the Narrative API,
 * or to design custom data layers to represent the fetched content
 *
 * @package Boxalino\RealTimeUserExperience\Model\Response
 */
class ApiEntityCollection extends ApiEntityCollectionModelAbstract
    implements AccessorModelInterface
{

    /**
     * @var null | ProductSearchResultsInterface
     */
    protected $collection = null;

    /**
     * @var \ArrayIterator
     */
    protected $responseCollection;

    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory
     */
    protected $collectionFactory;

    public function __construct(
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $collectionFactory
    ){
        $this->collectionFactory = $collectionFactory;
    }

    /**
     * Accessing collection of products based on the hits
     * (\Magento\Catalog\Api\Data\ProductSearchResultsInterface)
     * @return \ArrayIterator
     */
    public function getCollection() : Collection
    {
        if(is_null($this->collection))
        {
            /** @var \Magento\Catalog\Model\ResourceModel\Product\Collection $collection */
            $collection = $this->collectionFactory->create();
            $collection->addAttributeToSelect('*')
                ->joinAttribute('status', 'catalog_product/status', 'entity_id', null, 'inner')
                ->joinAttribute('visibility', 'catalog_product/visibility', 'entity_id', null, 'inner')
                ->addFieldToFilter("entity_id", ['in' => $this->getHitIds()])
                ->addStoreFilter()
                ->addUrlRewrite();

            $this->collection = $collection;
        }

        return $this->collection;
    }

    /**
     * @return \ArrayIterator
     */
    public function getResponseCollection() : \ArrayIterator
    {
        return $this->responseCollection;
    }

    /**
     * Creates the collection which has only the return fields requested
     *
     * @param \ArrayIterator $blocks
     * @param string $hitAccessor
     */
    public function setResponseCollection(\ArrayIterator $blocks, string $hitAccessor) : void
    {
        $products = array_map(function(AccessorInterface $block) use ($hitAccessor) {
            if(property_exists($block, $hitAccessor))
            {
                return $block->get($hitAccessor);
            }
        }, $blocks->getArrayCopy());

        $this->responseCollection = $products;
    }

    /**
     * @param null | AccessorInterface $context
     * @return AccessorModelInterface
     */
    public function addAccessorContext(?AccessorInterface $context = null): AccessorModelInterface
    {
        parent::addAccessorContext($context);
        $this->setResponseCollection($context->getBlocks(), $context->getAccessorHandler()->getAccessorSetter("bx-hit"));

        return $this;
    }

}
