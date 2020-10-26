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
    public function set(ApiResponseViewInterface $apiResponseView);

    /**
     * @return ApiResponseViewInterface|null
     */
    public function get(): ApiResponseViewInterface;

}
