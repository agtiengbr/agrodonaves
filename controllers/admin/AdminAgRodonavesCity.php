<?php
class AdminAgRodonavesCityController extends ModuleAdminController
{
    public function __construct()
    {
        $this->bootstrap    = true;
        $this->table        = 'agrodonaves_city';
        $this->identifier   = 'id_agrodonaves_city';
        $this->className    = 'AgRodonavesCity';
        $this->noLink       = true;
        $this->list_no_link = true;
        $this->_defaultOrderBy = 'date_add';
        $this->_defaultOrderWay = 'DESC';
        parent::__construct();
        
        $this->module->prepareNotifications();

        $this->setFieldsList();
    }

    private function setFieldsList()
    {
        $this->fields_list = [
            'id_agrodonaves_city' => [
                'type'  => 'int',
                'title' => 'ID',
                'class' => 'fixed-width-sm'
            ],
            'city_id' => [
                'type'  => 'text',
                'title' => 'ID',
                'class' => 'fixed-width-sm'
            ],
            'city_name' => [
                'type'  => 'text',
                'title' => 'Cidade'
            ],
            'date_add' => [
                'type'  => 'datetime',
                'title' => 'Data da Busca',
                'class' => 'fixed-width-lg',
            ],
        ];
    }
}