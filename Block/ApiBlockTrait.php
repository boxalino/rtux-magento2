<?php
namespace Boxalino\RealTimeUserExperience\Block;

use Boxalino\RealTimeUserExperience\Api\ApiBlockAccessorInterface;
use Boxalino\RealTimeUserExperience\Api\ApiRendererInterface;
use Boxalino\RealTimeUserExperienceApi\Service\Api\Response\Accessor\BlockInterface;
use Boxalino\RealTimeUserExperienceApi\Service\ErrorHandler\MissingDependencyException;

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
     * @return \ArrayIterator|null
     */
    public function getBlocks() : ?\ArrayIterator
    {
        return $this->getBlock()->getBlocks();
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
        return $this->rtuxGroupBy;
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

        $apiBlock = $this->getLayout()->createBlock($block->getType(), $block->getName())
            ->setTemplate($block->getTemplate());

        if($apiBlock instanceof ApiRendererInterface)
        {
            $apiBlock->setRtuxVariantUuid($this->getRtuxVariantUuid())
                ->setRtuxGroupBy($this->getRtuxGroupBy())
                ->setBlock($block);
        }

        return $apiBlock;
    }

}
