<?php
namespace AGTI\Rodonaves\Form;

use AGTI\Cliente\Form\Form;
use AGTI\Rodonaves\Entity\Configuration as EntityConfiguration;
use HelperForm;
use Tools;

class Configuration extends Form
{
    protected $submitButton = 'agrodonaves-configuration-submit';

    public function renderHtml()
    {
        $inputs = [
            [
                'type' => 'text',
                'label' => 'Usuário',
                'name' => 'AGRODONAVES_API_USERNAME',
                'col' => 2
            ],
            [
                'type' => 'text',
                'label' => 'Senha',
                'name' => 'AGRODONAVES_API_PASSWORD',
                'col' => 2
            ],
            [
                'type' => 'text',
                'label' => 'CPF/CNPJ cadastrado na Rodonaves',
                'name' => 'AGRODONAVES_API_CUSTOMER_TAX_ID',
                'col' => 3
            ],
            [
                'type' => 'text',
                'label' => 'CEP de Origem',
                'name' => 'AGRODONAVES_POSTCODE_ORIGIN',
                'col' => 1
            ],
            [
                'type' => 'text',
                'label' => 'Endpoint da API',
                'desc' => 'Mantenha o campo vazio para usar o Endpoint padrão',
                'name' => 'AGRODONAVES_ENDPOINT',
                'required' => false
            ]
        ];

        $forms = [[
            'form' => [
                'legend' => ['title' => 'Dados da API'],
                'input' => $inputs,
                'submit' => ['title' => 'Salvar', 'name' => $this->submitButton]
            ]
        ]];

        $form = $this->getHelperForm();
        $this->fillForm($form);

        return $form->generateForm($forms);
    }

    public function postProcess()
    {
        if (Tools::isSubmit($this->submitButton)) {
            $this->persistData();
        }
    }

    protected function fillForm(HelperForm $form)
    {
        $config = new EntityConfiguration;
        $config->loadConfig();

        $form->fields_value['AGRODONAVES_API_USERNAME'] = $config->getUsername();
        $form->fields_value['AGRODONAVES_API_PASSWORD'] = $config->getPassword();
        $form->fields_value['AGRODONAVES_POSTCODE_ORIGIN'] = $config->getPostcodeFrom();
        $form->fields_value['AGRODONAVES_API_CUSTOMER_TAX_ID'] = $config->getTaxRegistrationId();
        $form->fields_value['AGRODONAVES_API_CUSTOMER_TAX_ID'] = $config->getTaxRegistrationId();
        $form->fields_value['AGRODONAVES_ENDPOINT'] = $config->getEndpoint();
    }
    
    protected function persistData()
    {
        $config = new EntityConfiguration;

        $config->setUsername(Tools::getValue('AGRODONAVES_API_USERNAME'));
        $config->setPassword(Tools::getValue('AGRODONAVES_API_PASSWORD'));
        $config->setPostcodeFrom(Tools::getValue('AGRODONAVES_POSTCODE_ORIGIN'));
        $config->setTaxRegistrationId(Tools::getValue('AGRODONAVES_API_CUSTOMER_TAX_ID'));
        $config->setEndpoint(Tools::getValue('AGRODONAVES_ENDPOINT'));

        $config->persist();
    }
}
