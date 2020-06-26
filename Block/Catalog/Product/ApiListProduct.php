<?php
namespace Boxalino\RealTimeUserExperience\Block\Catalog\Product;

use Boxalino\RealTimeUserExperience\Api\ApiBlockAccessorInterface;
use Boxalino\RealTimeUserExperience\Api\ApiListingBlockAccessorInterface;
use Boxalino\RealTimeUserExperience\Api\ApiProductBlockAccessorInterface;
use Boxalino\RealTimeUserExperience\Api\ApiRendererInterface;
use Boxalino\RealTimeUserExperience\Block\ApiBlockTrait;
use Boxalino\RealTimeUserExperience\Model\Response\Content\ApiEntityCollection;
use Boxalino\RealTimeUserExperience\Service\Api\Util\RequestParametersTrait;
use Boxalino\RealTimeUserExperienceApi\Service\Api\Response\Accessor\BlockInterface;
use Boxalino\RealTimeUserExperienceApi\Service\ErrorHandler\MissingDependencyException;
use Magento\Catalog\Api\Data\ProductInterface;

/**
 * Class ApiListProduct
 * Manages a product collection from the response
 * As observed, it does not extend the base Magento2 blocks :
 * Magento\Catalog\Block\Product\AbstractProduct or Magento\Catalog\Block\Product\ListProduct
 *
 * @package Boxalino\RealTimeUserExperience\Block\Catalog\Product
 */
class ApiListProduct extends \Magento\Framework\View\Element\Template
    implements ApiRendererInterface, ApiListingBlockAccessorInterface
{
    use ApiBlockTrait;
    use RequestParametersTrait;

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
    public function getApiBlock(ApiBlockAccessorInterface $block) : ApiRendererInterface
    {
        if(!$block->getType())
        {
            throw new MissingDependencyException("BoxalinoAPI RenderBlock Error: the block type is missing: " . json_encode($block));
        }

        if(!$block->getTemplate())
        {
            throw new MissingDependencyException("BoxalinoAPI RenderBlock Error: the block template is missing: " . json_encode($block));
        }

        $apiBlock = $this->getLayout()->createBlock($block->getType(), $block->getName())
            ->setTemplate($block->getTemplate());

        if($apiBlock instanceof ApiRendererInterface)
        {
            $apiBlock->setRtuxVariantUuid($this->getRtuxVariantUuid())
                ->setRtuxGroupBy($this->getRtuxGroupBy())
                ->setBlock($block);
        }

        if($apiBlock instanceof ApiProductBlockAccessorInterface)
        {
            $apiBlock->setProduct($this->getProductById($block->getProduct()->getId()));
        }

        return $apiBlock;
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

    /**
     * Block view mode to switch from list view to grid view (Magento)
     *
     * @duplicate from Toolbar block
     * @return string
     */
    public function getMode() : string
    {
        return $this->getRequest()->getParam($this->getBlockViewModeParameter(), "grid");
    }

}
