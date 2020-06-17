<?php
namespace Boxalino\RealTimeUserExperience\Model\Request;

use Boxalino\RealTimeUserExperience\Framework\Request\ContextTrait;
use Boxalino\RealTimeUserExperience\Framework\Request\RequestParametersTrait;
use Boxalino\RealTimeUserExperience\Helper\Configuration as StoreConfigurationHelper;
use Boxalino\RealTimeUserExperience\Model\ApiLoaderTrait;
use Boxalino\RealTimeUserExperience\Model\Response\Page\ApiResponsePage;
use Boxalino\RealTimeUserExperienceApi\Framework\Content\Page\ApiPageLoaderAbstract;
use Boxalino\RealTimeUserExperienceApi\Framework\Content\Page\ApiResponsePageInterface;
use Boxalino\RealTimeUserExperienceApi\Service\Api\ApiCallServiceInterface;
use Boxalino\RealTimeUserExperienceApi\Service\Api\Request\ContextInterface;
use Boxalino\RealTimeUserExperienceApi\Service\Api\Util\ConfigurationInterface;
use Symfony\Component\HttpFoundation\Request;

class ApiPageLoader extends ApiPageLoaderAbstract
{

    use ApiLoaderTrait;
    use ContextTrait;
    use RequestParametersTrait;

    CONST RTUX_API_LOADER_EVENT = "boxalino_rtux_api_loader_after";

    public function __construct(
        ApiCallServiceInterface $apiCallService,
        ConfigurationInterface $configuration,
        \Magento\Framework\Event\ManagerInterface $eventManager,
        StoreConfigurationHelper $storeConfigurationHelper
    ){
        parent::__construct($apiCallService, $configuration);
        $this->eventManager =$eventManager;
        $this->storeConfigurationHelper = $storeConfigurationHelper;
    }

    /**
     * @param ContextInterface $context
     */
    protected function prepareContext(ContextInterface $context): void
    {
        return;
    }

    /**
     * @param Request $request
     * @param ApiResponsePageInterface $page
     * @return mixed|void
     */
    protected function dispatchEvent(Request $request, ApiResponsePageInterface $page) : void
    {
        $this->eventManager->dispatch(self::RTUX_API_LOADER_EVENT,
            ["content" => $page, "request" => $request]
        );
    }

    /**
     * @param Request $request
     * @return ApiResponsePageInterface
     */
    public function getApiResponsePage(Request $request): ApiResponsePageInterface
    {
        return new ApiResponsePage();
    }
}
