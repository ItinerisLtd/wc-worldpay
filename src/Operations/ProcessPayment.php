<?php
declare(strict_types=1);

namespace Itineris\WCWorldpay\Operations;

use Itineris\WCWorldpay\API\CreditCardFactory;
use Itineris\WCWorldpay\API\GatewayFactory;
use Itineris\WCWorldpay\WCWorldPayGateway;
use Omnipay\WorldPay\Message\PurchaseRequest;
use Omnipay\WorldPay\Message\PurchaseResponse;

class ProcessPayment
{
    /**
     * Process Payment.
     *
     * Process the payment. Override this in your gateway. When implemented, this should.
     * return the success and redirect in an array. e.g:
     *
     *        return array(
     *            'result'   => 'success',
     *            'redirect' => $this->get_return_url( $order )
     *        );
     *
     * @param WCWorldPayGateway $wcGateway The plugin gateway instance.
     * @param int               $orderId   Order ID.
     *
     * @return array
     */
    public function execute(WCWorldPayGateway $wcGateway, int $orderId): array
    {
        $order = wc_get_order($orderId);

        $transactionId = (string) apply_filters(
            'wc_worldpay_cart_id',
            wp_generate_uuid4(),
            $order
        );
        $order->set_transaction_id($transactionId);

        $notifyUrl = add_query_arg([
            'wc-api' => $wcGateway->getId(),
            'orderId' => $order->get_id(),
        ], home_url('/'));

        $gateway = GatewayFactory::build($wcGateway);

        /* @var PurchaseRequest $request The request instance. */
        $request = $gateway->purchase([
            'amount' => $order->get_total(),
            'card' => CreditCardFactory::build($order),
            'currency' => $order->get_currency(),
            'notifyUrl' => $notifyUrl,
            'transactionId' => $order->get_transaction_id(),
        ]);

        /* @var PurchaseResponse $response The response instance. */
        $response = $request->send();

        if (! $response->isRedirect()) {
            return [
                'result' => 'fail',
                'redirect' => '',
            ];
        }

        return [
            'result' => 'success',
            'redirect' => $response->getRedirectUrl(),
        ];
    }
}
