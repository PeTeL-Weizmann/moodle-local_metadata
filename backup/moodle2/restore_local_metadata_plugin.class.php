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
 * Grid Information
 *
 * @package    local_enemyquestions
 * @version    1.0
 * @copyright  &copy; 2015 Ray Morris <ray.morris@teex.tamu.edu>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU Public License
 *
 */
defined('MOODLE_INTERNAL') || die();
/**
 * Restore plugin class that provides the necessary information
 * needed to restore enemyquestions
 */
class restore_local_metadata_plugin extends restore_local_plugin {
    /**
     * Returns the paths to be handled by the plugin at course level.
     */
    protected function define_module_plugin_structure() {
        $paths = array();
        $elename = 'plugin_local_metadata_module'; // This defines the postfix of 'process_*' below.
        $elepath = $this->get_pathfor('/');
        $paths[] = new restore_path_element($elename, $elepath);

        return $paths; // And we return the interesting paths.
    }
//    /**
//     * Process the enemyquestion element.
//     */
    public function process_plugin_local_metadata_module($data) {
        global $DB, $CFG;
        $data = (object)$data;

        if ($hasoldinstance = $DB->get_record('course_modules', array('id' => $data->instanceid))) {
            $oldcontext = \context_module::instance($data->instanceid); // save old context
            $data->instanceid = $this->task->get_moduleid();            // add new cm (instance) id to local_metadata DB

            // SG - PTL #710 - 20181106 - copy files from actyvity's 'fileupload' metadata field with new itemid (draftid)
            $filedatatype = $DB->get_record('local_metadata_field', array('id' => $data->fieldid));
            if ($filedatatype && $filedatatype->datatype == 'fileupload') {
                require_once ($CFG->libdir . '/filestorage/file_storage.php');
                require_once ($CFG->libdir . '/filelib.php');
                //$context = \context_coursecat::instance($filedatatype->categoryid);     // TODO - need to fix everyehere wrong course category context - see /local/metadata/fieldtype/fileupload/classes/metadata.php#105-138 edit_save_data()
                $context = \context_module::instance($data->instanceid);
                $fs = get_file_storage();
                $files = $fs->get_area_files($oldcontext->id, 'local_metadata', 'image', $data->data);
                foreach ($files as $file) {
                    if ($file->get_filename() != '.'){
                        $newdraftitemid = file_get_unused_draft_itemid();
                        $filerecord = (object)array(
                            'contextid' => $context->id,
                            'component' => 'local_metadata',
                            'filearea'  => 'image',
                            'itemid'    => $newdraftitemid,
                            'filepath'  => '/',
                        );
                        $fs->create_file_from_storedfile($filerecord, $file);   // copy file with new item id
                        $data->data = $newdraftitemid;                          // add new copied file's item id to local_metadata DB
                    }
                }
            }

            $DB->insert_record('local_metadata', $data);
        }


    }
}

