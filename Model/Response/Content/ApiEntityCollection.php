<?php declare(strict_types=1);
namespace Boxalino\RealTimeUserExperience\Model\Response\Content;

use Boxalino\RealTimeUserExperienceApi\Framework\Content\Listing\ApiEntityCollectionInterface;
use Boxalino\RealTimeUserExperienceApi\Service\Api\Response\Accessor\AccessorModelInterface;
use Boxalino\RealTimeUserExperienceApi\Framework\Content\Listing\ApiEntityCollectionModel;
use Magento\Catalog\Api\Data\ProductSearchResultsInterface;
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
class ApiEntityCollection extends ApiEntityCollectionModel
    implements AccessorModelInterface, ApiEntityCollectionInterface

{
    /**
     * @var null | ProductSearchResultsInterface
     */
    protected $collection = null;

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
     *
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

            /** order the product items to match the returned order by API response */
            foreach($this->getHitIds() as $id)
            {
                foreach($collection as $product)
                {
                    if($product->getId() === $id)
                    {
                        $collection->removeItemByKey($id);
                        $collection->addItem($product);
                    }
                }
            }

            $this->collection = $collection;
        }

        return $this->collection;
    }


}
