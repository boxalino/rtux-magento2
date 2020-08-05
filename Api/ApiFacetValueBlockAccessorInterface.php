<?php
namespace Boxalino\RealTimeUserExperience\Api;

/**
 * @package Boxalino\RealTimeUserExperience\Api
 */
interface ApiFacetValueBlockAccessorInterface extends ApiRendererInterface
{
    /**
     */
    public function getValue();

    /**
     * @param $value
     * @return mixed
     */
    public function setValue($value);

}
