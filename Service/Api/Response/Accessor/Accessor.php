<?php declare(strict_types=1);
namespace Boxalino\RealTimeUserExperience\Service\Api\Response\Accessor;

/**
 * Magento 2 module-scope subclass for M2.4.8+ DI compilation compatibility.
 * rtux-api-php is a plain PHP library outside Magento DI compilation scope.
 * This thin subclass satisfies the M2.4.8 compiler without altering behaviour.
 * Same pattern as ApiCallService in this package.
 */
class Accessor
	extends \Boxalino\RealTimeUserExperienceApi\Service\Api\Response\Accessor\Accessor
	implements \Boxalino\RealTimeUserExperienceApi\Service\Api\Response\Accessor\AccessorInterface
{

}
