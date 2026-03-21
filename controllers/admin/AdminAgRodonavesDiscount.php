<?php

class AdminAgRodonavesDiscountController extends ModuleAdminController
{
	public function __construct()
	{
		$this->bootstrap    = true;
        $this->table        = 'agrodonaves_discount';
        $this->identifier   = 'id_agrodonaves_discount';
        $this->className    = 'AgRodonavesDiscount';
        $this->noLink       = true;
        $this->list_no_link = true;

		parent::__construct();

		$this->module->prepareNotifications();


		$this->fields_list = [
			'id_agrodonaves_discount' => [
				'type'  => 'int',
				'title' => 'ID',
				'class' => 'fixed-width-sm'
			],
			'alias' => [
				'type'  => 'text',
				'title' => 'Nome da Campanha',
			],
			'type_discount' => [
				'type'       => 'select',
				'title'      => 'Tipo de Desconto',
				'filter_key' => 'a!type_discount',
				'list'       => [
					'0' => 'Percentual',
					'1' => 'Valor Fixo'
				],
				'class' => 'fixed-width-md'
			],
			'discount' => [
				'type'  => 'int',
				'title' => 'Desconto',
				'class' => 'fixed-width-sm'
			],
			'postcode_begin' => [
				'type'  => 'int',
				'title' => 'CEP Início',
				'class' => 'fixed-width-sm'
			],
			'postcode_end' => [
				'type'  => 'int',
				'title' => 'CEP Fim',
				'class' => 'fixed-width-sm'
			],
			'cart_value_begin' => [
				'type'  => 'price',
				'title' => 'Pedido Mínimo',
				'class' => 'fixed-width-sm'
			],
			'cart_value_end' => [
				'type'  => 'price',
				'title' => 'Pedido Máximo',
				'class' => 'fixed-width-sm'
			],
			'active' => [
				'type'   => 'bool',
				'title'  => 'Ativo',
				'active' => 'active'
			]
		];

		$this->fields_form = [
			'legend' => ['title' => 'Desconto'],
			'input'  => [
				[
					'name'     => 'alias',
					'type'     => 'text',
					'label'    => 'Nome da Campanha',
					'hint'     => 'Ex: Frete Grátis Sudeste',
					'col'      => '5',
					'required' => true
				],
				[
					'name'     => 'type_discount',
					'type'     => 'radio',
					'label'    => 'Tipo de desconto',
					'required' => true,
					'values'   => [
						[
							'label' => 'Percentual',
							'id'    => 'type_discount_percentual',
							'value' => 0
						],
						[
							'label' => 'Valor Fixo',
							'id'    => 'type_discount_fixed_value',
							'value' => 1
						]
					]
				],
				[
					'name'     => 'discount',
					'type'     => 'text',
					'label'    => 'Desconto',
					'required' => true,
					'col'      => 1
				],
				[
					'name'     => 'postcode_begin',
					'type'     => 'text',
					'label'    => 'CEP - Início',
					'col'      => 2
				],
				[
					'name'     => 'postcode_end',
					'type'     => 'text',
					'label'    => 'CEP - Fim',
					'col'      => 2
				],
				[
					'name'     => 'cart_value_begin',
					'type'     => 'text',
					'label'    => 'Pedido Mínimo',
					'prefix'   => 'R$',
					'col'      => 2
				],
				[
					'name'     => 'cart_value_end',
					'type'     => 'text',
					'label'    => 'Pedido Máximo',
					'prefix'   => 'R$',
					'col'      => 2
				],
				[
                    'type' => 'switch',
                    'label' => 'Ativo',
                    'name' => 'active',
                    'values' => [
                        [
                            'id'    => 'active_on',
                            'value' => 1,
                            'label' => 'Sim',
                        ],
                        [
                            'id'    => 'active_off',
                            'value' => 0,
                            'label' => 'Não',
                        ],
                    ],
                ]
			],
			'submit' => [
                'title' => 'Salvar',
            ]
		];

		$this->actions = ['edit', 'delete'];
		$this->bulk_actions = [
			'enableSelection' => [
				'text' => 'Ativar',
            	'icon' => 'icon-check'
			],
			'disableSelection' => [
				'text' => 'Desativar',
            	'icon' => 'icon-times'
			],
            'delete' => [
            	'text' => 'Excluir',
            	'icon' => 'icon-trash'
            ]
        ];
	}

	public function initContent()
	{
		if (Tools::getIsSet('active' . $this->table)) {
			$object = $this->loadObject();
			$object->active = !$object->active;
			$object->update();

			$this->module->confirmations[]  = 'Desconto atualizado com sucesso!';
			$this->module->saveNotifications();

			Tools::redirectAdmin(self::$currentIndex);
		}

		parent::initContent();
	}

    public function getList($id_lang, $orderBy = null, $orderWay = null, $start = 0, $limit = null, $id_lang_shop = null)
    {
        parent::getList($id_lang, $orderBy, $orderWay, $start, $limit, $this->context->shop->id);

        if (is_array($this->_list)) {
            $nb = count($this->_list);
            
            for ($i = 0; $i < $nb; $i++) {
                $this->_list[$i]['type_discount'] = isset($this->_list[$i]['type_discount']) && $this->_list[$i]['type_discount'] == 0? 'Percentual' : 'Valor Fixo';

                $this->_list[$i]['type_interval'] = isset($this->_list[$i]['type_interval']) && $this->_list[$i]['type_interval'] == 0? 'Faixa de CEP' : 'Região';
            }
        }
    }

    public function setMedia($isNewTheme = false)
    {
        parent::setMedia($isNewTheme);

        $this->addJs(array(
            _PS_MODULE_DIR_ . 'agrodonaves/views/js/discounts/form.js'
        ));
    }


    /******************* ações em massa ************************/
    protected function processBulkEnableSelection()
    {
        return $this->processBulkStatusSelection(1);
    }

    protected function processBulkDisableSelection()
    {
        return $this->processBulkStatusSelection(0);
    }

    protected function processBulkStatusSelection($status)
    {
        if (is_array($this->boxes) && !empty($this->boxes)) {
            foreach ($this->boxes as $id) {
                /** @var ObjectModel $object */
                $object = new $this->className((int)$id);
                $object->active = (int)$status;
                if (!$object->update()) {
                    $msg_error = Db::getInstance()->getMsgError();
                    $this->module->errors[] = "Erro atualizando status do desconto {$id} - {$msg_error}";
                } else {
                    $this->module->confirmations[] = "Desconto {$id} atualizada com sucesso!";
                }
            }
        }
    }

}
