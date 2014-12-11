
Moodle Basic auth plugin
========================

This is more for dev purposes and allows easier testing with tools such as webpage test, page speed, link checkers etc which often can use basic auth out of the box, and you don't want to need to customise to handle moodle specific authentication.

Example usage on the command line:

```curl -c /tmp/cookies -v -L --user user:password http://my.moodle.local/course/view.php?id=123```

 * -c file - keep and re-use cookies
 * -v show in and out headers
 * -L follow redirects
 * --user credentials

