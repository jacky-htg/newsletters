<!-- INCLUDE header.tpl -->
<p>« <a href="${RETURN_BACK_LINK}">${RETURN_BACK}</a></p>

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


<form method="POST" action="${ACTION}">
<input type="hidden" name="id" value="${ID}">
<p>* - ${STR_REQUIRED_FIELDS}</p>

<div class="form-group">
<label for="password">${STR_PASSWORD}*</label>
<input class="form-control" type="password" name="password">
</div>

<div class="form-group">
<label for="password_again">${STR_PASSWORD_AGAIN}*</label>
<input class="form-control" type="password" name="password_again">
</div>

<div class="form-group">
  <label for="status">${STR_ROLE}*</label>
  <select for="status" name="status" class="form-control form-primary">
	 <option value="admin" <!-- IF '${USER_ROLE}' == 'admin' -->selected="selected"<!-- END IF -->>${STR_ADMIN}</option>
	 <option value="moderator"  <!-- IF '${USER_ROLE}' == 'moderator' -->selected="selected"<!-- END IF -->>${STR_MODERATOR}</option>
     <option value="editor"  <!-- IF '${USER_ROLE}' == 'editor' -->selected="selected"<!-- END IF -->>${STR_EDITOR}</option>
   </select>
</div>

<input type="submit" class="btn btn-success" name="action" value="${BUTTON}">
</form>
<!-- INCLUDE footer.tpl -->