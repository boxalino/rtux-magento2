<?php
namespace Boxalino\RealTimeUserExperience\Registry;

use Boxalino\RealTimeUserExperience\Api\CurrentApiResponseViewRegistryInterface;
use Boxalino\RealTimeUserExperience\Model\Response\Page\ApiResponsePage;
use Boxalino\RealTimeUserExperienceApi\Service\Api\Response\ApiResponseViewInterface;

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
    private $apiResponseView;

    public function set(ApiResponseViewInterface $apiResponseView): void
    {
        $this->apiResponseView = $apiResponseView;
    }

    public function get(): ?ApiResponseViewInterface
    {
        return $this->apiResponseView ?? null;
    }

}
