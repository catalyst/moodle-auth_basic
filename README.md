
This is more for dev purposes and allows easier testing with tools
such as webpage test, etc which use basic auth

-i Show response headers
-v show in and out headers
-L follow redirects
-c file - keep and re-use cookies



curl -i -L --user user:password http://my.moodle.local/course/view.php?id=123

curl -c /tmp/curl-cookies -v -L --user user:password http://he.moodle.cqu.local/course/view.php?id=44

