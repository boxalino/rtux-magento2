<?php
namespace Boxalino\RealTimeUserExperience\Block\Catalog\Product\ProductList;

use Boxalino\RealTimeUserExperience\Api\ApiRendererInterface;
use Boxalino\RealTimeUserExperience\Block\ApiBlockTrait;
use Boxalino\RealTimeUserExperience\Block\FrameworkBlockTrait;
use Boxalino\RealTimeUserExperienceApi\Framework\Content\Listing\ApiSortingModelInterface;
use Boxalino\RealTimeUserExperienceApi\Service\Api\Response\Accessor\AccessorModelInterface;

/**
 * Class Sorting
 *
 * The block has been created to have the default template (Magento_Catalog::product/list/toolbar/sorter.phtml) re-usable
 * All information required is available in the sorting model (as defined in di.xml for the accessor definitions)
 *
 * @package Boxalino\RealTimeUserExperience\Block\Catalog\Product\ProductList
 */
class Sorting extends \Magento\Framework\View\Element\Template
    implements ApiRendererInterface
{
    use ApiBlockTrait;
    use FrameworkBlockTrait;

    /**
     * @var ApiSortingModelInterface | AccessorModelInterface
     */
    protected $sort;

    /**
     * Accesses the Sorting model of the Boxalino API response
     * (as configured as accessor getter in di.xml)
     *
     * @return ApiSortingModelInterface
     */
    public function getSort() : ApiSortingModelInterface
    {
        if(!$this->sort)
        {
            $this->sort = $this->getBlock()->getModel();
        }

        return $this->sort;
    }

    /**
     * List used on the template to loop through key->value and show the label
     *
     * @return array
     */
    public function getAvailableOrders() : array
    {
        return $this->getSort()->getSortings();
    }

    /**
     * Checks if the field is the one used for the sorting request
     *
     * @param string $key
     * @return bool
     */
    public function isOrderCurrent(string $key) : bool
    {
        return $key === $this->getSort()->getCurrent();
    }

    /**
     * Access current direction (asc or desc)
     * @return string
     */
    public function getCurrentDirection() : string
    {
        return $this->getSort()->getCurrentSortDirection();
    }

}
