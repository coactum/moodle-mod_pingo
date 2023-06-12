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
 * Backup steps for mod_pingo are defined here.
 *
 * @package     mod_pingo
 * @category    backup
 * @copyright   2023 coactum GmbH
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// For more information about the backup and restore process, please visit:
// More information about the backup process: {@link https://docs.moodle.org/dev/Backup_API}.
// More information about the restore process: {@link https://docs.moodle.org/dev/Restore_API}.

/**
 * Define the complete structure for backup, with file and id annotations.
 */
class backup_pingo_activity_structure_step extends backup_activity_structure_step {

    /**
     * Defines the structure of the resulting xml file.
     *
     * @return backup_nested_element The structure wrapped by the common 'activity' element.
     */
    protected function define_structure() {
        $userinfo = $this->get_setting_value('userinfo');

        // Replace with the attributes and final elements that the element will handle.
        $pingo = new backup_nested_element('pingo', array('id'), array(
            'name', 'intro', 'introformat', 'timecreated', 'timemodified', 'editableforall'));

        $connections = new backup_nested_element('connections');
        $connection = new backup_nested_element('connection', array('id'), array(
            'userid', 'authenticationtoken', 'timestarted'));

        // Build the tree with these elements with $root as the root of the backup tree.
        $pingo->add_child($connections);
        $connections->add_child($connection);

        // Define the source tables for the elements.

        $pingo->set_source_table('pingo', array('id' => backup::VAR_ACTIVITYID));

        if ($userinfo) {
            // Connections.
            $connection->set_source_table('pingo_connections', array('pingo' => backup::VAR_PARENTID));
        }

        // Define id annotations.
        $rating->annotate_ids('user', 'userid');

        // No file annotations.

        return $this->prepare_activity_structure($pingo);
    }
}
