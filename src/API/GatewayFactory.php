<?php
declare(strict_types=1);

namespace Itineris\WCWorldpay\API;

use Itineris\WCWorldpay\WCWorldPayGateway;
use Omnipay\Omnipay;
use Omnipay\WorldPay\Gateway;

class GatewayFactory
{
    /**
     * Create a Omnipay gateway instance.
     *
     * @param WCWorldPayGateway $wcGateway The plugin gateway instance.
     *
     * @return Gateway
     */
    public static function build(WCWorldPayGateway $wcGateway): Gateway
    {
        /* @var Gateway $gateway The gateway instance. */
        $gateway = Omnipay::create('WorldPay');

        $gateway->setInstallationId(
            $wcGateway->get_option('installationId')
        );
        $gateway->setAccountId(
            $wcGateway->get_option('merchantCode')
        );
        $gateway->setSecretWord(
            $wcGateway->get_option('md5Secret')
        );
        $gateway->setCallbackPassword(
            $wcGateway->get_option('paymentResponsePassword')
        );
        $gateway->setTestMode(
            'production' !== $wcGateway->get_option('environment')
        );

        $gateway->setFixContact(true);

        return $gateway;
    }
}
