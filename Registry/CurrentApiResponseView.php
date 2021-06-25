<?php
namespace Boxalino\RealTimeUserExperience\Registry;

use Boxalino\RealTimeUserExperience\Api\CurrentApiResponseViewRegistryInterface;
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
    private $apiResponseView = null;

    /** @var \ArrayObject  */
    private $apiResponseViewCollection;

    public function __construct()
    {
        $this->apiResponseViewCollection = new \ArrayObject();
    }

    public function set(ApiResponseViewInterface $apiResponseView): void
    {
        $this->apiResponseView = $apiResponseView;
    }

    public function get(): ?ApiResponseViewInterface
    {
        return $this->apiResponseView;
    }

    public function getByWidget(string $widget) : ?ApiResponseViewInterface
    {
        if($this->apiResponseViewCollection->offsetExists($widget))
        {
            return $this->apiResponseViewCollection->offsetGet($widget);
        }

        return null;
    }

    public function addByWidget(string $widget, ApiResponseViewInterface $apiResponse) : void
    {
        $this->apiResponseViewCollection->offsetSet($widget,$apiResponse);
    }


}
