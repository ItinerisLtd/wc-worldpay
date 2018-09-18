<?php
declare(strict_types=1);

namespace Itineris\WCWorldpay;

class Registrar
{
    /**
     * Register WCWorldPayGateway to WooCommerce.
     *
     * @param string[] $gateways Array of all registered WooCommerce payment gateway classes.
     *
     * @return string[]
     */
    public function registerGatewayClass(array $gateways): array
    {
        $gateways[] = WCWorldPayGateway::class;
        return $gateways;
    }
}
