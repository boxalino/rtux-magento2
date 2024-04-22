<?php
namespace Boxalino\RealTimeUserExperience\Model\Api\Context;

use Boxalino\RealTimeUserExperienceApi\Service\Api\Request\Context\ListingContextInterface;
use Boxalino\RealTimeUserExperienceApi\Service\Api\Request\ParameterFactoryInterface;
use Boxalino\RealTimeUserExperienceApi\Service\Api\Request\RequestDefinitionInterface;
use Boxalino\RealTimeUserExperienceApi\Service\Api\Request\RequestInterface;

/**
 * Trait ListingContextFilterablePropertiesTrait
 * Common context functions to get the names for the filterable properties
 *
 * @package Boxalino\RealTimeUserExperience\Framework\Request
 */
trait ListingContextFilterablePropertiesTrait
{

    use FrameworkFilterablePropertyTrait;

    protected int $facetMaxCountDefault = -1;
    protected int $facetMinPopulationDefault = 1;

    /**
     * Extend default to add filterable facets to request
     *
     * @param RequestInterface $request
     * @return RequestDefinitionInterface
     */
    public function get(RequestInterface $request): RequestDefinitionInterface
    {
        parent::get($request);
        $this->addStoreFilterableProperties($request);

        return $this->getApiRequest();
    }

    /**
     * @param $request
     */
    public function addStoreFilterableProperties($request) : void
    {
        if($this->getProperty("addStoreFilterableProperties"))
        {
            $storeFilterableProperties = $this->getStoreFilterablePropertiesByRequest($request);
            foreach ($storeFilterableProperties as $propertyName) {
                $this->getApiRequest()
                    ->addFacets(
                        $this->parameterFactory->get(ParameterFactoryInterface::BOXALINO_API_REQUEST_PARAMETER_TYPE_FACET)
                            ->add(
                                html_entity_decode($propertyName),
                                $this->getFacetMaxCount($propertyName),
                                $this->getFacetMinPopulation($propertyName),
                                $this->getFacetValueCorrelation()
                            )
                    );
            }
        }
    }

    /**
     * @param string $propertyName
     * @return int
     */
    public function getFacetMaxCount(string $propertyName) : int
    {
        return $this->facetMaxCountDefault;
    }

    /**
     * @param string $propertyName
     * @return int
     */
    public function getFacetMinPopulation(string $propertyName) : int
    {
        return $this->facetMinPopulationDefault;
    }

    /**
     * @param bool $value
     * @return ListingContextInterface
     */
    public function addStoreFilterablePropertiesToApiRequest(bool $value) : ListingContextInterface
    {
        $this->set("addStoreFilterableProperties", (bool)$value);
        return $this;
    }


}
