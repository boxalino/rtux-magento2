<?php
namespace Boxalino\RealTimeUserExperience\Api;

use Boxalino\RealTimeUserExperienceApi\Framework\Content\Page\ApiResponsePageInterface;

/**
 * A response block will have access to the API response
 * (used in navigation, search contexts)
 *
 * @package Boxalino\RealTimeUserExperience\Api
 */
interface ApiResponseBlockInterface extends ApiRendererInterface
{
    /**
     * @param ApiResponsePageInterface $apiResponsePage
     * @return ApiResponseBlockInterface
     */
    public function setApiResponsePage(ApiResponsePageInterface $apiResponsePage) : ApiResponseBlockInterface;

    /**
     * @return ApiResponsePageInterface
     */
    public function getApiResponsePage() :ApiResponsePageInterface;

}
