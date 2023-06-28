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
 * File containing the class definition for the survey quickstart form.
 *
 * @package     mod_pingo
 * @copyright   2023 coactum GmbH
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

global $CFG;
require_once("$CFG->libdir/formslib.php");

/**
 * Form for the survey quickstart.
 *
 * @package   mod_pingo
 * @copyright 2023 coactum GmbH
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL Juv3 or later
 */
class mod_pingo_quickstart_form extends moodleform {

    /**
     * Define the form - called by parent constructor.
     */
    public function definition() {

        global $OUTPUT;

        $mform = $this->_form; // Don't forget the underscore!

        $mform->addElement('html', '<h3 class="mt-5 mb-3">' .
            $this->_customdata['sessionname'] . ' (' . $this->_customdata['sessiontoken'] . ')</h3>');

        $mform->addElement('header', 'quickstart', get_string('quickstart', 'pingo'));

        $mform->addElement('html', get_string('quickstartexplanation', 'pingo'));

        $mform->addElement('hidden', 'id', null);
        $mform->setType('id', PARAM_INT);

        $mform->addElement('hidden', 'session', null);
        $mform->setType('session', PARAM_TEXT);

        $mform->addElement('hidden', 'mode', 2);
        $mform->setType('mode', PARAM_INT);

        $select = $mform->addElement('select', 'question_types',
            get_string('questiontypes', 'pingo'), $this->_customdata['question_types']);
        $mform->setType('question_types', PARAM_TEXT);

        foreach ($this->_customdata['answer_options'] as $type => $options) {
            if (!empty($options) && (!isset($options['']) || $options[''] != '')) {
                $select = $mform->addElement('select', 'answer_options[' . $type . ']',
                    get_string('answeroptions', 'pingo'), $options);
                $mform->setType('answer_options', PARAM_TEXT);
                $mform->hideIf('answer_options[' . $type . ']', 'question_types', 'neq', $type);
            }
        }

        $select = $mform->addElement('select', 'duration_choices',
            get_string('durationchoices', 'pingo'), $this->_customdata['duration_choices']);
        $mform->setType('duration_choices', PARAM_INT);

        $mform->addElement('advcheckbox', 'setsessionactive', '', get_string('setsessionactive', 'pingo'));
        $mform->setDefault('setsessionactive', 1);

        $mform->disable_form_change_checker();

        $this->add_action_buttons(false, get_string('startsurvey', 'pingo'));
    }

    /**
     * Custom validation should be added here
     * @param array $data Array with all the form data
     * @param array $files Array with files submitted with form
     * @return array Array with errors
     */
    public function validation($data, $files) {
        return array();
    }
}
