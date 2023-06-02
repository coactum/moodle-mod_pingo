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

use mod_pingo\pingo_api\mod_pingo_api;

use core\output\notification;

require(__DIR__.'/../../config.php');
require_once(__DIR__.'/lib.php');

require_once(__DIR__.'/classes/pingo_api/api.php');

// Course_module ID.
$id = optional_param('id', 0, PARAM_INT);

// If current connection should be closed.
$closeconnection = optional_param('closeconnection', 0, PARAM_INT);

// Session that should be displayed.
$mode = optional_param('mode', 1, PARAM_INT);

// Session that should be displayed.
$session = optional_param('session', 0, PARAM_INT);

// Set the basic variables $course, $cm and $moduleinstance.
if ($id) {
    [$course, $cm] = get_course_and_cm_from_cmid($id, 'pingo');
    $moduleinstance = $DB->get_record('pingo', ['id' => $cm->instance], '*', MUST_EXIST);
} else {
    throw new moodle_exception('missingparameter');
}

require_login($course, true, $cm);

$context = context_module::instance($cm->id);

if ($closeconnection && $DB->record_exists('pingo_connections', array('pingo' => $moduleinstance->id))) {
    require_sesskey();

    // Trigger pingo connection closed event.
    $event = \mod_pingo\event\connection_closed::create(array(
        'objectid' => (int) $DB->get_record('pingo_connections', array('pingo' => $moduleinstance->id))->id,
        'context' => $context
    ));

    $event->trigger();

    $DB->delete_records('pingo_connections', array('pingo' => $moduleinstance->id));

}

// Check if connection is active.
$activeconnection = $DB->get_record('pingo_connections', array('pingo' => $moduleinstance->id));

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
} else if (!$session) {
    $PAGE->navbar->add(get_string("sessionsoverview", "pingo"));
    $PAGE->set_url('/mod/pingo/view.php', array('id' => $cm->id));
} else {
    $PAGE->navbar->add(get_string("sessionview", "pingo"));
    $PAGE->set_url('/mod/pingo/view.php', array('id' => $cm->id, 'session' => $session, 'mode' => $mode));
}

$PAGE->requires->js_call_amd('mod_pingo/view', 'init', array('cmid' => $cm->id));

$completion = new completion_info($course);
$completion->set_module_viewed($cm);

$PAGE->set_title(get_string('modulename', 'mod_pingo').': ' . $modulename);
$PAGE->set_heading(format_string($course->fullname));
$PAGE->set_context($context);

