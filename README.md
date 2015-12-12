WP 4Geeks Referrals
====================
Donate link: http://alesanchezr.com/donate/
Tags: referrals, referrer, affiliate
License: MIT

The objective of this plugin is to have a referral/affiliate promotion in your site. It Generates and track any referal from any visitors of the website.

Description
-----------

When installed, you can create referrals links for specific visitors, it does not matter if the visitor is no registered.

Installation
------------

1. Upload the plugin files to the `/wp-content/plugins/plugin-name` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress
3. Use the Settings->4Geeks Referrals screen to see the referral codes generated
4. If you want to generate a referral token (or URL) for a user, you need to call the following function:

```php
$WP_Geeks_Referrals = WP_Geeks_Referrals::get_instance();
$referral = $WP_Geeks_Referrals->save_new_referral($user_identifier,$referred_by,$other_data);
```

* User identifier: can be an email, phone number o whetever information you have about the visitor of which you want to generate the token.
* Referred By: if the visitor came to our site through another referral
* Other data: If you whant to store any other information about the visitor.