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
 use mod_pingo\output\pingo_sessionsoverview;
 use mod_pingo\output\pingo_sessionview;

 use core\output\notification;

require(__DIR__.'/../../config.php');
require_once(__DIR__.'/lib.php');

// Course_module ID.
$id = optional_param('id', 0, PARAM_INT);

// If current connection should be closed.
$closeconnection = optional_param('closeconnection', 0, PARAM_INT);

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
    $DB->delete_records('pingo_connections', array('pingo' => $moduleinstance->id));
}

// Check if connection is active.
$activeconnection = $DB->get_record('pingo_connections', array('pingo' => $moduleinstance->id));

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
    $PAGE->set_url('/mod/pingo/view.php', array('id' => $cm->id, 'session' => $session));
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

            // Requesting authentication_token from PINGO for email and password from the form.
            $url = get_config('pingo', 'remoteserver') . "/api/get_auth_token";

            $data = 'password=' . urlencode($fromform->password) . '&email=' . urlencode($fromform->email);

            $options = array(
                'RETURNTRANSFER' => 1,
                'HEADER' => 0,
                'FAILONERROR' => 1,
            );

            $header = array(
                'Content-Type: application/x-www-form-urlencoded',
                'Content-Length: ' . strlen($data),
                'Accept: application/json'
            );

            $curl = new \curl();
            $curl->setHeader($header);
            $jsonresult = $curl->post($url, $data, $options);

            $result = json_decode($jsonresult, true);

            // var_dump($result['authentication_token']);

            if (isset($result['authentication_token'])) {
                $connection = new stdClass();
                $connection->pingo = (int) $cm->instance;
                $connection->userid = (int) $USER->id;
                $connection->timestarted = time();
                $connection->authenticationtoken = $result['authentication_token'];

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

    if ($activeconnection) {

        if (!$session) {

            // Requesting sessions list from PINGO .
            $url = get_config('pingo', 'remoteserver') . "/events?auth_token=" . $activeconnection->authenticationtoken;

            $data = '';

            $options = array(
                'RETURNTRANSFER' => 1,
                'HEADER' => 0,
                'FAILONERROR' => 1,
            );

            $header = array(
                'Content-Type: application/x-www-form-urlencoded',
                'Content-Length: ' . strlen($data),
                'Accept: application/json'
            );

            $curl = new \curl();
            $curl->setHeader($header);
            $jsonresult = $curl->get($url, $data, $options);

            $sessions = json_decode($jsonresult, true);

            // Add section with sessions overview.
            $sessionsoverview = new pingo_sessionsoverview($cm->id, $sessions);
            echo $OUTPUT->render($sessionsoverview);
        } else {
            // Requesting session from PINGO .
            $url = get_config('pingo', 'remoteserver') . "/events/$session/?auth_token=" . $activeconnection->authenticationtoken;

            $data = '';

            $options = array(
                'RETURNTRANSFER' => 1,
                'HEADER' => 0,
                'FAILONERROR' => 1,
            );

            $header = array(
                'Content-Type: application/x-www-form-urlencoded',
                'Content-Length: ' . strlen($data),
                'Accept: application/json'
            );

            $curl = new \curl();
            $curl->setHeader($header);
            $jsonresult = $curl->get($url, $data, $options);

            $session = json_decode($jsonresult, true);

            // Add section with session view.
            $sessionview = new pingo_sessionview($cm->id, $session);
            echo $OUTPUT->render($sessionview);
            var_dump($activeconnection->authenticationtoken);

            // echo '<pre>' , var_dump($session) , '</pre>';
        }

    } else {
        // Add form for pingo login.
        $mform = new mod_pingo_login_form(new moodle_url('/mod/pingo/view.php', array('id' => $cm->id)));

        // Set default data.
        $mform->set_data(array('id' => $cm->id, 'email' => 'b3855300@urhen.com', 'password' => 'Supergeheimespasswort1!'));
        // $mform->set_data(array('id' => $cm->id));

        echo $mform->render();

    }

} else { // Student view.
    $activesurvey = false;

    if ($activesurvey) {
        // Print QR code to survey;
    } else {
        echo get_string('nosurveyactive', 'mod_pingo');
    }
}

// Output footer.
echo $OUTPUT->footer();
