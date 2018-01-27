<!-- INCLUDE header.tpl -->
<script type="text/javascript" src="./templates/js/jquery.paulund_modal_box.js"></script>
<script type="text/javascript">

	var DOM = (typeof(document.getElementById) != 'undefined');

	function Check_action()
	{
		if(document.forms[0].action.value == 0) { window.alert('${ALERT_SELECT_ACTION}'); }
	}

	function CheckAll_Activate(Element,Name)
	{
		if (DOM){
			thisCheckBoxes = Element.parentNode.parentNode.parentNode.parentNode.getElementsByTagName('input');

			var m = 0;

			for(var i = 1; i < thisCheckBoxes.length; i++){
				if (thisCheckBoxes[i].name == Name){
					thisCheckBoxes[i].checked = Element.checked;
					if (thisCheckBoxes[i].checked == true) { m++; }
					if (thisCheckBoxes[i].checked == false) { m--; }
				}
			}

			if (m > 0) { document.getElementById("apply").disabled = false; }
			else { document.getElementById("apply").disabled = true;  }
		}
	}

	function Count_checked()
	{
		var All = document.forms[0];
		var m = 0;

		for(var i = 0; i < All.elements.length; ++i){
			if(All.elements[i].checked) { m++; }
		}

		if(m > 0) { document.getElementById("apply").disabled = false; }
		else { document.getElementById("apply").disabled = true; }
	}

	function sendout()
	{
		var m = 0;
		pausesend = false;

		var All = document.forms[0];

		for(var i = 0; i < All.elements.length; ++i){
			if (All.elements[i].checked) { m++; }
		}

		typesend = 1;
		completed = null;
		successful = 0;
		unsuccessful = 0;
		totalmail = 0;

		if (m == 0) {
			saveResult('${ALERT_MALING_NOT_SELECTED}');
		} else {
			$('#timer2').text('00:00:00');
			$("#pausesendout").removeClass('disabled').removeAttr('disabled');
			$("#stopsendout").removeClass('disabled').removeAttr('disabled');
			$("#refreshemail").addClass('disabled').attr('disabled','disabled');
			$("#sendout").addClass('disabled').attr('disabled','disabled');
			$("#process").removeClass().addClass('showprocess');

			getcoutprocess();
			onlinelogprocess();
			process();
		}
	}

	function refreshsend()
	{
		pausesend = false;
		typesend = 2;
		completed = null;
		successful = 0;
		unsuccessful = 0;
		totalmail = 0;

		$('#timer2').text('00:00:00');
		$("#process").removeClass().addClass('showprocess');
		$("#refreshemail").addClass('disabled').attr('disabled','disabled');
		$("#sendout").addClass('disabled').attr('disabled','disabled');
		$("#stopsendout").removeClass('disabled').removeAttr('disabled');

		getcoutprocess();
		onlinelogprocess();
		process();
	}

	function stopsend(str)
	{
		$.ajax({
			type: 'GET',
			url:'./?t=ajax&action=process&status=' + str,
			dataType : "json",
			success:function(data){
				pausesend = true;
				show = false;
				$("#process").removeClass();
				$("#pausesendout").addClass('disabled').attr('disabled','disabled');
				$("#stopsendout").addClass('disabled').attr('disabled','disabled');
				$("#sendout").removeClass('disabled').removeAttr('disabled');
				$("#refreshemail").addClass('disabled').attr('disabled','disabled');

				if (str == 'stop'){
					$('#timer2').text('00:00:00');
					$('.progress-bar').css('width', '0%');
					$('#leftsend').text(0);
				}
			},
			error: function(error) { saveResult("${ALERT_ERROR_SERVER}: " + data.error); },
		});
	}

	function getcoutprocess()
	{
		if (pausesend == false && completed === null) {
			$.ajax({
				type:'GET',
				cache: false,
				url:'./?t=ajax&action=countsend&id_log=' + id_log,
				dataType : "json",
				success:function(data){
					if (id_log != undefined) {
						var totalmail = data.total;
						var successful = data.success;
						var unsuccessful = data.unsuccessful;
						var timeleft = data.time;
						var leftsend = data.leftsend;

						$('#totalsendlog').text(totalmail);
						$('#unsuccessful').text(unsuccessful);
						$('#successful').text(successful);
						$('#timer2').text(timeleft);
						onlinelogprocess();
						$('.progress-bar').css('width', leftsend + '%');
						$('#leftsend').text(leftsend);
						setTimeout('getcoutprocess(id_log)', 2000);
					} else { setTimeout('getcoutprocess()', 1000); }
				}
			});
		}
	}

	function onlinelogprocess()
	{
		if (pausesend == false && completed === null) {
			$.ajax({
				type:'GET',
				cache: false,
				url:'./?t=ajax&action=logonline',
				dataType : "json",
				success:function(data){
					var msg = '';
					var status;
					var email;
					id_log = data.item[0].id_log;

					for(var i=0; i < data.item.length; i++)	{
						if (data.item[i].status == "yes")
							status = '${STR_SENT}';
						else
							status = '${STR_WASNT_SENT}';
						email = data.item[i].email;

						if(email != 'undefined'){
							msg += email + ' - ' + status;
							msg += '<br>';
						}
						$('#onlinelog').html(msg);
					}
				},
			});
		}
	}

	function process()
	{
		var oForm = document.forms[0];
		var sBody = getRequestBody(oForm);

		if (pausesend == false){
			if (typesend == 1){
				var url = "./?t=ajax&action=send&typesend=1";
			} else {
				var url = "./?t=ajax&action=send&typesend=2";
			}

			if (completed != 'yes') {
				setTimeout('process()', 90000);
			}

			$.ajax({
				type:'POST',
				url:url,
				cache:false,
				data:sBody,
				dataType:"json",
				success:function(data){
					if (data.completed == 'yes'){
						$("#process").removeClass();
						completed = data.completed;
						completeProcess();
					} else {
						setTimeout('process()', 3000);
					}
				}
			});
		}
	}

	function completeProcess()
	{
		$("#pausesendout").addClass('disabled').attr('disabled','disabled');
		$("#stopsendout").addClass('disabled').attr('disabled','disabled');
		$("#sendout").removeClass('disabled').removeAttr('disabled');
		$("#refreshemail").removeClass('disabled').removeAttr('disabled');
		$("#process").removeClass();
		$("#timer2").text('00:00:00');
		$('#leftsend').text(100);
		$('.progress-bar').css('width', '100%');

		show = false;
		getcoutprocess();
	}

	function saveResult(sText){
		var sElem = document.getElementById("divStatus");
		sElem.innerHTML = sText;
	}

	function getRequestBody(oForm) {
		var aParams = new Array();

		for(var i = 0; i < oForm.elements.length; i++) {
			var sParam = encodeURIComponent(oForm.elements[i].name);

			if (sParam != ''){
				if (oForm.elements[i].name == 'activate[]'){
					if (oForm.elements[i].checked){
						var sParam = encodeURIComponent(oForm.elements[i].name);
						sParam += "=";
						sParam += encodeURIComponent(oForm.elements[i].value);
					}
				} else {
					var sParam = encodeURIComponent(oForm.elements[i].name);
					sParam += "=";
					sParam += encodeURIComponent(oForm.elements[i].value);
				}

				aParams.push(sParam);
			}
		}

		return aParams.join("&");
	}

	function PnumberChange()
	{
		var pnumber = document.getElementById("pnumber").value;
		document.cookie = "pnumber=" + pnumber;
		location.reload();
	}

