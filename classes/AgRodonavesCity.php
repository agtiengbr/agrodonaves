<?php

use PrestaShop\PrestaShop\Core\Exception\DatabaseException;

class AgRodonavesCity extends AgObjectModel
{
    public static $definition = [
        'table' => 'agrodonaves_city',
        'primary' => 'id_agrodonaves_city',
        'fields' => [
            'id_agrodonaves_city' => ['type' => self::TYPE_INT],
            'city_name' => ['type' => self::TYPE_STRING, 'db_type' => 'varchar(128)'],
            'city_id' => ['type' => self::TYPE_INT, 'db_type' => 'int'],
            'date_add' => ['type' => self::TYPE_DATE, 'db_type' => 'datetime']
        ],
        'indexes' => [
            [
                'fields' => ['city_name'],
                'name' => 'search_index'
            ],
            [
                'fields' => ['city_id'],
                'prefix' => 'unique',
                'name' => 'uniqueness'
            ]
        ]
    ];

    public $id_agrodonaves_city;
    public $city_name;
    public $city_id;
    public $date_add;

    /**
     * @return AgRodonavesCity
     */
    public static function get($name)
    {
        $sql = new DbQuery;
        $sql->from('agrodonaves_city')
            ->where("city_name='" . pSQL($name) . "'");
        $db_data = Db::getInstance()->getRow($sql);

        $error = Db::getInstance()->getMsgError();
        if ($error) {
            throw new PrestaShopDatabaseException($error);
        }
        
        if (!is_array($db_data)) {
            $db_data = [];
        }

        $return = new AgRodonavesCity();
        $return->hydrate($db_data);
        
        return $return;
    }

    /**
     * Salva o token no banco de dados.
     * 
     * @throws Exception Erro de validação do Object Model
     * @throws DatabaseException Erro gravando os dados no BD.
     */
    public static function saveCity($name, $id)
    {
        $obj = new AgRodonavesCity();

        $obj->city_name = $name;
        $obj->city_id = $id;

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