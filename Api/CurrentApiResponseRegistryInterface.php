<?php
namespace Boxalino\RealTimeUserExperience\Api;
use Boxalino\RealTimeUserExperienceApi\Service\Api\Response\ResponseDefinitionInterface;

/**
 * Interface CurrentApiResponseRegistryInterface
 * Data registry to store the available API response to reuse within multiple layout segments
 *
 * @package Boxalino\RealTimeUserExperience\Api
 */
interface CurrentApiResponseRegistryInterface
{

    /**
     * Setting the current/active API response
     *
     * @param ResponseDefinitionInterface $apiResponse
     */
    public function set(ResponseDefinitionInterface $apiResponse);

    /**
     * Accessing the available API response
     *
     * @return ResponseDefinitionInterface|null
     */
    public function get(): ResponseDefinitionInterface;

}
