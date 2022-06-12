<?php

namespace Piotr\Module\ProductBlocks\Install;

use Configuration;
use Module;
use Piotr\Module\ProductBlocks\Enum\Blocks;

class Installer
{
    private array $hooks = [
        'displayHome',
        'actionFrontControllerSetMedia'
    ];

    /**
     * @var Module
     */
    private Module $module;

    public function __construct(Module $module)
    {
        $this->module = $module;
    }

    public function install(): bool
    {
        if (!$this->registerHooks()) {
            \PrestaShopLogger::addLog("error register hooks");
            $this->module->displayError("error register hooks");
            return false;
        }

        if (!$this->installDatabase()) {
            \PrestaShopLogger::addLog("error register hooks");
            $this->module->displayError("error register hooks");
            return false;
        }

        if (!$this->installTab()) {
            \PrestaShopLogger::addLog("error create tab");
            $this->module->displayError("error create tab");
            return false;
        }

        $this->saveConfiguration();

        return true;
    }

    /**
     * @return bool
     */
    public function uninstall(): bool
    {
        return $this->uninstallTab();
    }

    /**
     * @return void
     */
    private function saveConfiguration(): void
    {
        foreach (Blocks::getConstants() as $block) {
            Configuration::updateValue($block, "");
        }
    }

    /**
     * @return bool
     */
    private function installDatabase(): bool
    {
        // query create table
        return true;
    }

    /**
     * @return bool
     */
    private function installTab(): bool
    {
        // init admin tab
        return true;
    }

    /**
     * @return bool
     */
    private function uninstallTab(): bool
    {
        //remove admin tab
        return true;
    }

    /**
     * @return bool
     */
    private function registerHooks(): bool
    {
        if (count($this->hooks) > 0) {
            return $this->module->registerHook($this->hooks);
        }

        return true;
    }
}