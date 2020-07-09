<?php declare(strict_types=1);
namespace Boxalino\RealTimeUserExperience\Model\Response\Content;

use Magento\Framework\Api\AbstractSimpleObject;

/**
 * Class ApiSortingOption
 * Used for injecting sorting options available for the integration
 *
 * @package Boxalino\RealTimeUserExperience\Model\Response\Content
 */
class ApiSortingOption extends AbstractSimpleObject
{

    const FIELD = 'field';
    const DIRECTION = 'direction';
    const API_FIELD = 'api-field';
    const REVERSE = 'reverse';
    const LABEL = 'label';

    /**
     * @return string
     */
    public function getField(): string
    {
        return $this->_get(self::FIELD);
    }

    /**
     * @param string $field
     * @return ApiSortingOption
     */
    public function setField(string $field): ApiSortingOption
    {
        $this->setData(self::FIELD, $field);
        return $this;
    }

    /**
     * @return string
     */
    public function getDirection(): string
    {
        return $this->_get(self::DIRECTION);
    }

    /**
     * @param string $direction
     * @return ApiSortingOption
     */
    public function setDirection(string $direction): ApiSortingOption
    {
        $this->setData(self::DIRECTION, $direction);
        return $this;
    }

    /**
     * @return string
     */
    public function getApiField(): string
    {
        return $this->_get(self::API_FIELD);
    }

    /**
     * @param string $apiField
     * @return ApiSortingOption
     */
    public function setApiField(string $apiField): ApiSortingOption
    {
        $this->setData(self::API_FIELD, $apiField);
        return $this;
    }

    /**
     * @return bool
     */
    public function isReverse(): bool
    {
        return $this->_get(self::REVERSE);
    }

    /**
     * @param bool $reverse
     * @return ApiSortingOption
     */
    public function setReverse(bool $reverse): ApiSortingOption
    {
        $this->setData(self::REVERSE, $reverse);
        return $this;
    }

    /**
     * @return string
     */
    public function getLabel(): string
    {
        return $this->_get(self::LABEL);
    }

    /**
     * @param string $label
     * @return ApiSortingOption
     */
    public function setLabel(string $label): ApiSortingOption
    {
        $this->setData(self::LABEL, $label);
        return $this;
    }


}
