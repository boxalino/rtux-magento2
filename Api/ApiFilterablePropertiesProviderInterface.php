<?php
namespace Boxalino\RealTimeUserExperience\Api;

/**
 * Interface ApiFilterablePropertiesProviderInterface
 * Used on listing/search and various contexts to access the Magento2 filterable properties codes
 *
 * @package Boxalino\RealTimeUserExperience\Api
 */
interface ApiFilterablePropertiesProviderInterface 
{

    /**
     * Access all filterable properties from the project
     * Used to dynamically include all facet requests as part of the API request
     * 
     * @return array
     */
   public function getFilterableAttributes() : array;

   
}
