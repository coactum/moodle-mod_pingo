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

$string['activatesession'] = 'Activate for participants';
$string['addquestionfromcatalogue'] = 'Add question from catalogue';
$string['alltags'] = 'All tags';
$string['alluserdatadeleted'] = 'All user data deleted';
$string['answeroptions'] = 'Answer options';
$string['catalogue'] = 'Catalogue';
$string['closeconnection'] = 'Close connection';
$string['connected'] = 'Connected';
$string['connectionsdetails'] = 'Connection settings';
$string['createsessioninpingo'] = 'Create session (in PINGO)';
$string['deletealluserdata'] = 'Delete all user data';
$string['durationchoices'] = 'Duration';
$string['editability'] = 'Editability';
$string['editableforall'] = 'Usable by other teachers';
$string['editableforall_help'] = 'If enabled, all teachers can view all sessions of the connected PINGO account, create surveys in them and view their results in this activity. If this option is not enabled, only the person who logs in with their PINGO account can do this, all other teachers will then only see the participants view. <br><strong>Attention:</strong> This setting cannot be changed after the activity has been created. If you want to change it later, you have to delete the activity and create it again. In this case, no data in PINGO will be lost.';
$string['errfetching'] = 'Error while fetching data. Please reload page.';
$string['errnoemail'] = 'Not a valid mail adress';
$string['errnoquestionchoosen'] = 'No question choosen.';
$string['errnotallowedforotherteachers'] = 'Other teachers are not allowed to add surveys in this activity.';
$string['errsurveynotcreated'] = 'Error while creating new survey.';
$string['errsurveynotstopped'] = 'Error while stopping the survey.';
$string['errunauthorized'] = 'Authentication failed. The saved login for PINGO is invalid or has been revoked in PINGO. You have to close the connection and login in again.';
$string['eventconnectionclosed'] = 'PINGO connection closed';
$string['eventconnectioncreated'] = 'PINGO connection created';
$string['eventpingologinfailed'] = 'PINGO login failed';
$string['eventpingologinsuccessful'] = 'PINGO login successful';
$string['eventpingosurveycreated'] = 'PINGO survey created';
$string['filterbytags'] = 'Filter by tag';
$string['login'] = 'Login';
$string['loginfailed'] = 'PINGO login failed';
$string['loginfailedinvalidcredentials'] = 'PINGO login failed (incorrect credentials)';
$string['loginsuccessful'] = 'PINGO login successful';
$string['logintopingo'] = 'Login to PINGO';
$string['managequestionsinpingo'] = 'Manage question catalogue (in PINGO)';
$string['modulename'] = 'PINGO';
$string['modulename_help'] = 'The PINGO activity enables the integration of the free-to-use classroom response system PINGO into Moodle.

PINGO allows the easy collection of anonymous live feedback and can be used in a variety of ways in teaching. Surveys can be conveniently prepared in advance and then quickly made available to the entire audience via their mobile devices during the lecture.

This activity enables the integration of PINGO into Moodle. It allows teachers to log in to PINGO directly in the activity and then access their sessions created in PINGO, view them, add surveys to them, and then launch them.
Participants can then view the surveys directly in the activity. For additional actions, such as creating new sessions or questions, the activity also redirects to the web version of PINGO.

Teachers can ...

* conveniently log into PINGO
* view all sessions created in PINGO
* add and launch quick surveys and questions from the question catalogue to a session
* view individual sessions and the last active survey in each session

Students can ...

