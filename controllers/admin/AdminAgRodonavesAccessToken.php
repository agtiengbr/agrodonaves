<?php

class AdminAgRodonavesAccessTokenController extends ModuleAdminController
{
	public function __construct()
	{
		$this->bootstrap    = true;
        $this->table        = 'agrodonaves_access_token';
        $this->identifier   = 'id_agrodonaves_access_token';
        $this->className    = 'AgRodonavesAccessToken';
        $this->noLink       = true;
        $this->list_no_link = true;

		parent::__construct();
        
		$this->module->prepareNotifications();

        $this->setFieldsList();
    }

    private function setFieldsList()
    {
        $this->fields_list = [
            'id_agrodonaves_access_token' => [
                'type'  => 'int',
                'title' => 'ID',
                'class' => 'fixed-width-sm'
            ],
            'token' => [
                'type'  => 'text',
                'title' => 'Token',
                'maxlength' => 200
            ],
            'expiration_date' => [
                'type'  => 'datetime',
                'title' => 'Data de Expiração',
                'class' => 'fixed-width-lg'
            ],
            'date_add' => [
                'type'  => 'datetime',
                'title' => 'Data de Criação',
                'class' => 'fixed-width-lg'
            ],
        ];
    }
}