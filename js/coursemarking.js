$(document).ready(function(){
	$('#id_timeopen_enabled').click(function(){
		if($(this).attr("checked") == "checked"){
			$('#id_timeopen_day,#id_timeopen_month,#id_timeopen_year,#id_timeopen_hour,#id_timeopen_minute').removeAttr('disabled');
		}else{
			$('#id_timeopen_day,#id_timeopen_month,#id_timeopen_year,#id_timeopen_hour,#id_timeopen_minute').attr('disabled','true');
		}
	});

	$('#id_timeclose_enabled').click(function(){
		if($(this).attr("checked") == "checked"){
			$('#id_timeclose_day,#id_timeclose_month,#id_timeclose_year,#id_timeclose_hour,#id_timeclose_minute').removeAttr('disabled');
		}else{
			$('#id_timeclose_day,#id_timeclose_month,#id_timeclose_year,#id_timeclose_hour,#id_timeclose_minute').attr('disabled','true');
		}
	});

	$('#conditiongradeadds').click(function(){
		var count = $('#fitem_conditiongradeadds_count').val();
		var html = '';
		html = '<div class="fitem fitem_fgroup">'+
			   '<div class="fitemtitle">'+
			   '<div class="fgrouplabel"><label>Grade condition &nbsp;</label></div></div>'+
			   '<fieldset class="felement fgroup"><label class="accesshide">&nbsp;</label><select name="conditiongradegroup['+count+'][conditiongradeitemid]">'+
			   '<option value="0">(none)</option><option value="1">Course total</option></select>'+
		       '&nbsp; must be at least &nbsp;<label class="accesshide">&nbsp;</label><input size="3" name="conditiongradegroup['+count+'][conditiongrademin]" type="text">'+
		       '&nbsp;% and less than &nbsp;<label class="accesshide">&nbsp;</label><input size="3" name="conditiongradegroup['+count+'][conditiongrademax]" type="text">&nbsp;%</fieldset></div>';
		$('#grade-group').append(html);
		count++;
		$('#fitem_conditiongradeadds_count').val(count);
	});
})
