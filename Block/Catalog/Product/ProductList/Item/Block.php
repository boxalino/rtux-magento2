<?php
namespace Boxalino\RealTimeUserExperience\Block\Catalog\Product\ProductList\Item;

use Boxalino\RealTimeUserExperience\Api\ApiBlockAccessorInterface;
use Boxalino\RealTimeUserExperience\Api\ApiProductBlockAccessorInterface;
use Boxalino\RealTimeUserExperience\Api\ApiRendererInterface;
use Boxalino\RealTimeUserExperience\Block\ApiBlockTrait;
use Boxalino\RealTimeUserExperience\Service\Api\Util\RequestParametersTrait;
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
class Block extends ItemBlock implements ProductAwareInterface, ApiRendererInterface, ApiProductBlockAccessorInterface
{
    use ApiBlockTrait;
    use RequestParametersTrait;

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
        if($this->getRtuxGroupBy()=='id')
        {
            return $this->getApiProduct()->getId();
        }

        return $this->getBlock()->getProduct()->get($this->getRtuxGroupBy())[0];
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
    public function getApiProduct() : ?AccessorInterface
    {
        return $this->getBlock()->getProduct();
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
                    "addto" . $this->getApiProduct()->getId())
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
     * Block view mode to switch from list view to grid view (Magento)
     * Use the general configuration for product list mode from config path catalog/frontend/list_mode as default value
     *
     * @duplicate from Toolbar block
     * @return string
     */
    public function getMode() : string
    {
        return $this->getRequest()->getParam($this->getBlockViewModeParameter(), "grid");
    }

}
