<?php declare(strict_types=1);
namespace Boxalino\RealTimeUserExperience\Model\Response\Content;

use Boxalino\RealTimeUserExperienceApi\Framework\Content\Listing\ApiSortingModelInterface;
use Boxalino\RealTimeUserExperienceApi\Service\Api\Response\Accessor\AccessorInterface;
use Boxalino\RealTimeUserExperienceApi\Service\Api\Response\Accessor\AccessorModelInterface;
use Boxalino\RealTimeUserExperienceApi\Service\ErrorHandler\MissingDependencyException;
use Boxalino\RealTimeUserExperienceApi\Framework\Content\Listing\ApiSortingModelAbstract;

/**
 * Class ApiSorting
 * @package Boxalino\RealTimeUserExperience\Model\Response\Content
 */
class ApiSorting extends ApiSortingModelAbstract
    implements ApiSortingModelInterface
{

    /**
     * @var []
     */
    protected $sortings = [];

    /**
     * @var AccessorInterface
     */
    protected $activeSorting;

    /**
     * @param string $key
     * @return |null
     */
    public function get(string $key)
    {
        foreach($this->getSortings() as $key=>$sorting)
        {
            foreach($sorting as $field=>$direction)
            {
                if($field == $key)
                {
                    return $key;
                }
            }
        }

        return $this->getDefaultSortField();
    }

    /**
     * @param string $key
     * @return bool
     */
    public function has(string $key): bool
    {
        return true;
    }

    /**
     * @return array
     */
    public function getSortings(): array
    {
        return [
            "relevance" => ["relevance"=>"desc"],
            "relevance-asc" => ["relevance"=>"asc"], //this does not generally happen
            "price" => ["price" => "desc"],
            "price-asc" => ["price" => "asc"],
            "name" => ["name" => "desc"],
            "name-asc" => ["name" => "asc"],
            "newest" => ["id"=>"desc"]
        ];
    }

    /**
     * @return string
     */
    public function getDefaultSortField(): string
    {
        return "score";
    }

    /**
     * Based on the response, transform the response field+direction into a e-shop valid sorting
     */
    public function getCurrent() : string
    {
        $responseField = $this->activeSorting->getField();
        if(!empty($responseField))
        {
            $direction = $this->activeSorting->getReverse() === true ? mb_strtolower(self::SORT_DESCENDING)
                : mb_strtolower(self::SORT_ASCENDING);
            $field = $this->getResponseField($responseField);
            foreach($this->getSortings() as $key => $sorting)
            {
                foreach($sorting as $sortingField=>$sortingDirection)
                {
                    if($sortingField == $field && $sortingDirection == $direction)
                    {
                        return $key;
                    }
                }
            }
        }

        return $this->getDefaultSortField();
    }

    /**
     * @param null | AccessorInterface $context
     * @return AccessorModelInterface
     */
    public function addAccessorContext(?AccessorInterface $context = null): AccessorModelInterface
    {
        $this->setActiveSorting($context->getSorting());
        return $this;
    }

}
