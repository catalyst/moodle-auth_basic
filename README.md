<a href="https://travis-ci.org/catalyst/moodle-auth_basic">
<img src="https://travis-ci.org/catalyst/moodle-auth_basic.svg?branch=master">
</a>

* [What is this?](#what-is-this)
* [Installation](#installation)
* [Logging out](#logging-out)
* [Feedback and issues](#feedback-and-issues)
* [Master password feature](#master-password-feature)

What is this?
========================

This is a moodle plugin which enables you to authenticate via HTTP basic auth.

This is more for development and backend purposes and allows easier testing with tools such as webpage test, page speed, link checkers etc which often can use basic auth out of the box, but you don't want to attempt to customize them in order to handle moodle specific authentication, or try to, where Moodle API access is inappropriate.

You would almost never want to use this for real human users as basic auth is a fairly terrible user expereince. It is designed to work side by side with your real moodle authentication but doesn't impact on normal authentication.

Even in production this has value for use cases such as performance regression testing using a real user and a real page which does a full bootstrap.

Unlike the core 'no authentication' plugin, this still requires real users and does proper password checks. It can be set to ignore the auth type against the account, eg manual, ldap, smtp so can be used side by side with other auth plugins, as long as those plugins store or cache the password, ie prevent_local_passwords() returns false for those plugins. So it can only be used with existing accounts and doesn't create accounts.

From a security perspective this auth plugin is exactly as secure as the manual auth plugin, so this should only be used in conjuntion with https.

Installation
------------

1. Install the plugin the same as any standard moodle plugin either via the
Moodle plugin directory, or you can use git to clone it into your source:

     git clone git@github.com:catalyst/moodle-auth_basic.git auth/basic

    Or install via the Moodle plugin directory:
    
     https://moodle.org/plugins/auth_basic

2. Then run the Moodle upgrade

If you have issues please log them in github here:

https://github.com/catalyst/moodle-auth_basic/issues

Or if you want paid support please contact Catalyst IT Australia:

https://www.catalyst-au.net/contact-us


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
 
 
 Master password feature
 ------------
 
 Set up master password feature for load test (Non Production Environments).
 
 Add this setting to config.php:
 
 ```php
 $CFG->forced_plugin_settings = array(
     'auth_basic' => array(
         'master' => 'masterpassword',
     ),
 );
 ```
 with 'masterpassword' is your Master Password.
 
 
 Template to use with curl:
 
 * random-user: Select a random non-suspended user
 
 ```curl --user random-user:masterpassword http://my.moodle.local/course/view.php?id=123```
 
 * random-role-{roleid}: Select a random non-suspended user with roleid at site level
 
 ```curl --user random-role-1:masterpassword http://my.moodle.local/course/view.php?id=123```
 
 * random-course-{courseid}: Select a random non-suspended user who is enrolled in the course
 
 ```curl --user random-course-10:masterpassword http://my.moodle.local/course/view.php?id=123```
 
 * random-course-{courseid}-role-{roleid}: Select a random non-suspended user who is enrolled in the course with roleid
 
 ```curl --user random-course-10-role-1:masterpassword http://my.moodle.local/course/view.php?id=123```
 

Feedback and issues
-------------------

Please raise any issues in github:

https://github.com/catalyst/moodle-auth_basic/issues

If you need anything urgently and would like to sponsor it's implemenation please email me: Brendan Heywood brendan@catalyst-au.net
