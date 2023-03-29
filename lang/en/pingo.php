<?php
// This file is part of Moodle - https://moodle.org/
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
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

/**
 * English strings for the plugin are defined here.
 *
 * @package     mod_pingo
 * @category    string
 * @copyright   2023 coactum GmbH
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

// Common strings.
$string['pluginname'] = 'Pingo';

// Strings for mod_form.php.
$string['modulename'] = 'pingo';
$string['modulename_help'] = 'The pingo activity allows ... ';
$string['modulename_link'] = 'mod/pingo/view';
$string['pluginadministration'] = 'Administration of pingo';
$string['editability'] = 'Editability';
$string['editableforall'] = 'Sessions editable by other teachers';
$string['editableforall_help'] = 'If enabled, other teachers can edit the session configured in PINGO.';

// Strings for index.php.
$string['modulenameplural'] = 'Pingos';
$string['nonewmodules'] = 'No new modules';

// Strings for lib.php.
$string['deletealluserdata'] = 'Delete all user data';

// Strings for the capabilities.
$string['pingo:addinstance'] = 'Add new pingo';
$string['pingo:viewsessionsoverview'] = 'View overview of PINGO sessions';
$string['pingo:logintosession'] = 'Start PINGO session';
$string['pingo:viewallsessions'] = 'View all PINGO sessions';
$string['pingo:startsurvey'] = 'Start survey from PINGO';

// Strings for the tasks.
$string['task'] = 'Task';

// Strings for the admin settings.
$string['connectionsdetails'] = 'Connection settings';
$string['remoteserver'] = 'PINGO server';
$string['remoteserverall'] = 'PINGO server to which the plugin should connect.';

// Strings for the view page.
$string['overview'] = 'Overview of all PINGO sessions';
$string['viewallpingos'] = 'View all PINGO instances in the course';
$string['loginfailed'] = 'PINGO login failed';
$string['loginfailedinvalidcredentials'] = 'PINGO login failed (incorrect credentials)';
$string['loginsuccessful'] = 'PINGO login successful';
$string['sessionid'] = 'Session ID';
$string['sessiontoken'] = 'Session token';
$string['sessionactive'] = 'Session active';
$string['nosession'] = 'No session active';
$string['sessionlogout'] = 'Logout from current session';
$string['nosurveyactive'] = 'No survey active';

// Strings for the login form.
$string['pingousername'] = 'The username in PINGO';
$string['pingousername_help'] = 'The username for the account in PINGO';
$string['pingopassword'] = 'The password in PINGO';
$string['pingopassword_help'] = 'The password for the account in PINGO';
$string['nopingoyet'] = 'No account yet?';
$string['registerforpingo'] = 'Register for PINGO (external page)';

// Strings for the events.
$string['eventsessioncreated'] = 'PINGO session created';
$string['eventpingologinfailed'] = 'PINGO login failed';
$string['eventpingologinsuccessful'] = 'PINGO login successful';

// Strings for the privacy api.
/*
$string['privacy:metadata:pingo_participants'] = 'Contains the personal data of all pingo participants.';
$string['privacy:metadata:pingo_submissions'] = 'Contains all data related to pingo submissions.';
$string['privacy:metadata:pingo_participants:pingo'] = 'Id of the pingo activity the participant belongs to';
$string['privacy:metadata:pingo_submissions:pingo'] = 'Id of the pingo activity the submission belongs to';
$string['privacy:metadata:core_message'] = 'The pingo plugin sends messages to users and saves their content in the database.';
*/
