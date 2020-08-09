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

}
