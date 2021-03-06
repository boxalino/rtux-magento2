<?php
namespace Boxalino\RealTimeUserExperience\Api;

use Boxalino\RealTimeUserExperienceApi\Service\Api\Response\ApiResponseViewInterface;

/**
 * Interface CurrentApiResponseViewRegistryInterface
 * Registry for the Boxalino\RealTimeUserExperienceApi\Service\Api\Response\ApiResponseViewInterface response view element
 *
 * @package Boxalino\RealTimeUserExperience\Api
 */
interface CurrentApiResponseViewRegistryInterface
{

    /**
     * @param ApiResponseViewInterface $apiResponseView
     */
    public function set(ApiResponseViewInterface $apiResponseView): void;

    /**
     * @return ApiResponseViewInterface|null
     */
    public function get(): ?ApiResponseViewInterface;

    /**
     * @param string $widget
     * @return ApiResponseViewInterface|null
     */
    public function getByWidget(string $widget) : ?ApiResponseViewInterface;

    /**
     * @param string $widget
     * @param ApiResponseViewInterface $apiResponse
     */
    public function addByWidget(string $widget, ApiResponseViewInterface $apiResponse) : void;

}
