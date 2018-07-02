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
 * @package   auth_basic
 * @copyright Brendan Heywood <brendan@catalyst-au.net>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['auth_basicdescription'] = 'Users can sign in via HTTP Basic authentication';
$string['pluginname'] = 'Basic authentication';
$string['send401'] = 'Force Basic for everyone';
$string['send401_help'] = 'If Yes then all users will be prompted with the basic auth dialog and the normal login page will be disabled. In most cases you won\'t want this.';
$string['onlybasic'] = 'Only basic';
$string['onlybasic_help'] = 'If Yes then only users whose auth type has been explicitly set to \'basic\' will work. For additional security.';
$string['debug'] = 'Debug mode';
$string['debug_help'] = 'Dump details of auth process to error log and http headers';
$string['send401_cancel'] = 'You need to enter a valid username and password';

/*
 * Privacy provider (GDPR)
 */
$string["privacy:no_data_reason"] = "The 'auth basic' plugins doesn't store any personnel data.";
