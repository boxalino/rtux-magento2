<?php
namespace Boxalino\RealTimeUserExperience\Registry;

use Boxalino\RealTimeUserExperience\Api\CurrentApiResponseViewRegistryInterface;
use Boxalino\RealTimeUserExperience\Model\Response\Page\ApiResponsePage;
use Boxalino\RealTimeUserExperienceApi\Service\Api\Response\ApiResponseViewInterface;
use Boxalino\RealTimeUserExperienceApi\Service\ErrorHandler\MissingDependencyException;

/**
 * Class CurrentApiResponseView
 * Registry for the API response view element
 *
 * @package Boxalino\RealTimeUserExperience\Registry
 */
class CurrentApiResponseView implements CurrentApiResponseViewRegistryInterface
{
    /**
     * @var ApiResponseViewInterface
     */
    private $apiResponseView = null;

    public function set(ApiResponseViewInterface $apiResponseView)
    {
        $this->apiResponseView = $apiResponseView;
    }

    public function get()
    {
        return $this->apiResponseView;
    }

}
