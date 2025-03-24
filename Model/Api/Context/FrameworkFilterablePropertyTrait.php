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
     * @var array
     */
    protected array $_filterableProperties = [];

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
        return array_keys($this->_getFilterableProperties());
    }

    /**
     * @param string $name
     * @return int
     */
    public function getFilterablePropertyPositionByName(string $name) : int
    {
        return $this->_getFilterableProperties()[$name]['position'] ?? 0;
    }

    /**
     * @return array
     */
    protected function _getFilterableProperties() : array
    {
        if(empty($this->_filterableProperties))
        {
            $this->_filterableProperties = $this->filterablePropertyProvider->getFilterableAttributes();
        }

        return $this->_filterableProperties;
    }


}
