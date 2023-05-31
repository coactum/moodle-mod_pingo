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
 * Class for fetching data from pingo.
 *
 * @package     mod_pingo
 * @copyright   2023 coactum GmbH
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace mod_pingo\pingo_api;

use stdclass;

/**
 * Class for fetching data from pingo.
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
     * @param string $email The email for authentication to PINGO.
     * @param string $password The password for authentication to PINGO.
     * @return string The authentication token for PINGO.
     */
    public static function get_authtoken($remoteurl, $email, $password) {
        // Requesting authentication_token from PINGO for email and password.
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

        var_dump($curl);

        return json_decode($jsonresult, true)['authentication_token'];
    }

    /**
     * Method for fetching the requested session from PINGO.
     *
     * @param string $remoteurl The URL to fetch the data from.
     * @param int $session The ID of the session in PINGO.
     * @param string $authtoken The authentication token from the user.
     * @return object Object with all data from the session in PINGO.
     */
    public static function get_session($remoteurl, $session, $authtoken) {
        // Requesting session from PINGO .
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
        $jsonresult = $curl->get($url, $data, $options);

        if ($curl->info['http_code'] == 401) {
            \core\notification::error(get_string('errunauthorized', 'mod_pingo'));
            return false;
        } else {
            return json_decode($jsonresult, true);
        }
    }

    /**
     * Method for fetching all sessions from PINGO.
     *
     * @param string $remoteurl The URL to fetch the data from.
     * @param string $authtoken The authentication token from the user.
     * @return object Object with all data from all sessions in PINGO.
     */
    public static function get_sessions($remoteurl, $authtoken) {
        // Requesting sessions list from PINGO .
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

        if ($curl->info['http_code'] == 401) {
            \core\notification::error(get_string('errunauthorized', 'mod_pingo'));
            return false;
        } else {
            return json_decode($jsonresult, true);
        }

    }

    /**
     * Method for fetching all data needed for the quickstart form.
     *
     * @param string $remoteurl The URL to fetch the data from.
     * @return object Object with all data for the form (durationchoices, questiontypes, answeroptions).
     */
    public static function get_quickstart_formdata($remoteurl) {
        $unauthorized = false;

        $url = $remoteurl . "/api/question_types";

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

        if ($curl->info['http_code'] == 401) {
            $unauthorized = true;
        } else {
            $qts = json_decode($jsonresult, true)['question_types'];

            $questiontypes = array();
            $answeroptions = array();

            foreach ($qts as $i => $question) {
                if (current_language() == 'de') {
                    if ($question['options_de'][0] != '') {
                        foreach ($question['options_de'] as $i => $opt) {
                            $tempoptions[$i] = $opt;
                        }
                        $answeroptions[$question['type']] = array_combine($tempoptions, $tempoptions);
                    } else {
                        $answeroptions[$question['type']] = array_combine($question['options'], $question['options']);
                    }
                    $questiontypes[$question['type']] = $question['name_de'];
                } else {
                    if ($question['options_en'][0] != '') {
                        foreach ($question['options_en'] as $i => $opt) {
                            $tempoptions[$i] = $opt;
                        }
                        $answeroptions[$question['type']] = array_combine($tempoptions, $tempoptions);
                    } else {
                        $answeroptions[$question['type']] = array_combine($question['options'], $question['options']);
                    }
                    $questiontypes[$question['type']] = $question['name_en'];
                }
            }
        }

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

        $curl->setHeader($header);
        $jsonresult = $curl->get($url, $data, $options);

        if ($curl->info['http_code'] == 401) {
            $unauthorized = true;
        }

        if ($unauthorized) {
            \core\notification::error(get_string('errunauthorized', 'mod_pingo'));
            return false;
        } else {
            $durationchoices = json_decode($jsonresult, true);

            $data = new stdclass;
            $data->durationchoices = array_combine($durationchoices["duration_choices"], $durationchoices["duration_choices"]);
            $data->questiontypes = $questiontypes;
            $data->answeroptions = $answeroptions;

            return $data;
        }
    }

    /**
     * Method for fetching all data needed for the quickstart form.
     *
     * @param string $remoteurl The URL to fetch the data from.
     * @param string $authtoken The authentication token from the user.
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
        //$data = '{auth_token=' . $authtoken . '&options=' . $answeroption . '&q_type=' . $questiontype . '&duration=' . $duration . '}';
        $data = 'auth_token=' . $authtoken . '&options=' . $answeroption . '&q_type=' . $questiontype . '&duration=' . $duration;
        // $data = 'auth_token=' . json_encode($authtoken) . '&options=' . json_encode($answeroption) . '&q_type=' . json_encode($questiontype). '&duration=' . json_encode($duration);

        // Set the request header.
        $header = array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data),
            'Accept: application/json'
        );

        // Perform the POST request.
        $curl->setHeader($header);
        $curl->post($url, $data);

        var_dump($curl);

        // Check for any errors.
        if ($curl->error) {
            var_dump('Error: ' . $curl->errorMessage);
        } else {
            // Get the response body.
            $response = $curl->response;
            var_dump($response);
        }
    }
}