</script>
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
<form action="${ACTION}" onSubmit="if(this.action.value == 0){window.alert('${ALERT_SELECT_ACTION}');return false;}if(this.action.value == 4){return confirm('${ALERT_CONFIRM_REMOVE}');} if(this.action.value == 1) return false" method="post">
	<table class="table table-bordered table-hover">
		<thead>
		<tr>
			<th width="10px"><input type="checkbox" title="TABLECOLMN_CHECK_ALLBOX" onclick="CheckAll_Activate(this,'activate[]');"></th>
			<th width="50px">ID</th>
			<th width="50%">${TH_TABLE_MAILER}</th>
			<th>${TH_TABLE_CATEGORY}</th>
			<th>${TH_TABLE_ACTIVITY}</th>
			<th width="70px">${TH_TABLE_POSITION}</th>
			<th>${TH_TABLE_EDIT}</th>
		</tr>
		</thead>
		<!-- BEGIN row -->
		<tbody>
		<!-- BEGIN column -->
		<tr <!-- IF '${CLASS_NOACTIVE}' == 'no' -->class="danger"<!-- END IF -->>
			<td style="vertical-align: middle;"><input type="checkbox" onclick="Count_checked();" title="${TABLECOLMN_CHECKBOX}" value="${ROW_ID_TEMPLATE}" name=activate[]></td>
			<td style="vertical-align: middle;">${ROW_ID_TEMPLATE}</td>
			<td style="vertical-align: middle;" class="text-left"><a title="${STR_EDIT_MAILINGTEXT}" href="./?t=edit_template&id_template=${ROW_ID_TEMPLATE}">${ROW_TMPLNAME}</a><br>
				<br>
				${ROW_CONTENT}
				</div></td>
			<td style="vertical-align: middle;">${ROW_CATNAME}</td>
			<td style="vertical-align: middle;">${ROW_ACTIVE}</td>
			<td style="vertical-align: middle;" class="text-center"><a href="./?id_template=${ROW_ID_TEMPLATE}&pos=up" class="btn btn-outline btn-default" title="${STR_DOWN}"><i class="fa fa-chevron-up"></i></a><br>

				<input style="margin-top: 15px; margin-bottom: 15px;" class="form-control" type="text" name="pos" value="${ROW_POS}">

				<a href="./?id_template=${ROW_ID_TEMPLATE}&pos=down" class="btn btn-outline btn-default" title="${STR_DOWN}"><i class="fa fa-chevron-down "></i></a></td>
			<td style="vertical-align: middle;" class="text-center"><a href="./?t=edit_template&id_template=${ROW_ID_TEMPLATE}" class="btn btn-outline btn-default" title="${STR_EDIT}"><i class="fa fa-edit"></i></a></td>
		</tr>
		<!-- END column -->
		<!-- END row -->
		</tbody>
	</table>
	<!-- BEGIN pagination -->
	<div class="row">
		<div class="col-sm-6">
			<div class="dataTables_length">
				<label> ${STR_PNUMBER}:
					<select onchange="PnumberChange(this);" class="span1 form-control" id="pnumber" name="pnumber">
						<option value="5" <!-- IF '${PNUMBER}' == 5 -->selected="selected"<!-- END IF -->> 5 </option>
						<option value="10" <!-- IF '${PNUMBER}' == 10 -->selected="selected"<!-- END IF -->> 10	</option>
						<option value="15" <!-- IF '${PNUMBER}' == 15 -->selected="selected"<!-- END IF -->> 15 </option>
						<option value="20" <!-- IF '${PNUMBER}' == 20 -->selected="selected"<!-- END IF -->> 20	</option>
						<option value="50" <!-- IF '${PNUMBER}' == 50 -->selected="selected"<!-- END IF -->> 50	</option>
						<option value="100"	<!-- IF '${PNUMBER}' == 100 -->selected="selected"<!-- END IF -->> 100	</option>
					</select>
				</label>
			</div>
		</div>
		<div class="col-sm-6">
			<div class="dataTables_paginate paging_simple_numbers">
				<ul class="pagination">
					<!-- IF '${PERVPAGE}' != '' -->
					<li class="paginate_button previous">${PERVPAGE}</li>
					<!-- END IF -->
					<!-- IF '${PERV}' != '' -->
					<li class="paginate_button previous">${PERV}</li>
					<!-- END IF -->
					<!-- IF '${PAGE2LEFT}' != '' -->
					<li class="paginate_button ">${PAGE2LEFT}</li>
					<!-- END IF -->
					<!-- IF '${PAGE1LEFT}' != '' -->
					<li class="paginate_button ">${PAGE1LEFT}</li>
					<!-- END IF -->
					<!-- IF '${CURRENT_PAGE}' != '' -->
					<li class="paginate_button active">${CURRENT_PAGE}</li>
					<!-- END IF -->
					<!-- IF '${PAGE1RIGHT}' != '' -->
					<li class="paginate_button ">${PAGE1RIGHT}</li>
					<!-- END IF -->
					<!-- IF '${PAGE2RIGHT}' != '' -->
					<li class="paginate_button ">${PAGE2RIGHT}</li>
					<!-- END IF -->
					<!-- IF '${NEXTPAGE}' != '' -->
					<li class="paginate_button next">${NEXTPAGE}</li>
					<!-- END IF -->
					<!-- IF '${NEXT}' != '' -->
					<li class="paginate_button next">${NEXT}</li>
					<!-- END IF -->
				</ul>
			</div>
		</div>
	</div>
	<!-- END pagination -->
	<div class="row">
		<div class="col-sm-12">
			<div class="form-inline">
				<div class="control-group">
					<select id="select_action" class="span3 form-control" name="action">
						<option value="0">--${STR_ACTION}--</option>
						<option value="1">${STR_SENDOUT}</option>
						<option value="2">${STR_ACTIVATE}</option>
						<option value="3">${STR_DEACTIVATE}</option>
						<option value="4">${STR_REMOVE}</option>
					</select>
          <span class="help-inline">
          <input type="submit" id="apply" value="${STR_APPLY}" class="btn btn-success" disabled="" name="">
          </span> </div>
			</div>
		</div>
	</div>
