<?php declare(strict_types=1);
namespace Boxalino\RealTimeUserExperience\Model\Response\Content;

use Boxalino\RealTimeUserExperienceApi\Framework\Content\Listing\ApiSortingModelInterface;
use Boxalino\RealTimeUserExperienceApi\Service\Api\Response\Accessor\AccessorInterface;
use Boxalino\RealTimeUserExperienceApi\Service\Api\Response\Accessor\AccessorModelInterface;
use Boxalino\RealTimeUserExperienceApi\Framework\Content\Listing\ApiSortingModelAbstract;

/**
 * Class ApiSorting
 *
 * In a default M2 environment, the sort is described by 2 parameters: field (ex:price) and direction (ex:desc)
 * As a recommended integration, we suggest to use a combined key field-direction for the sort field (ex: price-desc)
 * The 2nd option will allow to have extended labels (ex: "Highest price first") as opposed to the generic "Price"
 *
 * The ApiSortingModelInterface dependency is added with the integration repository
 * The ApiSorting is used as "model" for the Layout Block
 *
 * @package Boxalino\RealTimeUserExperience\Model\Response\Content
 */
class ApiSorting extends ApiSortingModelAbstract
    implements ApiSortingModelInterface
{

    /**
     * The argument is provided via DI
     * To be extended for any desired sorting field for the integration project
     *
     * @param array $sortingList
     */
    public function __construct(array $sortingList)
    {
        parent::__construct();
        foreach($sortingList as $urlKey => $sortDefinition)
        {
            $sortingOption = new ApiSortingOption($sortDefinition);
            $this->add([$sortingOption->getField() => $sortingOption->getApiField()]);
            $this->addSystemSortingOption($urlKey, $sortingOption);
        }
    }

    /**
     * Adding system sort options per project integration specifications
     *
     * @param string $key
     * @param ApiSortingOption $option
     * @return $this
     */
    public function addSystemSortingOption(string $key, ApiSortingOption $option) : self
    {
        $this->sortings[$key] = $option;
        return $this;
    }

    /**
     * The default sort field recommended with the Boxalino API is the "position" (label: "Relevance"/"Recommended")
     * because the product order is the recommended one
     *
     * @return string
     */
    public function getDefaultSortField(): string
    {
        return "position";
    }

    /**
     * @inherited from Magento2
     * @return string
     */
    public function getDefaultSortDirection() : string
    {
        return ApiSortingModelInterface::SORT_ASCENDING;
    }

    /**
     * Transform a request key to a valid API sort
     *
     * @param string $key
     * @return array
     */
    public function getRequestSorting(string $key) : array
    {
        $requestedSortingList = [];
        if($this->has($key))
        {
            $requestedSortingList[] = [
                "field" => $this->get($key)->getApiField(),
                "reverse" => $this->get($key)->isReverse()
            ];
        }

        return $requestedSortingList;
    }

    /**
     * Based on the response,
     * transforms the response field and direction into a e-shop valid sorting
     */
    public function getCurrent() : string
    {
        $responseField = $this->getCurrentApiSortField();
        if(!empty($responseField))
        {
            $direction = $this->getCurrentSortDirection();
            $field = $this->getResponseField($responseField);
            foreach($this->getSortings() as $key => $sorting)
            {
                /** @var $sorting ApiSortingOption */
                if($sorting->getField() == $field && $sorting->getDirection() == $direction)
                {
                    return $key;
                }
            }
        }

        return $this->getDefaultSortField();
    }

    /**w
     * @param string $key
     * @return ApiSortingOption |null
     */
    public function get(string $key)
    {
        return $this->sortings[$key] ?? null;
    }

    /**
     * @param string $key
     * @return bool
     */
    public function has(string $key): bool
    {
        return isset($this->sortings[$key]);
    }

    /**
     * Accessing the sort options available for the e-shop
     *
     * @return array
     */
    public function getSortings(): array
    {
        return $this->sortings;
    }

    /**
     * The getter "getSorting" is used in accordance to the property name assigned for the bx-sort accessor
     * (in di.xml)
     *
     * @param null | AccessorInterface $context
     * @return AccessorModelInterface
     */
    public function addAccessorContext(AccessorInterface $context = null): AccessorModelInterface
    {
        $this->setActiveSorting($context->getSorting());
        return $this;
    }

}
