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
 * Prints an instance of mod_pingo.
 *
 * @package     mod_pingo
 * @copyright   2023 coactum GmbH
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use mod_pingo\output\pingo_connectioninfo;
use mod_pingo\output\pingo_tabarea;
use mod_pingo\output\pingo_sessionsoverview;
use mod_pingo\output\pingo_sessionview;
use mod_pingo\output\pingo_participantsview;

use mod_pingo\pingo_api\mod_pingo_api;

use core\output\notification;

require(__DIR__.'/../../config.php');
require_once(__DIR__.'/lib.php');

require_once(__DIR__.'/classes/pingo_api/api.php');

// Course_module ID.
$id = optional_param('id', 0, PARAM_INT);

// If current connection should be closed.
$closeconnection = optional_param('closeconnection', 0, PARAM_INT);

// The mode for the page (1=sessions, 2=quickstart, 3=questionfromcatalog, 4=session).
$mode = optional_param('mode', 1, PARAM_INT);

// Session that should be displayed.
$session = optional_param('session', 0, PARAM_INT);

// Tag for the question from catalogue form.
$tag = optional_param('tag', 0, PARAM_TEXT);

// Session that should be shown in the participants view.
$activatesession = optional_param('activatesession', -1, PARAM_INT);

// Set the basic variables $course, $cm and $moduleinstance.
if ($id) {
    [$course, $cm] = get_course_and_cm_from_cmid($id, 'pingo');
    $moduleinstance = $DB->get_record('pingo', ['id' => $cm->instance], '*', MUST_EXIST);
} else {
    throw new moodle_exception('missingparameter');
}

require_login($course, true, $cm);

$context = context_module::instance($cm->id);

// Check if connection is active.
$activeconnection = $DB->get_record('pingo_connections', array('pingo' => $moduleinstance->id));

if ($closeconnection && $DB->record_exists('pingo_connections', array('pingo' => $moduleinstance->id))) {
    require_sesskey();

    // Trigger PINGO connection closed event.
    $event = \mod_pingo\event\connection_closed::create(array(
        'objectid' => (int) $activeconnection->id,
        'context' => $context
    ));

    $event->trigger();

    $DB->delete_records('pingo_connections', array('pingo' => $moduleinstance->id));

    $urlparams = array('id' => $id);
    $redirecturl = new moodle_url('/mod/pingo/view.php', $urlparams);

    redirect($redirecturl, get_string('eventconnectionclosed', 'mod_pingo'), null, notification::NOTIFY_SUCCESS);

}

$remoteurl = get_config('pingo', 'remoteserver');

// Trigger course_module_viewed event.
$event = \mod_pingo\event\course_module_viewed::create(array(
    'objectid' => $moduleinstance->id,
    'context' => $context
));

$event->add_record_snapshot('course_modules', $cm);
$event->add_record_snapshot('course', $course);
$event->add_record_snapshot('pingo', $moduleinstance);
$event->trigger();

// Get the name for this activity.
$modulename = format_string($moduleinstance->name, true, array(
    'context' => $context
));

// Set $PAGE and completion.

$PAGE->navbar->add(get_string("overview", "pingo"), new moodle_url('/mod/pingo/view.php', array('id' => $cm->id)));

if (!$activeconnection) {
    $PAGE->navbar->add(get_string("login", "pingo"));
    $PAGE->set_url('/mod/pingo/view.php', array('id' => $cm->id));
} else if (!$session && $mode == 1) {
    $PAGE->navbar->add(get_string("sessions", "pingo"));
    $PAGE->set_url('/mod/pingo/view.php', array('id' => $cm->id, 'mode' => $mode));
} else if ($mode == 2) {
    $PAGE->navbar->add(get_string("quickstart", "pingo") . ' (' . $session . ')');
    $PAGE->set_url('/mod/pingo/view.php', array('id' => $cm->id, 'session' => $session, 'mode' => $mode));
} else if ($mode == 3) {
    $PAGE->navbar->add(get_string("catalogue", "pingo") . ' (' . $session . ')');
    $PAGE->set_url('/mod/pingo/view.php', array('id' => $cm->id, 'session' => $session, 'mode' => $mode));
} else if ($mode == 4) {
    $PAGE->navbar->add(get_string("session", "pingo") . ' (' . $session . ')');
    $PAGE->set_url('/mod/pingo/view.php', array('id' => $cm->id, 'session' => $session, 'mode' => $mode));
} else {
    $PAGE->set_url('/mod/pingo/view.php', array('id' => $cm->id, 'session' => $session, 'mode' => $mode));
}

