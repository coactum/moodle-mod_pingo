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
 * This file contains the forms to create and edit an instance of the module.
 *
 * @package     mod_pingo
 * @copyright   2023 coactum GmbH
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

 defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot.'/course/moodleform_mod.php');

/**
 * Module instance settings form.
 *
 * @package    mod_pingo
 * @copyright  2023 coactum GmbH
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class mod_pingo_mod_form extends moodleform_mod {

    /**
     * Define the form elements.
     */
    public function definition() {
        global $CFG;

        $mform = &$this->_form;

        // Adding the "general" fieldset, where all the common settings are showed.
        $mform->addElement('header', 'general', get_string('general', 'form'));

        // Adding the standard "name" field.
        $mform->addElement('text', 'name', get_string('modulename', 'mod_pingo'), ['size' => '64']);

        if (!empty($CFG->formatstringstriptags)) {
            $mform->setType('name', PARAM_TEXT);
        } else {
            $mform->setType('name', PARAM_CLEANHTML);
        }

        $mform->addRule('name', null, 'required', null, 'client');
        $mform->addRule('name', get_string('maximumchars', '', 255), 'maxlength', 255, 'client');
        $mform->addHelpButton('name', 'modulename', 'mod_pingo');

        $this->standard_intro_elements();

        // Add custom activity settings.
        $mform->addElement('header', 'editability', get_string('editability', 'pingo'));

        $id = optional_param('update', null, PARAM_INT);

        // If other teachers can view sessions and create surveys (only changeable at instance creation).
        $mform->addElement('selectyesno', 'editableforall', get_string('editableforall', 'pingo'));
        $mform->addHelpButton('editableforall', 'editableforall', 'pingo');

        if (isset($id) && $id !== 0) {
            $mform->disabledIf('editableforall', 'update', 'neq', 0);
        } else {
            $mform->setDefault('editableforall', 0);
        }

        // Add standard grading elements.
        $this->standard_grading_coursemodule_elements();

        // Add standard elements.
        $this->standard_coursemodule_elements();

        // Add standard buttons.
        $this->add_action_buttons();
    }

    /**
     * Form for custom validation.
     *
     * @param object $data The data from the form.
     * @param object $files The files from the form.
     * @return object $errors The errors.
     */
    public function validation($data, $files) {
        $errors = parent::validation($data, $files);

        return $errors;
    }
}