* view the active session and open the survey there for voting';
$string['modulename_link'] = 'mod/pingo/view';
$string['modulenameplural'] = 'PINGOs';
$string['noactivesession'] = 'No active session.';
$string['nocountdown'] = 'No countdown';
$string['nonewmodules'] = 'No new modules';
$string['nopingoyet'] = 'No account yet?';
$string['nosessionsavailable'] = 'No sessions available';
$string['nosurveys'] = 'You have not yet created any surveys in this session.';
$string['overview'] = 'Overview';
$string['pingo:addinstance'] = 'Add new PINGO';
$string['pingo:logintopingo'] = 'Login to PINGO';
$string['pingo:startsurvey'] = 'Start survey in session';
$string['pingo:viewallsessions'] = 'View all PINGO sessions';
$string['pingo:viewoverview'] = 'View overview page';
$string['pingoemail'] = 'The email in PINGO';
$string['pingoemail_help'] = 'The email used for the PINGO login';
$string['pingoimpressum'] = 'Imprint (PINGO)';
$string['pingopassword'] = 'The password for PINGO';
$string['pingopassword_help'] = 'The password for the PINGO account';
$string['pingoprivacypolicy'] = 'Privacy policy (PINGO)';
$string['pluginadministration'] = 'Administration of PINGO';
$string['pluginname'] = 'PINGO';
$string['privacy:metadata:pingo'] = 'In order to authenticate and use the PINGO classroom response service, some personal data needs to be exchanged with the external PINGO server.';
$string['privacy:metadata:pingo:authenticationtoken'] = 'The authentication token obtained from PINGO is sent to the PINGO server with every action so that the requested data can be retrieved.';
$string['privacy:metadata:pingo:email'] = 'The email address of the PINGO account is sent to the PINGO server to authenticate the login and obtain an authentication token.';
$string['privacy:metadata:pingo:password'] = 'The password of the PINGO account is sent to the PINGO server to authenticate the login. It is not stored in Moodle.';
$string['privacy:metadata:pingo_connections'] = 'Contains personal login data of the teachers for the login to PINGO.';
$string['privacy:metadata:pingo_connections:activesession'] = 'The PINGO session made available in the participants view';
$string['privacy:metadata:pingo_connections:authenticationtoken'] = 'The token for authentication with PINGO. Is fetched from PINGO when logging in for the first time in the plugin instance and then stored in the database. Is sent to PINGO again with every action in the plugin instance and allows full access to all data of the user stored on the PINGO server (e.g. questions, surveys, sessions, etc.). Can be invalidated in PINGO.';
$string['privacy:metadata:pingo_connections:pingo'] = 'ID of the plugin instance';
$string['privacy:metadata:pingo_connections:timestarted'] = 'Date on which the login to PINGO was made';
$string['privacy:metadata:pingo_connections:userid'] = 'Moodle ID of the user who registers the plugin instance with PINGO';
$string['questionfromcatalogue'] = 'From catalogue';
$string['questionfromcatalogueexplanation'] = 'Start a question from your question catalogue in this session.';
$string['questiontypes'] = 'Question type';
$string['quickstart'] = 'Quickstart';
$string['quickstartexplanation'] = 'Start a generic survey in this session.';
$string['quicksurvey'] = 'Quick survey';
$string['registerforpingo'] = 'Register for PINGO (external page)';
$string['reloadpage'] = 'Reload page';
$string['remoteserver'] = 'PINGO server';
$string['remoteserverall'] = 'PINGO server to which the plugin should connect.';
$string['session'] = 'Session';
$string['sessionactivated'] = 'Session activated';
$string['sessions'] = 'Sessions';
$string['setsessionactive'] = 'Set session active for students';
$string['startsurvey'] = 'Start';
$string['state'] = 'State';
$string['stopsurvey'] = 'Stop';
$string['stoptime'] = 'Time to stop';
$string['surveycreated'] = 'Survey created';
$string['surveyended'] = 'Ended:';
$string['surveyends'] = 'Ends in ';
$string['surveyhasnoend'] = 'No end date.';
$string['surveyinsession'] = 'Survey in session';
$string['surveystopped'] = 'Survey will be stopped';
$string['viewallpingos'] = 'View all PINGO instances in the course';
$string['voteinpingo'] = 'Vote (in PINGO)';
$string['yourquestions'] = 'Your questions';
