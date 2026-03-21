<?php

use PrestaShop\PrestaShop\Core\Exception\DatabaseException;

class AgRodonavesAccessToken extends AgObjectModel
{
    public static $definition = [
        'table' => 'agrodonaves_access_token',
        'primary' => 'id_agrodonaves_access_token',
        'fields' => [
            'id_agrodonaves_access_token' => ['type' => self::TYPE_INT],
            'token' => ['type' => self::TYPE_STRING, 'db_type' => 'varchar(512)'],
            'expiration_date' => ['type' => self::TYPE_DATE, 'db_type' => 'datetime'],
            'date_add' => ['type' => self::TYPE_DATE, 'db_type' => 'datetime']
        ],
        'indexes' => [
            [
                'columns' => ['expiration_date'],
                'name' => 'search_index'
            ]
        ]
    ];

    public $id_agrodonaves_access_token;
    public $token;
    public $expiration_date;
    public $date_add;

    /**
     * @return AccessToken
     */
    public static function get()
    {
        $sql = new DbQuery;
        $sql->from('agrodonaves_access_token')
            ->where('expiration_date > "' . date('Y-m-d H:i:s') . '"');

        $db_data = Db::getInstance()->getRow($sql);
        if (!is_array($db_data)) {
            $db_data = [];
        }

        $obj = new AgRodonavesAccessToken;
        $obj->hydrate($db_data);
        return $obj;
    }

    /**
     * Salva o token no banco de dados.
     * 
     * @throws Exception Erro de validação do Object Model
     * @throws DatabaseException Erro gravando os dados no BD.
     */
    public static function saveToken($access_token, DateTime $expiration_date)
    {
        $obj = new AgRodonavesAccessToken;

        $obj->token = $access_token;
        $obj->expiration_date = $expiration_date->format('Y-m-d H:i:s');

        $valid = $obj->validateFields(false, true);
        if ($valid !== true) {
            throw new Exception($valid);
        }
        
        $obj->add();

        $error = Db::getInstance()->getMsgError();
        if ($error) {
            throw new DatabaseException($error);
        }
    }
}