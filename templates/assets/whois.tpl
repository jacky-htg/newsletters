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
<p>« <a href="./?t=subscribers">${RETURN_BACK}</a></p>
<!-- BEGIN whois -->
<table class="table-hover table table-bordered" border="0" cellspacing="0" cellpadding="0" width="100%">
  <thead>
    <tr>
      <th class="catmenu menu">${TH_TABLE_IP_INFO}</th>
    </tr>
  </thead>
  <tbody>
    <!-- BEGIN row -->
    <tr class="trcat">
      <td>${SOCK}<br></td>
    </tr>
    <!-- END row -->
  <tbody>
</table>
<!-- IF '${STR_ERROR}' != '' -->
<span class="error">${STR_ERROR}</span>
<!-- END IF -->
<!-- END whois -->
<!-- INCLUDE footer.tpl -->