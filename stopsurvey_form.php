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
 * File containing the class definition for the stop survey form.
 *
 * @package     mod_pingo
 * @copyright   2023 coactum GmbH
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

global $CFG;
require_once("$CFG->libdir/formslib.php");

/**
 * Form for stopping a survey.
 *
 * @package   mod_pingo
 * @copyright 2023 coactum GmbH
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL Juv3 or later
 */
class mod_pingo_stopsurvey_form extends moodleform {

    /**
     * Define the form - called by parent constructor.
     */
    public function definition() {

        global $OUTPUT;

        $mform = $this->_form; // Don't forget the underscore!

        $mform->addElement('hidden', 'id', null);
        $mform->setType('id', PARAM_INT);

        $mform->addElement('hidden', 'session', null);
        $mform->setType('session', PARAM_TEXT);

        $mform->addElement('hidden', 'surveyid', null);
        $mform->setType('surveyid', PARAM_TEXT);

        $mform->addElement('hidden', 'surveyended', 0);
        $mform->setType('surveyended', PARAM_BOOL);

        $mform->addElement('hidden', 'mode', 4);
        $mform->setType('mode', PARAM_INT);

        $select = $mform->addElement('select', 'stoptime',
            get_string('stoptime', 'pingo'), $this->_customdata['duration_choices']);
        $mform->setType('stoptime', PARAM_INT);

        $mform->disable_form_change_checker();

        $this->add_action_buttons(false, get_string('stopsurvey', 'pingo'));
    }

    /**
     * Custom validation should be added here
     * @param array $data Array with all the form data
     * @param array $files Array with files submitted with form
     * @return array Array with errors
     */
    public function validation($data, $files) {
        return [];
    }
}
