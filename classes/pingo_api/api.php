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
 * Class for fetching data from PINGO.
 *
 * @package     mod_pingo
 * @copyright   2023 coactum GmbH
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace mod_pingo\pingo_api;

use stdclass;

/**
 * Class for fetching data from PINGO.
 *
 * @package   mod_pingo
 * @copyright 2023 coactum GmbH
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL Juv3 or later
 */
class mod_pingo_api {

    /**
     * Method for fetching the authentication token from PINGO.
     *
     * @param string $remoteurl The URL to fetch the data from.
     * @param string $email The email for the login to PINGO.
     * @param string $password The password for the PINGO login.
     * @return string The authentication token from PINGO.
     */
    public static function get_authtoken($remoteurl, $email, $password) {
        // Requesting authentication token from PINGO for email and password.
        $url = $remoteurl . "/api/get_auth_token";

        $data = 'password=' . urlencode($password) . '&email=' . urlencode($email);

        $options = array(
            'RETURNTRANSFER' => 1,
            'HEADER' => 0,
            'FAILONERROR' => 1,
        );

        $header = array(
            'Content-Type: application/x-www-form-urlencoded',
            'Content-Length: ' . strlen($data),
            'Accept: application/json'
        );

        $curl = new \curl();
        $curl->setHeader($header);
        $jsonresult = $curl->post($url, $data, $options);

        $response = json_decode($jsonresult, true);

        if (isset($response['authentication_token'])) {
            return $response['authentication_token'];
        } else {
            return false;
        }
    }

    /**
     * Method for fetching the requested session from PINGO.
     *
     * @param string $remoteurl The URL to fetch the data from.
     * @param string $authtoken The authentication token for PINGO.
     * @param int $session The ID of the session in PINGO.
     * @return object Object with all data from the session in PINGO.
     */
    public static function get_session($remoteurl, $authtoken, $session) {
        // Requesting session from PINGO.
        $url = $remoteurl . "/events/$session/?auth_token=" . $authtoken;

        $data = '';

        $options = array(
            'RETURNTRANSFER' => 1,
            'HEADER' => 0,
            'FAILONERROR' => 1,
        );

        $header = array(
            'Content-Type: application/x-www-form-urlencoded',
            'Content-Length: ' . strlen($data),
            'Accept: application/json'
        );

        $curl = new \curl();

        $curl->setHeader($header);
        $jsonresult = $curl->get($url, $data);

        if (!isset($curl->info['http_code'])) {
            return false;
        } else if ($curl->info['http_code'] == 401) {
            \core\notification::error(get_string('errunauthorized', 'mod_pingo'));
            return false;
        } else if ($curl->info['http_code'] != 200) {
            return false;
        } else if (!$session = json_decode($jsonresult, true)) {
            return false;
        } else {
            return $session;
        }
    }

    /**
     * Method for fetching all sessions from PINGO.
     *
     * @param string $remoteurl The URL to fetch the data from.
     * @param string $authtoken The authentication token for PINGO.
     * @return object Object with all data from all sessions in PINGO.
     */
    public static function get_sessions($remoteurl, $authtoken) {
        // Requesting sessions list from PINGO.
        $url = $remoteurl . "/events?auth_token=" . $authtoken;

        $data = '';

        $options = array(
            'RETURNTRANSFER' => 1,
            'HEADER' => 0,
            'FAILONERROR' => 1,
        );

        $header = array(
            'Content-Type: application/x-www-form-urlencoded',
            'Content-Length: ' . strlen($data),
            'Accept: application/json'
        );

        $curl = new \curl();
        $curl->setHeader($header);
        $jsonresult = $curl->get($url, $data, $options);

        if (!isset($curl->info['http_code'])) {
            return false;
        } else if ($curl->info['http_code'] == 401) {
            \core\notification::error(get_string('errunauthorized', 'mod_pingo'));
            return false;
        } else {
            return json_decode($jsonresult, true);
        }

    }

