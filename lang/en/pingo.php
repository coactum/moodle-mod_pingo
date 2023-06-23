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
$string['pluginname'] = 'PINGO';

// Strings for mod_form.php.
$string['modulename'] = 'PINGO';
$string['modulename_help'] = 'The PINGO activity allows ... ';
$string['modulename_link'] = 'mod/pingo/view';
$string['pluginadministration'] = 'Administration of PINGO';
$string['editability'] = 'Editability';
$string['editableforall'] = 'Editable by other teachers';
$string['editableforall_help'] = 'If enabled, all teachers in this activity can view all sessions of the connected PINGO account, create surveys in them and view their results. If this option is not enabled, only the person who logs in with their PINGO account can do this, all other teachers will then only see the participant view. <br><strong>Attention:</strong> This setting cannot be changed after the activity has been created. If you want to change it later, you have to delete the activity and create it again. In this case, no data will be lost.';

// Strings for index.php.
$string['modulenameplural'] = 'PINGOs';
$string['nonewmodules'] = 'No new modules';

// Strings for lib.php.
$string['deletealluserdata'] = 'Delete all user data';
$string['alluserdatadeleted'] = 'All user data deleted';

// Strings for the capabilities.
$string['pingo:addinstance'] = 'Add new PINGO';
$string['pingo:viewoverview'] = 'View overview page';
$string['pingo:logintopingo'] = 'Login to PINGO';
$string['pingo:viewallsessions'] = 'View all sessions in PINGO';
$string['pingo:startsurvey'] = 'Start survey from PINGO';

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
$string['connected'] = 'Connected';
$string['closeconnection'] = 'Close connection';
$string['noconnection'] = 'Not connected';
$string['yoursessions'] = 'Your sessions';
$string['nosessionsavailable'] = 'No sessions available';
$string['answers'] = 'Answers';
$string['continuesurvey'] = 'Continue';
$string['repeatsurvey'] = 'Repeat';
$string['stopsurvey'] = 'Stop';
$string['stoptime'] = 'Time to stop';
$string['nosurveys'] = 'You have not yet created any surveys in this session.';
$string['nosurveyactive'] = 'No survey active';
$string['sessions'] = 'Sessions';
$string['quickstart'] = 'Quickstart';
$string['catalog'] = 'Catalog';
$string['createsessioninpingo'] = 'Create session (in PINGO)';
$string['startsurvey'] = 'Start';
$string['quicksurvey'] = 'Quick survey';
$string['questionfromcatalog'] = 'From catalog';
$string['session'] = 'Session';
$string['surveycreated'] = 'Survey created';
$string['reloadpage'] = 'Reload page';
$string['surveystopped'] = 'Survey will be stopped';
$string['surveyends'] = 'Ends in ';
$string['surveyhasnoend'] = 'No end date.';
$string['surveyended'] = 'Ended:';
$string['nosessionchoosen'] = 'No session choosen.';
$string['noactivesession'] = 'No active session.';
$string['setsessionactive'] = 'Set session active for students';
$string['voteinpingo'] = 'Vote (in PINGO)';
$string['state'] = 'State';
$string['activatesession'] = 'Activate for participants';
$string['sessionactivated'] = 'Session activated';
$string['surveyinsession'] = 'Survey in session';

// Strings for the login form.
$string['pingoemail'] = 'The email in PINGO';
$string['pingoemail_help'] = 'The email used for the PINGO account';
$string['pingopassword'] = 'The password in PINGO';
$string['pingopassword_help'] = 'The password for the PINGO account';
$string['nopingoyet'] = 'No account yet?';
$string['registerforpingo'] = 'Register for PINGO (external page)';
$string['logintopingo'] = 'Login to PINGO';
$string['pingoimpressum'] = 'Imprint (PINGO)';
$string['pingoprivacypolicy'] = 'Privacy policy (PINGO)';

// Strings for the quickstart form.
$string['quickstartexplanation'] = 'Start a generic survey in this session.';
$string['questiontypes'] = 'Question type';
$string['answeroptions'] = 'Answer options';
$string['durationchoices'] = 'Duration';
$string['nocountdown'] = 'No countdown';

// Strings for the questionfromcatalog form.
$string['addquestionfromcatalog'] = 'Add question from catalogue';
$string['questionfromcatalogexplanation'] = 'Start a question from your question catalogue in this session.';
$string['managequestionsinpingo'] = 'Manage question catalogue (in PINGO)';
$string['yourquestions'] = 'Your questions';
$string['filterbytags'] = 'Filter by tag';
$string['alltags'] = 'All tags';

// Strings for the events.
$string['eventconnectionclosed'] = 'PINGO connection closed';
$string['eventconnectioncreated'] = 'PINGO connection created';
$string['eventpingologinfailed'] = 'PINGO login failed';
$string['eventpingologinsuccessful'] = 'PINGO login successful';

// Strings for all errors.
$string['errunauthorized'] = 'Authentication failed. The saved login for PINGO is invalid or has been revoked in PINGO. You have to close the connection and login in again.';
$string['errnoemail'] = 'Not a valid mail adress';
$string['errfetching'] = 'Error while fetching session data. Please reload page.';
$string['errsurveynotcreated'] = 'Error while creating new survey.';
$string['errsurveynotstopped'] = 'Error while stopping the survey.';
$string['errnoquestionchoosen'] = 'No question choosen.';
$string['errnotallowedforotherteachers'] = 'Other teachers are not allowed to add surveys in this activity.';

// Strings for the privacy api.
$string['privacy:metadata:pingo_connections'] = 'Contains personal login data of the teachers for the login to PINGO.';
$string['privacy:metadata:pingo_connections:userid'] = 'Moodle ID of the user who registers the plug-in instance with PINGO';
$string['privacy:metadata:pingo_connections:pingo'] = 'ID of the plugin instance';
$string['privacy:metadata:pingo_connections:authenticationtoken'] = 'The token for authentication with PINGO. Is fetched from PINGO when logging in for the first time in the plugin instance and then stored in the database. Is sent to PINGO again with every action in the plugin instance and allows full access to all data of the user stored on the PINGO server (e.g. questions, surveys, sessions, etc.). Can be invalidated in PINGO (log out of all external applications).';
$string['privacy:metadata:pingo_connections:timestarted'] = 'Date on which the login to PINGO was made';
$string['privacy:metadata:pingo_connections:activesession'] = 'The PINGO session made available in the participants view';
