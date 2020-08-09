<?php
namespace Boxalino\RealTimeUserExperience\Block\LayeredNavigation;

use Boxalino\RealTimeUserExperience\Api\ApiRendererInterface;
use Boxalino\RealTimeUserExperience\Api\ApiResponseBlockInterface;
use Boxalino\RealTimeUserExperience\Block\ApiBlockTrait;
use Boxalino\RealTimeUserExperience\Model\Request\ApiPageLoader;
use Boxalino\RealTimeUserExperience\Api\CurrentApiResponseRegistryInterface;
use Boxalino\RealTimeUserExperience\Api\CurrentApiResponseViewRegistryInterface;
use Boxalino\RealTimeUserExperienceApi\Service\Api\Request\ContextInterface;
use Boxalino\RealTimeUserExperienceApi\Service\Api\Request\RequestInterface;
use Magento\LayeredNavigation\Block\Navigation as MagentoNavigation;
use Magento\Framework\View\Element\Template\Context;
use Magento\Catalog\Model\Layer\Resolver;
use Magento\Catalog\Model\Layer\FilterList;
use Magento\Catalog\Model\Layer\AvailabilityFlagInterface;

/**
 * Class LayeredNavigation
 *
 * Generally, in the context of layered navigation (search, navigation, etc)
 * Extends from the Magento Layered Navigation block in order to ensure a fallback strategy
 * The $apiContext argument is defined in the definition of the virtual type (di.xml)
 *
 * @package Boxalino\RealTimeUserExperience\Block\LayeredNavigation
 */
class Navigation extends MagentoNavigation
    implements ApiRendererInterface
{

    use ApiBlockTrait;

    /**
     * @var CurrentApiResponseRegistryInterface
     */
    protected $currentApiResponse;

    /**
     * @var CurrentApiResponseViewRegistryInterface
     */
    protected $currentApiResponseView;

    /**
     * @var ContextInterface
     */
    protected $apiContext;

    /**
     * @var RequestInterface
     */
    protected $requestWrapper;

    public function __construct(
        CurrentApiResponseRegistryInterface $currentApiResponse,
        CurrentApiResponseViewRegistryInterface $currentApiResponseView,
        ApiPageLoader $apiPageLoader,
        ContextInterface $apiContext,
        RequestInterface $requestWrapper,
        Context $context,
        Resolver $layerResolver,
        FilterList $filterList,
        AvailabilityFlagInterface $visibilityFlag,
        array $data = []
    ){
        parent::__construct($context, $layerResolver, $filterList, $visibilityFlag, $data);
        $this->currentApiResponse = $currentApiResponse;
        $this->currentApiResponseView = $currentApiResponseView;
        $this->requestWrapper = $requestWrapper;
        $this->apiLoader = $apiPageLoader;
        $this->apiContext = $apiContext;
    }

    /**
     * The layered navigation blocks are by default located on the left container
     * In the assumption that the Boxalino Intelligence Layout Block has position:left,
     * it is expected for the blocks to be located under that response "parameter"
     *
     * @return \ArrayIterator
     */
    public function getBlocks() : \ArrayIterator
    {
        if($this->currentApiResponse->get() && $this->currentApiResponseView->get()->isFallback())
        {
            return new \ArrayIterator();
        }

        return $this->currentApiResponse->get()->getLeft();
    }

    /**
     * Default: use default template in case of fallback
     * Boxalino API: use the generic template to render blocks and children
     *
     * @return string
     */
    public function getTemplate()
    {
        if($this->currentApiResponse->get() && $this->currentApiResponseView->get()->isFallback())
        {
            return parent::getTemplate();
        }

        return ApiResponseBlockInterface::BOXALINO_RTUX_API_BLOCK_TEMPLATE_DEFAULT;
    }

    /**
     * Default: apply layer
     * Boxalino API: makes the API request (technically, for the layered navigation - the request is already done by the Result
     *
     * @return $this
     */
    protected function _prepareLayout()
    {
        try{
            if($this->currentApiResponse->get())
            {
                $this->_logger->info("LAYERED NAVIGATION - HAS REQUEST");
                return $this;
            }

            $this->_logger->info("LAYERED NAVIGATION - GET REQUEST");
            $this->apiLoader
                ->setRequest($this->requestWrapper->setRequest($this->_request))
                ->setApiContext($this->apiContext)
                ->load();
        } catch (\Throwable $exception)
        {
            $this->apiLoader->getApiResponsePage()->setFallback(true);
            $this->_logger->warning("BoxalinoAPI Layered Navigation Error: " . $exception->getMessage());

            return parent::_prepareLayout();
        }

        return $this;
    }

    /**
     * Default: loads toolbar
     * Boxalino API: does nothing
     *
     * @inheritdoc
     * @since 100.3.4
     */
    protected function _beforeToHtml()
    {
        if($this->currentApiResponse->get() && $this->currentApiResponseView->get()->isFallback())
        {
            return parent::_beforeToHtml();
        }

        return $this;
    }

    /**
     * Default: Check availability display layer block
     * Boxalino API: always display
     *
     * @return bool
     */
    public function canShowBlock()
    {
        if($this->currentApiResponse->get() && $this->currentApiResponseView->get()->isFallback())
        {
            return parent::canShowBlock();
        }

        return true;
    }

}
