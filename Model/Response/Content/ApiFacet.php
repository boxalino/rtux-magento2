<?php declare(strict_types=1);
namespace Boxalino\RealTimeUserExperience\Model\Response\Content;

use Boxalino\RealTimeUserExperienceApi\Service\Api\Response\Accessor\AccessorFacetModelInterface;
use Boxalino\RealTimeUserExperienceApi\Framework\Content\Listing\ApiFacetModelAbstract;
use Magento\Eav\Model\Entity\Attribute\AbstractAttribute;

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
    /**
     * @var \Magento\Eav\Model\Config
     */
    protected $_config;

    public function __construct(\Magento\Eav\Model\Config $config)
    {
        parent::__construct();
        $this->_config = $config;
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
            } catch(\Throwable $exception) {
                //$this->log("ERROR: " . $exception->getMessage());
            }

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

}
