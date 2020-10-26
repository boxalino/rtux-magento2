<?php declare(strict_types=1);
namespace Boxalino\RealTimeUserExperience\Model\Response\Page;

use Boxalino\RealTimeUserExperienceApi\Framework\Content\Page\ApiResponsePageInterface;
use Boxalino\RealTimeUserExperienceApi\Service\Api\Response\ApiResponseViewInterface;

/**
 * Class ApiResponsePage
 * After the API request - stores all information relevant for a view
 * (required by dependency)
 *
 * @package Boxalino\RealTimeUserExperience\Model\Response\Page
 */
class ApiResponsePage implements ApiResponsePageInterface
{
    /**
     * @var \ArrayIterator
     */
    protected $blocks;

    /**
     * @var string
     */
    protected $requestId;

    /**
     * @var string
     */
    protected $groupBy;

    /**
     * @var bool
     */
    protected $fallback = true;

    /**
     * @var string
     */
    protected $variantUuid;

    /**
     * @var bool
     */
    protected $hasSearchSubPhrases = false;

    /**
     * @var string | null
     */
    protected $redirectUrl;

    /**
     * @var int
     */
    protected $totalHitCount = 0;

    /**
     * @var string
     */
    protected $searchTerm;

    /**
     * @var \ArrayIterator
     */
    protected $left;

    /**
     * @var \ArrayIterator
     */
    protected $right;

    /**
     * @var \ArrayIterator
     */
    protected $bottom;

    /**
     * @var \ArrayIterator
     */
    protected $top;

    /**
     * @return \ArrayIterator
     */
    public function getBlocks() : \ArrayIterator
    {
        return $this->blocks;
    }

    /**
     * @return string
     */
    public function getRequestId() : string
    {
        return $this->requestId;
    }

    /**
     * @return string
     */
    public function getGroupBy() : string
    {
        return $this->groupBy;
    }

    /**
     * @param \ArrayIterator $blocks
     * @return $this
     */
    public function setBlocks(\ArrayIterator $blocks) : ApiResponseViewInterface
    {
        $this->blocks = $blocks;
        return $this;
    }

    /**
     * @param string $groupBy
     * @return $this
     */
    public function setGroupBy(string $groupBy) : ApiResponseViewInterface
    {
        $this->groupBy = $groupBy;
        return $this;
    }

    /**
     * @param string $requestId
     * @return $this
     */
    public function setRequestId(string $requestId) : ApiResponseViewInterface
    {
        $this->requestId = $requestId;
        return $this;
    }

    /**
     * @return bool
     */
    public function isFallback(): bool
    {
        return $this->fallback;
    }

    /**
     * @param bool $fallback
     * @return ApiResponsePage
     */
    public function setFallback(bool $fallback): ApiResponseViewInterface
    {
        $this->fallback = $fallback;
        return $this;
    }

    /**
     * @return string
     */
    public function getVariantUuid(): string
    {
        return $this->variantUuid;
    }

    /**
     * @param string $variantUuid
     * @return ApiResponsePage
     */
    public function setVariantUuid(string $variantUuid): ApiResponseViewInterface
    {
        $this->variantUuid = $variantUuid;
        return $this;
    }

    /**
     * @return bool
     */
    public function hasSearchSubPhrases(): bool
    {
        return $this->hasSearchSubPhrases;
    }

    /**
     * @param bool $hasSearchSubPhrases
     * @return ApiResponsePage
     */
    public function setHasSearchSubPhrases(bool $hasSearchSubPhrases)
    {
        $this->hasSearchSubPhrases = $hasSearchSubPhrases;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getRedirectUrl()
    {
        return $this->redirectUrl;
    }

    /**
     * @param string|null $redirectUrl
     * @return ApiResponsePage
     */
    public function setRedirectUrl($redirectUrl = null)
    {
        $this->redirectUrl = $redirectUrl;
        return $this;
    }

    /**
     * @return int
     */
    public function getTotalHitCount(): int
    {
        return $this->totalHitCount;
    }

    /**
     * @param int $totalHitCount
     * @return ApiResponsePage
     */
    public function setTotalHitCount(int $totalHitCount)
    {
        $this->totalHitCount = $totalHitCount;
        return $this;
    }

    /**
     * @param string $searchTerm
     * @return $this
     */
    public function setSearchTerm(string $searchTerm)
    {
        $this->searchTerm = $searchTerm;
        return $this;
    }

    /**
     * @return string
     */
    public function getSearchTerm() : string
    {
        return $this->searchTerm;
    }

    /**
     * @return \ArrayIterator
     */
    public function getLeft(): \ArrayIterator
    {
        return $this->left;
    }

    /**
     * @param \ArrayIterator $left
     * @return ApiResponseViewInterface
     */
    public function setLeft(\ArrayIterator $left): ApiResponseViewInterface
    {
        $this->left = $left;
        return $this;
    }

    /**
     * @return \ArrayIterator
     */
    public function getRight(): \ArrayIterator
    {
        return $this->right;
    }

    /**
     * @param \ArrayIterator $right
     * @return ApiResponseViewInterface
     */
    public function setRight(\ArrayIterator $right): ApiResponseViewInterface
    {
        $this->right = $right;
        return $this;
    }

    /**
     * @return \ArrayIterator
     */
    public function getBottom(): \ArrayIterator
    {
        return $this->bottom;
    }

    /**
     * @param \ArrayIterator $bottom
     * @return ApiResponseViewInterface
     */
    public function setBottom(\ArrayIterator $bottom): ApiResponseViewInterface
    {
        $this->bottom = $bottom;
        return $this;
    }

    /**
     * @return \ArrayIterator
     */
    public function getTop(): \ArrayIterator
    {
        return $this->top;
    }

    /**
     * @param \ArrayIterator $top
     * @return ApiResponseViewInterface
     */
    public function setTop(\ArrayIterator $top): ApiResponseViewInterface
    {
        $this->top = $top;
        return $this;
    }

}
