<?php

namespace Piotr\Module\ProductBlocks\Dto;

use PrestaShop\PrestaShop\Adapter\Presenter\Product\ProductListingLazyArray;

class ProductBlockDto
{
    /**
     * @var string
     */
    private string $blockId;
    /**
     * @var string
     */
    private string $categoryName;
    /**
     * @var string
     */
    private string $categoryUrl;
    /**
     * @var array<ProductListingLazyArray>
     */
    private array $products;

    /**
     * @param string $blockId
     * @param string $categoryName
     * @param string $categoryUrl
     * @param ProductListingLazyArray[] $products
     */
    public function __construct(string $blockId, string $categoryName, string $categoryUrl, array $products)
    {
        $this->blockId = $blockId;
        $this->categoryName = $categoryName;
        $this->categoryUrl = $categoryUrl;
        $this->products = $products;
    }

    /**
     * @return string
     */
    public function getCategoryName(): string
    {
        return $this->categoryName;
    }

    /**
     * @return string
     */
    public function getCategoryUrl(): string
    {
        return $this->categoryUrl;
    }

    /**
     * @return ProductListingLazyArray[]
     */
    public function getProducts(): array
    {
        return $this->products;
    }

    /**
     * @return string
     */
    public function getBlockId(): string
    {
        return $this->blockId;
    }

}