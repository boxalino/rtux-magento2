<?php
namespace Boxalino\RealTimeUserExperience\Model\Request;

use Boxalino\RealTimeUserExperience\Framework\Request\ContextTrait;
use Boxalino\RealTimeUserExperience\Framework\Request\RequestParametersTrait;
use Boxalino\RealTimeUserExperience\Helper\Configuration as StoreConfigurationHelper;
use Boxalino\RealTimeUserExperience\Model\ApiLoaderTrait;
use Boxalino\RealTimeUserExperienceApi\Framework\Content\Page\ApiPageLoaderAbstract;
use Boxalino\RealTimeUserExperienceApi\Framework\Content\Page\ApiResponsePageInterface;
use Boxalino\RealTimeUserExperienceApi\Service\Api\ApiCallServiceInterface;
use Boxalino\RealTimeUserExperienceApi\Service\Api\Request\RequestInterface;
use Boxalino\RealTimeUserExperienceApi\Service\Api\Response\ApiResponseViewInterface;
use Boxalino\RealTimeUserExperienceApi\Service\Api\Util\ConfigurationInterface;

class ApiPageLoader extends ApiPageLoaderAbstract
{

    use ApiLoaderTrait;
    use ContextTrait;
    use RequestParametersTrait;

    CONST RTUX_API_LOADER_EVENT = "boxalino_rtux_api_loader_after";

    public function __construct(
        ApiCallServiceInterface $apiCallService,
        ConfigurationInterface $configuration,
        ApiResponsePageInterface $apiResponsePage,
        \Magento\Framework\Event\ManagerInterface $eventManager,
        StoreConfigurationHelper $storeConfigurationHelper
    ){
        parent::__construct($apiCallService, $configuration);
        $this->apiResponsePage = $apiResponsePage;
        $this->eventManager =$eventManager;
        $this->storeConfigurationHelper = $storeConfigurationHelper;
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
        return $this->apiResponsePage;
    }
}
