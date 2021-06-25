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
     * @return \Magento\Framework\Event\ManagerInterface
     */
    public function getEventManager(): \Magento\Framework\Event\ManagerInterface
    {
        return $this->eventManager;
    }

    /**
     * @param \Magento\Framework\Event\ManagerInterface $eventManager
     * @return void
     */
    public function setEventManager(\Magento\Framework\Event\ManagerInterface $eventManager): void
    {
        $this->eventManager = $eventManager;
    }

    /**
     * @return \Boxalino\RealTimeUserExperience\Helper\Configuration
     */
    public function getStoreConfigurationHelper(): \Boxalino\RealTimeUserExperience\Helper\Configuration
    {
        return $this->storeConfigurationHelper;
    }

    /**
     * @param \Boxalino\RealTimeUserExperience\Helper\Configuration $storeConfigurationHelper
     * @return void
     */
    public function setStoreConfigurationHelper(\Boxalino\RealTimeUserExperience\Helper\Configuration $storeConfigurationHelper): void
    {
        $this->storeConfigurationHelper = $storeConfigurationHelper;
    }


}
