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
 * Privacy subsystem implementation for pingo.
 *
 * @package    mod_pingo
 * @copyright  2023 coactum GmbH
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace mod_pingo\privacy;

use \core_privacy\local\request\userlist;
use \core_privacy\local\request\approved_contextlist;
use \core_privacy\local\request\approved_userlist;
use \core_privacy\local\request\writer;
use \core_privacy\local\request\helper;
use \core_privacy\local\metadata\collection;
use \core_privacy\local\request\transform;
use \core_privacy\local\request\contextlist;

use \core_privacy\local\request\user_preference_provider;


/**
 * Implementation of the privacy subsystem plugin provider for the pingo activity module.
 *
 * @copyright  2023 coactum GmbH
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class provider implements
    // This plugin has data.
    \core_privacy\local\metadata\provider,

    // This plugin currently implements the original plugin\provider interface.
    \core_privacy\local\request\plugin\provider,

    // This plugin is capable of determining which users have data within it.
    \core_privacy\local\request\core_userlist_provider,

    \core_privacy\local\request\user_preference_provider {

    /**
     * Provides the meta data stored for a user stored by the plugin.
     *
     * @param   collection     $items The initialised collection to add items to.
     * @return  collection     A listing of user data stored through this system.
     */
    public static function get_metadata(collection $items) : collection {

        /* // The table 'pingo_participants' stores all pingo participants and their data.
        $items->add_database_table('pingo_participants', [
            'pingo' => 'privacy:metadata:pingo_participants:pingo',
        ], 'privacy:metadata:pingo_participants');

        // The table 'pingo_submissions' stores all group subbissions.
        $items->add_database_table('pingo_submissions', [
            'pingo' => 'privacy:metadata:pingo_submissions:pingo',
        ], 'privacy:metadata:pingo_submissions');

        // The plguin uses multiple subsystems that save personal data.
        $items->add_subsystem_link('core_files', [], 'privacy:metadata:core_files');
        $items->add_subsystem_link('core_rating', [], 'privacy:metadata:core_rating');
        $items->add_subsystem_link('core_message', [], 'privacy:metadata:core_message');

        // User preferences in the plugin.
        $items->add_user_preference('pingo_sortoption', 'privacy:metadata:preference:pingo_sortoption'); */

        return $items;
    }

    /**
     * Get the list of contexts that contain user information for the specified user.
     *
     * In this case of all pingos where a user is exam participant.
     *
     * @param   int         $userid     The user to search.
     * @return  contextlist $contextlist  The contextlist containing the list of contexts used in this plugin.
     */
    public static function get_contexts_for_userid(int $userid) : contextlist {
        $contextlist = new contextlist();

        $params = [
            'modulename' => 'pingo',
            'contextlevel' => CONTEXT_MODULE,
            'userid' => $userid,
        ];

        // Get contexts of ... .

        $sql;
        /* $sql = "SELECT c.id
                  FROM {context} c
                  JOIN {course_modules} cm ON cm.id = c.instanceid AND c.contextlevel = :contextlevel
                  JOIN {modules} m ON m.id = cm.module AND m.name = :modulename
                  JOIN {pingo} e ON e.id = cm.instance
                  JOIN {pingo_participants} p ON p.pingo = e.id
                  WHERE p.moodleuserid = :userid
        "; */

        $contextlist->add_from_sql($sql, $params);

        return $contextlist;
    }

    /**
     * Get the list of users within a specific context.
     *
     * @param   userlist    $userlist   The userlist containing the list of users who have data in this context/plugin combination.
     */
    public static function get_users_in_context(userlist $userlist) {
        $context = $userlist->get_context();

        if (!is_a($context, \context_module::class)) {
            return;
        }

        $params = [
            'instanceid' => $context->id,
            'modulename' => 'pingo',
        ];

        // Get users.
        $sql;
        /* $sql = "SELECT p.moodleuserid
                  FROM {course_modules} cm
                  JOIN {modules} m ON m.id = cm.module AND m.name = :modulename
                  JOIN {pingo} e ON e.id = cm.instance
                  JOIN {pingo_participants} p ON p.pingo = e.id
                 WHERE cm.id = :instanceid"; */
        $userlist->add_from_sql('userid', $sql, $params);
    }

    /**
     * Export all user data for the specified user, in the specified contexts.
     *
     * @param   approved_contextlist    $contextlist    The approved contexts to export information for.
     */
    public static function export_user_data(approved_contextlist $contextlist) {
        global $DB;

        if (empty($contextlist)) {
            return;
        }

        $user = $contextlist->get_user();
        $userid = $user->id;

        list($contextsql, $contextparams) = $DB->get_in_or_equal($contextlist->get_contextids(), SQL_PARAMS_NAMED);
        $params = $contextparams;

        /* $sql = "SELECT
                c.id AS contextid,
                p.*,
                cm.id AS cmid
            FROM {context} c
            JOIN {course_modules} cm ON cm.id = c.instanceid
            JOIN {pingo} p ON P.id = cm.instance
            WHERE (
                c.id {$contextsql}
            )
        ";

        $pingos = $DB->get_recordset_sql($sql, $params);

        if ($pingos->valid()) {
            foreach ($pingos as $pingo) {

                if ($pingo) {
                    $context = \context::instance_by_id($pingo->contextid);

                    // Store the main pingo data.
                    $contextdata = helper::get_context_data($context, $user);

                    // Write it.
                    writer::with_context($context)->export_data([], $contextdata);

                    // Write generic module intro files.
                    helper::export_context_files($context, $user);

                    self::export_entries_data($userid, $pingo->id, $pingo->contextid);

                    self::export_annotations_data($userid, $pingo->id, $pingo->contextid);

                }

            }
        }

        $pingos->close(); */
    }

    /**
     * Store all information about all ....
     *
     * @param   int         $userid The userid of the user whose data is to be exported.
     * @param   int         $pingoid The id of the pingo.
     * @param   int         $pingocontextid The context id of the pingo.
     */
    /* protected static function export_entries_data(int $userid, $pingoid, $pingocontextid) {
        global $DB;

        // Find all entries for this pingo written by the user.
        $sql = "SELECT
                    e.id,
                    e.pingo,
                    e.userid,
                    e.timecreated,
                    e.timemodified,
                    e.text,
                    e.format,
                    e.rating,
                    e.feedback,
                    e.formatfeedback,
                    e.teacher,
                    e.timemarked,
                    e.baseentry
                   FROM {pingo_entries} e
                   WHERE (
                    e.pingo = :pingoid AND
                    e.userid = :userid
                    )
        ";

        $params['userid'] = $userid;
        $params['pingoid'] = $pingoid;

        // Get the pingos from the entries.
        $entries = $DB->get_recordset_sql($sql, $params);

        if ($entries->valid()) {
            foreach ($entries as $entry) {
                if ($entry) {
                    $context = \context::instance_by_id($pingocontextid);

                    self::export_entry_data($userid, $context, ['pingo-entry-' . $entry->id], $entry);
                }
            }
        }

        $entries->close();
    } */

    /**
     * Export all data in the entry.
     *
     * @param   int         $userid The userid of the user whose data is to be exported.
     * @param   \context    $context The instance of the pingo context.
     * @param   array       $subcontext The location within the current context that this data belongs.
     * @param   \stdClass   $entry The entry.
     */
    /* protected static function export_entry_data(int $userid, \context $context, $subcontext, $entry) {

        if ($entry->timecreated != 0) {
            $timecreated = transform::datetime($entry->timecreated);
        } else {
            $timecreated = null;
        }

        if ($entry->timemodified != 0) {
            $timemodified = transform::datetime($entry->timemodified);
        } else {
            $timemodified = null;
        }

        if ($entry->timemarked != 0) {
            $timemarked = transform::datetime($entry->timemarked);
        } else {
            $timemarked = null;
        }

        // Store related metadata.
        $entrydata = (object) [
            'pingo' => $entry->pingo,
            'userid' => $entry->userid,
            'timecreated' => $timecreated,
            'timemodified' => $timemodified,
            'rating' => $entry->rating,
            'teacher' => $entry->teacher,
            'timemarked' => $timemarked,
            'baseentry' => $entry->baseentry,
        ];

        $entrydata->text = writer::with_context($context)->rewrite_pluginfile_urls($subcontext, 'mod_pingo',
            'entry', $entry->id, $entry->text);

        $entrydata->text = format_text($entrydata->text, $entry->format, (object) [
            'para' => false,
            'context' => $context,
        ]);

        $entrydata->feedback = writer::with_context($context)->rewrite_pluginfile_urls($subcontext, 'mod_pingo',
            'feedback', $entry->id, $entry->feedback);

        $entrydata->feedback = format_text($entrydata->feedback, $entry->formatfeedback, (object) [
            'para' => false,
            'context' => $context,
        ]);

        // Store the entry data.
        writer::with_context($context)
            ->export_data($subcontext, $entrydata)
            ->export_area_files($subcontext, 'mod_pingo', 'entry', $entry->id)
            ->export_area_files($subcontext, 'mod_pingo', 'feedback', $entry->id);

        // Store all ratings against this entry as the entry belongs to the user. All ratings on it are ratings of their content.
        \core_rating\privacy\provider::export_area_ratings($userid, $context, $subcontext, 'mod_pingo',
            'entry', $entry->id, false);
    } */

    /**
     * Store all user preferences for the plugin.
     *
     * @param   int         $userid The userid of the user whose data is to be exported.
     */
    /* public static function export_user_preferences(int $userid) {
        $user = \core_user::get_user($userid);

        if ($pingosortoption = get_user_preferences('pingo_sortoption', 0, $userid)) {
            switch ($pingosortoption) {
                case 1:
                    $sortoption = get_string('currenttooldest', 'mod_pingo');
                    break;
                case 2:
                    $sortoption = get_string('oldesttocurrent', 'mod_pingo');
                    break;
                case 3:
                    $sortoption = get_string('lowestgradetohighest', 'mod_pingo');
                    break;
                case 4:
                    $sortoption = get_string('highestgradetolowest', 'mod_pingo');
                    break;
                default:
                    $sortoption = get_string('currenttooldest', 'mod_pingo');
                    break;
            }

            writer::export_user_preference('mod_pingo', 'pingo_sortoption', $pingosortoption, $sortoption);
        }

        if ($pingopagecount = get_user_preferences('pingo_pagecount', 0, $userid)) {
            writer::export_user_preference('mod_pingo', 'pingo_pagecount', $pingopagecount,
                get_string('privacy:metadata:preference:pingo_pagecount', 'mod_pingo'));
        }

        if ($pingoactivepage = get_user_preferences('pingo_activepage', 0, $userid)) {
            writer::export_user_preference('mod_pingo', 'pingo_activepage', $pingoactivepage,
                get_string('privacy:metadata:preference:pingo_activepage', 'mod_pingo'));
        }
    } */


    /**
     * Delete all data for all users in the specified context.
     *
     * @param context $context The specific context to delete data for.
     */
    public static function delete_data_for_all_users_in_context(\context $context) {
        global $DB;

        // Check that this is a context_module.
        if (!$context instanceof \context_module) {
            return;
        }

        // Get the course module.
        if (!$cm = get_coursemodule_from_id('pingo', $context->instanceid)) {
            return;
        }

        // Delete advanced grading information (not implemented yet).

        /* // Delete all ratings in the context.
        \core_rating\privacy\provider::delete_ratings($context, 'mod_pingo', 'entry');

        // Delete all files from the entry.
        $fs = get_file_storage();
        $fs->delete_area_files($context->id, 'mod_pingo', 'entry');
        $fs->delete_area_files($context->id, 'mod_pingo', 'feedback');

        // Delete all records.
        if ($DB->record_exists('pingo_participants', ['pingo' => $cm->instance])) {
            $DB->delete_records('pingo_participants', ['pingo' => $cm->instance]);
        }

        if ($DB->record_exists('pingo_submissions', ['pingo' => $cm->instance])) {
            $DB->delete_records('pingo_submissions', ['pingo' => $cm->instance]);
        } */
    }

    /**
     * Delete all user data for the specified user, in the specified contexts.
     *
     * @param   approved_contextlist    $contextlist    The approved contexts and user information to delete information for.
     */
    public static function delete_data_for_user(approved_contextlist $contextlist) {

        global $DB;

        $userid = $contextlist->get_user()->id;

        foreach ($contextlist->get_contexts() as $context) {
            // Get the course module.
            $cm = $DB->get_record('course_modules', ['id' => $context->instanceid]);

            /* // Handle any advanced grading method data first (not implemented yet).

            // Delete ratings.
            $entriessql = "SELECT
                                e.id
                                FROM {pingo_entries} e
                                WHERE (
                                    e.pingo = :pingoid AND
                                    e.userid = :userid
                                )
            ";

            $entriesparams = [
                'pingoid' => $cm->instance,
                'userid' => $userid,
            ];

            \core_rating\privacy\provider::delete_ratings_select($context, 'mod_pingo',
                'entry', "IN ($entriessql)", $entriesparams);

            // Delete all files from the entries.
            $fs = get_file_storage();
            $fs->delete_area_files_select($context->id, 'mod_pingo', 'entry', "IN ($entriessql)", $entriesparams);
            $fs->delete_area_files_select($context->id, 'mod_pingo', 'feedback', "IN ($entriessql)", $entriesparams);

            // Delete entries for user.
            if ($DB->record_exists('pingo_entries', ['pingo' => $cm->instance, 'userid' => $userid])) {

                $DB->delete_records('pingo_entries', [
                    'pingo' => $cm->instance,
                    'userid' => $userid,
                ]);

            } */
        }
    }

    /**
     * Delete multiple users within a single context.
     *
     * @param approved_userlist $userlist The approved context and user information to delete information for.
     */
    public static function delete_data_for_users(approved_userlist $userlist) {
        global $DB;

        $context = $userlist->get_context();
        $cm = $DB->get_record('course_modules', ['id' => $context->instanceid]);

        list($userinsql, $userinparams) = $DB->get_in_or_equal($userlist->get_userids(), SQL_PARAMS_NAMED);
        $params = array_merge(['pingoid' => $cm->instance], $userinparams);

        // Handle any advanced grading method data first (not implemented yet).

        // Delete ratings.
        /* $entriesselect = "SELECT
                            e.id
                            FROM {pingo_entries} e
                            WHERE (
                                e.pingo = :pingoid AND
                                userid {$userinsql}
                            )
        ";

        \core_rating\privacy\provider::delete_ratings_select($context, 'mod_pingo', 'entry', "IN ($entriesselect)", $params);

        // Delete all files from the entries.
        $fs = get_file_storage();
        $fs->delete_area_files_select($context->id, 'mod_pingo', 'entry', "IN ($entriesselect)", $params);
        $fs->delete_area_files_select($context->id, 'mod_pingo', 'feedback', "IN ($entriesselect)", $params);

        // Delete entries for users.
        if ($DB->record_exists_select('pingo_entries', "pingo = :pingoid AND userid {$userinsql}", $params)) {
            $DB->delete_records_select('pingo_entries', "pingo = :pingoid AND userid {$userinsql}", $params);
        } */

    }
}
