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
 * Master Password Form
 *
 * @package    auth_basic
 * @copyright  2018 Nathan Nguyen <nathannguyen@catalyst-au.nete>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();
require_once("$CFG->libdir/formslib.php");

class savepassword_form extends moodleform {

    protected function definition() {
        global $CFG;
        $mform = $this->_form;

        $mform->addElement('text', 'password', get_string('password', 'auth_basic'), array('disabled' => true));
        $mform->setDefault('password', $this->_customdata['password']);

        $mform->addElement('hidden', 'userid', $this->_customdata['userid']);

        $whitelist = $CFG->auth_basic_whitelist_ips;
        if (isset($whitelist)) {
            $mform->addElement('checkbox', 'whitelistonly', get_string('whitelistonly', 'auth_basic', $whitelist));
            $mform->setDefault('whitelistonly', true);
            $mform->addHelpButton('whitelistonly', 'whitelistonly', 'auth_basic');
            $mform->addElement('hidden', 'whitelist', $whitelist);
        }

        $buttonarray = array();
        $buttonarray[] =& $mform->createElement('submit', 'submitbutton', get_string('savepassword', 'auth_basic'));
        $buttonarray[] =& $mform->createElement('cancel', 'cancel', get_string('regeneratepassword', 'auth_basic'));
        $mform->addGroup($buttonarray, 'buttonar', '', array(' '), false);

    }
}