Moodle Basic auth plugin
========================

Enable users to authenticate using basic auth.

This is more for dev purposes and allows easier testing with tools such as webpage test, page speed, link checkers etc which often can use basic auth out of the box, and you don't want to need to customise to handle moodle specific authentication.

Unlike the core 'no authentication' plugin, this still requires real users and does proper password checks. It does ignore the auth type against the account, eg manual, ldap, smtp so can be used side by side with other auth plugins, as long as those plugins store or cache the password, ie prevent_local_passwords() returns false. So it can only be used with existing accounts and can't create accounts.

From a security perspective this auth plugin is exactly as secure as the manual auth plugin, so this should only be used in conjuntion with https. This will warn on the settings page if your moodle is not running over https.

Logging out
-----------

Note many browsers cache credentials that have work for ever, so you may logout, then click login and find yourself immediately logged back in without being prompted.


Curl example
------------

Example usage on the command line:

```curl -c /tmp/cookies -v -L --user user:password http://my.moodle.local/course/view.php?id=123```

 * -c file - keep and re-use cookies
 * -v show request and response headers
 * -L follow redirects
 * --user credentials

