<?php
namespace Boxalino\RealTimeUserExperience\Block;

use Boxalino\RealTimeUserExperience\Api\ApiBlockAccessorInterface;
use Boxalino\RealTimeUserExperienceApi\Framework\Content\Page\ApiLoaderInterface;
use Boxalino\RealTimeUserExperienceApi\Service\Api\Request\ContextInterface;
use Boxalino\RealTimeUserExperienceApi\Service\Api\Request\RequestInterface;
use Boxalino\RealTimeUserExperienceApi\Service\Api\Response\ApiResponseViewInterface;
use Boxalino\RealTimeUserExperienceApi\Service\Api\Response\ResponseDefinitionInterface;
use Boxalino\RealTimeUserExperienceApi\Service\ErrorHandler\MissingDependencyException;
use Magento\Framework\DataObject;

/**
 * Trait ApiLoaderTrait
 * Setters and getters for the generic ApiRendererInterface blocks
 *
 * NOTICE: Integration sample available for the use of the Block\Api from the Integration Layer repository
 * NOTICE: THE ABSTRACT FUNCTIONS ARE DECLARED IN ORDER TO ENFORCE A CONTEXT OF \Magento\Framework\View\Element\Template
 *
 * @package Boxalino\RealTimeUserExperience\Block
 */
trait ApiBlockLoaderTrait
{

    use ApiBlockTrait;

    /**
     * @var RequestInterface
     */
    protected $requestWrapper;

    /**
     * @var ContextInterface
     */
    protected $apiContext;

    /**
     * As seen in the Magento\Framework\DataObject;
     *
     * @param string $key
     * @param string|int $index
     * @return mixed
     */
    abstract public function getData($key = '', $index = null);

    /**
     * As seen in the Magento\Framework\DataObject;
     *
     * @param string|array $key
     * @param mixed $value
     * @return $this
     */
    abstract public function setData($key, $value = null);

    /**
     * Get request for the Context
     * As seen in Magento\Framework\View\Element\AbstractBlock
     *
     * @return \Magento\Framework\App\RequestInterface
     */
    abstract public function getRequest();


    /**
     * Access the blocks from the widget-linked API loader
     *
     * @return \ArrayIterator
     */
    public function getBlocks() : \ArrayIterator
    {
        if($this->currentApiResponse->getByWidget($this->getData("widget")) instanceof ResponseDefinitionInterface)
        {
            return $this->getApiLoader()->getApiResponsePage()->getBlocks();
        }

        return new \ArrayIterator();
    }

    /**
     * @return \ArrayIterator
     */
    public function getBlocksByBxContent() : \ArrayIterator
    {
        if($this->currentApiResponse->getByWidget($this->getData("widget")) instanceof ResponseDefinitionInterface)
        {
            /** @var ApiBlockAccessorInterface $apiBlock */
            foreach($this->getApiLoader()->getApiResponsePage()->getBlocks() as $apiBlock)
            {
                /** both apiBlock section property and layout XML section is CUSTOM for your own project/logic/block accessor */
                if($apiBlock->getPosition() === $this->getData("position"))
                {
                    /** set the block in order to properly access the bxAttributes (see ApiBlockTrait) */
                    $this->setBlock($apiBlock);

                    return $apiBlock->getBlocks();
                }
            }
        }

        return new \ArrayIterator();
    }

    /**
     * Create an ApiLoader
     *
     * @return void
     */
    protected function _prepareApiLoader() : void
    {
        $widget = $this->getData("widget");

        $this->apiLoader
            ->addApiContext($this->getApiContext(), $widget)
            ->setRequest($this->requestWrapper->setRequest($this->getRequest()))
            ->create($widget);
    }

    /**
     * Set the context (XML-defined) parameters to the API context
     *
     * @return void
     */
    protected function _prepareApiContext() : void
    {
        /** the configurations for the context can be defined via XML or directly in the $apiContext model */
        $apiContext = $this->getData("apiContext")
            ->setWidget($this->getData("widget"))
            ->setHitCount($this->getData("hitCount"))
            ->setGroupBy($this->getData("groupBy"))
            ->set("returnFields", $this->getData("returnFields"));

        $this->setApiContext($apiContext);
    }

    /**
     * @return ContextInterface
     */
    public function getApiContext(): ContextInterface
    {
        return $this->apiContext;
    }

    /**
     * @param ContextInterface $apiContext
     */
    public function setApiContext(ContextInterface $apiContext): void
    {
        $this->apiContext = $apiContext;
        $this->setData("apiContext", $apiContext);
    }

    /**
     * Access the apiLoader with the adjacent elements (response) for it
     * (changed the strategy from ApiBlockTrait)
     * @return ApiLoaderInterface
     */
    public function getApiLoader() : ApiLoaderInterface
    {
        $apiLoader = $this->apiLoader->getApiLoaderByWidget($this->getData("widget"));
        if($apiLoader instanceof ApiLoaderInterface)
        {
            return $apiLoader;
        }

        if($this->apiLoader)
        {
            return $this->apiLoader;
        }

        throw new MissingDependencyException("The Api Loader for {$this->getData("widget")} is not available");
    }

    /**
     * Register the generated API response for targetted widget
     */
    public function addResponseToRegistry() : void
    {
        $this->currentApiResponse->addByWidget($this->getData("widget"), $this->currentApiResponse->get());
        $this->currentApiResponseView->addByWidget($this->getData("widget"), $this->currentApiResponseView->get());
    }

    /**
     * (changed the strategy from ApiBlockTrait)
     *
     * @return bool
     */
    public function isApiFallback() : bool
    {
        if($this->currentApiResponse->getByWidget($this->getData("widget")) instanceof ResponseDefinitionInterface
            && $this->currentApiResponseView->getByWidget($this->getData("widget")) instanceof ApiResponseViewInterface)
        {
            return $this->currentApiResponseView->getByWidget($this->getData("widget"))->isFallback();
        }

        if($this->currentApiResponse->get() instanceof ResponseDefinitionInterface
            && $this->currentApiResponseView->get() instanceof ApiResponseViewInterface)
        {
            return $this->currentApiResponseView->get()->isFallback();
        }

        return true;
    }


}
