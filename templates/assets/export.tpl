<!-- INCLUDE header.tpl -->
<p>« <a href="./?t=subscribers">${STR_BACK}</a></p>
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
<form action="${ACTION}" target=_blank method="post">
  <div class="form-group">
    <label for="export_type">${STR_EXPORT}</label>
    <div class="radio">
      <label> <input type="radio" value="1" checked name="export_type"> ${STR_EXPORT_TEXT} </label>
      <label> <input type="radio" value="2" name="export_type"> ${STR_EXPORT_EXCEL} </label>
    </div>
  </div>
  <div class="form-group">
    <label class="control-label" for="zip">${STR_COMPRESSION}</label>
    <div class="radio">
      <label> <input type="radio" checked value="1" name="zip"> ${STR_COMPRESSION_OPTION_1} </label>
      <label> <input type="radio" value="2" name="zip"> ${STR_COMPRESSION_OPTION_2} </label>
    </div>
  </div>
  <div class="form-group">
    <label class="control-label" for="id_cat[]">${STR_CATEGORY}</label>
    <!-- BEGIN categories_list -->
    <div class="checkbox">
      <label><input type="checkbox" checked="checked" value="${ID_CAT}" name="id_cat[]">${NAME} </label>
    </div>
    <!-- END categories_list -->
  </div>
  <div class="form-group">
    <input class="btn btn-success" type="submit" name="action" value="${BUTTON_APPLY}">
  </div>
</form>
<!-- INCLUDE footer.tpl -->