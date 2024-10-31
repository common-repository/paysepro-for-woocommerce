=== Paysepro for WooCommerce ===

Contributors: nextlevel
Donate link: https://www.paysepro.com/
Tags: Paysepro, woocommerce, payments, payment gateway, webpay, credit cards, paysafecard, oxxo, boleto, bitcoin, sms, worldwide payments, Neosurf, Cashu, Klarna, web payments
Requires at least: 3.7.0
Tested up to: 5.9
Stable tag: 1.1
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Requires PHP: 5.6

Paysepro is a platform that allows you to integrate a collecting system in any website or online business, to sell products and services worldwide.

== Description ==

We offer the best payments solutions in the market, so there will always be a sales channel available that suits your customer's needs.
We are a Company with years of experience in the market. We are focused in providing global collecting solutions through multiple and well known payment providers. That, allows our customers to focus on selling goods and services, saving time and money by integrating a collecting system on their projects.

Website:         https://www.paysepro.com

Payment methods: https://www.paysepro.com/pricing

== Installation ==

- You'll need a working WordPress installation using the WooCommerce plugin.

- You'll also need a standard Paysepro services:
  You can create a Paysepro account at https://www.paysepro.com/ then a service at http://members.paysepro.com/checkout.html

- Go to "Plugins -> Add new", search for the Paysepro plugin in the WordPress Plugin Directory then click "Install now".
  You can also click "Upload plugin" to manually upload the plugins' zip file, after which it will be installed automatically.
  If for any reason this fails, you can also manually extract the plugin's folder into wp-content/plugins/.

- In your WordPress plugins panel, activate the Paysepro plugin.

- Go to the checkout configuration and proceed to configure the plugin:
  * The text fields contain the text that will be used during the checkout process.
  * The Service ID can be found near your service's name at "Payment Box", at your Paysepro panel.
  * Paste the provided IPN URL into the "Background URL (IPN)" setting at your service's configuration
    at your Paysepro panel (click the pencil icon at "My Services" to edit your service). ????

== Changelog ==

= 1.0 =
* Initial release tested with WordPress 4.7, and WooCommerce 2.6.11

== Recommendations and important notes ==


- Some payment methods provided by Paysepro (such as credit card payments) will confirm the payment immediately, so the payer will
  see the payment status as "Completed". However, other payment methods (such as local cash payment services) may take longer
  to confirm the payment. In these cases the payer will see the status "Processing". After the payment is confirmed
  by the local payments provider, the status will internally be updated to "Completed". Depending on your specific
  needs, you may want to use the "Hold Stock" WooCommerce setting if you need to make sure that stock is available for payments
  that are not notified immediately.


== Frequently Asked Questions ==
1. How to install?
Check the installation instructions above.

== Upgrade Notice ==
No upgrades.

== Screenshots ==
1. No screenshots by now.
