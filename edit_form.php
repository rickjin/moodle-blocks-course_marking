<?php
/**
 * *************************************************************************
 * *                  Course Marking Block                                **
 * *************************************************************************
 * @copyright   emeneo.com                                                **
 * @link        emeneo.com                                                **
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later  **
 * *************************************************************************
 * ************************************************************************
*/

defined('MOODLE_INTERNAL') || die();
require_once($CFG->libdir.'/gradelib.php');

class block_course_marking_edit_form extends block_edit_form {
    protected function specific_definition($mform) {
        global $DB;

        // Fields for editing HTML block title and contents.
        $mform->addElement('header', 'configheader', get_string('coursemarkingsetting', 'block_course_marking'));
        // Date and time conditions
        $mform->addElement('date_time_selector', 'config_availablefrom',get_string('availablefrom', 'condition'), array('optional' => true));
        $mform->addElement('date_time_selector', 'config_availableuntil',get_string('availableuntil', 'condition'), array('optional' => true));

		// Conditions based on grades
            $gradeoptions = array();
            $items = grade_item::fetch_all(array('courseid' => $this->page->course->id));
            $items = $items ? $items : array();
            foreach ($items as $id => $item) {
                $gradeoptions[$id] = $item->get_name();
            }
            asort($gradeoptions);
            $gradeoptions = array(0 => get_string('none', 'condition')) + $gradeoptions;

            $grouparray = array();
            $grouparray[] = $mform->createElement('select', 'conditiongradeitemid', '', $gradeoptions);
            $grouparray[] = $mform->createElement('static', '', '',
                    ' ' . get_string('grade_atleast', 'condition').' ');
            $grouparray[] = $mform->createElement('text', 'conditiongrademin', '', array('size' => 3));
            $grouparray[] = $mform->createElement('static', '', '',
                    '% ' . get_string('grade_upto', 'condition') . ' ');
            $grouparray[] = $mform->createElement('text', 'conditiongrademax', '', array('size' => 3));
            $grouparray[] = $mform->createElement('static', '', '', '%');
            $group = $mform->createElement('group', 'config_conditiongradegroup',
                    get_string('gradecondition', 'condition'), $grouparray);

            // Get full version (including condition info) of section object
            //$ci = new condition_info_section($this->_customdata['cs']);
            //$fullcs = $ci->get_full_section();
            //$count = count($fullcs->conditionsgrade) + 1;
			$count = 1;

            // Grade conditions
            $this->repeat_elements(array($group), $count, array(), 'conditiongraderepeats',
                    'conditiongradeadds', 2, get_string('addgrades', 'condition'), true);
            $mform->addHelpButton('config_conditiongradegroup[0]', 'gradecondition', 'condition');

			// Do we display availability info to students?
            $showhide = array(
                CONDITION_STUDENTVIEW_SHOW => get_string('showavailabilitysection_show', 'block_course_marking'),
                CONDITION_STUDENTVIEW_HIDE => get_string('showavailabilitysection_hide', 'block_course_marking'));
            $mform->addElement('select', 'config_showavailability',get_string('showavailabilitysection', 'block_course_marking'), $showhide);
            $mform->setDefault('config_showavailability', $this->_customdata['showavailability']);
			
    }
}