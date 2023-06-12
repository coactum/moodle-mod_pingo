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
 * File containing the class definition for the pingo login form.
 *
 * @package     mod_pingo
 * @copyright   2023 coactum GmbH
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

global $CFG;
require_once("$CFG->libdir/formslib.php");

/**
 * Form for the login to pingo.
 *
 * @package   mod_pingo
 * @copyright 2023 coactum GmbH
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL Juv3 or later
 */
class mod_pingo_login_form extends moodleform {

    /**
     * Define the form - called by parent constructor.
     */
    public function definition() {

        global $OUTPUT;

        $mform = $this->_form; // Don't forget the underscore!

        $mform->addElement('header', 'login', get_string('login', 'pingo'));

        $mform->addElement('hidden', 'id', null);
        $mform->setType('id', PARAM_INT);

        $mform->addElement('hidden', 'user', null);
        $mform->setType('user', PARAM_INT);

        $mform->addElement('text', 'email', get_string('pingoemail', 'mod_pingo'));
        $mform->addHelpButton('email', 'pingoemail', 'mod_pingo');
        $mform->addRule('email', null, 'required', null, 'client');
        $mform->addRule('email', get_string('errnoemail', 'mod_pingo'), 'email', '', 'client', false, false);
        $mform->setType('email', PARAM_EMAIL);

        $mform->addElement('passwordunmask', 'password', get_string('pingopassword', 'mod_pingo'));
        $mform->addHelpButton('password', 'pingopassword', 'mod_pingo');
        $mform->setType('password', PARAM_TEXT);
        $mform->addRule('password', null, 'required', null, 'client');

        $mform->addElement('static', 'signupforpingo', get_string('nopingoyet', 'mod_pingo'),
            '<a class="btn btn-secondary" target="_blank" href="' . get_config('pingo', 'remoteserver') . '/users/sign_up"' . '">' .
            get_string('registerforpingo', 'mod_pingo') . '</a>');

        $this->add_action_buttons(false, get_string('logintopingo', 'mod_pingo'));
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
