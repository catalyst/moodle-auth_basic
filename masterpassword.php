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
 * Master Password Settings
 *
 * @package    auth_basic
 * @copyright  2018 Nathan Nguyen <nathannguyen@catalyst-au.nete>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(__DIR__.'/../../config.php');
require_once($CFG->libdir.'/adminlib.php');

$action = optional_param('action', 0, PARAM_TEXT);
$password = optional_param('password', null, PARAM_TEXT);
$whitelistips = optional_param('whitelistips', null, PARAM_TEXT);

require_login();
require_capability('moodle/site:config', context_system::instance());

admin_externalpage_setup('auth_basic_masterpassword');
$thispage = '/auth/basic/masterpassword.php';
$PAGE->set_url(new moodle_url($thispage));

if (!isset($CFG->auth_basic_enabled_master_password)) {
    echo $OUTPUT->header();
    echo $OUTPUT->heading(get_string('auth_basic_masterpassword', 'auth_basic'));
    echo html_writer::tag('div', get_string('auth_basic_notenabled_masterpassword', 'auth_basic'));
    echo $OUTPUT->footer();
} else {
    if (($action === 'regenerate' && confirm_sesskey()) || empty($password) ) {
        $password = time().uniqid();
    } else if ($action === 'save' && confirm_sesskey() && !empty($password) ) {
        global $DB;
        $record = new stdClass();
        $record->password = $password;
        $record->userid = $USER->id;
        $record->enabled = 1;
        $record->ips = $whitelistips;
        $record->usage = 0;
        $record->timecreated = time();
        $record->timeexpired = time() + DAYSECS;
        $DB->insert_record('auth_basic_master_password', $record);
        redirect($CFG->wwwroot.'/auth/basic/masterpassword.php/');
    }

    echo $OUTPUT->header();
    echo $OUTPUT->heading(get_string('auth_basic_masterpassword', 'auth_basic'));

    $regenerateurl = new moodle_url($thispage, array('sesskey' => sesskey(), 'action' => 'regenerate'));
    $saveurl = new moodle_url($thispage, array('sesskey' => sesskey(), 'action' => 'save', 'password' => $password));

    // Generate Password.
    echo html_writer::start_tag('div', array('class' => 'alert-info'));
    echo $OUTPUT->single_button($regenerateurl, get_string('regenerate_password', 'auth_basic'), 'post');
    echo html_writer::tag('div', $password,
        array('style' => 'display: inline-block; margin-left: 5px'));
    echo html_writer::end_tag('div');

    // Save Password Form.
    $formcontent = html_writer::start_tag('div', array('style' => 'margin-top: 5px; margin-bottom: 5px'));
    $formcontent .= html_writer::label('White list IPs', 'whitelistips');
    $formcontent .= html_writer::start_tag('textarea',
        array('name' => 'whitelistips', 'rows' => '4', 'cols' => '50', 'id' => 'whitelistips', 'style' => 'width: 100%'));
    $formcontent .= html_writer::end_tag('textarea');
    $formcontent .= html_writer::end_tag('div');
    $formcontent .= html_writer::empty_tag('input', array('type' => 'submit', 'name' => 'submit',
        'value' => get_string('use_password', 'auth_basic')));

    echo html_writer::tag('form', $formcontent,
        array('id' => 'savepasswordform', 'action' => $saveurl, 'method' => 'post'));

    echo $OUTPUT->heading(get_string('auth_basic_generated_masterpassword', 'auth_basic'));
    // Master Password Table.
    $table = new table_sql('master_password');
    $table->set_sql('*', "{auth_basic_master_password}", 'userid = :userid', array('userid' => $USER->id));
    $table->define_baseurl("$CFG->wwwroot/auth/basic/masterpassword.php");
    $table->sortable(true, 'timecreated', SORT_DESC);
    $table->out(10, true);

    echo $OUTPUT->footer();
}

