<!-- INCLUDE header.tpl -->
<script type="text/javascript">

var DOM = (typeof(document.getElementById) != 'undefined');

function CheckAll_Activate(Element,Name)
{
	if(DOM){
		thisCheckBoxes = Element.parentNode.parentNode.parentNode.parentNode.parentNode.getElementsByTagName('input');

		var m = 0;

		for(var i = 1; i < thisCheckBoxes.length; i++){
			if (thisCheckBoxes[i].name == Name){
				thisCheckBoxes[i].checked = Element.checked;
				if (thisCheckBoxes[i].checked == true) m++;
				if (thisCheckBoxes[i].checked == false) m--;
			}
		}

		if (m > 0) { document.getElementById("apply").disabled = false; }
		else { document.getElementById("apply").disabled = true;  }
	}
}

function Count_checked()
{
	var All = document.forms[1];
	var m = 0;

	for(var i = 0; i < All.elements.length; ++i){
		if (All.elements[i].checked) { m++; }
	}

	if (m > 0) { document.getElementById("apply").disabled = false; }
	else { document.getElementById("apply").disabled = true; }
}
	
function PnumberChange()
{
	var pnumber = document.getElementById("pnumber").value;
	document.cookie = "pnumber_subscribers=" + pnumber;
	location.reload();
}

</script>
<!-- IF '${INFO_ALERT}' != '' -->
<div class="alert alert-info">${INFO_ALERT}</div>
<!-- END IF -->
<!-- IF '${MSG_ALERT}' != '' -->
<div class="alert alert-success alert-dismissable">
  <button class="close" aria-hidden="true" data-dismiss="alert" type="button">×</button>
  ${MSG_ALERT}
</div>
<!-- END IF -->
<div class="row">
  <div class="col-lg-12">
    <div class="BtnPanelIcon">
     <a class="btn btn-outline btn-default btn-lg" title="${PROMPT_ADD_USER}" href="./?t=add_user"> <span class="fa fa-user-plus fa-2x"></span> <span class="IconText">${STR_ADD_USER}</span> </a>
     <a class="btn btn-outline btn-danger btn-lg" title="${PROMPT_REMOVE_SUBSCRIBERS}" onclick="return confirm('${ALERT_CLEAR_ALL}');" href="./?t=subscribers&remove=all"> <span class="fa fa-trash-o fa-2x"></span> <span class="IconText">${STR_REMOVE_ALL_SUBSCRIBERS}</span> </a>
     <a class="btn btn-outline btn-default btn-lg" title="${PROMPT_IMPORT_SUBSCRIBERS}" href="./?t=import"> <span class="fa fa-download fa-2x"></span> <span class="IconText">${STR_IMPORT_USER}</span> </a>
     <a class="btn btn-outline btn-default btn-lg" title="${PROMPT_EXPORT_SUBSCRIBERS}" href="./?t=export"> <span class="fa fa-upload fa-2x"></span> <span class="IconText">${STR_EXPORT_USER}</span> </a>
    </div>
  </div>
</div>
<div class="row">
  <div class="col-lg-12">
    <form class="form-inline" style="margin-bottom: 20px; margin-top: 20px;" method="GET" name="searchform" action="${ACTION}">
     <input type="hidden" name="t" value="subscribers">
       <div class="form-group">
       <input class="form-control form-warning input-sm" type="text" name="search" value="${SEARCH}" placeholder="${FORM_SEARCH_NAME}">
       </div>
      <input class="btn btn-info" type="submit" value="${BUTTON_FIND}">
    </form>
  </div>
</div>
<!-- BEGIN show_return_back -->
<p>« <a href="./?t=subscribers">${STR_BACK}</a></p>
<!-- END show_return_back -->
<!-- IF '${ERROR_ALERT}' != '' -->
<div class="alert alert-danger alert-dismissable">
  <button class="close" aria-hidden="true" data-dismiss="alert">×</button>
  <strong>${STR_ERROR}!</strong> ${ERROR_ALERT}
</div>
<!-- END IF -->
<!-- IF '${MSG_ALERT}' != '' -->
<div class="alert alert-success alert-dismissable">
  <button class="close" aria-hidden="true" data-dismiss="alert" type="button">×</button>
  ${MSG_ALERT}
