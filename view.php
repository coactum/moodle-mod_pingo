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

require(__DIR__.'/../../config.php');
require_once(__DIR__.'/lib.php');

// Course_module ID.
$id = optional_param('id', 0, PARAM_INT);

// Set the basic variables $course, $cm and $moduleinstance.
if ($id) {
    [$course, $cm] = get_course_and_cm_from_cmid($id, 'pingo');
    $moduleinstance = $DB->get_record('pingo', ['id' => $cm->instance], '*', MUST_EXIST);
} else {
    throw new moodle_exception('missingparameter');
}

require_login($course, true, $cm);

$context = context_module::instance($cm->id);

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

// Render and output page.
$page = new pingo_view($cm);

echo $OUTPUT->render($page);

// Output footer.
echo $OUTPUT->footer();
