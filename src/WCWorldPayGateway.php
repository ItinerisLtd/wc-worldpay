<?php
declare(strict_types=1);

namespace Itineris\WCWorldpay;

use Itineris\WCWorldpay\Operations\HandleCallback;
use Itineris\WCWorldpay\Operations\ProcessPayment;
use WC_Payment_Gateway;

/**
 * The main class.
 *
 * @see https://docs.woocommerce.com/document/payment-gateway-api/
 */
class WCWorldPayGateway extends WC_Payment_Gateway
{
    /**
     * WCWorldPayGateway constructor.
     */
    public function __construct()
    {
        $this->id = 'wc-worldpay';
        $this->has_fields = false;
        $this->method_title = __('WorldPay', 'wc-worldpay');
        $this->method_description = __(
            'Redirects customers to WorldPay to enter their payment information.',
            'wc-worldpay'
        );

        $this->init_form_fields();
        $this->init_settings();

        $this->title = $this->get_option('title');
        $this->description = $this->get_option('description');

        add_action('woocommerce_update_options_payment_gateways_' . $this->id, [$this, 'process_admin_options']);
        add_action('woocommerce_api_' . $this->id, [$this, 'handleCallback']);
    }

    /**
     * ID getter.
     *
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    public function handleCallback(): void
    {
        $handleCallback = new HandleCallback($this);
        $handleCallback->execute();
    }

    /**
     * Initialise settings form fields.
     *
     * Add an array of fields to be displayed on the gateway's settings screen.
     *
     * @return void
     */
    public function init_form_fields(): void
    {
        $this->form_fields = [
            'enabled' => [
                'title' => __('Enable/Disable', 'woocommerce'),
                'type' => 'checkbox',
                'label' => __('Enable WorldPay', 'woocommerce'),
                'default' => 'yes',
            ],
            'title' => [
                'title' => __('Title', 'woocommerce'),
                'type' => 'text',
                'description' => __('This controls the title which the user sees during checkout.', 'woocommerce'),
                'default' => __('WorldPay', 'wc-worldpay'),
                'desc_tip' => true,
            ],
            'description' => [
                'title' => __('Description', 'woocommerce'),
                'type' => 'textarea',
                'desc_tip' => true,
                'description' => __(
                    'This controls the description which the user sees during checkout.',
                    'woocommerce'
                ),
                'default' => __('Pay via WorldPay', 'wc-worldpay'),
            ],
            'environment' => [
                'title' => 'Environment',
                'type' => 'select',
                'default' => 'test',
                'options' => [
                    'test' => __('Test', 'wc-worldpay'),
                    'production' => __('Production', 'wc-worldpay'),
                ],
            ],
            'installationId' => [
                'title' => __('Installation ID', 'wc-worldpay'),
                'type' => 'text',
                'desc_tip' => true,
                'description' => __('The ID for this installation. For example: 24601', 'wc-worldpay'),
            ],
            'merchantCode' => [
                'title' => __('Merchant Code (Optional)', 'wc-worldpay'),
                'type' => 'text',
                'desc_tip' => true,
                'description' => __(
                    '(Optional) This specifies which merchant code should receive funds for this payment.',
                    'wc-worldpay'
                ),
            ],
            'md5Secret' => [
                'title' => __('MD5 Secret for Transactions', 'wc-worldpay'),
                'type' => 'password',
                'desc_tip' => true,
                'description' => __(
                    '30-char MD5 secret for transactions field in the Integration Setup for your installation using the Merchant Interface > Installations option', // phpcs:ignore
                    'wc-worldpay'
                ),
            ],
            'paymentResponsePassword' => [
                'title' => __('Payment Response Password', 'wc-worldpay'),
                'type' => 'password',
                'desc_tip' => true,
                'description' => __(
                    'This 25-char password is used to validate a Payment Notifications message.',
                    'wc-worldpay'
                ),
            ],
        ];
    }

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
     * @param int $orderId Order ID.
     *
     * @return array
     */
    public function process_payment($orderId): array
    {
        $operation = new ProcessPayment();
        return $operation->execute($this, $orderId);
    }
}