    /**
     * Method for fetching the available duration choices from PINGO.
     *
     * @param string $remoteurl The URL to fetch the data from.
     * @return object Object The duration choices from PINGO.
     */
    public static function get_durationchoices($remoteurl) {
        // Requesting duration choices from PINGO.
        $url = $remoteurl . "/api/duration_choices";

        $data = '';

        $options = array(
            'RETURNTRANSFER' => 1,
            'HEADER' => 0,
            'FAILONERROR' => 1,
        );

        $header = array(
            'Content-Type: application/x-www-form-urlencoded',
            'Content-Length: ' . strlen($data),
            'Accept: application/json'
        );

        $curl = new \curl();
        $curl->setHeader($header);
        $jsonresult = $curl->get($url, $data, $options);

        if (!isset($curl->info['http_code'])) {
            return false;
        } else {
            $response = json_decode($jsonresult, true);

            if (isset($response["duration_choices"])) {
                foreach ($response["duration_choices"] as $duration) {
                    $formatedduration = format_text($duration, 2);
                    $mins = floor($formatedduration / 60);
                    $secs = $formatedduration % 60;

                    if ($mins < 1) {
                        $timestr = $secs . 's';
                    } else if ($secs == 0) {
                        $timestr = $mins . 'min';
                    } else {
                        $timestr = $mins . 'min ' . $secs . 's';
                    }

                    $durationchoices[$formatedduration] = $timestr;
                }

                $durationchoices[0] = get_string('nocountdown', 'mod_pingo');
                return $durationchoices;
            } else {
                return false;
            }
        }
    }

    /**
     * Method for fetching question types and answer options for the quickstart form.
     *
     * @param string $remoteurl The URL to fetch the data from.
     * @param string $authtoken The authentication token for PINGO.
     * @return object Object with all data for the form (question types and answer options).
     */
    public static function get_quickstart_formdata($remoteurl, $authtoken) {
        $url = $remoteurl . "/api/question_types";

        // Check if unauthorized (because question_types dont need auth_token).
        if (!self::get_sessions($remoteurl, $authtoken)) {
            return false;
        }

        $data = '';

        $options = array(
            'RETURNTRANSFER' => 1,
            'HEADER' => 0,
            'FAILONERROR' => 1,
        );

        $header = array(
            'Content-Type: application/x-www-form-urlencoded',
            'Content-Length: ' . strlen($data),
            'Accept: application/json'
        );

        $curl = new \curl();
        $curl->setHeader($header);
        $jsonresult = $curl->get($url, $data, $options);
        $response = json_decode($jsonresult, true);

        if (!isset($curl->info['http_code'])) {
            return false;
        } else if (isset($response['question_types'])) {
            $qts = $response['question_types'];

            $questiontypes = array();
            $answeroptions = array();

            foreach ($qts as $i => $question) {
                $tempoptions = array();

                if (current_language() == 'de') {
                    if ($question['options_de'][0] != '') {
                        foreach ($question['options_de'] as $i => $opt) {
                            $tempoptions[format_text($i, 2)] = format_text($opt, 2);
                        }
                        $answeroptions[format_text($question['type'], 2)] = array_combine($tempoptions, $tempoptions);
                    } else {
                        foreach ($question['options'] as $i => $opt) {
                            $tempoptions[format_text($i, 2)] = format_text($opt, 2);
                        }
                        $answeroptions[format_text($question['type'], 2)] = array_combine($tempoptions, $tempoptions);
                    }
                    $questiontypes[format_text($question['type'], 2)] = format_text($question['name_de'], 2);

                } else {
                    if ($question['options_en'][0] != '') {
                        foreach ($question['options_en'] as $i => $opt) {
                            $tempoptions[format_text($i, 2)] = format_text($opt, 2);
                        }
                        $answeroptions[format_text($question['type'], 2)] = array_combine($tempoptions, $tempoptions);
                    } else {
                        foreach ($question['options'] as $i => $opt) {
                            $tempoptions[format_text($i, 2)] = format_text($opt, 2);
                        }
                        $answeroptions[format_text($question['type'], 2)] = array_combine($tempoptions, $tempoptions);
                    }
                    $questiontypes[format_text($question['type'], 2)] = format_text($question['name_en'], 2);
                }
            }

            $data = new stdclass;
            $data->questiontypes = $questiontypes;
            $data->answeroptions = $answeroptions;

            return $data;
        } else {
            return false;
        }
    }

