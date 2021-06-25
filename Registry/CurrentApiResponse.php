<?php
namespace Boxalino\RealTimeUserExperience\Registry;

use Boxalino\RealTimeUserExperience\Api\CurrentApiResponseRegistryInterface;
use Boxalino\RealTimeUserExperienceApi\Service\Api\Response\ResponseDefinitionInterface;

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

    /** @var \ArrayObject  */
    private $apiResponseCollection;

    public function __construct()
    {
        $this->apiResponseCollection = new \ArrayObject();
    }

    public function set(ResponseDefinitionInterface $apiResponse): void
    {
        $this->apiResponse = $apiResponse;
    }

    public function get(): ?ResponseDefinitionInterface
    {
        return $this->apiResponse;
    }

    public function getByWidget(string $widget) : ?ResponseDefinitionInterface
    {
        if($this->apiResponseCollection->offsetExists($widget))
        {
            return $this->apiResponseCollection->offsetGet($widget);
        }

        return null;
    }

    public function addByWidget(string $widget, ResponseDefinitionInterface $apiResponse) : void
    {
        $this->apiResponseCollection->offsetSet($widget,$apiResponse);
    }


}
