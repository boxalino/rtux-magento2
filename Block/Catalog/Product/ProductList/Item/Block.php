<?php
namespace Boxalino\RealTimeUserExperience\Block\Catalog\Product\ProductList\Item;

use Boxalino\RealTimeUserExperience\Api\ApiBlockAccessorInterface;
use Boxalino\RealTimeUserExperience\Api\ApiProductBlockAccessorInterface;
use Boxalino\RealTimeUserExperience\Api\ApiRendererInterface;
use Boxalino\RealTimeUserExperience\Block\ApiBlockTrait;
use Boxalino\RealTimeUserExperience\Block\FrameworkBlockTrait;
use Boxalino\RealTimeUserExperienceApi\Service\Api\Response\Accessor\AccessorInterface;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Block\Product\Context;
use Magento\Catalog\Block\Product\ProductList\Item\Block as ItemBlock;
use Magento\Catalog\Block\Product\AwareInterface as ProductAwareInterface;
use Magento\Catalog\Model\Product;
use Magento\Framework\App\ActionInterface;
use Magento\Framework\Url\Helper\Data as UrlHelper;
use Magento\Catalog\Api\ProductRepositoryInterface;

/**
 * Class Block
 *
 * Product Item box on listing views
 * The product is set from the parent block which loads a collection
 * Alternatively, it is possible to load the product based on the product ID from the JSON response ::getApiProduct()
 *
 * @package Boxalino\RealTimeUserExperience\Block\Catalog\Product\ProductList\Item
 */
class Block extends ItemBlock
    implements ProductAwareInterface, ApiRendererInterface, ApiProductBlockAccessorInterface
{
    use ApiBlockTrait;
    use FrameworkBlockTrait;

    /**
     * @var UrlHelper
     */
    protected $urlHelper;

    /**
     * @var ProductRepositoryInterface
     */
    protected $productRepository;

    /**
     * @var ProductInterface
     */
    protected $product = null;

    /** @var AccessorInterface */
    protected $_apiItem;

    public function __construct(
        UrlHelper $urlHelper,
        ProductRepositoryInterface $productRepository,
        Context $context, array $data = []
    ){
        parent::__construct($context, $data);
        $this->urlHelper = $urlHelper;
        $this->productRepository = $productRepository;
    }

    /**
     * If the product has not been loaded from the collection (set via parent block) - load it based on the product ID
     *
     * @return \Magento\Catalog\Api\Data\ProductInterface|Product|mixed|void
     */
    public function getProduct()
    {
        $product = parent::getProduct();
        if(!$product)
        {
            $this->setProduct($this->productRepository->getById($this->getProductId()));
        }

        return parent::getProduct();
    }

    /**
     * @return string
     */
    protected function getProductId() : string
    {
        if($this->getRtuxGroupBy() == 'id')
        {
            return $this->getApiItem()->getId();
        }

        return $this->getApiItem()->get($this->getRtuxGroupBy())[0];
    }

    /**
     * Update if the Item block is to display other child elements per your layout design
     *
     * @param ApiBlockAccessorInterface $block
     * @return $this|ApiRendererInterface|null
     */
    public function getApiBlock(ApiBlockAccessorInterface $block): ?ApiRendererInterface
    {
        return $this;
    }

    /**
     * Accessing the data as returned in the response
     * Object built with the returnFields property from the request
     * or based on the scenario rule (from Boxalino Intelligence)
     */
    public function getApiItem() : ?AccessorInterface
    {
        return $this->getBlock()->getBxHit();
    }

    /**
     * @param AccessorInterface $item
     */
    public function setApiItem(AccessorInterface $item): void
    {
       $this->_apiItem = $item;
    }

    /**
     * Setting the "addto" block (unless configured via narrative)
     * <block
     *      class="Magento\Catalog\Block\Product\ProductList\Item\AddTo\Compare" name="category.product.addto.compare"
     *      as="compare" template="Magento_Catalog::product/list/addto/compare.phtml"
     * />
     * Can be extended to include and other blocks
     * @param string $alias
     * @return bool|\Magento\Framework\View\Element\BlockInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getChildBlock($alias)
    {
        if($alias === 'addto')
        {
            return  $this->getLayout()
                ->createBlock("Magento\Catalog\Block\Product\ProductList\Item\AddTo\Compare",
                    "addto" . $this->getProductId())
                ->setTemplate("Magento_Catalog::product/list/addto/compare.phtml");
        }

        return parent::getChildBlock($alias);
    }

    /**
     * Get post parameters
     *
     * @duplicate from Magento Core
     * @param Product $product
     * @return array
     */
    public function getAddToCartPostParams(Product $product)
    {
        $url = $this->getAddToCartUrl($product);
        return [
            'action' => $url,
            'data' => [
                'product' => $product->getEntityId(),
                ActionInterface::PARAM_NAME_URL_ENCODED => $this->urlHelper->getEncodedUrl($url),
            ]
        ];
    }

    /**
     * @return string
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getCurrencyCode() : string
    {
        return $this->_storeManager->getStore()->getCurrentCurrency()->getCode();
    }


}
