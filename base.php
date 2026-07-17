<?php

use AGTI\Cliente\Factory\DeliveryTimeFormatterFactory;
use AGTI\Cliente\Presenter\Tab;
use AGTI\Cliente\Presenter\Tabs;
use AGTI\Rodonaves\Entity\AccessToken as EntityAccessToken;
use AGTI\Rodonaves\Entity\ApiUser;
use AGTI\Rodonaves\Entity\Configuration as EntityConfiguration;
use AGTI\Rodonaves\Entity\ServiceArgs\Auth as ServiceArgsAuth;
use AGTI\Rodonaves\Form\Configuration;
use AGTI\Rodonaves\Interfaces\AccessToken;
use AGTI\Rodonaves\Interfaces\SimulateShipping;
use AGTI\Rodonaves\Service\Auth;
use Configuration as GlobalConfiguration;

require_once _PS_MODULE_DIR_ . 'agcliente/lib/AgCarrierModule.php';
require_once _PS_MODULE_DIR_ . 'agrodonaves/vendor/autoload.php';

class baseAgRodonaves extends AgCarrierModule
{
    public static $delay;
    protected $hooks = [
        'displayHeader'
    ];

    protected $main_tab ='AdminParentShipping';
    protected $tabs = [
        [
            'name' => 'Rodonaves',
            'className' => 'AdminAgRodonaves',
            'active' => 1,
            'childs' => [
                [
                    'name' => 'Requisições',
                    'className' => 'AdminAgRodonavesRequest',
                    'active' => 1,
                ],
                [
                    'name' => 'Descontos',
                    'className' => 'AdminAgRodonavesDiscount',
                    'active' => 1,
                ],
                [
                    'name' => 'Cache de Preços',
                    'className' => 'AdminAgRodonavesCache',
                    'active' => 1,
                ],
                [
                    'name' => 'Cache de Cidades',
                    'className' => 'AdminAgRodonavesCity',
                    'active' => 1,
                ],
                [
                    'name' => 'Tokens da API',
                    'className' => 'AdminAgRodonavesAccessToken',
                    'active' => 1,
                ],
            ]
        ]
    ];
    
    public function __construct()
    {
        $this->name                   = 'agrodonaves';
        $this->version                = '1.1.8';
        $this->bootstrap              = true;
        $this->author                 = 'AGTI';
        $this->need_instance          = 1;
        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => '8.99');

        parent::__construct();

