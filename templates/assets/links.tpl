<!-- INCLUDE header.tpl -->
<script type="text/javascript">

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
     <a class="btn btn-outline btn-default btn-lg" title="${PROMPT_EXPORT_LINKS}" href="./?t=export_links"> <span class="fa fa-upload fa-2x"></span> <span class="IconText">${STR_EXPORT_LINKS}</span> </a>
    </div>
  </div>
</div>
<div class="row">
  <div class="col-lg-12">
    <form class="form-inline" style="margin-bottom: 20px; margin-top: 20px;" method="GET" name="searchform" action="${ACTION}">
     <input type="hidden" name="t" value="links">
     <input type="hidden" name="type" value="detail">
       <div class="form-group">
       <input class="form-control form-warning input-sm" type="text" name="search" value="${SEARCH}" placeholder="${FORM_SEARCH_NAME}">
       </div>
      <input class="btn btn-info" type="submit" value="${BUTTON_FIND}">
    </form>
  </div>
</div>

<p>« <a href="./?t=links">Back</a></p>

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
<form class="form-horizontal" action="${ACTION}" method="post">
  <table class="table table-bordered table-hover">
    <thead>
      <tr>
        <th class="${TH_CLASS_NAME}"><a href="./?t=links&type=detail&name=${GET_NAME}<!-- IF '${SEARCH}' != '' -->&search=${SEARCH}<!-- END IF -->${PAGENAV}">${TABLE_NAME}</a></th>
        <th class="${TH_CLASS_EMAIL}"><a href="./?t=links&type=detail&email=${GET_EMAIL}${PAGENAV}<!-- IF '${SEARCH}' != '' -->&search=${SEARCH}<!-- END IF -->">${TABLE_EMAIL}</a></th>
        <th class="${TH_CLASS_URL}"><a href="./?t=links&type=detail&url=${GET_URL}${PAGENAV}<!-- IF '${SEARCH}' != '' -->&search=${SEARCH}<!-- END IF -->">${TABLE_URL}</a></th>
        <th class="${TH_CLASS_IP}"><a href="./?t=links&type=detail&url=${GET_IP}${PAGENAV}<!-- IF '${SEARCH}' != '' -->&search=${SEARCH}<!-- END IF -->">${TABLE_IP}</a></th>
        <th class="${TH_CLASS_COUNTRY}"><a href="./?t=links&type=detail&url=${GET_COUNTRY}${PAGENAV}<!-- IF '${SEARCH}' != '' -->&search=${SEARCH}<!-- END IF -->">${TABLE_COUNTRY}</a></th>
        <th class="${TH_CLASS_CITY}"><a href="./?t=links&type=detail&url=${GET_CITY}${PAGENAV}<!-- IF '${SEARCH}' != '' -->&search=${SEARCH}<!-- END IF -->">${TABLE_CITY}</a></th>
        <th class="${TH_CLASS_CREATED_AT}"><a href="./?t=links&type=detail&url=${GET_CREATED_AT}${PAGENAV}<!-- IF '${SEARCH}' != '' -->&search=${SEARCH}<!-- END IF -->">${TABLE_CREATED_AT}</a></th>
      </tr>
    </thead>
    <tbody>
      <!-- BEGIN column -->
      <tr <!-- IF '${STATUS_CLASS}' == 'noactive' -->class="danger"<!-- END IF -->>
        <td style="vertical-align: middle;">${NAME}</td>
        <td style="vertical-align: middle;">${EMAIL}</td>
        <td style="vertical-align: middle;">${URL}</td>
        <td style="vertical-align: middle;">${IP}</td>
        <td style="vertical-align: middle;">${COUNTRY}</td>
        <td style="vertical-align: middle;">${CITY}</td>
        <td style="vertical-align: middle;">${CREATED_AT}</td>
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
  
</form>
<p>${STR_NUMBER_OF_LINKS}: ${NUMBER_OF_LINKS}</p>
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
