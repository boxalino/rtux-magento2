<?php
namespace Boxalino\RealTimeUserExperience\Model\ResourceModel\Api\Context;

use Boxalino\RealTimeUserExperience\Api\ApiFilterablePropertiesProviderInterface;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\DB\Select;

/**
 * Trait FrameworkFilterableAttribute
 * Common context functions to get the names for the filterable properties
 *
 * @package Boxalino\RealTimeUserExperience\Model\ResourceModel\Request
 */
class FrameworkFilterableAttribute implements ApiFilterablePropertiesProviderInterface
{

    /**
     * @var \Magento\Framework\DB\Adapter\AdapterInterface
     */
    protected $adapter;

    /**
     * @var string
     */
    protected $prefix = '';
    
    /**
     * @param ResourceConnection $resource
     */
    public function __construct(
        ResourceConnection $resource
    ) {
        $this->adapter = $resource->getConnection();
    }

    /**
     * Query to access all filterable properties from the setup
     *
     * @return QueryBuilder
     */
    public function getFilterableAttributes() : array
    {
        $select = $this->_getFilterableAttributeSql(["attribute_code"]);

        return $this->adapter->fetchCol($select);
    }

    /**
     * Query to access all filterable properties from the setup
     * And the name (as exported to Boxalino)
     *
     * @return QueryBuilder
     */
    public function getFilterableAttributesWithPrefix() : array
    {
        $select = $this->_getFilterableAttributeSql(
            [new \Zend_Db_Expr("CONCAT('$this->prefix' , attribute_code) AS attribute_code" )]
        );

        return $this->adapter->fetchCol($select);
    }

    /**
     * @param array $fields
     * @return Select
     */
    protected function _getFilterableAttributeSql(array $fields) : Select
    {
        return $select = $this->adapter->select()
            ->from(
                ['e_a' => $this->adapter->getTableName('eav_attribute')],
                $fields
            )
            ->joinInner(
                ['c_e_a' => $this->adapter->getTableName('catalog_eav_attribute')],
                'c_e_a.attribute_id = e_a.attribute_id',
                []
            )
            ->where('e_a.entity_type_id = ?', \Magento\Catalog\Setup\CategorySetup::CATALOG_PRODUCT_ENTITY_TYPE_ID)
            ->where('c_e_a.is_filterable_in_search = 1 OR c_e_a.is_filterable = 1')
            ->where("e_a.frontend_input <> 'price'");
    }

    /**
     * To make it compliant with SDK/exporter-magento2 integrations
     *
     * @param string $prefix
     * @return ApiFilterablePropertiesProviderInterface
     */
    public function setPropertyPrefix(string $prefix) : ApiFilterablePropertiesProviderInterface
    {
        $this->prefix = $prefix;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getPropertyPrefix() : ?string
    {
        return $this->prefix;
    }
    
    
}
