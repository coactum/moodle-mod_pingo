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
 * Privacy subsystem implementation for the PINGO plugin.
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

/**
 * Implementation of the privacy subsystem plugin provider for the PINGO activity module.
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
    \core_privacy\local\request\core_userlist_provider {

    /**
     * Provides the meta data stored for a user stored by the plugin.
     *
     * @param   collection     $items The initialised collection to add items to.
     * @return  collection     A listing of user data stored through this system.
     */
    public static function get_metadata(collection $items) : collection {

        // The table 'pingo_connections' stores all data for pingo connections.
        $items->add_database_table('pingo_connections', [
            'pingo' => 'privacy:metadata:pingo_connections:userid',
            'userid' => 'privacy:metadata:pingo_connections:pingo',
            'authenticationtoken' => 'privacy:metadata:pingo_connections:authenticationtoken',
            'timestarted' => 'privacy:metadata:pingo_connections:timestarted',
            'activesession' => 'privacy:metadata:pingo_connections:activesession',
        ], 'privacy:metadata:pingo_connections');

        // The plugin does not use any subsystems that save personal data.

        // No user preferences in the plugin.

        return $items;
    }

    /**
     * Get the list of contexts that contain user information for the specified user.
     *
     * In this case of all PINGO instances where a user is authenticated to PINGO.
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

        // Get contexts.
        $sql = "SELECT c.id
                  FROM {context} c
                  JOIN {course_modules} cm ON cm.id = c.instanceid AND c.contextlevel = :contextlevel
                  JOIN {modules} m ON m.id = cm.module AND m.name = :modulename
                  JOIN {pingo} p ON p.id = cm.instance
                  JOIN {pingo_connections} pc ON pc.pingo = p.id
                  WHERE c.userid = :userid
        ";

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
        $sql = "SELECT pc.userid
                  FROM {course_modules} cm
                  JOIN {modules} m ON m.id = cm.module AND m.name = :modulename
                  JOIN {pingo} p ON p.id = cm.instance
                  JOIN {pingo_connections} pc ON pc.pingo = p.id
                 WHERE cm.id = :instanceid";
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

        $sql = "SELECT
                c.id AS contextid,
                p.*,
                cm.id AS cmid
            FROM {context} c
            JOIN {course_modules} cm ON cm.id = c.instanceid
            JOIN {pingo} p ON p.id = cm.instance
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

                    self::export_connections_data($userid, $pingo->id, $pingo->contextid);
                }

            }
        }

        $pingos->close();
    }

    /**
     * Store all information about all connections.
     *
     * @param   int         $userid The userid of the user whose data is to be exported.
     * @param   int         $pingoid The id of the pingo.
     * @param   int         $pingocontextid The context id of the pingo.
     */
    protected static function export_connections_data(int $userid, $pingoid, $pingocontextid) {
        global $DB;

        // Find all connections for the PINGO instance.
        $sql = "SELECT
                    pc.id,
                    pc.userid,
                    pc.pingo,
                    pc.authenticationtoken,
                    pc.timestarted,
                    pc.activesession
                   FROM {pingo_connections} pc
                   WHERE (
                    pc.pingo = :pingoid AND
                    pc.userid = :userid
                    )
        ";

        $params['userid'] = $userid;
        $params['pingoid'] = $pingoid;

        $connections = $DB->get_recordset_sql($sql, $params);

        if ($connections->valid()) {
            foreach ($connections as $connection) {
                if ($connection) {
                    $context = \context::instance_by_id($pingocontextid);

                    self::export_connection_data($userid, $context, ['pingo-connection-' . $connection->id], $connection);
                }
            }
        }

        $connections->close();
    }

    /**
     * Export all data for the connection.
     *
     * @param   int         $userid The userid of the user whose data is to be exported.
     * @param   \context    $context The instance of the PINGO context.
     * @param   array       $subcontext The location within the current context that this data belongs.
     * @param   \stdClass   $connection The connection.
     */
    protected static function export_connection_data(int $userid, \context $context, $subcontext, $connection) {

        if ($connection->timestarted != 0) {
            $timestarted = transform::datetime($connection->timestarted);
        } else {
            $timestarted = null;
        }

        // Store related metadata.
        $connectiondata = (object) [
            'userid' => $connection->userid,
            'pingo' => $connection->pingo,
            'authenticationtoken' => $connection->authenticationtoken,
            'timestarted' => $timestarted,
            'activesession' => $connection->activesession,
        ];

        // Store the entry data.
        writer::with_context($context)
            ->export_data($subcontext, $connectiondata);
    }

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

        // Delete all records.
        if ($DB->record_exists('pingo_connections', ['pingo' => $cm->instance])) {
            $DB->delete_records('pingo_connections', ['pingo' => $cm->instance]);
        }
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

            // Delete connections for user.
            if ($DB->record_exists('pingo_connections', ['pingo' => $cm->instance, 'userid' => $userid])) {

                $DB->delete_records('pingo_connections', [
                    'pingo' => $cm->instance,
                    'userid' => $userid,
                ]);
            }
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

        // Delete connections for users.
        if ($DB->record_exists_select('pingo_connections', "pingo = :pingoid AND userid {$userinsql}", $params)) {
            $DB->delete_records_select('pingo_connections', "pingo = :pingoid AND userid {$userinsql}", $params);
        }
    }
}
