<?php
declare(strict_types=1);

namespace Itineris\WCWorldpay;

use Itineris\WCWorldpay\AdminNotices\MissingWooCommerce;
use Itineris\WCWorldpay\Operations\HandleCallback;

class Plugin
{
    /**
     * Begins execution of the plugin.
     *
     * @return void
     */
    public function run(): void
    {
        if (! class_exists('WC_Payment_Gateway')) {
            add_action('admin_notices', [MissingWooCommerce::class, 'printAdminNotice']);
            return;
        }

        $registrar = new Registrar();
        add_filter('woocommerce_payment_gateways', [$registrar, 'registerGatewayClass']);
    }
}
