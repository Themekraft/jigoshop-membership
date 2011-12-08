=== Jigoshop Membership ===
Contributors: travel-junkie
Tags: jigoshop, e-commerce, membership
Requires at least: WP 3.2.1
Tested up to: WP 3.2.1
Stable tag: 1.0.1

Adds basic membership capabilities to a Jigoshop installation

== Description ==
Lets you take a product and transform it into a membership product. Can be used for things such as support. 

== Installation ==
1. Download the plugin
2. Upload to wp-content/plugins/
3. Activate in the backend
4. Done... No options available!

== Frequently Asked Questions ==

= How do I get it to run properly? =

Its fairly easy.
1. First you create a new product. We recommend to set the product type to 'Virtual'
2. Go to the Attributes tab and add a new one
3. Set the name to 'Length' (very important!)
4. Set the value to something like '1 year' or '3 months', you can use any valid strtotime() option
5. In your template files you can use different conditional tags to show/hide data:
	- jigomem_user_gets_support( $user_id );
	- jigomem_user_had_support( $user_id );
	- jigomem_get_support_date( $user_id, $format );
6. On your user overview table you can now see a new column called Membership

= Why no options? =

Because we don't need any. Jigoshop takes care of that. But maybe later...

== Languages ==
* English
* German