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

 use mod_pingo\output\pingo_sessioninfo;

 use core\output\notification;

 use GuzzleHttp\Client;

require(__DIR__.'/../../config.php');
require_once(__DIR__.'/lib.php');

// Course_module ID.
$id = optional_param('id', 0, PARAM_INT);

// If current session should be closed.
$sessionlogout = optional_param('sessionlogout', 0, PARAM_INT);


// Set the basic variables $course, $cm and $moduleinstance.
if ($id) {
    [$course, $cm] = get_course_and_cm_from_cmid($id, 'pingo');
    $moduleinstance = $DB->get_record('pingo', ['id' => $cm->instance], '*', MUST_EXIST);
} else {
    throw new moodle_exception('missingparameter');
}

require_login($course, true, $cm);

$context = context_module::instance($cm->id);

if ($sessionlogout && $DB->record_exists('pingo_sessions', array('pingo' => $moduleinstance->id))) {
    $DB->delete_records('pingo_sessions', array('pingo' => $moduleinstance->id));
}

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
$PAGE->set_url('/mod/pingo/view.php', array('id' => $cm->id));

$PAGE->navbar->add(get_string("overview", "pingo"));

$completion = new completion_info($course);
$completion->set_module_viewed($cm);

$PAGE->set_title(get_string('modulename', 'mod_pingo').': ' . $modulename);
$PAGE->set_heading(format_string($course->fullname));
$PAGE->set_context($context);


// Check if session is active.
$activesession = $DB->get_record('pingo_sessions', array('pingo' => $moduleinstance->id));

if (!$activesession) {

    // Check if new session should be saved.
    require_once($CFG->dirroot . '/mod/pingo/login_form.php');

    // Instantiate form.
    $mform = new mod_pingo_login_form(null);

    if ($fromform = $mform->get_data()) {

        // In this case you process validated data. $mform->get_data() returns data posted in form.
        if (isset($fromform->username) && $fromform->password) { // Try login.

            // Magic code making guzzle request to pingo and returning session_token if login succeded.

             ############### TESTING CURL ###############
            // $url = "{" . get_config('pingo', 'remoteserver') . "}/login";

            // $data = array(
            //     'username' => $fromform->username,
            //     'passwort' => $fromform->password,
            // );

            // $jsondata = json_encode($data);

            // $options = array(
            //     'RETURNTRANSFER' => 1,
            //     'HEADER' => 0,
            //     'FAILONERROR' => 1,
            // );

            // $header = array(
            //     'Content-Type: application/json',
            //     'Content-Length: ' . strlen($jsondata),
            //     'Accept: application/json',
            //     "{111}:{111}"
            // );

            // $curl = new \curl();
            // $curl->setHeader($header);
            // $jsonresult = $curl->post($url, $jsondata, $options);

            // var_dump($jsonresult);

            // $result = json_decode($jsonresult);


            // var_dump($result);

             ############### TESTING GUZZLE ###############
            // $httpclient = new GuzzleHttp\Client();

            // $response = $httpclient->request('GET', 'https://example.com/api/resource');
            // $statuscode = $response->getStatusCode();
            // $body = $response->getBody()->getContents();
            // var_dump($statuscode);



            $sessiontoken = false;
            $sessionid = false;

            $sessiontoken = 'ansu89a9';
            $sessionid = 1;


            if ($sessionid && $sessiontoken) {
                $session = new stdClass();
                $session->pingo = (int) $cm->instance;
                $session->userid = (int) $USER->id;
                $session->timestarted = time();
                $session->sessionid = $sessionid;
                $session->sessiontoken = $sessiontoken;

                $newsessionid = $DB->insert_record('pingo_sessions', $session);

                // Trigger pingo session login successfull event.
                $event = \mod_pingo\event\pingologin_successful::create(array(
                    'objectid' => $sessionid,
                    'context' => $context
                ));

                $event->trigger();

                // Trigger pingo session created event.
                $event = \mod_pingo\event\session_created::create(array(
                    'objectid' => $newsessionid,
                    'context' => $context
                ));

                $event->trigger();

                $urlparams = array('id' => $id);
                $redirecturl = new moodle_url('/mod/pingo/view.php', $urlparams);

                redirect($redirecturl, get_string('loginsuccessful', 'mod_pingo'), null, notification::NOTIFY_SUCCESS);

            } else {
                // Trigger pingo session login failed event.
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

$viewsessionsoverview = has_capability('mod/pingo:viewsessionsoverview', $context);

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

if ($viewsessionsoverview) { // Teacher view.

    // Add section for sessioninfo.
    $sessioninfo = new pingo_sessioninfo($cm->id, $activesession);
    echo $OUTPUT->render($sessioninfo);

    if ($activesession) {
        // Render response from guzzle request with sessions list from pingo.
    } else {
        // Add form for pingo login.
        $mform = new mod_pingo_login_form(new moodle_url('/mod/pingo/view.php', array('id' => $cm->id)));

        // Set default data.
        $mform->set_data(array('id' => $cm->id, 'username' => $USER->username));

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
