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
 * Anobody can login using basic auth http headers
 *
 * @package auth_basic
 * @license http://www.gnu.org/copyleft/gpl.html GNU Public License
 */

defined('MOODLE_INTERNAL') || die();
require_once($CFG->libdir.'/authlib.php');

class auth_plugin_basic extends auth_plugin_base {

    function auth_plugin_basic() {
        $this->authtype = 'basic';
        $this->config = get_config('auth/basic');
    }

    function loginpage_hook() {

	    global $CFG, $DB, $USER, $SESSION;

        if ( isset($_SERVER['PHP_AUTH_USER']) &&
             isset($_SERVER['PHP_AUTH_PW']) ) {
            if ($user = $DB->get_record('user', array('username'=>$_SERVER['PHP_AUTH_USER'], 'mnethostid'=>$CFG->mnet_localhost_id))){
                $pass = $_SERVER['PHP_AUTH_PW'];
                if ( validate_internal_user_password($user, $pass) ){

                    $USER = complete_user_login($user);

                    if (isset($SESSION->wantsurl) && !empty($SESSION->wantsurl)) {
                        $urltogo = $SESSION->wantsurl;
                    } else if (isset($_GET['wantsurl'])) {
                        $urltogo = $_GET['wantsurl'];
                    } else {
                        $urltogo = '/';
                    }

                    $USER->loggedin = true;
                    $USER->site = $CFG->wwwroot;
                    set_moodle_cookie($USER->username);
                    redirect($urltogo);
                }
            }
        }
    }

    function user_login ($username, $password) {
        // Never gets this far
        return false;
    }

    function prevent_local_passwords() {
        return false;
    }

    function is_internal() {
        return true;
    }

    function can_be_manually_set() {
        return true;
    }

    function config_form($config, $err, $user_fields) {
        include "config.php";
    }

    function process_config($config) {
        return true;
    }

}


