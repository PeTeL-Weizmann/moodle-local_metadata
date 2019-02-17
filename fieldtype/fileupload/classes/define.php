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
 * Fileupload profile field
 *
 * @package   profilefield_fileupload
 * @copyright  2008 onwards Shane Elliot {@link http://pukunui.com}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace metadatafieldtype_fileupload;

defined('MOODLE_INTERNAL') || die;

/**
 * Class local_metadata_define_fileupload
 * @copyright  2008 onwards Shane Elliot {@link http://pukunui.com}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class define extends \local_metadata\fieldtype\define_base {

    /**
     * Add elements for creating/editing a fileupload profile field.
     *
     * @param moodleform $form
     */
    public function define_form_specific($form) {
        // Select whether or not this should be checked by default.
        $form->addElement('textarea', 'param1', get_string('profilefileuploadoptions', 'metadatafieldtype_fileupload'), ['rows' => 6, 'cols' => 40]);
//        $form->setDefault('text', 'defaultdata', get_string('ofiledefaultdata', 'metadatafieldtype_fileupload'), 'size="50"');
        $form->setType('param1', PARAM_TEXT);
    }

    /**
     * Validates data for the profile field.
     *
     * @param array $data
     * @param array $files
     * @return array
     */
    public function define_validate_specific($data, $files) {
        $err = [];

        return $err;
    }

    /**
     * Processes data before it is saved.
     * @param array|stdClass $data
     * @return array|stdClass
     */
    public function define_save_preprocess($data) {
        //$data->param1 = str_replace("\r", '', $data->param1);

        return $data;
    }
}


