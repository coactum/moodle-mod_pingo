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
 * The task that provides a complete restore of mod_pingo is defined here.
 *
 * @package     mod_pingo
 * @category    backup
 * @copyright   2023 coactum GmbH
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

// More information about the backup process: {@link https://docs.moodle.org/dev/Backup_API}.
// More information about the restore process: {@link https://docs.moodle.org/dev/Restore_API}.

require_once($CFG->dirroot.'/mod/pingo/backup/moodle2/restore_pingo_stepslib.php');

/**
 * Restore task for mod_pingo.
 */
class restore_pingo_activity_task extends restore_activity_task {

    /**
     * Defines particular settings that this activity can have.
     */
    protected function define_my_settings() {
        return;
    }

    /**
     * Defines particular steps that this activity can have.
     *
     * @return base_step.
     */
    protected function define_my_steps() {
        $this->add_step(new restore_pingo_activity_structure_step('pingo_structure', 'pingo.xml'));
    }

    /**
     * Defines the contents in the activity that must be processed by the link decoder.
     *
     * @return array.
     */
    public static function define_decode_contents() {
        $contents = [];

        // Define the contents (files).
        // tablename, [field1, field 2], $mapping.

        $contents[] = new restore_decode_content('pingo', ['intro'], 'pingo');

        return $contents;
    }

    /**
     * Defines the decoding rules for links belonging to the activity to be executed by the link decoder.
     *
     * @return array.
     */
    public static function define_decode_rules() {
        $rules = [];

        // Define the rules.

        $rules[] = new restore_decode_rule('PINGOINDEX', '/mod/pingo/index.php?id=$1', 'course');
        $rules[] = new restore_decode_rule('PINGOVIEWBYID', '/mod/pingo/view.php?id=$1', 'course_module');

        return $rules;
    }

    /**
     * Defines the restore log rules that will be applied by the
     * restore_logs_processor when restoring mod_pingo logs. It
     * must return one array of restore_log_rule objects.
     *
     * @return array.
     */
    public static function define_restore_log_rules() {
        $rules = [];

        // Define the rules to restore the logs (one rule for each event / file in the plugin/event/ folder).

        $rules[] = new restore_log_rule('pingo', 'view', 'view.php?id={course_module}', '{pingo}');

        return $rules;
    }


    /**
     * Define the restore log rules that will be applied
     * by the restore_logs_processor when restoring
     * course logs. It must return one array
     * of restore_log_rule objects
     *
     * Note this rules are applied when restoring course logs
     * by the restore final task, but are defined here at
     * activity level. All them are rules not linked to any module instance (cmid = 0)
     */
    public static function define_restore_log_rules_for_course() {
        $rules = [];

        $rules[] = new restore_log_rule('pingo', 'view all', 'index.php?id={course}', null);

        return $rules;
    }
}
