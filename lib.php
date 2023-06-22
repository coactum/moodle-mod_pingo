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
 * Library of interface functions and constants.
 *
 * @package     mod_pingo
 * @copyright   2023 coactum GmbH
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Indicates API features that the plugin supports.
 *
 * @uses FEATURE_MOD_INTRO
 * @uses FEATURE_SHOW_DESCRIPTION
 * @uses FEATURE_GRADE_HAS_GRADE
 * @uses FEATURE_COMPLETION_TRACKS_VIEWS
 * @uses FEATURE__BACKUP_MOODLE2
 * @param string $feature Constant for requested feature.
 * @return mixed True if module supports feature, null if it doesn't.
 */
function pingo_supports($feature) {

    // Adding support for FEATURE_MOD_PURPOSE (MDL-71457) and providing backward compatibility (pre-v4.0).
    if (defined('FEATURE_MOD_PURPOSE') && $feature === FEATURE_MOD_PURPOSE) {
        return MOD_PURPOSE_COLLABORATION;
    }

    switch ($feature) {
        case FEATURE_MOD_INTRO:
            return true;
        case FEATURE_SHOW_DESCRIPTION:
            return true;
        case FEATURE_COMPLETION_TRACKS_VIEWS:
            return true;
        case FEATURE_BACKUP_MOODLE2:
            return true;

        default:
            return null;
    }
}

/**
 * Saves a new instance of the plugin into the database.
 *
 * Given an object containing all the necessary data,
 * (defined by the form in mod_form.php) this function
 * will create a new instance and return the id number
 * of the new instance.
 *
 * @param object $moduleinstance An object from the form.
 * @param mod_pingo_mod_form $mform The form.
 * @return int The id of the newly inserted record.
 */
function pingo_add_instance($moduleinstance, $mform = null) {
    global $DB;

    $moduleinstance->timecreated = time();

    $moduleinstance->id = $DB->insert_record('pingo', $moduleinstance);

    return $moduleinstance->id;
}

/**
 * Updates an instance of the plugin in the database.
 *
 * Given an object containing all the necessary data (defined in mod_form.php),
 * this function will update an existing instance with new data.
 *
 * @param object $moduleinstance An object from the form in mod_form.php.
 * @param mod_pingo_mod_form $mform The form.
 * @return bool True if successful, false otherwise.
 */
function pingo_update_instance($moduleinstance, $mform = null) {
    global $DB;

    $moduleinstance->timemodified = time();
    $moduleinstance->id = $moduleinstance->instance;

    $DB->update_record('pingo', $moduleinstance);

    return true;
}

/**
 * Removes an instance of the plugin from the database.
 *
 * Given an ID of an instance of this module,
 * this function will permanently delete the instance
 * and any data that depends on it.
 *
 * @param int $id Id of the module instance.
 * @return bool True if successful, false on failure.
 */
function pingo_delete_instance($id) {
    global $DB;

    if (!$pingo = $DB->get_record('pingo', array('id' => $id))) {
        return false;
    }
    if (!$cm = get_coursemodule_from_instance('pingo', $pingo->id)) {
        return false;
    }
    if (!$course = $DB->get_record('course', array('id' => $cm->course))) {
        return false;
    }

    // Delete pingo connections.
    if ($DB->record_exists('pingo_connections', array('pingo' => $id))) {
        $DB->delete_records('pingo_connections', array('pingo' => $id));
    }

    // Delete pingo, else return false.
    if (!$DB->delete_records("pingo", array("id" => $pingo->id))) {
        return false;
    }

    return true;
}


/**
 * Returns a small object with summary information about what a
 * user has done with a given particular instance of this module
 * Used for user activity reports.
 * $return->time = the time they did it
 * $return->info = a short text description
 *
 * @param object $course The course object.
 * @param object $user The user object.
 * @param object $mod The modulename.
 * @param object $pingo The plugin instance.
 * @return object A standard object with 2 variables: info and time (last modified)
 */
function pingo_user_outline($course, $user, $mod, $pingo) {
    $return = new stdClass();
    $return->time = time();
    $return->info = '';
    return $return;
}

/**
 * Implementation of the function for printing the form elements that control
 * whether the course reset functionality affects the module (called by course/reset.php).
 *
 * @param object $mform Form passed by reference.
 */
function pingo_reset_course_form_definition(&$mform) {
    $mform->addElement('header', 'pingoheader', get_string('modulenameplural', 'mod_pingo'));

    $mform->addElement('checkbox', 'reset_pingo_all', get_string('deletealluserdata', 'mod_pingo'));
}

/**
 * Course reset form defaults.
 *
 * @param object $course Course object.
 * @return array
 */
