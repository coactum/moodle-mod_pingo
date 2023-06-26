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
 * All the steps to restore mod_pingo are defined here.
 *
 * @package     mod_pingo
 * @category    backup
 * @copyright   2023 coactum GmbH
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// More information about the backup process: {@link https://docs.moodle.org/dev/Backup_API}.
// More information about the restore process: {@link https://docs.moodle.org/dev/Restore_API}.

/**
 * Defines the structure step to restore one mod_pingo activity.
 */
class restore_pingo_activity_structure_step extends restore_activity_structure_step {

    /**
     * Defines the structure to be restored.
     *
     * @return restore_path_element[].
     */
    protected function define_structure() {
        $paths = array();
        $userinfo = $this->get_setting_value('userinfo');

        $paths[] = new restore_path_element('pingo', '/activity/pingo');

        if ($userinfo) {
            $paths[] = new restore_path_element('pingo_connections', '/activity/pingo/connections/connection');
        }

        return $this->prepare_activity_structure($paths);
    }

    /**
     * Processes the PINGO restore data.
     *
     * @param array $data Parsed element data.
     */
    protected function process_pingo($data) {
        global $DB;

        $data = (object) $data;
        $oldid = $data->id;
        $data->course = $this->get_courseid();

        $newitemid = $DB->insert_record('pingo', $data);
        $this->apply_activity_instance($newitemid);

        return;
    }

    /**
     * Restore PINGO connection.
     *
     * @param object $data data.
     */
    protected function process_pingo_connections($data) {
        global $DB;

        $data = (object) $data;
        $oldid = $data->id;

        $data->pingo = $this->get_new_parentid('pingo');
        $data->userid = $this->get_mappingid('user', $data->userid);

        $newitemid = $DB->insert_record('pingo_connections', $data);
        $this->set_mapping('pingo_connection', $oldid, $newitemid);
    }

    /**
     * Defines post-execution actions like restoring files.
     */
    protected function after_execute() {
        // Add related files, no need to match by itemname (just internally handled context).
        $this->add_related_files('mod_pingo', 'intro', null);
    }
}
