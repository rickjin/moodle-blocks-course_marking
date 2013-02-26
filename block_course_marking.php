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
class block_course_marking extends block_base {

    public function init() {
        $this->title = get_string('pluginname', 'block_course_marking');
    }

    public function instance_allow_multiple() {
        return false;
    }

    public function has_config() {
        return true;
    }

	public function get_content() {
		global $CFG, $COURSE, $USER, $DB;

		$parentContextId = $this->instance->parentcontextid;

		if ($this->content !== null) {
		  return $this->content;
		}
		
		$canAddtolist = true;
		$canRemovefromlist = false;
		$showBlock = true;

		$currentTime = time();
		$setting = $this->getSetting($parentContextId);
		if(!$setting['showavailability']){
			$showBlock = false;
		}

		$openTime = $closeTime = '';
		if(isset($setting['timeopen']))$openTime = $setting['timeopen'];
		if(isset($setting['timeclose']))$closeTime = $setting['timeclose'];

		if($openTime&&($currentTime<$openTime)){
			$showBlock = false;
		}
		
		if($closeTime&&($currentTime>$closeTime)){
			$showBlock = false;
		}

		$courseTotal = $this->getCourseTotal($COURSE->id,$USER->id);

		if(is_array($setting['conditiongradegroup'])){
			foreach($setting['conditiongradegroup'] as $gardeGroup){
				if($gardeGroup['conditiongradeitemid'] == 1){
					if($gardeGroup['conditiongrademin']!=''&&$courseTotal<$gardeGroup['conditiongrademin']){
						$showBlock = false;
					}elseif($gardeGroup['conditiongrademax']!=''&&$courseTotal>$gardeGroup['conditiongrademax']){
						$showBlock = false;
					}
				}
			}
		}

		if($showBlock){
		$courseMarking = $DB->get_record('course_marking', array('course_id'=>$COURSE->id,'user_id'=>$USER->id));
		if($courseMarking){
			$canRemovefromlist = true;
		}

		$build = '';
		$build.= '<link rel="stylesheet" type="text/css" href="../blocks/course_marking/style.css" media="all">';
		$build.= '<form action="'.$CFG->wwwroot.'/blocks/course_marking/update.php" method="post">';
		$build.= '<input type="hidden" name="courseid" value='.$COURSE->id.'>';
		$build.= '<input type="hidden" name="userid" value='.$USER->id.'>';
		$build.= '<input type="hidden" name="return" value="'.$CFG->wwwroot.'/course/view.php?id='.$COURSE->id.'">';
		if($canRemovefromlist){
			$build.= '<input type="hidden" name="type" value="remove">';
			$build.= '<div align="center"><input type="submit" class="redbutton" value="'.get_string('removefromlist', 'block_course_marking').'"></div>';
		}elseif($canAddtolist){
			$build.= '<input type="hidden" name="type" value="add">';
			$build.= '<div align="center"><input type="submit" class="greenbutton" value="'.get_string('addtolist', 'block_course_marking').'"></div>';
		}
		$build.= '</form>';
		
		$this->content         =  new stdClass;
		$this->content->text   = $build;
 
		return $this->content;
		}
	}

	function getSetting($parentContextId){
		global $DB;
		$res = $DB->get_record('block_instances',array('blockname'=>'course_marking','parentcontextid'=>$parentContextId));
		if($res->configdata != ''){
			$setting = unserialize(base64_decode($res->configdata));
			@$settingData['timeopen'] = $setting->availablefrom;
			@$settingData['timeclose'] = $setting->availableuntil;
			@$settingData['conditiongradegroup'] = $setting->conditiongradegroup;
			@$settingData['showavailability'] = $setting->showavailability;
			return $settingData;
		}
	}

	function getCourseTotal($courseId,$userId){
		global $DB;
		$res = $DB->get_record_sql('select g.finalgrade as total from mdl_grade_items as gi,mdl_grade_grades as g where g.itemid=gi.id and gi.courseid='.$courseId.' and g.userid='.$userId." and usermodified is null limit 1");
		if($res){
			return round($res->total);
		}else{
			return false;
		}
	}
}
?>