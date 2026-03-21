<?php

class AgRodonavesDiscount extends AgObjectModel
{
	public static $definition = [
		'table'     => 'agrodonaves_discount',
        'primary'   => 'id_agrodonaves_discount',
        'multilang' => false,
        'fields'    => [
            'id_agrodonaves_discount'=> ['type' => self::TYPE_INT,    'db_type' => 'int',         'validate' => 'isInt'],
            'alias'                    => ['type' => self::TYPE_STRING, 'db_type' => 'varchar(50)', 'validate' => 'isGenericName', 'required' => true],
            'type_discount' 		   => ['type' => self::TYPE_INT,    'db_type' => 'int',   'validate' => 'isInt',               'required' => true],
            'discount' 				   => ['type' => self::TYPE_FLOAT,  'db_type' => 'float', 'validate' => 'isFloat',             'required' => true],
            'postcode_begin'           => ['type' => self::TYPE_INT,    'db_type' => 'int'],
            'postcode_end'             => ['type' => self::TYPE_INT,    'db_type' => 'int'],
            'cart_value_begin'         => ['type' => self::TYPE_FLOAT,   'db_type' => 'float'],
            'cart_value_end'           => ['type' => self::TYPE_FLOAT,   'db_type' => 'float'],
            'id_zone'                  => ['type' => self::TYPE_INT,    'db_type' => 'text'],
            'active'                   => ['type' => self::TYPE_BOOL,   'db_type' => 'boolean', 'default' => 0]
        ]
	];

	public $id_agrodonaves_discount;
    public $alias;
	public $type_discount;
	public $discount;
	// public $type_interval;
    public $cart_value_begin;
    public $cart_value_end;
    public $postcode_begin;
    public $postcode_end;
    public $id_zone;
    public $active;



    public static function hasIntersectionWithOtherInterval($zipcode_begin, $zipcode_end, $cart_value_begin, $cart_value_end, $id_interval)
    {
        $sql = 'SELECT * FROM '.  _DB_PREFIX_ . 'agrodonaves_discount ';
        $sql .= ' WHERE CAST(postcode_end AS SIGNED INTEGER) >= '  .(int) $zipcode_begin;
        $sql .= ' AND CAST(postcode_begin AS SIGNED INTEGER) <= '  .(int) $zipcode_end;
        $sql .= ' AND (cart_value_end  >= '  .(float) $cart_value_begin . ' OR cart_value_end = 0 OR cart_value_end IS NULL)';
        $sql .= ' AND cart_value_begin  <= '  .(float) $cart_value_end;

        if ($id_interval) {
            $sql .= ' AND id_agrodonaves_discount != ' . (int) $id_interval;
        }

        $db_data = Db::getInstance()->getRow($sql);
        
        if (!is_array($db_data)) {
            $db_data = array();
        }

        $return = new AgRodonavesDiscount();
        $return->hydrate($db_data);

        return $return;
    }

    public static function getByZone($id_zone, $id_interval)
    {
        $sql = 'SELECT * FROM '.  _DB_PREFIX_ . 'agrodonaves_discount ';
        $sql .= ' AND id_zone=' . (int)$id_zone;
        $sql .= ' AND active=1';


        if ($id_interval) {
            $sql .= ' AND id_agrodonaves_discount != ' . (int) $id_interval;
        }

        $db_data = Db::getInstance()->getRow($sql);
        
        if (!is_array($db_data)) {
            $db_data = array();
        }


        $return = new AgRodonavesDiscount();
        $return->hydrate($db_data);

        return $return;
    }

    //adiciona a validação para verificar CEPS em mais de um intervalo ou região
    public function validateFields($die = true, $error_return = false)
    {        
        if (!parent::validateFields($die, $error_return)) {
            return false;
        }

        // if ($this->type_interval == 0) {
            $intersection = self::hasIntersectionWithOtherInterval(
                $this->postcode_begin,
                $this->postcode_end,
                $this->cart_value_begin,
                $this->cart_value_end,
                $this->id
            );
            
            if (Validate::isLoadedObject($intersection)) {
                if ($die) {
                    throw new PrestaShopException(sprintf(
                        'O intervalo escolhido conflita com o desconto #%d.',
                        $this->postcode_begin,
                        $this->postcode_end,
                        $intersection->id
                    ));
                }

                return false;
            }
        // } else {
        //     $discount = self::getByZoneAndService($this->id_zone, $this->id_agrodonaves_service, $this->id);

        //     if (Validate::isLoadedObject($discount)) {
        //         if ($die) {
        //             $zone = new Zone($this->id_zone);

        //             throw new PrestaShopException(sprintf(
        //                 'Desconto para a região %s já está em uso no desconto #%d.',
        //                 $zone->name,
        //                 $discount->id
        //             ));
        //         }

        //         return false;
        //     }
        // }

        return true;
    }

    public static function getDiscountByPostcodeAndPrice($postcode, $price)
    {
        $postcode = str_replace('.', '', $postcode);
        $postcode = str_replace('-', '', $postcode);

        $sql = new DbQuery();
        $sql->from('agrodonaves_discount')
            ->where('CAST(postcode_begin AS SIGNED INTEGER) <= ' . (int) $postcode)
            ->where('CAST(postcode_end AS SIGNED INTEGER) >= ' . (int) $postcode)
            ->where('cart_value_begin <= ' . (float) $price)
            ->where('cart_value_end >= ' . (float) $price . ' OR cart_value_end = 0 OR cart_value_end IS NULL')
            ->where('active=1');
            
        $discount = Db::getInstance()->getRow($sql);
        if (!is_array($discount)) {
            $discount = [];
        }

        $return = new AgRodonavesDiscount;
        $return->hydrate($discount);

        return $return;
    }

    public function applyTo($price)
    {
        if ($this->type_discount == 1) {
            $return = max(0, $price - $this->discount);
        } else {
            $return = max(0, $price * (1 - $this->discount / 100));
        }

        return $return;
    }
}
