<?php
namespace Boxalino\RealTimeUserExperience\Api;

use Boxalino\RealTimeUserExperienceApi\Service\Api\Response\Accessor\BlockInterface;

/**
 * In order to be able to dynamically generate block names, the block type (class) is needed
 * and a unique block name
 *
 * @package Boxalino\RealTimeUserExperience\Api
 */
interface ApiBlockAccessorInterface extends BlockInterface
{

    /**
     * @return string
     */
    public function getType() : string;

    /**
     * @return string|null
     */
    public function getName() : ?string;

}
