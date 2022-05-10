<?php
namespace Boxalino\RealTimeUserExperience\Model\Api\Context;

use Boxalino\RealTimeUserExperience\Api\ApiFilterablePropertiesProviderInterface;
use Boxalino\RealTimeUserExperienceApi\Framework\ApiPropertyTrait;
use Boxalino\RealTimeUserExperienceApi\Service\Api\Request\RequestInterface;

/**
 * Trait FrameworkFilterablePropertyTrait
 * Common context functions to get the names for the filterable properties
 *
 * @package Boxalino\RealTimeUserExperience\Model\Api\Context
 */
trait FrameworkFilterablePropertyTrait
{

    use ApiPropertyTrait;

    /**
     * @var ApiFilterablePropertiesProviderInterface
     */
    protected $filterablePropertyProvider;

    /**
     * @param RequestInterface $request
     * @return array
     */
    protected function getStoreFilterablePropertiesByRequest(RequestInterface $request) : array
    {
        $properties = array_diff($this->getFilterablePropertyNames(), $this->getSelectedFacetsByRequest($request));
        if($this->filterablePropertyProvider->getPropertyPrefix())
        {
            $prefix = $this->filterablePropertyProvider->getPropertyPrefix();
            array_walk($properties, function($property) use ($prefix)
            {
                return $prefix . $property;
            });
        }

        return $properties;
    }

    /**
     * @return array
     */
    public function getFilterablePropertyNames() : array
    {
        return $this->filterablePropertyProvider->getFilterableAttributes();
    }


}