$PAGE->requires->js_call_amd('mod_pingo/view', 'init', array());

$completion = new completion_info($course);
$completion->set_module_viewed($cm);

$PAGE->set_title(get_string('modulename', 'mod_pingo').': ' . $modulename);
$PAGE->set_heading(format_string($course->fullname));
$PAGE->set_context($context);

if (!$activeconnection) { // Login.

    // Check if new connection should be saved.
    require_once($CFG->dirroot . '/mod/pingo/login_form.php');

    // Instantiate form.
    $mform = new mod_pingo_login_form(null);

    if ($fromform = $mform->get_data()) {

        // In this case you process validated data. $mform->get_data() returns data posted in form.
        if (isset($fromform->email) && $fromform->password) { // Try login.

            // Get PINGO authentication token.
            $authtoken = mod_pingo_api::get_authtoken($remoteurl, $fromform->email, $fromform->password);

            if (isset($authtoken) && $authtoken && $authtoken != 'invalid' && $authtoken != 0) {
                $connection = new stdClass();
                $connection->pingo = (int) $cm->instance;
                $connection->userid = (int) $USER->id;
                $connection->timestarted = time();
                $connection->authenticationtoken = $authtoken;

                $newconnectionid = $DB->insert_record('pingo_connections', $connection);

                // Trigger PINGO connection login successful event.
                $event = \mod_pingo\event\pingo_login_successful::create(array(
                    'objectid' => $newconnectionid,
                    'context' => $context
                ));

                $event->trigger();

                // Trigger PINGO connection created event.
                $event = \mod_pingo\event\connection_created::create(array(
                    'objectid' => $newconnectionid,
                    'context' => $context
                ));

                $event->trigger();

                $urlparams = array('id' => $id);
                $redirecturl = new moodle_url('/mod/pingo/view.php', $urlparams);

                redirect($redirecturl, get_string('loginsuccessful', 'mod_pingo'), null, notification::NOTIFY_SUCCESS);

            } else {
                // Trigger PINGO connection login failed event.
                $event = \mod_pingo\event\pingo_login_failed::create(array(
                    'objectid' => (int) $USER->id,
                    'context' => $context
                ));

                $event->trigger();

                $urlparams = array('id' => $id);
                $redirecturl = new moodle_url('/mod/pingo/view.php', $urlparams);

                redirect($redirecturl, get_string('loginfailed', 'mod_pingo'), null, notification::NOTIFY_ERROR);

            }

        } else { // No login because of invalid credentials.
            $urlparams = array('id' => $id);
            $redirecturl = new moodle_url('/mod/pingo/view.php', $urlparams);

            redirect($redirecturl, get_string('loginfailedinvalidcredentials', 'mod_pingo'), null, notification::NOTIFY_ERROR);
        }
    }
} else if ($mode === 2) {  // Quickstart.

    require_once($CFG->dirroot . '/mod/pingo/quickstart_form.php');

    $data = mod_pingo_api::get_quickstart_formdata($remoteurl, $activeconnection->authenticationtoken);
    $durationchoices = mod_pingo_api::get_durationchoices($remoteurl);

    if ($data) {
        $mform = new mod_pingo_quickstart_form(null,
            array('question_types' => $data->questiontypes, 'duration_choices' => $durationchoices,
            'answer_options' => $data->answeroptions, 'sessiontoken' => '', 'sessionname' => ''));

        if ($fromform = $mform->get_data()) {

            // In this case you process validated data. $mform->get_data() returns data posted in form.
            if ($fromform->session && isset($fromform->duration_choices)) {

                // Get session data from PINGO.
                $sessiondata = mod_pingo_api::get_session($remoteurl, $activeconnection->authenticationtoken, $fromform->session);

                if (!empty($sessiondata)) {
                    if (!isset($fromform->answer_options) || !isset($fromform->answer_options[$fromform->question_types])) {
                        $fromform->answer_options[$fromform->question_types] = false;
                    }

                    $surveycreated = mod_pingo_api::run_quickstart($remoteurl, $activeconnection->authenticationtoken,
                        $fromform->session, $fromform->question_types, $fromform->answer_options[$fromform->question_types],
                        $fromform->duration_choices);

                    if ($surveycreated) {
                        // Set session active.
                        if ($fromform->setsessionactive) {
                            $connection = $DB->get_record('pingo_connections', array('pingo' => $moduleinstance->id));
                            $connection->activesession = $fromform->session;

                            $DB->update_record('pingo_connections', $connection);
                        }

                        // Trigger PINGO survey created event.
                        $event = \mod_pingo\event\pingo_survey_created::create(array(
                            'objectid' => (int) $fromform->session,
                            'context' => $context
                        ));

                        $event->trigger();

                        $urlparams = array('id' => $id, 'session' => $session, 'mode' => 4);
                        $redirecturl = new moodle_url('/mod/pingo/view.php', $urlparams);
                        redirect($redirecturl, get_string('surveycreated', 'mod_pingo'), null, notification::NOTIFY_SUCCESS);
                    } else {
                        $urlparams = array('id' => $id, 'session' => $session, 'mode' => 2);
                        $redirecturl = new moodle_url('/mod/pingo/view.php', $urlparams);

                        redirect($redirecturl, get_string('errsurveynotcreated', 'mod_pingo'), null, notification::NOTIFY_ERROR);
                    }
                } else {
                    $urlparams = array('id' => $id, 'session' => $session, 'mode' => 2);
                    $redirecturl = new moodle_url('/mod/pingo/view.php', $urlparams);

                    redirect($redirecturl, get_string('errsurveynotcreated', 'mod_pingo'), null, notification::NOTIFY_ERROR);
                }
            } else {
                $urlparams = array('id' => $id, 'session' => $session, 'mode' => 2);
                $redirecturl = new moodle_url('/mod/pingo/view.php', $urlparams);

                redirect($redirecturl, get_string('errsurveynotcreated', 'mod_pingo'), null, notification::NOTIFY_ERROR);
            }
        }
    }
} else if ($mode === 3) {  // Question from catalogue.
    require_once($CFG->dirroot . '/mod/pingo/questionfromcatalogue_form.php');

    // Get data for form from PINGO.
    $data = mod_pingo_api::get_questionfromcatalogue_formdata($remoteurl, $activeconnection->authenticationtoken, '');
    $durationchoices = mod_pingo_api::get_durationchoices($remoteurl);

    if ($data) {
        $mform = new mod_pingo_questionfromcatalogue_form(null,
            array('questions' => $data->questions, 'duration_choices' => $durationchoices, 'sessiontoken' => '',
                'sessionname' => '', 'remoteurl' => $remoteurl, 'tags' => $data->tags));

        if ($fromform = $mform->get_data()) {
            // In this case you process validated data. $mform->get_data() returns data posted in form.

            // Redirect if questions should be filtered by tag.
            if ($fromform->reload == 1) {

                // If tag value contains js and is therefore filtered by moodle.
                if (isset($fromform->tag)) {
                    $tag = $fromform->tag;
                } else {
                    $tag = '';
                }

                $urlparams = array('id' => $id, 'session' => $session, 'mode' => 3, 'tag' => $tag);
                $redirecturl = new moodle_url('/mod/pingo/view.php', $urlparams);

                redirect($redirecturl);
            }

            if ($fromform->session && isset($fromform->duration_choices)) {
                // Get session data from PINGO.
                $sessiondata = mod_pingo_api::get_session($remoteurl, $activeconnection->authenticationtoken, $fromform->session);

                if (!empty($sessiondata)) {
                    $surveycreated = mod_pingo_api::run_question_from_catalogue($remoteurl, $activeconnection->authenticationtoken,
                        $fromform->session, $fromform->question, $fromform->duration_choices);

                    if ($surveycreated) {
                        // Set session active.
                        if ($fromform->setsessionactive) {
                            $connection = $DB->get_record('pingo_connections', array('pingo' => $moduleinstance->id));
                            $connection->activesession = $fromform->session;

                            $DB->update_record('pingo_connections', $connection);
                        }

                        // Trigger PINGO survey created event.
                        $event = \mod_pingo\event\pingo_survey_created::create(array(
                            'objectid' => (int) $fromform->session,
                            'context' => $context
                        ));

                        $event->trigger();

                        $urlparams = array('id' => $id, 'session' => $session, 'mode' => 4);
                        $redirecturl = new moodle_url('/mod/pingo/view.php', $urlparams);
                        redirect($redirecturl, get_string('surveycreated', 'mod_pingo'), null, notification::NOTIFY_SUCCESS);
                    } else {
                        $urlparams = array('id' => $id, 'session' => $session, 'mode' => 3);
                        $redirecturl = new moodle_url('/mod/pingo/view.php', $urlparams);

                        redirect($redirecturl, get_string('errsurveynotcreated', 'mod_pingo'), null, notification::NOTIFY_ERROR);
                    }
                } else {
                    $urlparams = array('id' => $id, 'session' => $session, 'mode' => 3);
                    $redirecturl = new moodle_url('/mod/pingo/view.php', $urlparams);
                    redirect($redirecturl, get_string('errsurveynotcreated', 'mod_pingo'), null, notification::NOTIFY_ERROR);
                }
            } else {
                $urlparams = array('id' => $id, 'session' => $session, 'mode' => 3);
                $redirecturl = new moodle_url('/mod/pingo/view.php', $urlparams);

                redirect($redirecturl, get_string('errsurveynotcreated', 'mod_pingo'), null, notification::NOTIFY_ERROR);
            }
        }
    }
} else if ($mode === 4) { // Session.
    require_once($CFG->dirroot . '/mod/pingo/stopsurvey_form.php');

    // Get data for form from PINGO.
    $sessiondata = mod_pingo_api::get_session($remoteurl, $activeconnection->authenticationtoken, $session);
    $durationchoices = mod_pingo_api::get_durationchoices($remoteurl);

    if ($durationchoices) {
        $durationchoices[0] = get_string('now');
    }

    if ($sessiondata) {
        $mform = new mod_pingo_stopsurvey_form(null,
            array('remoteurl' => $remoteurl, 'authtoken' => $activeconnection->authenticationtoken,
                'duration_choices' => $durationchoices, 'session' => $session));

        if ($fromform = $mform->get_data()) {
            // In this case you process validated data. $mform->get_data() returns data posted in form.

            // Reload page if session has ended.
            if ($fromform->surveyended == 1) {
                $urlparams = array('id' => $id, 'session' => $session, 'mode' => 4);
                $redirecturl = new moodle_url('/mod/pingo/view.php', $urlparams);

                redirect($redirecturl);
            }

            if ($fromform->session && isset($fromform->stoptime)) {

                if (!empty($sessiondata)) {
                    $surveystopped = mod_pingo_api::stop_survey($remoteurl, $activeconnection->authenticationtoken,
                        $fromform->session, $fromform->surveyid, $fromform->stoptime);

                    if ($surveystopped) {
                        $urlparams = array('id' => $id, 'session' => $session, 'mode' => 4);
                        $redirecturl = new moodle_url('/mod/pingo/view.php', $urlparams);

                        redirect($redirecturl, get_string('surveystopped', 'mod_pingo'), null, notification::NOTIFY_SUCCESS);
                    } else {
                        $urlparams = array('id' => $id, 'session' => $session, 'mode' => 4);
                        $redirecturl = new moodle_url('/mod/pingo/view.php', $urlparams);

                        redirect($redirecturl, get_string('errsurveynotstopped', 'mod_pingo'), null, notification::NOTIFY_ERROR);
                    }
                } else {
                    $urlparams = array('id' => $id, 'session' => $session, 'mode' => 4);
                    $redirecturl = new moodle_url('/mod/pingo/view.php', $urlparams);

                    redirect($redirecturl, get_string('errsurveynotstopped', 'mod_pingo'), null, notification::NOTIFY_ERROR);
                }
            } else {
                $urlparams = array('id' => $id, 'session' => $session, 'mode' => 4);
                $redirecturl = new moodle_url('/mod/pingo/view.php', $urlparams);

                redirect($redirecturl, get_string('errsurveynotstopped', 'mod_pingo'), null, notification::NOTIFY_ERROR);
            }
        }
    }
} else if ($activatesession >= 0) {
    $connection = $DB->get_record('pingo_connections', array('pingo' => $moduleinstance->id));
    $connection->activesession = $activatesession;

    $DB->update_record('pingo_connections', $connection);

    $urlparams = array('id' => $id, 'mode' => 1);
    $redirecturl = new moodle_url('/mod/pingo/view.php', $urlparams);

    redirect($redirecturl, get_string('sessionactivated', 'mod_pingo'), null, notification::NOTIFY_SUCCESS);
}

