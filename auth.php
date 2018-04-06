<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.
/**
 * Authenticate using valid basic auth http headers on internal accounts
 *
 * @package   auth_basic
 * @copyright Brendan Heywood <brendan@catalyst-au.net>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();
require_once($CFG->libdir.'/authlib.php');

/**
 * Plugin for basic authentication.
 *
 * @copyright  Brendan Heywood <brendan@catalyst-au.net>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class auth_plugin_basic extends auth_plugin_base {

    public $defaults = array(
        'debug'     => 0,
        'send401'   => 0,
        'onlybasic' => 1,
    );

    /**
     * Constructor.
     */
    public function __construct() {
        $this->authtype = 'basic';
        $this->config = (object) array_merge($this->defaults, (array) get_config('auth_basic') );
    }

    /**
     * A debug function, dumps to the php log as well as into the
     * response headers for easy curl based debugging
     *
     */
    private function log($msg) {
        if ($this->config->debug) {
            // @codingStandardsIgnoreStart
            error_log('auth_basic: ' . $msg);
            // @codingStandardsIgnoreEnd
            header ("X-auth_basic: $msg", false);
        }
    }

    /**
     * All the checking happens before the login page in this hook
     */
    public function pre_loginpage_hook() {

        $this->log(__FUNCTION__ . ' enter');
        $this->loginpage_hook();
        $this->log(__FUNCTION__ . ' exit');
    }

    /**
     * All the checking happens before the login page in this hook
     */
    public function loginpage_hook() {

        global $CFG, $DB, $USER, $SESSION;

        $this->log(__FUNCTION__);

        if ( isset($_SERVER['PHP_AUTH_USER']) &&
             isset($_SERVER['PHP_AUTH_PW']) ) {
            $this->log(__FUNCTION__ . ' has credentials');
            if ($user = $DB->get_record('user', array( 'username' => $_SERVER['PHP_AUTH_USER'] ) ) ) {

                $this->log(__FUNCTION__ . ' found user '.$user->username);
                $pass = $_SERVER['PHP_AUTH_PW'];

                if ( ($user->auth == 'basic' || $this->config->onlybasic == '0') &&
                     ( validate_internal_user_password($user, $pass) ) ) {

                    $this->log(__FUNCTION__ . ' password good');
                    complete_user_login($user);

                    if (isset($SESSION->wantsurl) && !empty($SESSION->wantsurl)) {
                        $urltogo = $SESSION->wantsurl;
                    } else if (isset($_GET['wantsurl'])) {
                        $urltogo = $_GET['wantsurl'];
                    } else {
                        $urltogo = $CFG->wwwroot;
                    }

                    $USER->loggedin = true;
                    $USER->site = $CFG->wwwroot;
                    set_moodle_cookie($USER->username);

                    // If we are not on the page we want, then redirect to it.
                    if ( qualified_me() !== $urltogo ) {
                        $this->log(__FUNCTION__ . " redirecting to $urltogo");
                        redirect($urltogo);
                    } else {
                        $this->log(__FUNCTION__ . " continuing onto " . qualified_me() );
                    }
                } else {
                    $this->log(__FUNCTION__ . ' password bad');
                }
            } else {
                $this->log(__FUNCTION__ . " invalid user: '{$_SERVER['PHP_AUTH_USER']}'");
            }
        }

        // No Basic auth credentials in headers.
        if ( $this->config->send401 == '1') {

            global $SITE;
            $realm = $SITE->shortname;
            $this->log(__FUNCTION__ . ' prompting for password');
            header('WWW-Authenticate: Basic realm="'.$realm.'"');
            header('HTTP/1.0 401 Unauthorized');
            print print_string('send401_cancel', 'auth_basic');
            exit;
        }
    }

    /**
     * Returns false regardless of the username and password as we never get
     * to the web form. If we do, some other auth plugin will handle it
     *
     * @param string $username The username
     * @param string $password The password
     * @return bool Authentication success or failure.
     *
     * @SuppressWarnings("unused")
     */
    public function user_login ($username, $password) {
        return false;
    }

}
