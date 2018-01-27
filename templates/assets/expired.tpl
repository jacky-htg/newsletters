<!-- INCLUDE header.tpl -->
<!-- IF '${INFO_ALERT}' != '' -->
<div class="alert alert-info">
    <span class="icon icon-exclamation-sign"></span>
    ${INFO_ALERT}
</div>
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
<p>${STR_MSG}</p>
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