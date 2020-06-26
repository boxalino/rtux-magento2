<?php
namespace Boxalino\RealTimeUserExperience\Api;

use Boxalino\RealTimeUserExperienceApi\Service\Api\Response\Accessor\AccessorInterface;
use Boxalino\RealTimeUserExperienceApi\Service\Api\Response\Accessor\BlockInterface;

/**
 * @package Boxalino\RealTimeUserExperience\Api
 */
interface ApiToolbarBlockAccessorInterface extends ApiRendererInterface
{
    /**
     * @return AccessorInterface|null
     */
    public function getSorting() : ?AccessorInterface;

    /**
     * @return AccessorInterface|null
     */
    public function getPagination() : ?AccessorInterface;

}
