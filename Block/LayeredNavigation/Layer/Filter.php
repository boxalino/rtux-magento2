<?php
namespace Boxalino\RealTimeUserExperience\Block\LayeredNavigation\Layer;

use Boxalino\RealTimeUserExperience\Api\ApiFacetBlockAccessorInterface;
use Boxalino\RealTimeUserExperience\Block\ApiBlockTrait;
use Boxalino\RealTimeUserExperienceApi\Service\Api\Response\Accessor\Facet;

/**
 * Class Filter
 * The template used to render the filter view is defined in the Layout Block
 * Extend in order to set different templates per visualisation type or facet name/etc
 *
 * @package Boxalino\RealTimeUserExperience\Block\LayeredNavigation\Layer
 */
class Filter extends \Magento\Framework\View\Element\Template
    implements ApiFacetBlockAccessorInterface
{
    use ApiBlockTrait;

    /**
     * @var Facet
     */
    protected $filter;

    /**
     * @param Facet $filter
     * @return $this
     */
    public function setFilter(Facet $filter)
    {
        $this->filter = $filter;
        return $this;
    }

    /**
     * @return Facet
     */
    public function getFilter() : Facet
    {
        return $this->filter;
    }

    /**
     * @param Facet $filter
     * @return string
     */
    public function render(Facet $filter) : string
    {
        $this->setFilter($filter);
        return $this->_toHtml();
    }

}
