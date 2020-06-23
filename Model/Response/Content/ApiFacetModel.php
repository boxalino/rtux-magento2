<?php declare(strict_types=1);
namespace Boxalino\RealTimeUserExperience\Model\Response\Content;

use Boxalino\RealTimeUserExperienceApi\Service\Api\Response\Accessor\AccessorFacetModelInterface;
use Boxalino\RealTimeUserExperienceApi\Framework\Content\Listing\ApiFacetModelAbstract;

/**
 * Class ApiFacetModel
 *
 * Item refers to any data model/logic that is desired to be rendered/displayed
 * The integrator can decide to either use all data as provided by the Narrative API,
 * or to design custom data layers to represent the fetched content
 *
 * @package Boxalino\RealTimeUserExperienceSample\Service\Api\Content
 */
class ApiFacetModel extends ApiFacetModelAbstract
    implements AccessorFacetModelInterface
{
    /**
     * Accessing translation for the property name from DB
     *
     * @param string $propertyName
     * @return string
     */
    public function getLabel(string $propertyName) : string
    {
        if(strpos($propertyName, self::BOXALINO_STORE_FACET_PREFIX)===0)
        {
            $propertyName = substr($propertyName, strlen(self::BOXALINO_STORE_FACET_PREFIX), strlen($propertyName));
        }
        return ucwords(str_replace("_", " ", $propertyName));
    }

}
