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

    /**
     * Constructor.
     */
    public function __construct() {
        $this->authtype = 'basic';
        $this->config = get_config('auth_basic');
    }

    /**
     * All the checking happens before the login page in this hook
     */
    public function pre_loginpage_hook() {

        if ($this->config->debug){
            error_log('Basic: pre auth login page hook');
        }

        $this->loginpage_hook();

    }

    /**
     * All the checking happens before the login page in this hook
     */
    public function loginpage_hook() {

        global $CFG, $DB, $USER, $SESSION;

        if ($this->config->debug){
            error_log('Basic: auth login page hook');
        }

        if ( isset($_SERVER['PHP_AUTH_USER']) &&
             isset($_SERVER['PHP_AUTH_PW']) ) {
            if ($this->config->debug){
                error_log('Basic: found credentials');
            }
            if ($user = $DB->get_record('user', array( 'username' => $_SERVER['PHP_AUTH_USER'] ) ) ) {
                if ($this->config->debug){
                    error_log('Basic: found valid user');
                }
                $pass = $_SERVER['PHP_AUTH_PW'];
                if ( ($user->auth == 'basic' || $this->config->onlybasic == '0') &&
                     ( validate_internal_user_password($user, $pass) ) ) {

                    $USER = complete_user_login($user);
                    if ($this->config->debug){
                        error_log('Basic: successful authentication');
                    }

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

                    // If we are not on the page we want, then redirect to it
                    if( qualified_me() !== $urltogo ) {
                        if ($this->config->debug){
                            error_log("Basic: redirecting to $urltogo");
                        }
                        redirect($urltogo);
                        exit;
                    } else {
                        if ($this->config->debug){
                            error_log("Basic: Continuing onto " . qualified_me() );
                        }
                    }
                } else {
                    if ($this->config->debug){
                        error_log('Basic: failed auth');
                    }
                }
            } else{
                if ($this->config->debug){
                    error_log("Basic: invalid user: '{$_SERVER['PHP_AUTH_USER']}'");
                }
            }
        }
        // No Basic auth credentials in headers.
        if ( $this->config->send401 == '1') {

            global $SITE;
            $realm = $SITE->shortname;
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
     */
    public function user_login ($username, $password) {
        return false;
    }

    /**
     * Prints a form for configuring this authentication plugin.
     *
     * This function is called from admin/auth.php, and outputs a full page with
     * a form for configuring this plugin.
     *
     * @param object $config
     * @param object $err
     * @param array $userfields
     */
    public function config_form($config, $err, $userfields) {
        include("config.php");
    }

    /**
     * Processes and stores configuration data for this authentication plugin.
     *
     * @param object $config
     */
    public function process_config($config) {
        if (!isset($config->send401)) {
             $config->send401 = false;
        }
        set_config('send401', $config->send401, 'auth_basic');

        if (!isset($config->onlybasic)) {
             $config->onlybasic = true;
        }
        set_config('onlybasic', $config->onlybasic, 'auth_basic');

        if (!isset($config->debug)) {
             $config->debug = false;
        }
        set_config('debug', $config->debug, 'auth_basic');
        return true;
    }

}

