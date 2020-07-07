<?php
namespace Boxalino\RealTimeUserExperience\Block;

use Boxalino\RealTimeUserExperience\Api\ApiBlockAccessorInterface;
use Boxalino\RealTimeUserExperience\Api\ApiRendererInterface;
use Boxalino\RealTimeUserExperience\Api\ApiResponseBlockInterface;
use Boxalino\RealTimeUserExperienceApi\Service\Api\Response\Accessor\BlockInterface;
use Boxalino\RealTimeUserExperienceApi\Service\ErrorHandler\MissingDependencyException;
use Boxalino\RealTimeUserExperience\Model\Request\ApiPageLoader;

/**
 * Trait ApiBlockTrait
 * Setters and getters for the generic ApiRendererInterface blocks
 *
 * @package Boxalino\RealTimeUserExperience\Block
 */
trait ApiBlockTrait
{
    /**
     * @var ApiBlockAccessorInterface
     */
    protected $rtuxApiBlock;

    /**
     * @var string | null
     */
    protected $rtuxVariantUuid = null;

    /**
     * @var string | null
     */
    protected $rtuxGroupBy = null;

    /**
     * @var \ArrayIterator | null
     */
    protected $apiBlocks = null;

    /**
     * @var ApiPageLoader
     */
    protected $apiLoader;

    /**
     * @return \ArrayIterator|null
     */
    public function getBlocks() : ?\ArrayIterator
    {
        if($this->apiBlocks)
        {
            return $this->apiBlocks;
        }

        return $this->getBlock()->getBlocks();
    }

    /**
     * @param \ArrayIterator $blocks
     * @return $this
     */
    public function setBlocks(\ArrayIterator $blocks) : ApiRendererInterface
    {
        $this->apiBlocks = $blocks;
        return $this;
    }

    /**
     * @param ApiBlockAccessorInterface $block
     * @return $this
     */
    public function setBlock(ApiBlockAccessorInterface $block) : ApiRendererInterface
    {
        $this->rtuxApiBlock = $block;
        return $this;
    }

    /**
     * @return ApiBlockAccessorInterface
     */
    public function getBlock() : ApiBlockAccessorInterface
    {
        return $this->rtuxApiBlock;
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setRtuxVariantUuid(string $value) : ApiRendererInterface
    {
        $this->rtuxVariantUuid = $value;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getRtuxVariantUuid() : ?string
    {
        if(is_null($this->rtuxVariantUuid))
        {
            $this->rtuxVariantUuid = $this->getApiLoader()->getApiResponsePage()->getVariantUuid();
        }

        return $this->rtuxVariantUuid;
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setRtuxGroupBy(string $value) : ApiRendererInterface
    {
        $this->rtuxGroupBy = $value;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getRtuxGroupBy() : ?string
    {
        if(is_null($this->rtuxGroupBy))
        {
            $this->rtuxGroupBy = $this->getApiLoader()->getApiResponsePage()->getGroupBy();
        }

        return $this->rtuxGroupBy;
    }

    /**
     * @return ApiPageLoader
     */
    public function getApiLoader() : ApiPageLoader
    {
        return $this->apiLoader;
    }

    /**
     * @param BlockInterface $block
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getApiBlock(ApiBlockAccessorInterface $block) : ?ApiRendererInterface
    {
        if(!$block->getType())
        {
            throw new MissingDependencyException("BoxalinoAPI RenderBlock Error: the block type is missing: " . json_encode($block));
        }

        if(!$block->getTemplate())
        {
            throw new MissingDependencyException("BoxalinoAPI RenderBlock Error: the block template is missing: " . json_encode($block));
        }

        try{
            $apiBlock = $this->getLayout()->createBlock($block->getType(), $block->getName())
                ->setTemplate($block->getTemplate());

            if($apiBlock instanceof ApiRendererInterface)
            {
                $apiBlock->setRtuxVariantUuid($this->getRtuxVariantUuid())
                    ->setRtuxGroupBy($this->getRtuxGroupBy())
                    ->setBlock($block);
            }

            return $apiBlock;
        } catch (\Exception $exception)
        {
            return $this->getDefaultBlock();
        }
    }

    /**
     * On pages extending from \Magento\Framework\View\Element\Template:
     * creates a Magento2 block with the BlockAccessorInterface details
     *
     * @return ApiRendererInterface
     */
    public function getDefaultBlock() : ApiRendererInterface
    {
        return $this->getLayout()
            ->createBlock(
                ApiRendererInterface::BOXALINO_RTUX_API_BLOCK_TYPE_DEFAULT,
                uniqid(ApiRendererInterface::BOXALINO_RTUX_API_BLOCK_NAME_DEFAULT)
            )
            ->setTemplate(ApiRendererInterface::BOXALINO_RTUX_API_BLOCK_TEMPLATE_DEFAULT);
    }

    /**
     * On pages extending from \Magento\Framework\View\Element\Template:
     * creates a Magento2 block with the ApiResponsePageInterface details/accessors
     *
     * @return ApiResponseBlockInterface
     */
    public function getDefaultResponseBlock() : ApiResponseBlockInterface
    {
        return $this->getLayout()
            ->createBlock(
                ApiRendererInterface::BOXALINO_RTUX_API_RESPONSE_TYPE_DEFAULT,
                uniqid(ApiRendererInterface::BOXALINO_RTUX_API_BLOCK_NAME_DEFAULT)
            )
            ->setTemplate(ApiRendererInterface::BOXALINO_RTUX_API_BLOCK_TEMPLATE_DEFAULT);
    }

}
