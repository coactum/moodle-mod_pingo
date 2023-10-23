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
 * File containing the class definition for the question from catalogue form.
 *
 * @package     mod_pingo
 * @copyright   2023 coactum GmbH
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

global $CFG;
require_once("$CFG->libdir/formslib.php");

/**
 * Form for adding a question from catalogue.
 *
 * @package   mod_pingo
 * @copyright 2023 coactum GmbH
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL Juv3 or later
 */
class mod_pingo_questionfromcatalogue_form extends moodleform {

    /**
     * Define the form - called by parent constructor.
     */
    public function definition() {

        global $OUTPUT;

        $mform = $this->_form; // Don't forget the underscore!

        $mform->addElement('html', '<h3 class="mt-5 mb-3">' .
            $this->_customdata['sessionname'] . ' (' . $this->_customdata['sessiontoken'] . ')</h3>');

        $mform->addElement('header', 'questionfromcatalogue', get_string('addquestionfromcatalogue', 'mod_pingo'));

        $mform->addElement('html', get_string('questionfromcatalogueexplanation', 'mod_pingo'));

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
        get_string('filterbytags', 'mod_pingo'), $this->_customdata['tags']);
        $mform->setType('tag', PARAM_TEXT);

        $radioarray = [];

        foreach ($this->_customdata['questions'] as $i => $question) {
            $radioarray[] = $mform->createElement('radio', 'question', '',
                format_text($question['name'], 2),
                format_text($question['id'], 2));
        }

        $mform->addGroup($radioarray, 'question', get_string('yourquestions', 'mod_pingo'), ['<br/>'], false);
        $mform->setType('question', PARAM_TEXT);

        $select = $mform->addElement('select', 'duration_choices',
            get_string('durationchoices', 'mod_pingo'), $this->_customdata['duration_choices']);
        $mform->setType('duration_choices', PARAM_INT);

        $mform->addElement('advcheckbox', 'setsessionactive', '', get_string('setsessionactive', 'mod_pingo'));
        $mform->setDefault('setsessionactive', 1);

        $mform->disable_form_change_checker();

        $this->add_action_buttons(false, get_string('startsurvey', 'mod_pingo'));
    }

    /**
     * Custom validation should be added here
     * @param array $data Array with all the form data
     * @param array $files Array with files submitted with form
     * @return array Array with errors
     */
    public function validation($data, $files) {
        $errors = parent::validation($data, $files);

        if (!isset($data['question']) && $data['reload'] == 0) {
            $errors['question'] = get_string('errnoquestionchoosen', 'mod_pingo');
        }

        return $errors;
    }
}