    /**
     * Method for running a quickstart survey.
     *
     * @param string $remoteurl The URL to fetch the data from.
     * @param string $authtoken The authentication token for PINGO.
     * @param string $session The ID of the session.
     * @param string $questiontype The question type choosen by the user.
     * @param string $answeroption The answer option choosen by the user.
     * @param string $duration The duration choosen by the user.
     */
    public static function run_quickstart($remoteurl, $authtoken, $session, $questiontype, $answeroption, $duration) {

        // Create a new cURL handle.
        $curl = new \curl();

        // Set the URL endpoint.
        $url = $remoteurl . "/events/$session/quick_start";

        // Set the request data.
        $data = array('auth_token' => $authtoken, 'options' => $answeroption, 'q_type' => $questiontype, 'duration' => $duration);
        $data = json_encode($data);

        // Set the request header.
        $header = array(
            'Content-Type: application/json',
            'Accept: application/json'
        );

        // Perform the POST request.
        $curl->setHeader($header);
        $curl->post($url, $data);

        // Check for any errors.
        if ($curl->error != '') {
            return false;
        } else if ($curl->response['HTTP/1.1'] == '201 Created') {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Method for fetching questions and tags for the add questions from catalogue form.
     *
     * @param string $remoteurl The URL to fetch the data from.
     * @param string $authtoken The authentication token for PINGO.
     * @param string $tag The tag of the questions that should be shown.
     * @return object Object with all data for the form (questions and tags).
     */
    public static function get_questionfromcatalogue_formdata($remoteurl, $authtoken, $tag) {
        $url = $remoteurl . "/questions?auth_token=$authtoken&tag=$tag";

        $data = '';

        $options = array(
            'RETURNTRANSFER' => 1,
            'HEADER' => 0,
            'FAILONERROR' => 1,
        );

        $header = array(
            'Accept: application/json'
        );

        $curl = new \curl();
        $curl->setHeader($header);
        $jsonresult = $curl->get($url, $data, $options);

        if (!isset($curl->info['http_code'])) {
            return false;
        } else if ($curl->info['http_code'] == 401) {
            \core\notification::error(get_string('errunauthorized', 'mod_pingo'));
            return false;
        } else if (!$questions = json_decode($jsonresult, true)) {
            return false;
        } else {
            // Get array with all tags used by questions.
            $tags = array('alltags' => get_string('alltags', 'mod_pingo'));
            foreach ($questions as $question) {
                foreach ($question['tags'] as $tag) {
                    $tagformatted = format_text($tag, 2);
                    if (!in_array($tagformatted, $tags)) {
                        $tags[$tagformatted] = $tagformatted;
                    }
                }
            }

            $data = new stdclass;
            $data->questions = $questions;
            $data->tags = $tags;
            return $data;

        }
    }

    /**
     * Method for running a question from catalogue as a survey.
     *
     * @param string $remoteurl The URL to fetch the data from.
     * @param string $authtoken The authentication token for PINGO.
     * @param string $session The ID of the session.
     * @param string $questionid The id of the question choosen by the user.
     * @param string $duration The duration choosen by the user.
     */
    public static function run_question_from_catalogue($remoteurl, $authtoken, $session, $questionid, $duration) {

        // Create a new cURL handle.
        $curl = new \curl();

        // Set the URL endpoint.
        $url = $remoteurl . "/events/$session/add_question.js";

        // Set the request data.
        $data = array('auth_token' => $authtoken, 'question' => $questionid, 'duration' => $duration);
        $data = json_encode($data);

        // Set the request header.
        $header = array(
            'Content-Type: application/json',
            'Accept: application/json'
        );

        // Perform the POST request.
        $curl->setHeader($header);
        $curl->post($url, $data);

        // Check for any errors.
        if ($curl->error != '') {
            return false;
        } else if ($curl->response['HTTP/1.1'] == '201 Created' || $curl->response['HTTP/1.1'] == '200 OK') {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Method for stopping a survey.
     *
     * @param string $remoteurl The URL to fetch the data from.
     * @param string $authtoken The authentication token for PINGO.
     * @param string $session The ID of the session.
     * @param string $surveyid The id of the survey that should be stopped.
     * @param string $stoptime The time when the survey should be stopped.
     */
    public static function stop_survey($remoteurl, $authtoken, $session, $surveyid, $stoptime) {

        // Create a new cURL handle.
        $curl = new \curl();

        // Set the URL endpoint.
        $url = $remoteurl . "/events/$session/surveys/$surveyid/stop";

        // Set the request data.
        $data = array('auth_token' => $authtoken, 'stoptime' => $stoptime);
        $data = json_encode($data);

        // Set the request header.
        $header = array(
            'Content-Type: application/json',
            'Accept: application/json'
        );

        // Perform the POST request.
        $curl->setHeader($header);
        $curl->post($url, $data);

        // Check for any errors.
        if ($curl->error != '') {
            return false;
        } else if ($curl->response['HTTP/1.1'] == '201 Created' || $curl->response['HTTP/1.1'] == '200 OK') {
            return true;
        } else {
            return false;
        }
    }
}
