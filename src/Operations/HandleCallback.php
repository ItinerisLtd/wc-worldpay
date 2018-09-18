<?php
declare(strict_types=1);

namespace Itineris\WCWorldpay\Operations;

use Itineris\WCWorldpay\API\GatewayFactory;
use Itineris\WCWorldpay\WCWorldPayGateway;
use Omnipay\WorldPay\Message\CompletePurchaseResponse;
use WC_Order;

class HandleCallback
{
    /**
     * The plugin gateway instance.
     *
     * @var WCWorldPayGateway
     */
    protected $wcGateway;

    /**
     * HandleCallback constructor.
     *
     * @param WCWorldPayGateway $wcGateway The plugin gateway instance.
     */
    public function __construct(WCWorldPayGateway $wcGateway)
    {
        $this->wcGateway = $wcGateway;
    }

    public function execute(): void
    {
        $order = $this->getOrder();
        if (! $order instanceof WC_Order) {
            return;
        }

        $gateway = GatewayFactory::build($this->wcGateway);

        /* @var CompletePurchaseResponse $response The response instance. */
        $response = $gateway->completePurchase()
                            ->send();

        $order->add_order_note(
            $response->getMessage()
        );

        if ($response->isSuccessful()) {
            $order->payment_complete(
                $response->getTransactionReference()
            );
        } else {
            $order->update_status('failed', __('Payment was declined by WorldPay.', 'wc-worldpay'));
        }

        wp_safe_redirect(
            $order->get_view_order_url()
        );
        exit;
    }

    /**
     * Get order from superglobals.
     *
     * @return mixed
     */
    protected function getOrder()
    {
        $order = null;
        if (isset($_GET['orderId'])) { // WPCS: CSRF, input var ok.
            $orderId = absint($_GET['orderId']); // WPCS: input var okay.
            $order = wc_get_order($orderId);
        }

        return $order;
    }
}
