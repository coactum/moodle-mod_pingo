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
 * File containing the class definition for the pingo question from catalog form.
 *
 * @package     mod_pingo
 * @copyright   2023 coactum GmbH
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

global $CFG;
require_once("$CFG->libdir/formslib.php");

/**
 * Form for adding a question from catalog.
 *
 * @package   mod_pingo
 * @copyright 2023 coactum GmbH
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL Juv3 or later
 */
class mod_pingo_questionfromcatalog_form extends moodleform {

    /**
     * Define the form - called by parent constructor.
     */
    public function definition() {

        global $OUTPUT;

        $mform = $this->_form; // Don't forget the underscore!

        $mform->addElement('header', 'questionfromcatalog', get_string('addquestionfromcatalog', 'pingo'));

        $mform->addElement('html', get_string('questionfromcatalogexplanation', 'pingo', $this->_customdata['session']));

        $mform->addElement('hidden', 'id', null);
        $mform->setType('id', PARAM_INT);

        $mform->addElement('hidden', 'session', null);
        $mform->setType('session', PARAM_TEXT);

        $mform->addElement('hidden', 'mode', 3);
        $mform->setType('mode', PARAM_INT);

        $mform->addElement('hidden', 'reload', false);
        $mform->setType('reload', PARAM_BOOL);

        $mform->addElement('html', '<br><a class="btn btn-primary m-2" href="' . $this->_customdata['remoteurl'] .
            '/questions" target="_blank">' . get_string('managequestionsinpingo', 'mod_pingo') . '</a>');

        $select = $mform->addElement('select', 'tag',
        get_string('filterbytags', 'pingo'), $this->_customdata['tags']);
        $mform->setType('tag', PARAM_TEXT);

        $radioarray = array();

        foreach ($this->_customdata['questions'] as $i => $question) {
            $radioarray[] = $mform->createElement('radio', 'question', '', $question['name'], $question['id']);
        }

        $mform->addGroup($radioarray, 'question', get_string('yourquestions', 'mod_pingo'), array('<br/>'), false);
        $mform->addRule('question', null, 'required', null, 'client');
        $mform->setType('question', PARAM_TEXT);

        $select = $mform->addElement('select', 'duration_choices',
            get_string('durationchoices', 'pingo'), $this->_customdata['duration_choices']);
        $mform->setType('duration_choices', PARAM_INT);

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
