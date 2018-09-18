<?php
declare(strict_types=1);

namespace Itineris\WCWorldpay\AdminNotices;

use WC_Payment_Gateway;

class MissingWooCommerce
{
    /**
     * Print admin notice if WooCommerce is missing.
     *
     * @return void
     */
    public function printAdminNotice(): void
    {
        if (class_exists(WC_Payment_Gateway::class)) {
            return;
        }

        // Translators: %1$s - WC WorldPay URL %2$s - WooCommerce URL.
        $messagePattern = __(
            '<a href="%1$s">WC WorldPay</a> depends on the last version of <a href="%2$s">WooCommerce</a> to work!',
            'wc-worldpay'
        );
        $message = sprintf(
            $messagePattern,
            'https://github.com/ItinerisLtd/wc-worldpay',
            'https://woocommerce.com'
        );

        echo wp_kses_post('<div class="error"><p>' . $message . '</p></div>');
    }
}