function pingo_reset_course_form_defaults($course) {
    return array('reset_pingo_all' => 1);
}

/**
 * This function is used by the reset_course_userdata function in moodlelib.
 * This function will remove all userdata from the specified pingo.
 *
 * @param object $data The data submitted from the reset course.
 * @return array status array
 */
function pingo_reset_userdata($data) {
    global $CFG, $DB;

    require_once($CFG->libdir . '/filelib.php');
    require_once($CFG->dirroot . '/rating/lib.php');

    $componentstr = get_string('modulenameplural', 'pingo');
    $status = array();

    // Get pingos in course that should be resetted.
    $sql = "SELECT p.id
                FROM {pingo} p
                WHERE p.course = ?";

    $params = array(
        $data->courseid
    );

    $pingos = $DB->get_records_sql($sql, $params);

    // Delete pingo connections.
    if (!empty($data->reset_pingo_all)) {

        $DB->delete_records_select('pingo_connections', "pingo IN ($sql)", $params);

        $status[] = array(
            'component' => $modulename,
            'item' => get_string('alluserdatadeleted', 'pingo'),
            'error' => false
        );
    }

    // Updating dates - shift may be negative too.
    if ($data->timeshift) {
        // Any changes to the list of dates that needs to be rolled should be same during course restore and course reset.
        // See MDL-9367.
        shift_course_mod_dates('pingo', array(''), $data->timeshift, $data->courseid);
        $status[] = array('component' => $componentstr, 'item' => get_string('datechanged'), 'error' => false);
    }

    return $status;
}

/**
 * File browsing support for mod_pingo file areas (for attachements?).
 *
 * @package     mod_pingo
 * @category    files
 *
 * @param file_browser $browser
 * @param array $areas
 * @param stdClass $course
 * @param stdClass $cm
 * @param stdClass $context
 * @param string $filearea
 * @param int $itemid
 * @param string $filepath
 * @param string $filename
 * @return file_info Instance or null if not found
 */
function pingo_get_file_info($browser, $areas, $course, $cm, $context, $filearea, $itemid, $filepath, $filename) {
    return null;
}

/**
 * Serves the files from the plugins file areas.
 *
 * @package     mod_pingo
 * @category    files
 *
 * @param stdClass $course The course object.
 * @param stdClass $cm The course module object.
 * @param stdClass $context The mod_pingo's context.
 * @param string $filearea The name of the file area.
 * @param array $args Extra arguments (itemid, path).
 * @param bool $forcedownload Whether or not force download.
 * @param array $options Additional options affecting the file serving.
 * @return bool false if file not found, does not return if found - just sends the file.
 */
function pingo_pluginfile($course, $cm, $context, $filearea, $args, $forcedownload, $options = array()) {
    global $DB, $CFG;

    if ($context->contextlevel != CONTEXT_MODULE) {
        send_file_not_found();
    }

    require_login($course, true, $cm);

    if (! $course->visible && ! has_capability('moodle/course:viewhiddencourses', $context)) {
        return false;
    }

    $areas = pingo_get_file_areas($course, $cm, $context);

    // Filearea must contain a real area.
    if (!isset($areas[$filearea])) {
        return false;
    }

    send_file_not_found();
}

/**
 * Extends the global navigation tree by adding mod_pingo nodes if there is a relevant content.
 *
 * This can be called by an AJAX request so do not rely on $PAGE as it might not be set up properly.
 *
 * @param navigation_node $pingonode An object representing the navigation tree node.
 * @param  stdClass $course Course object
 * @param  context_course $coursecontext Course context
 */
function pingo_extend_navigation_course($pingonode, $course, $coursecontext) {
    $modinfo = get_fast_modinfo($course); // Get mod_fast_modinfo from $course.
    $index = 1; // Set index.
    foreach ($modinfo->get_cms() as $cmid => $cm) { // Search existing course modules for this course.
        if ($index == 1 && $cm->modname == "pingo" && $cm->uservisible && $cm->available) {
            $url = new moodle_url("/mod/" . $cm->modname . "/index.php",
                array("id" => $course->id)); // Set url for the link in the navigation node.
            $node = navigation_node::create(get_string('viewallpingos', 'pingo'), $url,
                navigation_node::TYPE_CUSTOM, null , null , null);
            $pingonode->add_node($node);
            $index++;
        }
    }
}

/**
 * Extends the settings navigation with the mod_pingo settings.
 *
 * This function is called when the context for the page is a mod_pingo module.
 * This is not called by AJAX so it is safe to rely on the $PAGE.
 *
 * @param settings_navigation $settingsnav
 * @param navigation_node $pingonode
 */
function pingo_extend_settings_navigation($settingsnav, $pingonode = null) {
}
