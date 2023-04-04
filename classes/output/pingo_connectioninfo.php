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
 * Class containing data for pingo connection info block
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
 * Class containing data for pingo_connectioninfo
 *
 * @package     mod_pingo
 * @copyright   2023 coactum GmbH
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class pingo_connectioninfo implements renderable, templatable {

    /** @var int */
    protected $cmid;

    /**
     * Construct this renderable.
     * @param int $cmid The course module id
     * @param obj $activeconnection The object with the active connection
     */
    public function __construct($cmid, $activeconnection) {
        $this->cmid = $cmid;
        $this->activeconnection = $activeconnection;
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
        $data->remoteserver = get_config('pingo', 'remoteserver');
        $data->activeconnection = $this->activeconnection;
        return $data;
    }
}