if (!$activeconnection) {

    // Check if new connection should be saved.
    require_once($CFG->dirroot . '/mod/pingo/login_form.php');

    // Instantiate form.
    $mform = new mod_pingo_login_form(null);

    if ($fromform = $mform->get_data()) {

        // In this case you process validated data. $mform->get_data() returns data posted in form.
        if (isset($fromform->email) && $fromform->password) { // Try login.

            // Get PINGO authentication token.
            $authtoken = mod_pingo_api::get_authtoken($remoteurl, $fromform->email, $fromform->password);

            if (isset($authtoken)) {
                $connection = new stdClass();
                $connection->pingo = (int) $cm->instance;
                $connection->userid = (int) $USER->id;
                $connection->timestarted = time();
                $connection->authenticationtoken = $authtoken;

                $newconnectionid = $DB->insert_record('pingo_connections', $connection);

                // Trigger pingo connection login successful event.
                $event = \mod_pingo\event\pingologin_successful::create(array(
                    'objectid' => $newconnectionid,
                    'context' => $context
                ));

                $event->trigger();

                // Trigger pingo connection created event.
                $event = \mod_pingo\event\connection_created::create(array(
                    'objectid' => $newconnectionid,
                    'context' => $context
                ));

                $event->trigger();

                $urlparams = array('id' => $id);
                $redirecturl = new moodle_url('/mod/pingo/view.php', $urlparams);

                redirect($redirecturl, get_string('loginsuccessful', 'mod_pingo'), null, notification::NOTIFY_SUCCESS);

            } else {
                // Trigger pingo connection login failed event.
                $event = \mod_pingo\event\pingologin_failed::create(array(
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
} else if ($mode === 2) {

    require_once($CFG->dirroot . '/mod/pingo/quickstart_form.php');

    $data = mod_pingo_api::get_quickstart_formdata($remoteurl);

    if ($data) {
        $mform = new mod_pingo_quickstart_form(null,
            array('question_types' => $data->questiontypes, 'duration_choices' => $data->durationchoices,
            'answer_options' => $data->answeroptions, 'session' => $session));

        if ($fromform = $mform->get_data()) {

            // In this case you process validated data. $mform->get_data() returns data posted in form.

            if ($fromform->session) {
                // Get session data from PINGO.
                $session = mod_pingo_api::get_session($remoteurl, $fromform->session, $activeconnection->authenticationtoken);

                if (!empty($session)) {
                    if (!isset($fromform->answer_options) || !isset($fromform->answer_options[$fromform->question_types])) {
                        $fromform->answer_options[$fromform->question_types] = false;
                    }

                    $surveycreated = mod_pingo_api::run_quickstart($remoteurl, $activeconnection->authenticationtoken, $fromform->session,
                        $fromform->question_types, $fromform->answer_options[$fromform->question_types], $fromform->duration_choices);

                    if ($surveycreated) {
                        $urlparams = array('id' => $id, 'session' => $session['token'], 'mode' => 4);
                        $redirecturl = new moodle_url('/mod/pingo/view.php', $urlparams);

                        redirect($redirecturl, get_string('surveycreated', 'mod_pingo'), null, notification::NOTIFY_SUCCESS);
                    } else {
                        $urlparams = array('id' => $id, 'session' => $session['token'], 'mode' => 2);
                        $redirecturl = new moodle_url('/mod/pingo/view.php', $urlparams);

                        redirect($redirecturl, get_string('errsurveynotcreated', 'mod_pingo'), null, notification::NOTIFY_ERROR);

                    }
                }
            }
        }
    }
} else if ($mode === 3) {
    require_once($CFG->dirroot . '/mod/pingo/questionfromcatalog_form.php');

    // Get data for form from PINGO.
    $data = mod_pingo_api::get_questionfromcatalog_formdata($remoteurl, $activeconnection->authenticationtoken);

    if ($data) {
        $mform = new mod_pingo_questionfromcatalog_form(null,
            array('questions' => $data->questions, 'duration_choices' => $data->durationchoices, 'session' => $session,
                'remoteurl' => $remoteurl));

        if ($fromform = $mform->get_data()) {
            // In this case you process validated data. $mform->get_data() returns data posted in form.

            if ($fromform->session) {
                // Get session data from PINGO.
                $session = mod_pingo_api::get_session($remoteurl, $fromform->session, $activeconnection->authenticationtoken);

                if (!empty($session)) {
                    // $surveycreated = mod_pingo_api::run_questionfromcatalog($remoteurl, $activeconnection->authenticationtoken, $fromform->session,
                    //     $fromform->question_types, $fromform->answer_options[$fromform->question_types], $fromform->duration_choices);
                    $surveycreated = false;

                    if ($surveycreated) {
                        $urlparams = array('id' => $id, 'session' => $session['token'], 'mode' => 4);
                        $redirecturl = new moodle_url('/mod/pingo/view.php', $urlparams);

                        redirect($redirecturl, get_string('surveycreated', 'mod_pingo'), null, notification::NOTIFY_SUCCESS);
                    } else {
                        $urlparams = array('id' => $id, 'session' => $session['token'], 'mode' => 3);
                        $redirecturl = new moodle_url('/mod/pingo/view.php', $urlparams);

                        redirect($redirecturl, get_string('errsurveynotcreated', 'mod_pingo'), null, notification::NOTIFY_ERROR);

                    }
                }
            }
        }
    }
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

// Get grading of current user when pingo is rated.
/* if ($moduleinstance->assessed != 0) {
    $ratingaggregationmode = helper::get_pingo_aggregation($moduleinstance->assessed) . ' ' .
        get_string('forallmyentries', 'mod_pingo');
    $gradinginfo = grade_get_grades($course->id, 'mod', 'pingo', $moduleinstance->id, $USER->id);
    $userfinalgrade = $gradinginfo->items[0]->grades[$USER->id];
    $currentuserrating = $userfinalgrade->str_long_grade;
} else {
    $ratingaggregationmode = false;
    $currentuserrating = false;
} */

// Handle groups.
echo groups_print_activity_menu($cm, $CFG->wwwroot . "/mod/pingo/view.php?id=$id");

if ($viewoverview) { // Teacher view.

    // Add section for connectioninfo.
    $connectioninfo = new pingo_connectioninfo($cm->id, $activeconnection);
    echo $OUTPUT->render($connectioninfo);

    if ($activeconnection) { // Show content from PINGO.

        // Trigger pingo connection viewed event.
        $event = \mod_pingo\event\connection_viewed::create(array(
            'objectid' => (int) $activeconnection->id,
            'context' => $context
        ));

        $event->trigger();

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
                $tabs->active->catalog = true;
                break;
            case 4:
                $tabs->active->session = true;
                break;
        }

        $tabarea = new pingo_tabarea($cm->id, $tabs, $session);
        echo $OUTPUT->render($tabarea);

        // Show content.
        if ($mode === 1  && $viewallsessions) { // View sessions overview.

            // Get sessions data.
            $sessions = mod_pingo_api::get_sessions($remoteurl, $activeconnection->authenticationtoken);

            if ($sessions) {
                // Add section with sessions overview.
                $sessionsoverview = new pingo_sessionsoverview($cm->id, $sessions);
                echo $OUTPUT->render($sessionsoverview);
            } else {
                $urlparams = array('id' => $id, 'mode' => 1);
                $redirecturl = new moodle_url('/mod/pingo/view.php', $urlparams);

                echo '<div class="alert alert-danger alert-block fade in m-2" role="alert"><button type="button" class="close" data-dismiss="alert">×</button>' . get_string('errfetching', 'mod_pingo') . '</div>';
                echo '<a class="btn btn-primary" href="' . $redirecturl . '">' . get_string('reloadpage', 'mod_pingo') . '</a>';
            }

        } else if ($mode === 2) {

            // Get data for form from PINGO.
            $data = mod_pingo_api::get_quickstart_formdata($remoteurl);

            if ($data) {
                // Add form.
                $mform = new mod_pingo_quickstart_form(new moodle_url('/mod/pingo/view.php', array('id' => $cm->id)),
                    array('question_types' => $data->questiontypes, 'duration_choices' => $data->durationchoices,
                    'answer_options' => $data->answeroptions, 'session' => $session));

                // Set default data.
                $mform->set_data(array('id' => $cm->id, 'session' => $session));

                // Render form.
                echo $mform->render();
            } else {
                $urlparams = array('id' => $id, 'session' => $session, 'mode' => 2);
                $redirecturl = new moodle_url('/mod/pingo/view.php', $urlparams);

                echo '<div class="alert alert-danger alert-block fade in m-2" role="alert"><button type="button" class="close" data-dismiss="alert">×</button>' . get_string('errfetching', 'mod_pingo') . '</div>';
                echo '<a class="btn btn-primary" href="' . $redirecturl . '">' . get_string('reloadpage', 'mod_pingo') . '</a>';
            }

        } else if ($mode === 3) {

            // Get data for form from PINGO.
            $data = mod_pingo_api::get_questionfromcatalog_formdata($remoteurl, $activeconnection->authenticationtoken);

            if ($data) {
                // Add form.
                $mform = new mod_pingo_questionfromcatalog_form(new moodle_url('/mod/pingo/view.php', array('id' => $cm->id)),
                    array('questions' => $data->questions, 'duration_choices' => $data->durationchoices, 'session' => $session, 'remoteurl' => $remoteurl));

                // Set default data.
                $mform->set_data(array('id' => $cm->id, 'session' => $session));

                // Render form.
                echo $mform->render();
            } else {
                $urlparams = array('id' => $id, 'session' => $session, 'mode' => 3);
                $redirecturl = new moodle_url('/mod/pingo/view.php', $urlparams);

                echo '<div class="alert alert-danger alert-block fade in m-2" role="alert"><button type="button" class="close" data-dismiss="alert">×</button>' . get_string('errfetching', 'mod_pingo') . '</div>';
                echo '<a class="btn btn-primary" href="' . $redirecturl . '">' . get_string('reloadpage', 'mod_pingo') . '</a>';
            }

        } else if ($mode === 4) {
            if ($session) {

                // Get session data from PINGO.
                $sessiondata = mod_pingo_api::get_session($remoteurl, $session, $activeconnection->authenticationtoken);

                if ($sessiondata) {
                    // Add section with session view.
                    $sessionview = new pingo_sessionview($cm->id, $sessiondata, $context, $activeconnection->authenticationtoken);
                    echo $OUTPUT->render($sessionview);
                } else {
                    $urlparams = array('id' => $id, 'session' => $session, 'mode' => 4);
                    $redirecturl = new moodle_url('/mod/pingo/view.php', $urlparams);

                    echo '<div class="alert alert-danger alert-block fade in m-2" role="alert"><button type="button" class="close" data-dismiss="alert">×</button>' . get_string('errfetching', 'mod_pingo') . '</div>';
                    echo '<a class="btn btn-primary" href="' . $redirecturl . '">' . get_string('reloadpage', 'mod_pingo') . '</a>';
                }
            }

        }

    } else { // Show from for login to PINGO.
        // Add form for PINGO login.
        $mform = new mod_pingo_login_form(new moodle_url('/mod/pingo/view.php', array('id' => $cm->id)));

        // Set default data.
        $mform->set_data(array('id' => $cm->id, 'email' => 'b3855300@urhen.com', 'password' => 'Supergeheimespasswort1!'));

        echo $mform->render();

    }

} else { // Student view.

    $activesurvey = false;

    //var_dump($activeconnection);

    if ($activeconnection) {
        // Print QR code to survey;
        require_once($CFG->libdir.'/pdflib.php');

        $surveyid = '029725';

        $pdf = new TCPDF;

        // Set document information.
        $pdf->SetCreator(PDF_CREATOR);
        /* $pdf->SetAuthor($exammanagementinstanceobj->getMoodleSystemName());
        $pdf->SetTitle(get_string('examlabels', 'mod_exammanagement') . ': ' . $exammanagementinstanceobj->getCourse()->fullname . ', '. $exammanagementinstanceobj->moduleinstance->name);
        $pdf->SetSubject(get_string('examlabels', 'mod_exammanagement'));
        $pdf->SetKeywords(get_string('examlabels', 'mod_exammanagement') . ', ' . $exammanagementinstanceobj->getCourse()->fullname . ', ' . $exammanagementinstanceobj->moduleinstance->name); */

        $styleqr = array(
            'border' => false,
            'padding' => 0,
            'fgcolor' => array(0, 0, 0),
            'bgcolor' => false
        );

        $pdf->write2DBarcode($remoteurl . '/' . $surveyid, 'QRCODE,Q', 0 + 25, 0 + 18, 25, 25, $styleqr, 'N');

        //Close and output PDF document.
        // ob_start();
        // // All other content
        // ob_end_clean();
        //$pdf->Output('pingo_qrcode_' . $surveyid . '.pdf', 'I');

    } else {
        echo get_string('nosurveyactive', 'mod_pingo');
    }
}

// Output footer.
echo $OUTPUT->footer();
