<?php
namespace Boxalino\RealTimeUserExperience\Block\Catalog\Product;

use Boxalino\RealTimeUserExperience\Api\ApiBlockAccessorInterface;
use Boxalino\RealTimeUserExperience\Api\ApiRendererInterface;
use Boxalino\RealTimeUserExperience\Block\ApiBlockTrait;
use Boxalino\RealTimeUserExperience\Model\Request\ApiPageLoader;
use Boxalino\RealTimeUserExperience\Model\Response\Content\ApiEntityCollection;
use Boxalino\RealTimeUserExperience\Api\CurrentApiResponseViewRegistryInterface;
use Boxalino\RealTimeUserExperienceApi\Service\Api\Request\RequestInterface;
use BoxalinoClientProject\BoxalinoIntegration\Model\Api\Request\Context\ItemContext;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Model\ResourceModel\Product\Collection;
use Magento\Catalog\Block\Product\View as MagentoProductView;

/**
 * Catalog product view recommendations block
 *
 * Can be used as the preference for:
 * Magento\Catalog\Block\Product\ProductList\Related
 * Magento\Catalog\Block\Product\ProductList\Crosssell
 * Magento\Catalog\Block\Product\ProductList\Upsell
 * and others
 *
 * It inherits from the \Magento\Catalog\Block\Product\View in order to avoid the use of the deprecated AbstractProduct element
 */
abstract class View extends MagentoProductView
    implements ApiRendererInterface
{

    use ApiBlockTrait;

    /**
     * @var ApiPageLoader
     */
    protected $apiLoader;

    /**
     * @var ItemContext
     */
    protected $apiContext;

    /**
     * @var RequestInterface
     */
    protected $requestWrapper;

    /**
     * @var CurrentApiResponseViewRegistryInterface
     */
    protected $currentApiResponseView;

    /**
     * @var Collection
     */
    protected $itemsCollection;

    public function __construct(
        CurrentApiResponseViewRegistryInterface $currentApiResponseView,
        ApiPageLoader $apiPageLoader,
        ItemContext $apiContext,
        RequestInterface $requestWrapper,
        \Magento\Catalog\Block\Product\Context $context,
        \Magento\Framework\Url\EncoderInterface $urlEncoder,
        \Magento\Framework\Json\EncoderInterface $jsonEncoder,
        \Magento\Framework\Stdlib\StringUtils $string,
        \Magento\Catalog\Helper\Product $productHelper,
        \Magento\Catalog\Model\ProductTypes\ConfigInterface $productTypeConfig,
        \Magento\Framework\Locale\FormatInterface $localeFormat,
        \Magento\Customer\Model\Session $customerSession,
        ProductRepositoryInterface $productRepository,
        \Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrency,
        array $data = []
    ){
        $this->currentApiResponseView = $currentApiResponseView;
        $this->requestWrapper = $requestWrapper;
        $this->apiLoader = $apiPageLoader;
        $this->apiContext = $apiContext;
        parent::__construct(
            $context, $urlEncoder, $jsonEncoder, $string, $productHelper, $productTypeConfig,
            $localeFormat, $customerSession, $productRepository, $priceCurrency, $data
        );
    }


    /**
     * The base widget recommendation as configured in the Boxalino Intelligence admin as the master request
     * Property either set as an argument on block, a configuration field or hardcoded
     * @required
     * @return string
     */
    abstract public function getWidget() : string;

    /**
     * Property either set as an argument on block, a configuration field or hardcoded
     * @required
     * @return string
     */
    abstract public function getHitCount() : string;

    /**
     * It can either be used the (deprecated) registry to access the product ID
     * OR the request parameter
     * (it is expected for the block to be used for the catalog_product_view layout)
     * @required
     * @return string|null
     */
    protected function getContextItemId() : ?string
    {
        $id = (int) $this->_request->getParam('id', false);
        if($id)
        {
            return (string) $id;
        }

        return null;
    }

    /**
     * Makes the Boxalino API request
     *
     * @return View|void
     */
    protected function _prepareLayout()
    {
        try{
            if($this->currentApiResponseView->get())
            {
                return parent::_prepareLayout();
            }

            $this->apiContext->setProductId($this->getContextItemId())
                ->setWidget($this->getWidget())
                ->setHitCount($this->getHitCount());

            $this->apiLoader
                ->setRequest($this->requestWrapper->setRequest($this->_request))
                ->setApiContext($this->apiContext)
                ->load();
        } catch (\Throwable $exception)
        {
            $this->apiLoader->getApiResponsePage()->setFallback(true);

            $this->_logger->warning("BoxalinoAPI PDP Error on {$this->getType()}: " . $exception->getMessage());
            $this->_logger->warning("BoxalinoAPI PDP Error on {$this->getType()}: " . $exception->getTraceAsString());
        }

        parent::_prepareLayout();
    }

    /**
     * @return Collection
     */
    public function getItems() : ?Collection
    {
        if(!$this->currentApiResponseView->get())
        {
            return null;
        }

        if ($this->itemsCollection === null)
        {
            $this->getCollectionByTypeMatch();
        }

        return $this->itemsCollection;
    }

    /**
     * Required for the upsell & crosssell-rule type
     * (default Magento2 template: Magento_Catalog::product/list/items.phtml)
     *
     * @return Collection
     */
    public function getItemCollection()
    {
        if($this->itemsCollection === null)
        {
            $this->_prepareData();
        }
        return $this->itemsCollection;
    }

    /**
     * Required for the related-rule and upsell-rule block types
     * (default Magento2 template: Magento_Catalog::product/list/items.phtml)
     *
     * @return \Magento\Framework\DataObject[]
     */
    public function getAllItems()
    {
        if($this->itemsCollection === null)
        {
            $this->_prepareData();
        }

        return $this->itemsCollection->getItems();
    }

    /**
     * Find out if some products can be easy added to cart
     * @duplicate from Magento\Catalog\Block\Product\ProductList\Related
     *
     * @required
     * @return bool
     */
    public function canItemsAddToCart()
    {
        foreach ($this->getItems() as $item) {
            if (!$item->isComposite() && $item->isSaleable() && !$item->getRequiredOptions()) {
                return true;
            }
        }
        return false;
    }

    /**
     * Before to html handler
     *
     * @return $this
     */
    protected function _beforeToHtml()
    {
        $this->_prepareData();
        return parent::_beforeToHtml();
    }

    /**
     * If there was an issue during the request - load default Magento2 related products
     * Loads the product collection from the API response block matching the type of the recommendation block
     *
     * @return $this
     */
    protected function _prepareData()
    {
        if(!$this->currentApiResponseView->get() || !$this->getContextItemId())
        {
            return parent::_prepareData();
        }

        $this->getCollectionByTypeMatch();
        return $this;
    }

    /**
     * Access the returned PDP collection based on the block type
     * (the block type is declared in the layout XML definition for the PDP context)
     *
     * @return $this
     */
    protected function getCollectionByTypeMatch() : self
    {
        /** @var ApiBlockAccessorInterface $apiBlock */
        foreach($this->currentApiResponseView->get()->getBlocks() as $apiBlock)
        {
            /** upsell, crosssell, related, other */
            if($apiBlock->getType() === $this->getType())
            {
                /** @var ApiEntityCollection $collectionModel */
                $collectionModel = $apiBlock->getModel();
                $this->itemsCollection = $collectionModel->getCollection();
            }
        }

        return $this;
    }

}
