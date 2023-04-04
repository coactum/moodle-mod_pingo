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
$string['editableforall'] = 'connections editable by other teachers';
$string['editableforall_help'] = 'If enabled, other teachers can edit the connection configured in PINGO.';

// Strings for index.php.
$string['modulenameplural'] = 'Pingos';
$string['nonewmodules'] = 'No new modules';

// Strings for lib.php.
$string['deletealluserdata'] = 'Delete all user data';

// Strings for the capabilities.
$string['pingo:addinstance'] = 'Add new pingo';
$string['pingo:viewconnectionsoverview'] = 'View overview of PINGO connections';
$string['pingo:logintoconnection'] = 'Start PINGO connection';
$string['pingo:viewallconnections'] = 'View all PINGO connections';
$string['pingo:startsurvey'] = 'Start survey from PINGO';

// Strings for the tasks.
$string['task'] = 'Task';

// Strings for the admin settings.
$string['connectionsdetails'] = 'Connection settings';
$string['remoteserver'] = 'PINGO server';
$string['remoteserverall'] = 'PINGO server to which the plugin should connect.';

// Strings for the view page.
$string['overview'] = 'Overview';
$string['viewallpingos'] = 'View all PINGO instances in the course';
$string['login'] = 'Login';
$string['loginfailed'] = 'PINGO login failed';
$string['loginfailedinvalidcredentials'] = 'PINGO login failed (incorrect credentials)';
$string['loginsuccessful'] = 'PINGO login successful';
$string['connectionactive'] = 'Connection active';
$string['closeconnection'] = 'Close current connection';
$string['noconnection'] = 'No connection active';
$string['sessionsoverview'] = 'Sessions overview';
$string['yoursessions'] = 'Your sessions';
$string['nosessionsavailable'] = 'No sessions available';
$string['sessionview'] = 'Session';
$string['backtosessionsoverview'] = 'Back to sessions overview';
$string['lastsurvey'] = 'Last survey';
$string['answers'] = 'Answers';
$string['editquestions'] = 'Edit questions (in PINGO)';
$string['editsession'] = 'Edit session (in PINGO)';
$string['continue'] = 'Continue';
$string['repeat'] = 'Repeat';
$string['surveys'] = 'Surveys';
$string['nosurveys'] = 'You have not yet created any surveys in this session.';
$string['nosurveyactive'] = 'No survey active';

// Strings for the login form.
$string['pingoemail'] = 'The email in PINGO';
$string['pingoemail_help'] = 'The email used for the PINGO account';
$string['pingopassword'] = 'The password in PINGO';
$string['pingopassword_help'] = 'The password for the PINGO account';
$string['nopingoyet'] = 'No account yet?';
$string['registerforpingo'] = 'Register for PINGO (external page)';
$string['logintopingo'] = 'Login to PINGO';

// Strings for the events.
$string['eventconnectioncreated'] = 'PINGO connection created';
$string['eventpingologinfailed'] = 'PINGO login failed';
$string['eventpingologinsuccessful'] = 'PINGO login successful';

// Strings for all errors.
$string['errnoemail'] = 'Not a valid mail adress';
$string['errnosession'] = 'Error while fetching session data. Please reload page.';

// Strings for the privacy api.
/*
$string['privacy:metadata:pingo_participants'] = 'Contains the personal data of all pingo participants.';
$string['privacy:metadata:pingo_submissions'] = 'Contains all data related to pingo submissions.';
$string['privacy:metadata:pingo_participants:pingo'] = 'Id of the pingo activity the participant belongs to';
$string['privacy:metadata:pingo_submissions:pingo'] = 'Id of the pingo activity the submission belongs to';
$string['privacy:metadata:core_message'] = 'The pingo plugin sends messages to users and saves their content in the database.';
*/
