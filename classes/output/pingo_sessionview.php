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
 * Class containing data for pingo session view
 *
 * @package     mod_pingo
 * @copyright   2023 coactum GmbH
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
namespace mod_pingo\output;

use renderable;
use renderer_base;
use templatable;
use stdClass;

/**
 * Class containing data for pingo session view
 *
 * @package     mod_pingo
 * @copyright   2023 coactum GmbH
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class pingo_sessionview implements renderable, templatable {

    /** @var int */
    protected $cmid;
    /** @var object */
    protected $session;
    /** @var object */
    protected $context;
    /** @var string */
    protected $authtoken;
    /** @var string */
    protected $stopsurveyform;
    /** @var bool */
    protected $surveyactive;
    /** @var string */
    protected $surveyendstr;


    /**
     * Construct this renderable.
     * @param int $cmid The course module id
     * @param obj $session The object with the session
     * @param obj $context The object with the context
     * @param string $authtoken The authentication token
     * @param string $stopsurveyform The form for stopping the current survey
     * @param bool $surveyactive If the last survey is still running
     * @param string $surveyendstr String with information about the end time of the survey
     */
    public function __construct($cmid, $session, $context, $authtoken, $stopsurveyform, $surveyactive, $surveyendstr) {
        $this->cmid = $cmid;
        $this->session = $session;
        $this->context = $context;
        $this->authtoken = $authtoken;
        $this->stopsurveyform = $stopsurveyform;
        $this->surveyactive = $surveyactive;
        $this->surveyendstr = $surveyendstr;
    }

    /**
     * Export this data so it can be used as the context for a mustache template.
     *
     * @param renderer_base $output Renderer base.
     * @return stdClass
     */
    public function export_for_template(renderer_base $output) {
        $data = new stdClass();
        $data->cmid = $this->cmid;
        $data->session = $this->session;
        $data->startsurvey = has_capability('mod/pingo:startsurvey', $this->context);
        $data->remoteserver = get_config('pingo', 'remoteserver');
        $data->authtoken = $this->authtoken;
        $data->stopsurveyform = $this->stopsurveyform;
        $data->surveyactive = $this->surveyactive;
        $data->surveyendstr = $this->surveyendstr;

        // Set height for iframe based on content.
        if (!isset($this->session['latest_survey']['options'])) {
            $height = 450;
        } else {
            $height = 580;

            foreach ($this->session['latest_survey']['options'] as $option) {
                $height += 31;
            }
        }

        if ($data->surveyactive) {
            $height += 50;
        }

        $data->contentheight = $height;
        return $data;
    }
}
