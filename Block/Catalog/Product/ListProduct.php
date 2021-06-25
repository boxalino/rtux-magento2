<?php
namespace Boxalino\RealTimeUserExperience\Block\Catalog\Product;

use Boxalino\RealTimeUserExperience\Api\ApiBlockAccessorInterface;
use Boxalino\RealTimeUserExperience\Api\ApiProductListingBlockAccessorInterface;
use Boxalino\RealTimeUserExperience\Api\ApiProductBlockAccessorInterface;
use Boxalino\RealTimeUserExperience\Api\ApiRendererInterface;
use Boxalino\RealTimeUserExperience\Block\Api\ListApi;
use Boxalino\RealTimeUserExperience\Model\Response\Content\ApiEntityCollection;
use Boxalino\RealTimeUserExperienceApi\Service\Api\Response\Accessor\BlockInterface;
use Boxalino\RealTimeUserExperienceApi\Service\ErrorHandler\MissingDependencyException;
use Magento\Catalog\Api\Data\ProductInterface;

/**
 * Class ListProduct
 * Manages a product collection from the response
 * Besides the obvious speed performance, it is also desired for the pages/blocks to be non-cacheable
 *
 * As observed, it does NOT extend the base Magento2 blocks :
 * Magento\Catalog\Block\Product\AbstractProduct or Magento\Catalog\Block\Product\ListProduct
 *
 * @package Boxalino\RealTimeUserExperience\Block\Catalog\Product
 */
class ListProduct extends ListApi
    implements ApiRendererInterface, ApiProductListingBlockAccessorInterface
{

    /**
     * @var null | \Magento\Eav\Model\Entity\Collection\AbstractCollection
     */
    protected $_productCollection = null;

    /**
     * @var null | \ArrayIterator
     */
    protected $_collectionIterator = null;


    /**
     * @return \Magento\Eav\Model\Entity\Collection\AbstractCollection|null
     */
    public function getLoadedProductCollection(): ?\Magento\Eav\Model\Entity\Collection\AbstractCollection
    {
        if (is_null($this->_productCollection)) {
            /** @var ApiEntityCollection $apiCollectionModel */
            $apiCollectionModel = $this->getBlock()->getModel();
            $productCollection = $apiCollectionModel->getCollection();

            $this->_eventManager->dispatch(
                'rtux_api_block_product_list_collection',
                ['collection' => $productCollection]
            );

            $this->_productCollection = $productCollection;
        }

        return $this->_productCollection;
    }


    /**
     * The block is aware of the expected children elements (products)
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
                if ($apiBlock instanceof ApiProductBlockAccessorInterface)
                {
                    /** @var null |  ProductInterface $product only add a child block if the product ID has been loaded */
                    if ($product = $this->getProductById($this->getProductId($block)))
                    {
                        $apiBlock = $this->addApiRendererPropertiesOnBlock($apiBlock, $block);
                        $apiBlock->setProduct($product);

                        return $apiBlock;
                    }

                    /** do not generate an empty child/div if the product ID was not loaded into the collection */
                    return null;
                }

                return $this->addApiRendererPropertiesOnBlock($apiBlock, $block);
            }

            return $apiBlock;
        } catch (\Throwable $exception) {
            $this->_logger->warning("BoxalinoAPI ListProduct ERROR: " . $exception->getMessage());

            return null;
        }
    }

    /**
     * @param ApiBlockAccessorInterface $block
     * @return string
     */
    protected function getProductId(ApiBlockAccessorInterface $block) : string
    {
        if($this->getRtuxGroupBy() == 'id')
        {
            return $block->getBxHit()->getId();
        }

        try{
            $value = $block->getBxHit()->get($this->getRtuxGroupBy());
            return $value[0];
        } catch (\Throwable $exception)
        {
            throw new MissingDependencyException("The field: {$this->getRtuxGroupBy()} is not part of the API request returnFields");
        }
    }

    /**
     * Accesses product from the collection based on the product ID
     * Used to set the product in the child block
     * as seen in ::getApiBlock()
     *
     * @param int $index
     * @return ProductInterface | null
     */
    public function getProductById(int $index) : ?ProductInterface
    {
        if(is_null($this->_collectionIterator))
        {
            $this->_collectionIterator = $this->getLoadedProductCollection()->getIterator();
        }

        if($this->_collectionIterator->offsetExists($index))
        {
            return $this->_collectionIterator->offsetGet($index);
        }

        return null;
    }


}
