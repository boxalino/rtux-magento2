<?php declare(strict_types=1);
namespace Boxalino\RealTimeUserExperience\Model\Response\Content;

use Boxalino\RealTimeUserExperience\Service\Api\Response\Accessor\Facet;
use Boxalino\RealTimeUserExperience\Service\Api\Response\Accessor\FacetValue;
use Boxalino\RealTimeUserExperience\Service\Api\Util\RequestParametersTrait;
use Boxalino\RealTimeUserExperienceApi\Service\Api\Response\Accessor\AccessorFacetModelInterface;
use Boxalino\RealTimeUserExperienceApi\Framework\Content\Listing\ApiFacetModelAbstract;
use Magento\Eav\Model\Entity\Attribute\AbstractAttribute;
use Magento\Framework\UrlInterface;

/**
 * Class ApiFacet
 *
 * Item refers to any data model/logic that is desired to be rendered/displayed
 * The integrator can decide to either use all data as provided by the Narrative API,
 * or to design custom data layers to represent the fetched content
 *
 * @package Boxalino\RealTimeUserExperience\Model\Response\Content
 */
class ApiFacet extends ApiFacetModelAbstract
    implements AccessorFacetModelInterface
{

    use RequestParametersTrait;
    const SELECTED_FACET_VALUES_URL_DELIMITER = ",";

    /**
     * @var \Magento\Eav\Model\Config
     */
    protected $_config;

    /**
     * @var array | null
     */
    protected $urlParameters = null;

    /**
     * @var UrlInterface
     */
    protected $urlBuilder;

    /**
     * @var string
     */
    protected $facetValuesDelimiter;

    public function __construct(
        \Magento\Eav\Model\Config $config,
        UrlInterface $urlBuilder,
        string $facetValuesDelimiter
    ){
        parent::__construct();
        $this->_config = $config;
        $this->urlBuilder = $urlBuilder;
        $this->facetValuesDelimiter = $facetValuesDelimiter;
    }

    /**
     * @param array $facets
     * @return $this
     */
    public function setFacets(array $facets) : ApiFacetModelAbstract
    {
        parent::setFacets($facets);

        /** @var Facet $filter */
        foreach($this->facets as $filter)
        {
            /** @var FacetValue $facetValue */
            foreach($filter->getValues() as $facetValue)
            {
                $facetValue->setRemoveUrl($this->getRemoveUrlByFacetValue($filter, $facetValue));
                $facetValue->setSelectUrl($this->getUrlByFacetValue($filter, $facetValue));
            }
        }

        return $this;
    }

    /**
     * Accessing translation for the property name from DB
     *
     * @param string $propertyName
     * @return string
     */
    public function getLabel(string $propertyName) : string
    {
        /** if the facet name starts with products_ it makes it a Magento2 product attribute */
        if(strpos($propertyName, AccessorFacetModelInterface::BOXALINO_STORE_FACET_PREFIX)===0)
        {
            $propertyName = substr($propertyName, strlen(AccessorFacetModelInterface::BOXALINO_STORE_FACET_PREFIX), strlen($propertyName));
            try{
                $label = $this->_getAttributeModel($propertyName)->getStoreLabel();
                if(!empty($label))
                {
                    return $label;
                }
            } catch(\Throwable $exception) {}
        }

        return ucwords(str_replace("_", " ", $propertyName));
    }

    /**
     * Accessing Magento2 attribute
     *
     * @param string $attributeCode
     * @return AbstractAttribute
     */
    public function _getAttributeModel(string $attributeCode)
    {
        return $this->_config->getAttribute('catalog_product', $attributeCode);
    }

    /**
     * Create the URL for when a facet is selected
     * The range fields properties are set via JS
     *
     * @param Facet $facet
     * @param FacetValue $facetValue
     */
    public function getUrlByFacetValue(Facet $facet, FacetValue $facetValue) : string
    {
        $value = $facetValue->getValue();
        if($facet->isSelected() && $facet->allowMultiselect() && !$facetValue->isSelected())
        {
            $value = implode($this->facetValuesDelimiter,
                array_merge(explode($this->facetValuesDelimiter, $this->urlParameters[$facet->getRequestField()]), [$facetValue->getValue()])
            );
        }
        $query = [
            $facet->getRequestField() => $value,
            $this->getPageNumberParameter() => null,
        ];
        return $this->urlBuilder->getUrl('*/*/*', ['_current' => true, '_use_rewrite' => true, '_query' => $query]);
    }


    /**
     * As seen in Magento2 filter item model \Magento\Catalog\Model\Layer\Filter\Item
     *
     * @param Facet $facet
     * @param FacetValue $facetValue
     * @return string
     */
    public function getRemoveUrlByFacetValue(Facet $facet, FacetValue $facetValue) : string
    {
        $query = $this->getRemoveUrlQueryByFacetValue($facet, $facetValue);
        $params['_current'] = true;
        $params['_use_rewrite'] = true;
        $params['_query'] = $query;
        $params['_escape'] = true;

        return $this->urlBuilder->getUrl('*/*/*', $params);
    }

    /**
     * In case of the range facet - both range-from & range-to selected values are removed
     * In case of multiselect facet - the value is removed from the list
     * Otherwise, the value is set to clean (null)
     *
     * @param Facet $facet
     * @param FacetValue $facetValue
     * @return array
     */
    public function getRemoveUrlQueryByFacetValue(Facet $facet, FacetValue $facetValue) : array
    {
        $parameters = $this->getUrlParams();
        if($facet->isSelected() && $facet->isRange())
        {
            if($facetValue->getMinSelectedValue())
            {
                $parameters[$facet->getRangeFromField()] = $facet->getCleanValue();
            }

            if($facetValue->getMaxSelectedValue())
            {
                $parameters[$facet->getRangeToField()] = $facet->getCleanValue();
            }

            return $parameters;
        }

        if($facetValue->isSelected())
        {
            $values = explode($this->facetValuesDelimiter, $parameters[$facet->getRequestField()]);
            $parameters[$facet->getRequestField()] = $facet->getCleanValue();
            if($facet->allowMultiselect())
            {
                unset($values[array_search($facetValue->getValue(), $values)]);
                $parameters[$facet->getRequestField()] = implode($this->facetValuesDelimiter, $values);
            }

            return $parameters;
        }

        return $parameters;
    }

    /**
     * List of active facets and their value
     * In case of a range facet - it checks if it`s a range-from or range-to value selected
     *
     * @return array
     */
    public function getUrlParams() : array
    {
        if(is_null($this->urlParameters))
        {
            $parameters = [];
            /** @var Facet $selectedFacet */
            foreach($this->getSelectedFacets() as $selectedFacet)
            {
                if($selectedFacet->isRange())
                {
                    /** @var FacetValue $facetValue */
                    $facetValue = $selectedFacet->getSelectedValues()[0];
                    if($facetValue->getMinSelectedValue())
                    {
                        $parameters[$selectedFacet->getRangeFromField()] = $facetValue->getMinSelectedValue();
                    }

                    if($facetValue->getMaxSelectedValue())
                    {
                        $parameters[$selectedFacet->getRangeToField()] = $facetValue->getMaxSelectedValue();
                    }

                    continue;
                }

                $parameters[$selectedFacet->getRequestField()] = implode(
                    $this->facetValuesDelimiter,
                    array_map(
                        function(FacetValue $facetValue) {
                            return $facetValue->getValue();
                        },
                        $selectedFacet->getSelectedValues()
                    )
                );
            }
            $this->urlParameters = $parameters;
        }

        return $this->urlParameters;
    }

}
