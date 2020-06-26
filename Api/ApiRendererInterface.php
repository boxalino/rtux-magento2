<?php
namespace Boxalino\RealTimeUserExperience\Api;

use Magento\Framework\View\Element\BlockInterface;

/**
 * Interface ApiRendererInterface
 * Interface assigned to the blocks defined as "type" in the Boxalino Intelligence Layout Block definition
 *
 * @package Boxalino\RealTimeUserExperience\Api
 */
interface ApiRendererInterface extends BlockInterface
{
    /**
     * Boxalino API response block set on the Magento block type
     *
     * @return ApiBlockAccessorInterface|null
     */
    public function getBlock() : ?ApiBlockAccessorInterface;

    /**
     * @param ApiBlockAccessorInterface $block
     * @return ApiRendererInterface
     */
    public function setBlock(ApiBlockAccessorInterface $block) : ApiRendererInterface;

    /**
     * Access list of children blocks as defined in the Boxalino API response
     *
     * @return \ArrayIterator|null
     */
    public function getBlocks() : ?\ArrayIterator;

    /**
     * @param string $value
     * @return ApiRendererInterface
     */
    public function setRtuxVariantUuid(string $value) : ApiRendererInterface;

    /**
     * Access to the variant UUID - the request-response unique identifier
     * (generally displayed in templates for better tracking performance)
     *
     * @return string|null
     */
    public function getRtuxVariantUuid() : ?string;

    /**
     * @param string $value
     * @return ApiRendererInterface
     */
    public function setRtuxGroupBy(string $value) : ApiRendererInterface;

    /**
     * @return string|null
     */
    public function getRtuxGroupBy() : ?string;

    /**
     * Creates a block based on the ApiBlockAccessorInterface | Boxalino API response
     *
     * @param ApiBlockAccessorInterface $block
     * @return ApiRendererInterface|null
     */
    public function getApiBlock(ApiBlockAccessorInterface $block) : ?ApiRendererInterface;

}