        $this->displayName = 'Transportadora Rodonaves';
        $this->description = 'Ofereça a seus clientes a possiblidade de envio das encomendas através da Rodonaves.';
    }

    public function install()
    {
        $r = parent::install();

        if ($r) {
            $carrier = $this->getCarrier();
            if (!Validate::isLoadedObject($carrier) || $carrier->deleted) {
                $carrier = $this->installCarrier();
                GlobalConfiguration::updateValue('AGRODONAVES_ID_CARRIER', $carrier->id);
            }
        }
        
        return $r;
    }

    public function getCarrier()
    {
        $id_carrier = GlobalConfiguration::get('AGRODONAVES_ID_CARRIER');
        $obj = new Carrier($id_carrier);
        return $obj;
    }

    public function getContent()
    {
        $form = new Configuration($this);
        $form->postProcess();

        $tab = new Tab;
        $tab->setTitle("Configuração")
            ->setIcon("cogs")
            ->setid("config")
            ->setBody($form->renderHtml())
            ->setActive(true);

        $tabs = new Tabs;
        $tabs->addTab($tab);

        $tab = new Tab;
        $tab->setTitle("Simulação de Frete")
            ->setIcon("cogs")
            ->setId("simulation")
            ->setBody((new agcliente)->renderShippingForm($this));
        $tabs->addTab($tab);

        $tab = new Tab;
        $tab->setTitle("Suporte")
            ->setIcon("help")
            ->setId("support")
            ->setBody(agcliente::renderHelpTab($this));

        $tabs->addTab($tab);

        
        return $tabs->render();
    }

    
    public function simulateAllCarriersForProduct($postcode, $id_product, $id_product_attribute = 0, $quantity = 1, $postcode_origin = null)
    {
        $obj = new Product($id_product);
        $invoice_value = Product::getPriceStatic(
            $id_product,
            true,
            $id_product_attribute, 6,
            null,
            false,
            true,
            $quantity
        );

        $products = [[
            'weight' => max(0.01, $obj->weight),
            'height' => max(0.01, $obj->height),
            'length' => max(0.01, $obj->depth),
            'width' => max(0.01, $obj->width),
            'quantity' => $quantity
        ]];

        try {
            $r = $this->calc($postcode, $products, $invoice_value);
            return [[
                'carrier' => $this->getCarrier(),
                'price' => Tools::displayPrice($r),
                'delay' => self::getDelay()
            ]];
        } catch (Exception $e) {
            Logger::addLog("agrodonaves - Erro realizando cotação do frete - " . $e->getMessage() . $e->getTraceAsString(), 4, 0x5, 'Product', $id_product, true);
        }
    }

    public function simulateAllCarriersForCart($postcode, Cart $cart)
    {
        $products = $cart->getProducts();

        $products_to_rodonaves = [];
        $invoice_value = 0;
        foreach ($products as $product) {
            $products_to_rodonaves[] = [
                'weight' => max(0.01, $product['weight']),
                'height' => max(0.01, $product['height']),
                'length' => max(0.01, $product['depth']),
                'width' => max(0.01, $product['width']),
                'quantity' => $product['cart_quantity']
            ];

            $invoice_value += $product['cart_quantity'] * $product['price_with_reduction'];
        }

        try {
            $r = $this->calc($postcode, $products_to_rodonaves, $invoice_value);
            
            return [[
                'carrier' => $this->getCarrier(),
                'price' => Tools::displayPrice($r),
                'delay' => self::getDelay()
            ]];
        } catch (Exception $e) {
            return [];
            Logger::addLog("agrodonaves - Erro realizando cotação do frete - " . $e->getMessage() . $e->getTraceAsString(), 4, 0x6, 'Cart', $cart->id, true);
        }
    }

    public function getPackageShippingCost($cart, $shipping_cost, $products)
    {
        $address = new Address($cart->id_address_delivery);

        //lista de produtos
        $invoice_value = 0;
        $products_to_rodonaves = [];
        foreach ($products as $product) {
            $products_to_rodonaves[] = [
                'weight' => max(0.01, $product['weight']),
                'height' => max(0.01, $product['height']),
                'length' => max(0.01, $product['depth']),
                'width' => max(0.01, $product['width']),
                'quantity' => $product['cart_quantity']
            ];

            $invoice_value += $product['cart_quantity'] * $product['price_with_reduction'];
        }

        try {
            $r = $this->calc($address->postcode, $products_to_rodonaves, $invoice_value);
            return $r;
        } catch (Exception $e) {
            Logger::addLog("agrodonaves - Erro realizando cotação do frete - " . $e->getMessage() . $e->getTraceAsString(), 4, 0x4, 'Cart', $cart->id, true);
            return false;
        }
    }

    public static function getDelay()
    {
        return self::$delay;
    }

    private function calc($postcode_to, $products, $invoice_value)
    {
        $postcode_to = preg_replace("/[^0-9]+/", "", $postcode_to);
        if (!strlen($postcode_to)) {
            throw new Exception("Erro realizando simulação do frete - CEP {$postcode_to} é inválido.");
        }

        //CEP de Origem
        $config = new EntityConfiguration;
        $config->loadConfig();

        if (!$config->getPostcodeFrom()) {
            throw new Exception("CEP de origem não configurado.");
        }

        $token = $this->getAccessToken();

        $r = SimulateShipping::getShippingCost($token, $config->getPostcodeFrom(), $postcode_to, $products, $invoice_value, $config->getTaxRegistrationId());
        //aconteceu algum erro!
        if ($r->getValue() == 0) {
            return false;
        }

        $discount = AgRodonavesDiscount::getDiscountByPostcodeAndPrice($postcode_to, $invoice_value);

        $formatter = DeliveryTimeFormatterFactory::createFormatter(GlobalConfiguration::get('AGTI_SIMULATION_DELIVERY_DATE_MODE'));
        self::$delay =  $formatter->format($r->getDeliveryTime());
        
        return $discount->applyTo($r->getValue() + GlobalConfiguration::get('PS_SHIPPING_HANDLING'));
    }

    /**
     * @return EntityAccessToken
     */
    private function getAccessToken()
    {
        $config = new EntityConfiguration;
        $config->loadConfig();

        $user = new ApiUser;
        $user->setUsername($config->getUsername())
            ->setPassword($config->getPassword());

        $token = AccessToken::getAccessToken($user);
        return $token;
    }

    private function installCarrier()
    {
        $carrier                       = new Carrier();
        $carrier->name                 = 'Rodonaves';
        $carrier->id_tax_rules_group   = 0;
        $carrier->active               = 1;
        $carrier->deleted              = 0;
        $carrier->shipping_handling    = 0;
        $carrier->range_behavior       = 1;
        $carrier->is_module            = 1;
        $carrier->shipping_external    = 1;
        $carrier->external_module_name = $this->name;
        $carrier->need_range           = 1;
        $carrier->url = "https://cliente.rte.com.br/Tracking/";

        foreach (Language::getLanguages() as $lang) {
            $carrier->delay[$lang['id_lang']] = 'Consulte prazo de entrega.';
        }

        if ($carrier->add()) {
            $groups = Group::getGroups(true);
            foreach ($groups as $group) {
                Db::getInstance()->insert(
                    'carrier_group',
                    array(
                        'id_carrier' => (int) ($carrier->id),
                        'id_group'   => (int) ($group['id_group']
                        ),
                    )
                );
            }

            $rangePrice             = new RangePrice();
            $rangePrice->id_carrier = $carrier->id;
            $rangePrice->delimiter1 = '0';
            $rangePrice->delimiter2 = '1000000';
            $rangePrice->add();

            $zones = Zone::getZones(true);
            foreach ($zones as $zone) {
                Db::getInstance()->insert(
                    'carrier_zone',
                    array('id_carrier' => (int) ($carrier->id), 'id_zone' => (int) ($zone['id_zone'])
                    )
                );
            }

            $rangePrice             = new RangeWeight();
            $rangePrice->id_carrier = $carrier->id;
            $rangePrice->delimiter1 = '0';
            $rangePrice->delimiter2 = '1000000';
            $rangePrice->add();

            copy(_PS_MODULE_DIR_ . 'agrodonaves/views/img/logo.png', _PS_SHIP_IMG_DIR_ . (int) $carrier->id . '.jpg');

        }

        return $carrier;
    }
}