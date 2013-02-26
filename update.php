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
require('../../config.php');

global $CFG, $COURSE, $USER, $DB;

$data = $_POST;

if($data['type'] == 'add'){
	$courseMarking = $DB->get_record('course_marking', array('course_id'=>$data['courseid'],'user_id'=>$data['userid']));

	if(!$courseMarking){
		$fieldData->course_id = $data['courseid'] ;
		$fieldData->user_id = $data['userid'];
		$DB->insert_record('course_marking', $fieldData);
	}
}elseif($data['type'] == 'remove'){
	$DB->delete_records('course_marking', array('course_id'=>$data['courseid'],'user_id'=>$data['userid']));
}

redirect($data['return']);
?>