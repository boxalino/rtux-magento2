<?php
namespace Boxalino\RealTimeUserExperience\Block;

use Boxalino\RealTimeUserExperience\Api\ApiBlockAccessorInterface;
use Boxalino\RealTimeUserExperience\Api\ApiRendererInterface;
use Boxalino\RealTimeUserExperience\Api\ApiResponseBlockInterface;
use Boxalino\RealTimeUserExperience\Api\CurrentApiResponseRegistryInterface;
use Boxalino\RealTimeUserExperience\Api\CurrentApiResponseViewRegistryInterface;
use Boxalino\RealTimeUserExperienceApi\Service\Api\Response\Accessor\BlockInterface;
use Boxalino\RealTimeUserExperienceApi\Service\Api\Response\Accessor\BxAttributeList;
use Boxalino\RealTimeUserExperienceApi\Service\Api\Response\ApiResponseViewInterface;
use Boxalino\RealTimeUserExperienceApi\Service\Api\Response\ResponseDefinitionInterface;
use Boxalino\RealTimeUserExperienceApi\Service\ErrorHandler\MissingDependencyException;
use Boxalino\RealTimeUserExperience\Model\Request\ApiPageLoader;
use Boxalino\RealTimeUserExperienceApi\Service\ErrorHandler\UndefinedPropertyError;

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
     * @var CurrentApiResponseRegistryInterface
     */
    protected $currentApiResponse;

    /**
     * @var CurrentApiResponseViewRegistryInterface
     */
    protected $currentApiResponseView;

    /**
     * @var \ArrayIterator
     */
    protected $bxAttributes;


    /**
     * @return \ArrayIterator|null
     */
    public function getBlocks() : \ArrayIterator
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
     * @return ApiBlockAccessorInterface | null
     */
    public function getBlock()
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
    public function getRtuxVariantUuid()
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
    public function getRtuxGroupBy()
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
     * Generates API response block elements
     * Required parameters: type, template, name
     *
     * @param BlockInterface $block
     */
    public function getApiBlock(ApiBlockAccessorInterface $block) : ApiRendererInterface
    {
        if(!$block->getType())
        {
            throw new MissingDependencyException("BoxalinoAPI RenderBlock Error: the block type is missing: " . json_encode($block));
        }

        if(!$block->getTemplate())
        {
            throw new MissingDependencyException("BoxalinoAPI RenderBlock Error: the block template is missing: " . json_encode($block));
        }

        if(!$block->getName())
        {
            throw new MissingDependencyException("BoxalinoAPI RenderBlock Error: the block name is missing: " . json_encode($block));
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

            try{
                foreach($block->getChild() as $childBlockName)
                {
                    foreach($block->getBlocks() as $index => $nestedBlock)
                    {
                        try {
                            if($nestedBlock->getName() === $childBlockName)
                            {
                                $childBlock = $apiBlock->getApiBlock($nestedBlock);
                                if($apiBlock->getChildBlock($childBlockName))
                                {
                                    continue;
                                }
                                $apiBlock->setChild($childBlockName, $childBlock);

                                /** once the child block is set, it needs to be removed from the nested elements */
                                $block->getBlocks()->offsetUnset($index);
                                $apiBlock->setBlock($block);
                            }
                        } catch(UndefinedPropertyError $error) {
                        }
                    }
                }
            } catch(UndefinedPropertyError $error) {
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

    /**
     * @return bool
     */
    public function isApiFallback() : bool
    {
        if($this->currentApiResponse->get() instanceof ResponseDefinitionInterface && $this->currentApiResponseView->get() instanceof ApiResponseViewInterface)
        {
            return $this->currentApiResponseView->get()->isFallback();
        }

        return true;
    }

    /**
     * Access the Boxalino response attributes for API JS tracker
     *
     * @return \ArrayIterator
     */
    public function getBxAttributes() : \ArrayIterator
    {
        try {
            $block = $this->getBlock();
            if(is_null($block))
            {
                /** this is the place where generally, the API request is done from Block; change per your setup (if needed) */
                $this->_prepareLayout();
            }

            $this->bxAttributes = $this->getBlock()->getBxAttributes();
        } catch (\Throwable $exception)
        {
            $this->bxAttributes = new BxAttributeList();
        }

        return $this->bxAttributes;
    }


}
