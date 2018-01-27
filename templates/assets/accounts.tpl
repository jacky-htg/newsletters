<!-- INCLUDE header.tpl -->
<!-- IF '${INFO_ALERT}' != '' -->
<div class="alert alert-info"> ${INFO_ALERT} </div>
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
  ${MSG_ALERT} </div>
<!-- END IF -->
<a href="./?t=change_password">Сменить пароль</a><br>
<form method="POST" action="./?t=add_account">
  <table class="table table-striped table-bordered table-hover">
    <thead>
    <tr>
      <th>${TH_TABLE_LOGIN}</th>
      <th>${TH_TABLE_ROLE}</th>
      <th>${TH_TABLE_ACTION}</th>
    </tr>
    </thead>
    <tbody>
    <!-- BEGIN row -->
    <tr>
      <td>${LOGIN}</td>
      <td>${ROLE}</td>
      <td>
        <!-- IF '${ALLOW_EDIT}' == 'yes' -->
        <a class="btn btn-outline btn-default" title="${STR_EDIT}" href="./?t=edit_account&id=${ID}"><i class="fa fa-edit"></i></a> <a class="btn btn-outline btn-danger" title="${STR_REMOVE}" href="./?t=accounts&action=remove&id=${ID}"><i class="fa fa-trash-o"></i></a>
        <!-- END IF -->
      </td>
    </tr>
    <!-- END row -->
    </tbody>
  </table>
  <br>
  <input class="btn btn-success" type="submit" value="${BUTTON_ADD}">
</form>
<!-- INCLUDE footer.tpl -->