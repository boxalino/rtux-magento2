<?php
namespace Boxalino\RealTimeUserExperience\Model\Request;

use Boxalino\RealTimeUserExperience\Api\CurrentApiResponseRegistryInterface;
use Boxalino\RealTimeUserExperience\Api\CurrentApiResponseViewRegistryInterface;
use Boxalino\RealTimeUserExperience\Service\Api\Util\ContextTrait;
use Boxalino\RealTimeUserExperience\Service\Api\Util\RequestParametersTrait;
use Boxalino\RealTimeUserExperience\Helper\Configuration as StoreConfigurationHelper;
use Boxalino\RealTimeUserExperience\Model\ApiLoaderTrait;
use Boxalino\RealTimeUserExperienceApi\Framework\Content\Page\ApiResponsePage;
use Boxalino\RealTimeUserExperienceApi\Framework\Content\Page\ApiLoaderInterface;
use Boxalino\RealTimeUserExperienceApi\Framework\Content\Page\ApiPageLoaderAbstract;
use Boxalino\RealTimeUserExperienceApi\Framework\Content\Page\ApiResponsePageInterface;
use Boxalino\RealTimeUserExperienceApi\Service\Api\ApiCallServiceInterface;
use Boxalino\RealTimeUserExperienceApi\Service\Api\Request\RequestInterface;
use Boxalino\RealTimeUserExperienceApi\Service\Api\Response\ApiResponseViewInterface;
use Boxalino\RealTimeUserExperienceApi\Service\Api\Util\ConfigurationInterface;

/**
 * Class ApiPageLoader
 *
 * @package Boxalino\RealTimeUserExperience\Model\Request
 */
class ApiPageLoader extends ApiPageLoaderAbstract
{

    use ApiLoaderTrait;
    use ContextTrait;
    use RequestParametersTrait;

    CONST RTUX_API_LOADER_EVENT = "boxalino_rtux_api_loader_after";

    /**
     * @var CurrentApiResponseRegistryInterface
     */
    protected $currentApiResponse;

    /**
     * @var CurrentApiResponseViewRegistryInterface
     */
    protected $currentApiResponseView;

    public function __construct(
        CurrentApiResponseRegistryInterface$currentApiResponse,
        CurrentApiResponseViewRegistryInterface $currentApiResponseView,
        ApiCallServiceInterface $apiCallService,
        ConfigurationInterface $configuration,
        \Magento\Framework\Event\ManagerInterface $eventManager,
        StoreConfigurationHelper $storeConfigurationHelper
    ){
        parent::__construct($apiCallService, $configuration);
        $this->currentApiResponse = $currentApiResponse;
        $this->currentApiResponseView = $currentApiResponseView;
        $this->eventManager =$eventManager;
        $this->storeConfigurationHelper = $storeConfigurationHelper;
    }

    public function load(): ApiLoaderInterface
    {
        parent::load();
        $this->registerCurrentApiResponse();
        $this->registerCurrentApiResponseView();

        return $this;
    }

    /**
     * Registering the api response
     * @return $this
     */
    protected function registerCurrentApiResponse()
    {
        if($this->apiCallService->isFallback())
        {
            return $this;
        }

        $this->currentApiResponse->set($this->apiCallService->getApiResponse());
        return $this;
    }

    /**
     * Registering the api response view
     * @return $this
     */
    protected function registerCurrentApiResponseView()
    {
        if($this->apiCallService->isFallback())
        {
            return $this;
        }

        $this->currentApiResponseView->set($this->getApiResponsePage());
        return $this;
    }

    /**
     * @param RequestInterface $request
     * @param ApiResponsePageInterface $page
     * @return mixed|void
     */
    protected function dispatchEvent(RequestInterface $request, ApiResponsePageInterface $page) : void
    {
        $this->eventManager->dispatch(self::RTUX_API_LOADER_EVENT,
            ["content" => $page, "request" => $request]
        );
    }

    /**
     * @return ApiResponseViewInterface
     */
    public function getApiResponsePage(): ?ApiResponseViewInterface
    {
        if(!$this->apiResponsePage)
        {
            $this->apiResponsePage = new ApiResponsePage();
        }

        return $this->apiResponsePage;
    }

}
