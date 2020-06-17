<?php
namespace Boxalino\RealTimeUserExperience\Model;

use Boxalino\RealTimeUserExperienceApi\Service\Api\ApiCallServiceInterface;
use Magento\Framework\Event\ManagerInterface;
use \Boxalino\RealTimeUserExperience\Helper\Configuration;

trait ApiLoaderTrait
{
    /**
     * @var \Magento\Framework\Event\ManagerInterface
     */
    protected $eventManager;

    /**
     * @var \Boxalino\RealTimeUserExperience\Helper\Configuration
     */
    protected $storeConfigurationHelper;


    /**
     * @return mixed
     */
    protected function validateCall(ApiCallServiceInterface $apiCallService) : void
    {
        if($apiCallService->isFallback())
        {
            throw new \Exception($apiCallService->getFallbackMessage());
        }
    }

    /**
     * @return string
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getContextId() : string
    {
        return (string) $this->storeConfigurationHelper->getMagentoStoreId();
    }
}
