<?php

use PrestaShop\PrestaShop\Core\Exception\DatabaseException;

class AgRodonavesCache extends AgObjectModel
{
    public static $definition = [
        'table' => 'agrodonaves_cache',
        'primary' => 'id_agrodonaves_cache',
        'fields' => [
            'id_agrodonaves_cache' => ['type' => self::TYPE_INT],
            'postcode_from'               => ['type' => self::TYPE_STRING, 'db_type' => 'varchar(10)'],
            'postcode_to'                 => ['type' => self::TYPE_STRING, 'db_type' => 'varchar(10)'],
            'total_weight'                => ['type' => self::TYPE_FLOAT,  'db_type' => 'float'],
            'invoice_value'               => ['type' => self::TYPE_FLOAT,  'db_type' => 'float'],
            'packs'                       => ['type' => self::TYPE_STRING, 'db_type' => 'text'],
            'shipping_cost'               => ['type' => self::TYPE_FLOAT,  'db_type' => 'float'],
            'delivery_time'               => ['type' => self::TYPE_INT,    'db_type' => 'int unsigned'],
            'date_add'                    => ['type' => self::TYPE_DATE,   'db_type' => 'datetime'],
        ],
        'indexes' => [
            [
                //esse índice não pode ser único porque não é possível ter uma coluna Text de tamanho
                //ilimitado como índice
                'fields' => ['postcode_from', 'postcode_to', 'total_weight', 'invoice_value', 'packs(512)'],
                'name' => 'uniqueness'
            ]
        ]
    ];

    public $id_agrodonaves_cache;
    public $postcode_from;
    public $postcode_to;
    public $total_weight;
    public $invoice_value;
    public $packs;
    public $shipping_cost;
    public $delivery_time;
    public $date_add;

    /**
     * @return AgRodonavesCache
     */
    public static function get($postcode_from, $postcode_to, $total_weight, $invoice_value, $packs)
    {
        $sql = new DbQuery;
        $sql->from('agrodonaves_cache')
            ->where('postcode_from="' . pSQL($postcode_from) . '"')
            ->where('postcode_to="' . pSQL($postcode_to) . '"')
            ->where('ABS(total_weight - ' . (float)$total_weight  .') < 1E-3')
            ->where('ABS(invoice_value - ' . (float)$invoice_value  .') < 1E-3')
            ->where('packs = "' . pSQL(json_encode($packs)) . '"');

        $db_data = Db::getInstance()->getRow($sql, false);
        $error = Db::getInstance()->getMsgError();
        if ($error) {
            throw new PrestaShopDatabaseException($error);
        }

        if (!is_array($db_data)) {
            $db_data = [];
        }

        $return = new AgRodonavesCache();
        $return->hydrate($db_data);
        return $return;
    }

    /**
     * Salva o cache no banco de dados.
     * 
     * @throws Exception Erro de validação do Object Model
     * @throws DatabaseException Erro gravando os dados no BD.
     */
    public static function saveCache($postcode_from, $postcode_to, $total_weight, $invoice_value, $packs, $shipping_cost, $delivery_time)
    {
        $obj = new AgRodonavesCache;

        $obj->postcode_from = $postcode_from;
        $obj->postcode_to = $postcode_to;
        $obj->total_weight = $total_weight;
        $obj->invoice_value = $invoice_value;
        $obj->packs = json_encode($packs);
        $obj->shipping_cost = $shipping_cost;
        $obj->delivery_time = $delivery_time;

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