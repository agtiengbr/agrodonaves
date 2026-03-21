<?php

class AdminAgRodonavesRequestController extends ModuleAdminController
{
    public function __construct()
    {
        $this->bootstrap        = true;
        $this->table            = 'agrodonaves_request';
        $this->className        = 'AgRodonavesRequest';
        $this->identifier       = 'id_agrodonaves_request';
        $this->list_no_link     = true;
        $this->_defaultOrderBy  = 'id_agrodonaves_request';
        $this->_defaultOrderWay = 'DESC';


        parent::__construct();

		// if (!$this->module->auth()) {
        //     $this->module->warnings[] = "Erro de autenticação. Por favor verifique a sua licença abaixo e tente novamente.";
        //     $this->module->saveNotifications();
        //     AgModule::redirectToConfigPage('agcliente');
        // }

		$this->module->prepareNotifications();

        $this->fields_list = [
            'id_agrodonaves_request' => [
                'title' => 'ID',
                'align' => 'center',
                'type' => 'int',
                'class' => 'fixed-width-xs',
            ],
            'time_spent' => [
                'title' => 'Tempo Gasto',
                'type' => 'text',
                'suffix' => 's'
            ],
            'http_code' => [
                'title' => 'Código HTTP',
                'type' => 'int',
                'class' => 'fixed-width-md'
            ],
            'method' => [
                'title' => 'Método',
                'type' => 'text',
                'class' => 'fixed-width-md'
            ],
            'endpoint' => [
                'title' => 'URL',
                'type' => 'text'
            ],
            'endpoint' => [
                'title' => 'URL',
                'type' => 'text'
            ],
            'date_add' => [
                'title' => 'Data',
                'type' => 'datetime'
            ]
        ];

        $this->actions = ['view'];
    }

    public function initContent()
    {
        parent::initContent();

        if (Tools::getIsSet('view' . $this->table)) {
            $request = $this->loadObject();
            $request->response = json_decode($request->response);
            
            $html  = $this->content;

            //contéudo geral da ação VER
            $tpl = $this->context->smarty->createTemplate(_PS_MODULE_DIR_ . $this->module->name.'/views/templates/admin/ag_rodonaves_request/view.tpl');
            $tpl->assign(['obj' => $request]);
            $html .= $tpl->fetch();

            $this->content = $html;
            $this->context->smarty->assign(['content' => $html]);

            return;
        }
    }
}