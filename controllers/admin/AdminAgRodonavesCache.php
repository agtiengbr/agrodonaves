<?php

class AdminAgRodonavesCacheController extends ModuleAdminController
{
    public function __construct()
    {
        $this->bootstrap    = true;
        $this->table        = 'agrodonaves_cache';
        $this->identifier   = 'id_agrodonaves_cache';
        $this->className    = 'AgRodonavesCache';
        $this->noLink       = true;
        $this->list_no_link = true;
        $this->_defaultOrderBy = 'date_add';
        $this->_defaultOrderWay = 'DESC';
        parent::__construct();
        
        $this->module->prepareNotifications();

        $this->setFieldsList();
        $this->actions = ['delete'];
    }

    private function setFieldsList()
    {
        $this->fields_list = [
            'id_agrodonaves_cache' => [
                'type'  => 'int',
                'title' => 'ID',
                'class' => 'fixed-width-sm'
            ],
            'postcode_from' => [
                'type'  => 'text',
                'title' => 'CEP Origem',
                'maxlength' => 200
            ],
            'postcode_to' => [
                'type'  => 'text',
                'title' => 'CEP Destino',
                'maxlength' => 200
            ],
            'total_weight' => [
                'type'  => 'number',
                'title' => 'Peso',
                'class' => 'fixed-width-lg',
                'suffix' => 'kg'
            ],
            'invoice_value' => [
                'type'  => 'price',
                'title' => 'Valor Pedido',
                'class' => 'fixed-width-lg'
            ],
            'shipping_cost' => [
                'type'  => 'price',
                'title' => 'Custo do Frete',
                'class' => 'fixed-width-lg'
            ],
            'delivery_time' => [
                'type'  => 'int',
                'title' => 'Prazo de Entrega',
                'class' => 'fixed-width-lg',
                'suffix' => 'dias úteis'
            ],
            'date_add' => [
                'type' => 'datetime',
                'title' => 'Data do Cálculo'
            ]
        ];
    }
}