// Add settingsmenu and heading for moodle < 400.
if ($CFG->branch < 400) {
    $PAGE->force_settings_menu();
}

echo $OUTPUT->header();

if ($CFG->branch < 400) {
    echo $OUTPUT->heading($modulename);

    if ($moduleinstance->intro) {
        echo $OUTPUT->box(format_module_intro('pingo', $moduleinstance, $cm->id), 'generalbox', 'intro');
    }
}

$viewoverview = has_capability('mod/pingo:viewoverview', $context);
$viewallsessions = has_capability('mod/pingo:viewallsessions', $context);

// Handle groups.
echo groups_print_activity_menu($cm, $CFG->wwwroot . "/mod/pingo/view.php?id=$id");

// Teacher view.
if ($viewoverview && ($moduleinstance->editableforall || (!$activeconnection || $USER->id == $activeconnection->userid))) {

     // Add section for connectioninfo.
    $connectioninfo = new pingo_connectioninfo($cm->id, $activeconnection);
    echo $OUTPUT->render($connectioninfo);

    if ($activeconnection) { // Show content from PINGO.

        // Add section with sessions overview.
        $tabs = new stdClass;
        $tabs->active = new stdClass;

        switch ($mode) {
            case 1:
                $tabs->active->sessions = true;
                break;
            case 2:
                $tabs->active->quickstart = true;
                break;
            case 3:
                $tabs->active->catalogue = true;
                break;
            case 4:
                $tabs->active->session = true;
                break;
        }

        $tabarea = new pingo_tabarea($cm->id, $tabs, $session);
        echo $OUTPUT->render($tabarea);

        if (!isset($activeconnection->authenticationtoken)) {
            $activeconnection->authenticationtoken = false;
        }

        // Show content.
        if ($mode === 1  && $viewallsessions) { // View sessions overview.

            // Get sessions data.
            $sessions = mod_pingo_api::get_sessions($remoteurl, $activeconnection->authenticationtoken);

            if ($sessions) {
                // Check what sessions should be shown on participants view.
                foreach ($sessions as $i => $session) {
                    if ($session['token'] == $activeconnection->activesession) {
                        $sessions[$i]['visible'] = true;
                    }
                }
            }

            // Add section with sessions overview.
            $sessionsoverview = new pingo_sessionsoverview($cm->id, $sessions);
            echo $OUTPUT->render($sessionsoverview);

        } else if ($mode === 2) {

            // Get data for form from PINGO.
            $data = mod_pingo_api::get_quickstart_formdata($remoteurl, $activeconnection->authenticationtoken);
            $sessiondata = mod_pingo_api::get_session($remoteurl, $activeconnection->authenticationtoken, $session);
            $durationchoices = mod_pingo_api::get_durationchoices($remoteurl);

            if ($data && $sessiondata) {
                // Add form.
                $mform = new mod_pingo_quickstart_form(new moodle_url('/mod/pingo/view.php', array('id' => $cm->id)),
                    array('question_types' => $data->questiontypes, 'duration_choices' => $durationchoices,
                    'answer_options' => $data->answeroptions,
                    'sessiontoken' => format_text($sessiondata['token'], 2),
                    'sessionname' => format_text($sessiondata['name'], 2)));

                // Set default data.
                $mform->set_data(array('id' => $cm->id, 'session' => $sessiondata['token']));

                // Render form.
                echo $mform->render();
            } else {
                $urlparams = array('id' => $id, 'session' => $session, 'mode' => 2);
                $redirecturl = new moodle_url('/mod/pingo/view.php', $urlparams);

                echo '<div class="alert alert-danger alert-block fade in m-2" role="alert">';
                echo '<button type="button" class="close" data-dismiss="alert">×</button>';
                echo get_string('errfetching', 'mod_pingo') . '</div>';
                echo '<a class="btn btn-primary" href="' . $redirecturl . '">' . get_string('reloadpage', 'mod_pingo') . '</a>';
            }

        } else if ($mode === 3) {
            // Get data for form from PINGO.
            $data = mod_pingo_api::get_questionfromcatalogue_formdata($remoteurl, $activeconnection->authenticationtoken, $tag);
            $sessiondata = mod_pingo_api::get_session($remoteurl, $activeconnection->authenticationtoken, $session);
            $durationchoices = mod_pingo_api::get_durationchoices($remoteurl);

            if ($data && $sessiondata) {
                // Add form.
                $mform = new mod_pingo_questionfromcatalogue_form(new moodle_url('/mod/pingo/view.php', array('id' => $cm->id)),
                    array('questions' => $data->questions, 'duration_choices' => $durationchoices,
                    'sessiontoken' => format_text($sessiondata['token'], 2)
                    , 'sessionname' => format_text($sessiondata['name'], 2),
                    'remoteurl' => $remoteurl, 'tags' => $data->tags));

                if ($tag == 0) {
                    $tag = 'alltags';
                }

                // Set default data.
                $mform->set_data(array('id' => $cm->id, 'session' => $sessiondata['token'], 'tag' => $tag));

                // Render form.
                echo $mform->render();
            } else {
                $urlparams = array('id' => $id, 'session' => $session, 'mode' => 3);
                $redirecturl = new moodle_url('/mod/pingo/view.php', $urlparams);

                echo '<div class="alert alert-danger alert-block fade in m-2" role="alert">';
                echo '<button type="button" class="close" data-dismiss="alert">×</button>';
                echo get_string('errfetching', 'mod_pingo') . '</div>';
                echo '<a class="btn btn-primary" href="' . $redirecturl . '">' . get_string('reloadpage', 'mod_pingo') . '</a>';
            }

        } else if ($mode === 4) {
            if ($session) {

                // Get session data from PINGO.
                $sessiondata = mod_pingo_api::get_session($remoteurl, $activeconnection->authenticationtoken, $session);
                $durationchoices = mod_pingo_api::get_durationchoices($remoteurl);
                if ($durationchoices) {
                    $durationchoices[0] = get_string('now');
                }

                if ($sessiondata && $durationchoices) {
                    // Add form.
                    $mform = new mod_pingo_stopsurvey_form(new moodle_url('/mod/pingo/view.php', array('id' => $cm->id)),
                        array('duration_choices' => $durationchoices, 'session' => $session,
                        'remoteurl' => $remoteurl));

                    if (isset($sessiondata['latest_survey']) && isset($sessiondata['latest_survey']['id'])) {
                        $surveyid = $sessiondata['latest_survey']['id'];
                    } else {
                        $surveyid = false;
                    }

                    // Set default data.
                    $mform->set_data(array('id' => $cm->id, 'session' => $session,
                        'surveyid' => $surveyid));

                    // Render form.
                    $stopsurveyform = $mform->render();

                    if (isset($sessiondata['latest_survey']['ends'])) { // Survey has end date.
                        $now = new DateTime();
                        $endtime = new DateTime($sessiondata['latest_survey']['ends']);

                        $timetillend = $now->diff($endtime);

                        if ($timetillend->invert) { // Survey already ended.
                            $surveyactive = false;
                            $surveyendstr = get_string('surveyended', 'mod_pingo') . '<br>' . userdate($endtime->getTimestamp());
                        } else { // Survey running.
                            $surveyactive = true;
                            $surveyendstr = get_string('surveyends', 'mod_pingo') . '<span id="endtime">' .
                                $endtime->format('Y-m-d H:i:s') . '</span>';
                        }
                    } else { // Survey has no end date.
                        $surveyactive = true;
                        $surveyendstr = get_string('surveyhasnoend', 'mod_pingo');
                    }

                    // Add section with session view.
                    $sessionview = new pingo_sessionview($cm->id, $sessiondata, $context, $activeconnection->authenticationtoken,
                        $stopsurveyform, $surveyactive, $surveyendstr);
                    echo $OUTPUT->render($sessionview);

                } else {
                    $urlparams = array('id' => $id, 'session' => $session, 'mode' => 4);
                    $redirecturl = new moodle_url('/mod/pingo/view.php', $urlparams);

                    echo '<div class="alert alert-danger alert-block fade in m-2" role="alert">';
                    echo '<button type="button" class="close" data-dismiss="alert">×</button>';
                    echo get_string('errfetching', 'mod_pingo') . '</div>';
                    echo '<a class="btn btn-primary" href="' . $redirecturl . '">' . get_string('reloadpage', 'mod_pingo') . '</a>';
                }
            }

        }

    } else { // Show form for login to PINGO.
        // Add form for PINGO login.
        $mform = new mod_pingo_login_form(new moodle_url('/mod/pingo/view.php', array('id' => $cm->id)));

        // Set default data.
        $mform->set_data(array('id' => $cm->id));

        echo $mform->render();
    }

} else { // Student view.

    if ($viewoverview && $activeconnection && $USER->id != $activeconnection->userid) {
        echo '<div class="alert alert-danger alert-block fade in m-2" role="alert">';
        echo '<button type="button" class="close" data-dismiss="alert">×</button>';
        echo get_string('errnotallowedforotherteachers', 'mod_pingo') . '</div>';
    }

    if ($activeconnection) {
        if ($activeconnection->activesession != 0 && $sessiondata = mod_pingo_api::get_session($remoteurl,
            $activeconnection->authenticationtoken, $activeconnection->activesession)) {

            if (!isset($sessiondata['description'])) {
                $sessiondata['description'] = false;
            }

            if (isset($sessiondata['latest_survey']['ends'])) { // Survey has end date.
                $now = new DateTime();
                $endtime = new DateTime($sessiondata['latest_survey']['ends']);

                $timetillend = $now->diff($endtime);

                if ($timetillend->invert) { // Survey already ended.
                    $surveyactive = false;
                    $surveyendstr = get_string('surveyended', 'mod_pingo'). '<br>' . userdate($endtime->getTimestamp());
                } else { // Survey running.
                    $surveyactive = true;
                    $surveyendstr = get_string('surveyends', 'mod_pingo') . '<span id="endtime">' .
                        $endtime->format('Y-m-d H:i:s') . '</span>';
                }
            } else { // Survey has no end date.
                $surveyactive = true;
                $surveyendstr = get_string('surveyhasnoend', 'mod_pingo');
            }

            if ($sessiondata) {
                // Add section with sessions overview.
                $participantsview = new pingo_participantsview($cm->id, array($sessiondata), $surveyactive, $surveyendstr);
                echo $OUTPUT->render($participantsview);
            } else {
                $urlparams = array('id' => $id, 'mode' => 1);
                $redirecturl = new moodle_url('/mod/pingo/view.php', $urlparams);

                echo '<div class="alert alert-danger alert-block fade in m-2" role="alert">';
                echo '<button type="button" class="close" data-dismiss="alert">×</button>';
                echo get_string('errfetching', 'mod_pingo') . '</div>';
                echo '<a class="btn btn-primary" href="' . $redirecturl . '">' . get_string('reloadpage', 'mod_pingo') . '</a>';
            }
        } else {
            echo '<strong>' . get_string('noactivesession', 'mod_pingo') . '</strong>';
        }
    } else {
        echo '<strong>' . get_string('noactivesession', 'mod_pingo') . '</strong>';
    }
}

// Output footer.
echo $OUTPUT->footer();
