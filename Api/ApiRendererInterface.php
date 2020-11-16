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

    const BOXALINO_RTUX_API_BLOCK_TYPE_DEFAULT = "Boxalino\RealTimeUserExperience\Block\Api\Block";
    const BOXALINO_RTUX_API_RESPONSE_TYPE_DEFAULT = "Boxalino\RealTimeUserExperience\Block\Api\Response";
    const BOXALINO_RTUX_API_BLOCK_TEMPLATE_DEFAULT = "Boxalino_RealTimeUserExperience::api/block.phtml";
    const BOXALINO_RTUX_API_BLOCK_NAME_DEFAULT = "rtux_api_block_default";

    /**
     * Boxalino API response block set on the Magento block type
     *
     * @return ApiBlockAccessorInterface|null
     */
    public function getBlock();

    /**
     * @param ApiBlockAccessorInterface $block
     * @return ApiRendererInterface
     */
    public function setBlock(ApiBlockAccessorInterface $block) : ApiRendererInterface;

    /**
     * Sets the blocks from a generic parent
     *
     * @param \ArrayIterator $blocks
     * @return ApiRendererInterface
     */
    public function setBlocks(\ArrayIterator $blocks) : ApiRendererInterface;

    /**
     * Access list of children blocks as defined in the Boxalino API response
     *
     * @return \ArrayIterator|null
     */
    public function getBlocks() : \ArrayIterator;

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
    public function getRtuxVariantUuid();

    /**
     * @param string $value
     * @return ApiRendererInterface
     */
    public function setRtuxGroupBy(string $value) : ApiRendererInterface;

    /**
     * @return string|null
     */
    public function getRtuxGroupBy();

    /**
     * Creates a block based on the ApiBlockAccessorInterface | Boxalino API response
     *
     * @param ApiBlockAccessorInterface $block
     * @return ApiRendererInterface|null
     */
    public function getApiBlock(ApiBlockAccessorInterface $block) : ApiRendererInterface;

    /**
     * Generate a default narrative API renderer block
     *
     * @return ApiRendererInterface
     */
    public function getDefaultBlock() : ApiRendererInterface;

    /**
     * Generate a default narrative API response page block
     *
     * @return ApiResponseBlockInterface
     */
    public function getDefaultResponseBlock() : ApiResponseBlockInterface;

    /**
     * Access the Boxalino response attributes for API JS tracker
     *
     * @return \ArrayIterator
     */
    public function getBxAttributes() : \ArrayIterator;

}
