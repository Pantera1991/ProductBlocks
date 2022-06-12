<?php

use Piotr\Module\ProductBlocks\Form\ModuleConfiguration;
use Piotr\Module\ProductBlocks\Install\Installer;
use Piotr\Module\ProductBlocks\Widget\DisplayHomeWidget;
use PrestaShop\PrestaShop\Core\Module\WidgetInterface;

if (!defined('_PS_VERSION_')) {
    exit;
}

if (Shop::isFeatureActive()) {
    Shop::setContext(Shop::CONTEXT_ALL);
}

if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    require_once __DIR__ . '/vendor/autoload.php';
}

class Product_Blocks extends Module implements WidgetInterface
{
    public const TRANS_DOMAIN = 'Modules.Productblocks.Admin';

    private DisplayHomeWidget $displayHomeWidget;

    public function __construct()
    {
        $this->name = 'product_blocks';
        $this->version = '1.0.0';
        $this->author = 'Piotr Chmieliński';
        $this->need_instance = 0;
        $this->ps_versions_compliancy = [
            'min' => '1.7',
            'max' => _PS_VERSION_,
        ];
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('Product blocks');
        $this->description = $this->l('the module displays three blocks of products from selected categories.');
        $this->confirmUninstall = $this->l('Are you sure you want to uninstall?');

        $this->displayHomeWidget = new DisplayHomeWidget($this->context);
    }

    /**
     * @return bool
     */
    public function install(): bool
    {
        if (!parent::install()) {
            return false;
        }

        return (new Installer($this))->install();
    }

    /**
     * @return bool
     */
    public function uninstall(): bool
    {
        return (new Installer($this))->uninstall() && parent::uninstall();
    }

    /**
     * @return void
     */
    public function hookActionFrontControllerSetMedia(): void
    {
        if ('index' === $this->context->controller->php_self) {
            $this->context->controller->registerStylesheet(
                "module-productblocks-style",
                "modules/{$this->name}/views/css/display_home.css",
                [
                    'media' => 'all',
                    'priority' => 200,
                ]
            );
            $this->context->controller->registerJavascript(
                'module-productblocks-js',
                "modules/{$this->name}/views/js/display_home.js", [
                    'position' => 'bottom',
                    'priority' => 100,
                ]
            );
        }
    }

    /**
     * @param $hookName
     * @param array $configuration
     * @return string
     * @throws PrestaShopDatabaseException
     */
    public function renderWidget($hookName, array $configuration): string
    {
        if ("displayHome" === $hookName) {

            $this->getWidgetVariables($hookName, $configuration);
            return $this->fetch(
                DisplayHomeWidget::TEMPLATE_FILE,
            //$this->getCacheId(DisplayHomeWidget::CACHE_ID)
            );
        }
        return "";
    }

    /**
     * @param $hookName
     * @param array $configuration
     * @throws PrestaShopDatabaseException
     */
    public function getWidgetVariables($hookName, array $configuration): void
    {
        if (
            "displayHome" === $hookName
            //&& !$this->isCached(DisplayHomeWidget::TEMPLATE_FILE, $this->getCacheId(DisplayHomeWidget::CACHE_ID))
        ) {
            $vars = $this->displayHomeWidget->variables();
            $this->smarty->assign($vars);
        }
    }

    /**
     * @return string
     * @throws PrestaShopDatabaseException
     * @throws PrestaShopException
     */
    public function getContent(): string
    {
        return (new ModuleConfiguration($this, $this->context))->render();
    }

    /**
     * @return string
     */
    public function getAdminLink(): string
    {
        return $this->context->link->getAdminLink(
            'AdminModules',
            false,
            [],
            [
                'configure' => $this->name,
                'tab_module' => $this->tab,
                'module_name' => $this->name
            ]
        );
    }
}