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

<span class="info-import"></span>
<form enctype="multipart/form-data" action="${ACTION}" method="post">
    <div class="form-group">
        <label for="file">${STR_DATABASE_FILE}</label>
        <input type="file" name="file" value="">
    </div>
    <div class="form-group">
        <label for="charset">${STR_CHARSET}</label>
        <select class="form-control form-primary" name="charset">
            <option value="">--${STR_NO}--</option>
            ${OPTION}
        </select>
    </div>

    <div class="form-group">
        <label class="control-label" for="id_cat[]">${STR_CATEGORY}</label>
        <!-- BEGIN row -->
        <div class="checkbox">
            <label>
                <input type="checkbox" value="${ID_CAT}" name="id_cat[]">
                ${NAME}
            </label>
        </div>
        <!-- END row -->
    </div>
    <div class="form-group">
        <input class="btn btn-success" type="submit" name="action" value="${BUTTON_ADD}">
    </div>
</form>
<!-- INCLUDE footer.tpl -->