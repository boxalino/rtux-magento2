<?php declare(strict_types=1);
namespace Boxalino\RealTimeUserExperience\Service\Api\Request\Definition;

/**
 * Magento 2 module-scope subclass for M2.4.8+ DI compilation compatibility.
 * rtux-api-php is a plain PHP library outside Magento DI compilation scope.
 * This thin subclass satisfies the M2.4.8 compiler without altering behaviour.
 * Same pattern as ApiCallService in this package.
 */
class SearchRequestDefinition
	extends \Boxalino\RealTimeUserExperienceApi\Service\Api\Request\Definition\SearchRequestDefinition
	implements \Boxalino\RealTimeUserExperienceApi\Service\Api\Request\Definition\SearchRequestDefinitionInterface
{

}