</form>
<script type="text/javascript">

	var modalform = '<div id="onlinelog"></div>';
	modalform += '<p><span id="leftsend">0</span>% ${STR_TIME_LEFT}: <span id="timer2">00:00:00</span></p>';
	modalform += '<div class="progress progress-striped active"><div id="progressbarsend" class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 0%">';
	modalform += '</div></div>';
	modalform += '<input id="id_log" type="hidden" value="0">';
	modalform += '<div class="online_statistics">${STR_TOTAL}: <span id="totalsendlog">0</span> ';
	modalform += '<span style="color: green">${STR_GOOD}: </span><span style="color: green" id="successful">0</span> <span style="color: red">${STR_BAD}: </span><span style="color: red" id="unsuccessful">0</span><br><br>';
	modalform += '<button onClick="sendout();" id="sendout" class="btn btn-default btn-circle btn-modal btn-lg" title="${STR_SENDOUT_TO_SUBSCRIBERS}"><i class="fa fa-play"></i></button>';
	modalform += '<button onClick="stopsend(\'pause\');" id="pausesendout" class="btn btn-warning btn-circle btn-lg btn-modal disabled" disabled="disabled" title="${STR_PAUSE_SENDING}"><i class="fa fa-pause"></i></button>';
	modalform += '<button onClick="refreshsend();" id="refreshemail" class="btn btn-info btn-circle btn-lg btn-modal disabled" disabled="disabled" title="${STR_REFRESH_SENDING}"><i class="fa fa-undo"></i></button>';
	modalform += '<button onClick="stopsend(\'stop\');" id="stopsendout" class="btn btn-danger btn-circle btn-lg disabled" disabled="disabled" title="${STR_STOP_SENDING}"><i class="fa fa-stop"></i></button></div>';
	modalform += '<span id="divStatus" class="error"></span>';

	$(document).ready(function(){
		$('#apply').paulund_modal_box({
			title:'${STR_ONLINE_MAILINGLOG}',
			description: modalform
		});
	});

</script>
<!-- INCLUDE footer.tpl -->