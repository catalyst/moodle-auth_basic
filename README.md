<a href="https://travis-ci.org/brendanheywood/moodle-auth_basic">
<img src="https://travis-ci.org/brendanheywood/moodle-auth_basic.svg?branch=master">
</a>

Moodle Basic auth plugin
========================

Enable users to authenticate using basic auth.

This is more for dev purposes and allows easier testing with tools such as webpage test, page speed, link checkers etc which often can use basic auth out of the box, and you don't want to need to customise to handle moodle specific authentication.

Even in production this has value for use cases such as performance regression testing using a real user and a real page which does a full bootstrap.

Unlike the core 'no authentication' plugin, this still requires real users and does proper password checks. It can be set to ignore the auth type against the account, eg manual, ldap, smtp so can be used side by side with other auth plugins, as long as those plugins store or cache the password, ie prevent_local_passwords() returns false for those plugins. So it can only be used with existing accounts and doesn't create accounts.

From a security perspective this auth plugin is exactly as secure as the manual auth plugin, so this should only be used in conjuntion with https.

Logging out
-----------

Note that most browsers store basic auth credentials that have worked forever, so you may try to logout, then click somewhere else and find yourself immediately logged back in without being prompted. As a general rule only services will use basic auth, not humans in browsers.

Curl example
------------

Example usage on the command line:

```curl -c /tmp/cookies -v -L --user user:password http://my.moodle.local/course/view.php?id=123```

 * -c file - keep and re-use cookies
 * -v show request and response headers
 * -L follow redirects
 * --user credentials

