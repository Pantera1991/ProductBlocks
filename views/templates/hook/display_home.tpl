{if isset($blocks)}
    <div id="productBlocks" class="container">
        <div class="row">
            {foreach from=$blocks item=block}
                <div class="col col-md-12 col-lg-12 col-xl-4 pl-0 pr-0 mb-1 p-block-wrapper">
                    <article class="p-block" id="{$block->getBlockId()}">
                        <header class="p-block-header">
                            <h3 class="p-block-title">
                                {$block->getCategoryName()}
                                <span class="border-line"></span>
                            </h3>
                            <div class="p-block-action">
                                <button class="p-block-btn-nav"
                                        data-action="p-block-scroll"
                                        data-direction="up"
                                        data-id-block="{$block->getBlockId()}">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                         viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7"/>
                                    </svg>
                                </button>
                                <button class="p-block-btn-nav"
                                        data-action="p-block-scroll"
                                        data-direction="down"
                                        data-id-block="{$block->getBlockId()}">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                         viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </button>
                            </div>
                        </header>

                        <div class="p-block-content-wrapper">
                            <div class="p-block-content">
                                {foreach from=$block->getProducts() item=product}
                                    <div class="p-block-item">
                                        {if $product.cover}
                                            <a href="{$product.url}" class="thumbnail product-thumbnail">
                                                <img
                                                        src="{$product.cover.bySize.small_default.url}"
                                                        alt="{if !empty($product.cover.legend)}{$product.cover.legend}{else}{$product.name|truncate:30:'...'}{/if}"
                                                        loading="lazy"
                                                        data-full-size-image-url="{$product.cover.large.url}"
                                                        width="{$product.cover.bySize.small_default.width}"
                                                        height="{$product.cover.bySize.small_default.height}"
                                                />
                                            </a>
                                        {/if}
                                        <span class="p-product-name">{$product.name}</span>
                                        <div class="p-product-action">
                                            <span class="p-product-price">{$product.price}</span>
                                            {if $product.has_discount}
                                                <span class="p-product-price-regular">{$product.regular_price}</span>
                                            {/if}

                                            <div class="p-product-add">
                                                <form action="{$product->getAddToCartUrl()}" method="post">
                                                    <input
                                                            type="number"
                                                            name="qty"
                                                            inputmode="numeric"
                                                            pattern="[0-9]*"
                                                            {if $product.quantity_wanted}
                                                                value="{$product.quantity_wanted}"
                                                                min="{$product.minimal_quantity}"
                                                            {else}
                                                                value="1"
                                                                min="1"
                                                            {/if}
                                                            class="p-input-add"
                                                            aria-label="{l s='Quantity' d='Shop.Theme.Actions'}"
                                                    >
                                                    <button class="p-button-add" data-button-action="add-to-cart"
                                                            type="submit">
                                                        <svg viewBox="0 0 20 20" fill="#ffffff">
                                                            <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"/>
                                                        </svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                {/foreach}
                            </div>
                        </div>
                        <footer>
                            <a class="p-block-link" href="{$block->getCategoryUrl()}">
                                {l s="more in category" d='Modules.ProductBlocks.Displayhome'}
                            </a>
                        </footer>
                    </article>
                </div>
            {/foreach}
        </div>
    </div>
{/if}