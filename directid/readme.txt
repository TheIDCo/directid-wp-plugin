=== DirectID Widget ===
Contributors: Jack Schofield - miiCard
Tags: verification, financial, fintech
License: MIT

== Description ==
This plugin initializes the DirectID Widget in posts and pages containing the [directid-widget] tag.

It is also capable of loading into template errors by using the 'directid_widget' action.

== Installation ==
1. Create a new directory on your site '/wp-content/plugins/directid'

2. Upload the contents of this folder to the '/wp-content/plugins/directid' directory

3. In your `wp_config.php` file, add the line "define('DID_KEY', 'YOUR_ENCRYPTION_KEY');", where `YOUR_ENCRYPTION_KEY` is a randomly generated 64-character hexadecimal string. This will be used to encrypt your DirectID Secret Key when it is saved. This protects your secret key should your WordPress installation become compromised.

4. In your WordPress dashboard, activate the plugin.

5. In your WordPress dashboard, go to Settings -> DirectID Widget Settings

6. Enter and save the details provided by your account manager (Client ID, Secret Key, Company Name, API Root, OAuth Resource, CDN Path). Note that the Secret Key field will become empty after clicking 'save'.

7. Add [directid-widget] to the post/page where you want the widget to appear. Alternatively add <?php do_action('directid_widget'); ?> to your template where you want the widget to appear.

== URL Parameters ==
* didToken
Used to provide a pre-generated user session token (eg if the user has clicked on a link in an email to arrive at this page). Without this parameter, the token will be generated automatically based on the Client ID and Secret Key you entered in the DirectID Widget Settings. 

* didCustomerReference
Used to provide your identifying Customer Reference for the user; if not provided the Customer Reference will be "defaultuser". This reference will be displayed on the DirectID Dashboard or via the API when the individual's data is returned.

* didEmail
Used to attach an email address for the user. Can be combined with didCustomerReference.