# wc-worldpay

[![Packagist Version](https://img.shields.io/packagist/v/itinerisltd/wc-worldpay.svg)](https://packagist.org/packages/itinerisltd/wc-worldpay)
[![PHP from Packagist](https://img.shields.io/packagist/php-v/itinerisltd/wc-worldpay.svg)](https://packagist.org/packages/itinerisltd/wc-worldpay)
[![Packagist Downloads](https://img.shields.io/packagist/dt/itinerisltd/wc-worldpay.svg)](https://packagist.org/packages/itinerisltd/wc-worldpay)
[![GitHub License](https://img.shields.io/github/license/itinerisltd/wc-worldpay.svg)](https://github.com/ItinerisLtd/wc-worldpay/blob/master/LICENSE)
[![Hire Itineris](https://img.shields.io/badge/Hire-Itineris-ff69b4.svg)](https://www.itineris.co.uk/contact/)


WorldPay integration for WooCommerce.

<!-- START doctoc generated TOC please keep comment here to allow auto update -->
<!-- DON'T EDIT THIS SECTION, INSTEAD RE-RUN doctoc TO UPDATE -->


- [Minimum Requirements](#minimum-requirements)
- [Installation](#installation)
- [Setup](#setup)
- [Security Concerns about WorldPay HTML API](#security-concerns-about-worldpay-html-api)
- [Not Issue](#not-issue)
- [Features](#features)
- [Not Supported / Not Implemented](#not-supported--not-implemented)
- [Best Practices](#best-practices)
  - [HTTPS Everywhere](#https-everywhere)
  - [Payment Status](#payment-status)
- [Test Sandbox](#test-sandbox)
- [FAQ](#faq)
  - [Is `support.worldpay.com` secure?](#is-supportworldpaycom-secure)
  - [Will you add support for older PHP versions?](#will-you-add-support-for-older-php-versions)
  - [It looks awesome. Where can I find some more goodies like this?](#it-looks-awesome-where-can-i-find-some-more-goodies-like-this)
  - [This plugin isn't on wp.org. Where can I give a ⭐️⭐️⭐️⭐️⭐️ review?](#this-plugin-isnt-on-wporg-where-can-i-give-a-%EF%B8%8F%EF%B8%8F%EF%B8%8F%EF%B8%8F%EF%B8%8F-review)
- [Coding](#coding)
  - [Required Reading List](#required-reading-list)
  - [Testing](#testing)
- [Feedback](#feedback)
- [Security](#security)
- [Change log](#change-log)
- [Credits](#credits)
- [License](#license)

<!-- END doctoc generated TOC please keep comment here to allow auto update -->

## Minimum Requirements

- PHP v7.2
- php-curl
- WordPress v4.9.8
- WooCommerce v3.4.5

## Installation

```bash
$ composer require itinerisltd/wc-worldpay
```

## Setup

[Payment response(redirection)](http://support.worldpay.com/support/kb/bg/htmlredirect/htmlredirect.htm#rhtml/Telling_your_shopper_about.htm#_Payment_Response_messages) and [Enhancing security with MD5](http://support.worldpay.com/support/kb/bg/htmlredirect/htmlredirect.htm#rhtml/Enhancing_security_with_MD5.htm%3FTocPath%3D_____10) are mandatory.

On WorldPay's [integration setup page](http://support.worldpay.com/support/kb/bg/customisingadvanced/custa6011.html):

1. Enable **Enable the Shopper Response**
1. Enter `<wpdisplay item=MC_callback>` as **Payment Response URL**
1. Enable **Payment Response enabled?**
1. Enter a 25-char random passphrase as **Payment Response password**
1. Enter a 30-char random passphrase as **MD5 secret for transactions**
1. Enter `instId:amount:currency:cartId` as **SignatureFields**

Then, fill in the same information on WP admin dashboard - **WooCommerce > Settings > Payments > WordPay**.

Note that WorldPay truncate long **Payment Response password** without notices!

## Security Concerns about WorldPay HTML API

- Leaking **MD5 secret for transactions**
  * Allow evil hackers to set up fake checkout pages, pretending to be the merchant
  * WorldPay would accept these checkouts and charges the credit cards
- Leaking **Payment Response password**
  * Allow evil hackers to pretending to be WorldPay
  * WordPress would accept evil hackers' payment callbacks and changes order payment statuses

## Not Issue

If **Payment Response password**(also known as`callbackPW`) is incorrect, `InvalidResponseException` is throw to *stop the world*.
Credit card holders see white screen of death in such case.

## Features

- [Enhancing security with MD5](http://support.worldpay.com/support/kb/bg/htmlredirect/htmlredirect.htm#rhtml/Enhancing_security_with_MD5.htm%3FTocPath%3D_____10)

## Not Supported / Not Implemented

- Shipping address
- Reject according to fraud check results
- Token payment
- Recurring payment
- Refund
- Void

## Best Practices

### HTTPS Everywhere

Although WorldPay accepts insecure HTTP sites, you should **always use HTTPS** to protect all communication.

### Payment Status

Always double check payment status on `worldpay.com`.

## Test Sandbox

Use this [test credit card](http://support.worldpay.com/support/kb/bg/pdf/181450-test-transaction-f.pdf).

## FAQ

### Is `support.worldpay.com` secure?

No! `support.worldpay.com` does not support HTTPS.
This is unacceptable. Please [encourage them](https://www.worldpay.com/uk/about/contact-us) to use HTTPS everywhere.

### Will you add support for older PHP versions?

Never! This plugin will only works on [actively supported PHP versions](https://secure.php.net/supported-versions.php).

Don't use it on **end of life** or **security fixes only** PHP versions.

### It looks awesome. Where can I find some more goodies like this?

- Articles on [Itineris' blog](https://www.itineris.co.uk/blog/)
- More projects on [Itineris' GitHub profile](https://github.com/itinerisltd)
- Follow [@itineris_ltd](https://twitter.com/itineris_ltd) and [@TangRufus](https://twitter.com/tangrufus) on Twitter
- Hire [Itineris](https://www.itineris.co.uk/services/) to build your next awesome site

### This plugin isn't on wp.org. Where can I give a ⭐️⭐️⭐️⭐️⭐️ review?

Thanks! Glad you like it. It's important to make my boss know somebody is using this project. Instead of giving reviews on wp.org, consider:

- tweet something good with mentioning [@itineris_ltd](https://twitter.com/itineris_ltd)
- star this Github repo
- watch this Github repo
- write blog posts
- submit pull requests
- [hire Itineris](https://www.itineris.co.uk/services/)

## Coding

### Required Reading List

Read the followings before developing:

- [WorldPay HTML API](https://www.worldpay.com/uk/support/guides/business-gateway)
- [Omnipay: WorldPay](https://github.com/thephpleague/omnipay-worldpay)
- [thephpleague/omnipay#255 (comment)](https://github.com/thephpleague/omnipay/issues/255#issuecomment-90509446)
- [`Omnipay\WorldPay\Message\PurchaseRequest::getData()`](https://github.com/thephpleague/omnipay-worldpay/blob/cae548cb186c134510acdf488c14650782158bc6/src/Message/PurchaseRequest.php#L141-L190)

### Testing

```bash
$ composer test
$ composer check-style
```

Pull requests without tests will not be accepted!

## Feedback

**Please provide feedback!** We want to make this library useful in as many projects as possible.
Please submit an [issue](https://github.com/ItinerisLtd/wc-worldpay/issues/new) and point out what you do and don't like, or fork the project and make suggestions.
**No issue is too small.**

## Security

If you discover any security related issues, please email [hello@itineris.co.uk](mailto:hello@itineris.co.uk) instead of using the issue tracker.

## Change log

Please see [CHANGELOG](./CHANGELOG.md) for more information on what has changed recently.

## Credits

[wc-worldpay](https://github.com/ItinerisLtd/wc-worldpay) is a [Itineris Limited](https://www.itineris.co.uk/) project created by [Tang Rufus](https://typist.tech).

Full list of contributors can be found [here](https://github.com/ItinerisLtd/wc-worldpay/graphs/contributors).

## License

[wc-worldpay](https://github.com/ItinerisLtd/wc-worldpay) is licensed under the GPLv2 (or later) from the [Free Software Foundation](http://www.fsf.org/).
Please see [License File](./LICENSE) for more information.
