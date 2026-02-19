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

namespace local_course_nav_test;

use core\hook\output\after_standard_main_region_html_generation;
use core\output\html_writer;
use core\router\util;
use core_course\route\controller\course_navigation;

/**
 * Utility class for the plugin to display a button for the next element of the course in the footer.
 *
 * @package     local_course_nav_test
 * @copyright   2025 Laurent David <laurent.david@moodle.com>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class hook_callbacks {
    /**
     * Example of hook callback.
     *
     * @param after_standard_main_region_html_generation $hook The event.
     */
    public static function after_standard_main_region_html_generation(
        after_standard_main_region_html_generation $hook
    ) {
        $cm = $hook->renderer->get_page()->cm;
        if (!$cm) {
            return;
        }

        $existsnext = method_exists(course_navigation::class, 'cm_next_element');
        $existsprevious = method_exists(course_navigation::class, 'cm_previous_element');
        if (!$existsnext && !$existsprevious) {
            return;
        }

        if ($existsprevious) {
            $prevmodule = util::get_path_for_callable([course_navigation::class, 'cm_previous_element'], [
                'cm' => $cm->id,
            ]);
            $prevlink = html_writer::link(
                $prevmodule,
                get_string('previousactivity', 'local_course_nav_test'),
                ['class' => 'btn btn-warning mx-3']
            );
        } else {
            $prevlink = html_writer::link(
                '',
                get_string('previousactivity', 'local_course_nav_test'),
                ['class' => 'btn mx-3 disabled']
            );
        }
        if ($existsnext) {
            $nextmodule = util::get_path_for_callable([course_navigation::class, 'cm_next_element'], [
                'cm' => $cm->id,
            ]);
            $nextlink = html_writer::link(
                $nextmodule,
                get_string('nextactivity', 'local_course_nav_test'),
                ['class' => 'btn btn-warning mx-3']
            );
        } else {
            $nextlink = html_writer::link(
                '',
                get_string('nextactivity', 'local_course_nav_test'),
                ['class' => 'btn mx-3 disabled']
            );
        }

        $div = html_writer::div(
            $prevlink . $nextlink,
            'd-flex justify-content-between sticky-bottom py-3 bg-light border-top',
        );
        $hook->add_html($div);
    }
}