</div>
<!-- END IF -->
<!-- BEGIN row -->
<form class="form-horizontal" action="${ACTION}" onSubmit="if(this.action.value == 0){window.alert('${ALERT_SELECT_ACTION}');return false;}if(this.action.value == 3){return confirm('${ALERT_CONFIRM_REMOVE}');}" method="post">
  <table class="table table-bordered table-hover">
    <thead>
      <tr>
        <th width="10px"><input type="checkbox" title="${STR_CHECK_ALLBOX}" onclick="CheckAll_Activate(this,'activate[]');"></th>
        <th class="${TH_CLASS_NAME}"><a href="./?t=subscribers&name=${GET_NAME}<!-- IF '${SEARCH}' != '' -->&search=${SEARCH}<!-- END IF -->${PAGENAV}">${TABLE_NAME}</a></th>
        <th class="${TH_CLASS_EMAIL}"><a href="./?t=subscribers&email=${GET_EMAIL}${PAGENAV}<!-- IF '${SEARCH}' != '' -->&search=${SEARCH}<!-- END IF -->">${TABLE_EMAIL}</a></th>
        <th class="${TH_CLASS_TIME}"><a href="./?t=subscribers&time=${GET_TIME}${PAGENAV}<!-- IF '${SEARCH}' != '' -->&search=${SEARCH}<!-- END IF -->">${TABLE_ADDED}</a></th>
        <th>IP</th>
        <th class="${TH_CLASS_STATUS}"><a href="./?t=subscribers&status=${GET_STATUS}${PAGENAV}<!-- IF '${SEARCH}' != '' -->&search=${SEARCH}<!-- END IF -->">${TABLE_STATUS}</a></th>
        <th width="250px">${TABLE_ACTION}</th>
      </tr>
    </thead>
    <tbody>
      <!-- BEGIN column -->
      <tr <!-- IF '${STATUS_CLASS}' == 'noactive' -->class="danger"<!-- END IF -->>
        <td style="vertical-align: middle;"><input type="checkbox" onclick="Count_checked();" title="${STR_CHECK_BOX}" value="${ID_USER}" name="activate[]"></td>
        <td style="vertical-align: middle;">${NAME}</td>
        <td style="vertical-align: middle;">${EMAIL}</td>
        <td style="vertical-align: middle;">${PUTDATE_FORMAT}</td>
        <td style="vertical-align: middle;"><a title="${PROMPT_IP_INFO}" href="./?t=whois&ip=${IP}">${IP}</a></td>
        <td style="vertical-align: middle;">${STR_STAT}</td>
        <td style="vertical-align: middle;"><a class="btn btn-outline btn-default" href="./?t=edit_user&id_user=${ID_USER}" title="${STR_EDIT}"> <i class="fa fa-edit"></i></a> <a class="btn btn-outline btn-danger" href="./?t=subscribers&remove=${ID_USER}" title="${STR_REMOVE}"> <i class="fa fa-trash-o"></i></a>
		</td>
      </tr>
      <!-- END column -->
    </tbody>
  </table>
  <!-- BEGIN pagination -->
  <div class="row">
    <div class="col-sm-6">
      <div class="dataTables_length">
        <label>
          ${STR_PNUMBER}: <select onchange="PnumberChange(this);" class="span1 form-control" id="pnumber" name="pnumber">
            <option value="5"<!-- IF '${PNUMBER}' == 5 --> selected="selected"<!-- END IF -->> 5 </option>
            <option value="10"<!-- IF '${PNUMBER}' == 10 --> selected="selected"<!-- END IF -->> 10 </option>
            <option value="15"<!-- IF '${PNUMBER}' == 15 --> selected="selected"<!-- END IF -->> 15 </option>
            <option value="20"<!-- IF '${PNUMBER}' == 20 --> selected="selected"<!-- END IF -->> 20 </option>
            <option value="50"<!-- IF '${PNUMBER}' == 50 --> selected="selected"<!-- END IF -->> 50 </option>
            <option value="100"<!-- IF '${PNUMBER}' == 100 --> selected="selected"<!-- END IF -->> 100 </option>
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
            <option value="1">${STR_ACTIVATE}</option>
            <option value="2">${STR_DEACTIVATE}</option>
            <option value="3">${STR_REMOVE}</option>
          </select>
      <span class="help-inline">
      <input type="submit" id="apply" value="${STR_APPLY}" class="btn btn-success" disabled="" name="">
      </span> </div>
      </div>
    </div>
  </div>
</form>
<p>${STR_NUMBER_OF_SUBSCRIBERS}: ${NUMBER_OF_SUBSCRIBERS}</p>
<!-- END row -->
<!-- IF '${EMPTY_LIST}' != '' -->
<div class="warning_msg">${EMPTY_LIST}</div>
<!-- END IF -->
<!-- BEGIN notfound -->
<div class="alert">
  <button class="close" data-dismiss="alert">×</button>
  ${MSG_NOTFOUND} </div>
<!-- END notfound -->
<!-- INCLUDE footer.tpl -->
