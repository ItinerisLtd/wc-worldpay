<?php
/**
 * Plugin Name:     WC WorldPay
 * Plugin URI:      https://www.itineris.co.uk/
 * Description:     WorldPay integration for WooCommerce.
 * Version:         0.1.2
 * Author:          Itineris Limited
 * Author URI:      https://www.itineris.co.uk/
 * License:         GPL-2.0-or-later
 * License URI:     http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:     wc-worldpay
 */

declare(strict_types=1);

namespace Itineris\WCWorldpay;

// If this file is called directly, abort.
if (! defined('WPINC')) {
    die;
}

if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    require_once __DIR__ . '/vendor/autoload.php';
}

add_action('plugins_loaded', function(): void {
    $plugin = new Plugin();
    $plugin->run();
});
