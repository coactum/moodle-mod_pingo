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
 * Class containing data for pingo tab area
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
 * Class containing data for pingo tab area
 *
 * @package     mod_pingo
 * @copyright   2023 coactum GmbH
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class pingo_tabarea implements renderable, templatable {

    /** @var int */
    protected $cmid;
    /** @var object */
    protected $activetab;

    /**
     * Construct this renderable.
     * @param int $cmid The course module id
     * @param obj $activetab The object with the active tab.
     */
    public function __construct($cmid, $activetab) {
        $this->cmid = $cmid;
        $this->activetab = $activetab;
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
        $data->activetab = $this->activetab;
        return $data;
    }
}
