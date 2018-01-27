<!DOCTYPE html>
<html>
<head>
<title>${TITLE_SUBSCRIBE}</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
</head>
<body>
<div class="subform">  
  <form action="${ACTION}" method="post" accept-charset="utf-8">
  <input type="hidden" name="action" value="post">
    <!-- BEGIN row -->
    <p>
      <input type="checkbox" checked="checked" value="${ID_CAT}" name="id_cat[]">${NAME}
    </p>
    <!-- END row -->
    <table cellpadding="0" cellspacing="6">
      <tr>
        <td>${STR_NAME}</td>
        <td><input size="30" type="text" name="name"></td>
      </tr>
      <tr>
        <td>${STR_EMAIL}</td>
        <td><input size="30" type="text" name="email"></td>
      </tr>
      <tr>
        <td></td>
        <td><input type="submit" value="${BUTTON_SUBSCRIBE}"></td>
      </tr>
    </table>
  </form>
</div>
</body>
</html>