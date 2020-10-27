<?php
namespace Boxalino\RealTimeUserExperience\Registry;

use Boxalino\RealTimeUserExperience\Api\CurrentApiResponseRegistryInterface;
use Boxalino\RealTimeUserExperienceApi\Service\Api\Response\ResponseDefinition;
use Boxalino\RealTimeUserExperienceApi\Service\Api\Response\ResponseDefinitionInterface;
use Boxalino\RealTimeUserExperienceApi\Service\ErrorHandler\MissingDependencyException;

/**
 * Class CurrentApiResponse
 * Acts as a registry for the API response
 *
 * @package Boxalino\RealTimeUserExperience\Registry
 */
class CurrentApiResponse implements CurrentApiResponseRegistryInterface
{
    /**
     * @var ResponseDefinitionInterface
     */
    private $apiResponse = null;

    public function set(ResponseDefinitionInterface $apiResponse)
    {
        $this->apiResponse = $apiResponse;
    }

    public function get()
    {
        return $this->apiResponse;
    }
}
