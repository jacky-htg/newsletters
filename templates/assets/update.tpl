<!-- INCLUDE header.tpl -->
<!-- IF '${INFO_ALERT}' != '' -->
<div class="alert alert-info">${INFO_ALERT}</div>
<!-- END IF -->
<!-- BEGIN show_errors -->
<div class="alert alert-danger alert-dismissable">
	<button class="close" aria-hidden="true" data-dismiss="alert">×</button>
	<h4 class="alert-heading">${STR_IDENTIFIED_FOLLOWING_ERRORS}:</h4>
	<ul>
		<!-- BEGIN row -->
		<li> ${ERROR}</li>
		<!-- END row -->
	</ul>
</div>
<!-- END show_errors -->
<!-- IF '${MSG_ALERT}' != '' -->
<div class="alert alert-success alert-dismissable">
	<button class="close" aria-hidden="true" data-dismiss="alert" type="button">×</button>
	${MSG_ALERT}
</div>
<!-- END IF -->
<script type="text/javascript">
$(document).ready(function(){
	$("#start_update").on("click", function(){
		$("#btn_refresh").html('<div id="progress_bar" class="progress progress-striped active"><div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" style="width: 1%;"></div></div><span style="padding: 10px" id="status_process">${STR_START_UPDATE}</span>');
		
		$.ajax({
			type: "GET",
			cache: false,
			url: "./?t=ajax&action=start_update&p=start",
			dataType: "json",
			success: function(data){
  				if (data.result == 'yes') {
					$('.bar').css('width', '20%');
					$("#status_process").text(data.status);
					updateFiles();
				} else {
					$("#btn_refresh").html('<a id="start_update" class="btn" href="#"><i class="icon-refresh"></i>${BUTTON_UPDATE}</a><span style="padding: 10px">' + data.status + '</span>');
				}
			}
		});
	});
});

function updateFiles()
{
	$.ajax({
		type: "GET",
		cache: false,
		url: "./?t=ajax&action=start_update&p=update_files",
		dataType: "json",
		success: function(data){
			if (data.result == 'yes') {
				$('.bar').css('width', '70%');
				updateBD();
			} else {
				$("#btn_refresh").html('<a id="start_update" class="btn" href="#"><i class="icon-refresh"></i>${BUTTON_UPDATE}</a><span style="padding: 10px">' + data.status + '</span>');
			}
		}
	});
}

function updateBD()
{
	$.ajax({
		type: "GET",
		cache: false,
		url: "./?t=ajax&action=start_update&p=update_bd",
		dataType: "json",
		success: function(data){
			if (data.result == 'yes') {
				$('.bar').css('width', '100%');
				$('#progress_bar').delay(3000).fadeOut();
				$('#status_process').delay(3000).text('${MSG_UPDATE_COMPLETED}');
			} else {
				$("#btn_refresh").html('<a id="start_update" class="btn" href="#"><i class="icon-refresh"></i>${BUTTON_UPDATE}</a><span style="padding: 10px">' + data.status + '</span>');
			}
		}
	});
}	

</script>
<div class="span12">
	<!-- IF '${BUTTON_UPDATE}' != '' -->
	<div id="btn_refresh"><a id="start_update" class="btn btn-outline btn-default" href="#"><i class="fa fa-refresh"></i> ${BUTTON_UPDATE} </a></div>
	<!-- END IF -->

	<!-- IF '${MSG_NO_UPDATES}' != '' -->
	<a class="btn btn-outline btn-default" disabled><i class="fa fa-refresh"></i> ${MSG_NO_UPDATES}</a>
	<!-- END IF -->
</div>
<form action="${ACTION}" method="post">
	<input type="hidden" name="action" value="post">
	<div class="form-group">
		<label for="license_key">${STR_LICENSE_KEY}</label>
		<input class="form-control" type="text" style="text-transform: uppercase;" name="license_key" value="${LICENSE_KEY}">
	</div>
	<div class="controls">
		<input type="submit" class="btn btn-success" value="${BUTTON_SAVE}">
	</div>
</form>
<!-- INCLUDE footer.tpl -->