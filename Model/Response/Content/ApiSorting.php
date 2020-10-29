<?php declare(strict_types=1);
namespace Boxalino\RealTimeUserExperience\Model\Response\Content;

use Boxalino\RealTimeUserExperienceApi\Framework\Content\Listing\ApiSortingModelInterface;
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
        $this->addSortingOptionCollection($sortingList);
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

}
