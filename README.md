
Moodle Basic auth plugin
========================

This is more for dev purposes and allows easier testing with tools such as webpage test, page speed, link checkers etc which often can use basic auth out of the box, and you don't want to need to customise to handle moodle specific authentication.

Unlike the core 'no authentication' plugin, this still requires real users and does proper password checks. It does ignore the auth type against the account, eg manual,saml,ldap so can be used side by side with other auth plugins. If your site is only available over https then this isn't exposing passwords, although it still isn't recommnded for production use.

Curl example
------------

Example usage on the command line:

```curl -c /tmp/cookies -v -L --user user:password http://my.moodle.local/course/view.php?id=123```

 * -c file - keep and re-use cookies
 * -v show request and response headers
 * -L follow redirects
 * --user credentials
