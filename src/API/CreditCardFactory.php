<?php
declare(strict_types=1);

namespace Itineris\WCWorldpay\API;

use Omnipay\Common\CreditCard;
use WC_Order;

class CreditCardFactory
{
    /**
     * Create a Omnipay credit card instance.
     *
     * @param WC_Order $order The order instance.
     *
     * @return CreditCard
     */
    public static function build(WC_Order $order): CreditCard
    {
        return new CreditCard(
            array_filter([
                'firstName' => $order->get_billing_first_name(),
                'lastName' => $order->get_billing_last_name(),
                'email' => $order->get_billing_email(),
                'billingPhone' => $order->get_billing_phone(),
                'billingAddress1' => $order->get_billing_address_1(),
                'billingAddress2' => $order->get_billing_address_2(),
                'billingCity' => $order->get_billing_city(),
                'billingPostcode' => $order->get_billing_postcode(),
                'billingState' => $order->get_billing_state(),
                'billingCountry' => $order->get_billing_country(),
            ])
        );
    }
}
