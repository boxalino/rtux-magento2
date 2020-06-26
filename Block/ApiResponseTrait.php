<?php
namespace Boxalino\RealTimeUserExperience\Block;

use Boxalino\RealTimeUserExperience\Api\ApiResponseBlockInterface;
use Boxalino\RealTimeUserExperienceApi\Framework\Content\Page\ApiResponsePageInterface;

/**
 * Trait ApiResponseTrait
 * Setters and getters for the generic ApiRendererInterface blocks
 *
 * @package Boxalino\RealTimeUserExperience\Block
 */
trait ApiResponseTrait
{

    /**
     * @var ApiResponsePageInterface
     */
    protected $apiResponsePage;

    /**
     * @param ApiResponsePageInterface $apiResponsePage
     * @return $this
     */
    public function setApiResponsePage(ApiResponsePageInterface $apiResponsePage) : ApiResponseBlockInterface
    {
        $this->apiResponsePage = $apiResponsePage;
        return $this;
    }

    /**
     * @return ApiResponsePageInterface
     */
    public function getApiResponsePage() :ApiResponsePageInterface
    {
        return $this->apiResponsePage;
    }
}
