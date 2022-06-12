<?php

namespace Piotr\Module\ProductBlocks\Form;

use Category;
use Configuration;
use Context;
use Module;
use Piotr\Module\ProductBlocks\Enum\Blocks;
use PrestaShopBundle\Translation\TranslatorInterface;
use Tools;
use Validate;

class ModuleConfiguration
{
    public const SUBMIT_ACTION = "submitConfigProductBlocks";

    private Module $module;
    private Context $context;
    private TranslatorInterface $translator;

    /**
     * @param Module $module
     * @param Context $context
     */
    public function __construct(Module $module, Context $context)
    {
        $this->module = $module;
        $this->context = $context;
        $this->translator = $module->getTranslator();
    }

    /**
     * @return string
     * @throws \PrestaShopDatabaseException
     * @throws \PrestaShopException
     */
    public function render(): string
    {
        $response = $this->postProcess();

        return $response . $this->renderForm();
    }

    /**
     * @return string
     */
    private function postProcess(): string
    {
        $output = "";
        $errors = [];

        if (Tools::isSubmit(self::SUBMIT_ACTION)) {

            foreach (Blocks::getConstants() as $block) {
                $value = Tools::getValue($block);

                if (!Validate::isInt($value)) {
                    $errors[] = $this->translator->trans(
                            'The category ID is invalid for block: ',
                            [],
                            \Product_Blocks::TRANS_DOMAIN
                        ) . $block;
                }
            }

            if (count($errors) > 0) {
                $output = $this->module->displayError(implode('<br />', $errors));
            } else {
                foreach (Blocks::getConstants() as $block) {
                    $value = Tools::getValue($block);
                    Configuration::updateValue($block, (int)$value);
                }
                $output = $this->module->displayConfirmation(
                    $this->translator->trans(
                        'The settings have been updated.', [], \Product_Blocks::TRANS_DOMAIN
                    )
                );
            }
        }

        return $output;
    }

    /**
     * @return string
     * @throws \PrestaShopDatabaseException
     * @throws \PrestaShopException
     */
    private function renderForm(): string
    {
        $fieldsForm = [
            'form' => [
                'legend' => [
                    'title' => $this->translator->trans('Settings', [], 'Admin.Global'),
                    'icon' => 'icon-cogs',
                ],
                'description' => $this->translator->trans(
                    'Select from which category products should be displayed in each block', [],
                    \Product_Blocks::TRANS_DOMAIN
                ),
                'input' => $this->getInputs(),
                'submit' => [
                    'title' => $this->translator->trans('Save', [], 'Admin.Actions'),
                ],
            ],
        ];

        $lang = new \Language((int)\Configuration::get('PS_LANG_DEFAULT'));
        $form = new \HelperForm();
        $form->show_toolbar = false;
        $form->submit_action = self::SUBMIT_ACTION;
        $form->table = $this->module->name;
        $form->default_form_language = $lang->id;
        $form->module = $this->module;
        $form->allow_employee_form_lang = \Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ?: 0;
        $form->currentIndex = $this->module->getAdminLink();
        $form->token = \Tools::getAdminTokenLite('AdminModules');
        $form->tpl_vars = [
            'fields_value' => $this->getConfigFieldsValues(),
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
        ];
        return $form->generateForm([$fieldsForm]);
    }

    /**
     * @return array
     */
    private function getInputs(): array
    {
        $inputs = [];
        $i = 1;
        $categories = Category::getSimpleCategories($this->context->language->id);

        foreach (Blocks::getConstants() as $block) {
            $label = $this->translator->trans(
                    "Category block nr: ", [],
                    \Product_Blocks::TRANS_DOMAIN
                ) . $i;

            $inputs[] = [
                'type' => 'select',
                'label' => $label,
                'name' => $block,
                'options' => [
                    'query' => $categories,
                    'id' => 'id_category',
                    'name' => 'name',
                ]
            ];
            $i++;
        }

        return $inputs;
    }

    /**
     * @return array
     */
    private function getConfigFieldsValues(): array
    {
        $result = [];
        foreach (Blocks::getConstants() as $block) {
            $result[$block] = Tools::getValue($block, (int)Configuration::get($block));
        }

        return $result;
    }
}