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
 * JavaScript for the main page of the plugin.
 *
 * @module   mod_pingo/view
 * @copyright  2023 coactum GmbH
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

import $ from 'jquery';

export const init = () => {
    $("#id_tag").on('change', function() {
        $('input[name=reload]').val(1);
        this.form.submit();
    });

    $('input[name="activatesession"]').change(function() {
        if (this.checked) {
            window.location.search += '&activatesession=' + this.id;
        } else {
            window.location.search += '&activatesession=' + 0;
        }
    });

    if ($("#endtime").text()) {
        // Set the date we're counting down to
        var countDownDate = new Date($("#endtime").text()).getTime();

        // Update the count down every 1 second
        var x = setInterval(function() {

            // Get today's date and time
            var now = new Date().getTime();

            // Find the distance between now and the count down date
            var distance = countDownDate - now;

            // Time calculations for days, hours, minutes and seconds
            var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            var seconds = Math.floor((distance % (1000 * 60)) / 1000);

            if (seconds < 10) {
                seconds = '0' + seconds;
            }

            // Display the result in the element with id="demo"
            $("#endtime").html(minutes + ":" + seconds);

            // If the count down is finished, write some text
            if (distance < 0) {
                clearInterval(x);
                $("#endtime").html('-');

                location.reload();
                // if ($('input[name=ended]').val()) {
                //     $('input[name=ended]').val(1);
                //     $('form.mform').submit();
                // }
            }
        }, 1000);

    }
};