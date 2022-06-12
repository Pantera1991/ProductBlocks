<?php

namespace Piotr\Module\ProductBlocks\Widget;

use Category;
use Configuration;
use Context;
use Link;
use Piotr\Module\ProductBlocks\Dto\ProductBlockDto;
use Piotr\Module\ProductBlocks\Enum\Blocks;
use PrestaShop\PrestaShop\Adapter\Category\CategoryProductSearchProvider;
use PrestaShop\PrestaShop\Adapter\Presenter\Product\ProductLazyArray;
use PrestaShop\PrestaShop\Core\Product\Search\ProductSearchContext;
use PrestaShop\PrestaShop\Core\Product\Search\ProductSearchQuery;
use PrestaShop\PrestaShop\Core\Product\Search\SortOrder;
use ProductPresenterFactory;

class DisplayHomeWidget
{
    public const TEMPLATE_FILE = 'module:product_blocks/views/templates/hook/display_home.tpl';
    public const CACHE_ID = 'product_blocks_display_home';
    //todo move to Configuration
    public const N_PRODUCTS = 10;

    private Context $context;

    public function __construct(Context $context)
    {
        $this->context = $context;
    }

    /**
     * @throws \PrestaShopDatabaseException
     */
    public function variables(): array
    {
        $blocks = Blocks::getConstants();

        if (count($blocks) === 0) {
            return [];
        }

        $blocksForTemplate = [];

        foreach ($blocks as $block) {
            $categoryId = (int)Configuration::get($block);

            if ($categoryId === 0) {
                continue;
            }

            $category = new Category($categoryId, $this->context->language->id);
            $link = new Link();
            $blocksForTemplate[] = new ProductBlockDto(
                $block,
                $category->name,
                $link->getCategoryLink($category->id_category, $category->link_rewrite),
                $this->getProductsByCategory($category)
            );
        }
        return [
            "blocks" => $blocksForTemplate
        ];
    }

    /**
     * @throws \PrestaShopDatabaseException
     */
    private function getProductsByCategory(Category $category): array
    {
        $searchProvider = new CategoryProductSearchProvider(
            $this->context->getTranslator(),
            $category
        );

        $context = new ProductSearchContext($this->context);

        $query = (new ProductSearchQuery())
            ->setResultsPerPage(self::N_PRODUCTS)
            ->setPage(1)
            ->setSortOrder(new SortOrder('product', 'position', 'asc'));

        $resultQuery = $searchProvider->runQuery(
            $context,
            $query
        );

        return array_map([$this, 'prepareProduct'], $resultQuery->getProducts());
    }

    /**
     * @throws \ReflectionException
     */
    public function prepareProduct(array $rawProduct): ProductLazyArray
    {
        $presenterFactory = new ProductPresenterFactory($this->context);
        $presentationSettings = $presenterFactory->getPresentationSettings();
        return $presenterFactory->getPresenter()->present($presentationSettings, $rawProduct, $this->context->language);
    }
}