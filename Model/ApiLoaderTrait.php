<?php
namespace Boxalino\RealTimeUserExperience\Model;

use Boxalino\RealTimeUserExperienceApi\Service\Api\ApiCallServiceInterface;

/**
 * Trait ApiLoaderTrait
 * @package Boxalino\RealTimeUserExperience\Model
 */
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

    /** @var ApiCallServiceInterface */
    protected $apiCallService;

    protected function _beforeApiCallService(): void {}

    /**
     * @return string
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getContextId() : string
    {
        return \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
    }
}
