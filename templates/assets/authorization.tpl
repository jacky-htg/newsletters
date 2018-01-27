<!DOCTYPE html>
<html>
<head>
<title>${TITLE}</title>
<meta http-equiv="content-type" content="text/html; charset=utf-8">
<!-- Bootstrap Core CSS -->

<!-- Bootstrap Core CSS -->
<link href="./templates/assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

<!-- MetisMenu CSS -->
<link href="./templates/assets/vendor/metisMenu/metisMenu.min.css" rel="stylesheet">

<!-- Custom CSS -->
<link href="./templates/assets/dist/css/sb-admin-2.css" rel="stylesheet">

<!-- Custom Fonts -->
<link href="./templates/assets/vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
</head>
<body>
<div class="container">
    <div class="row">
        <div class="col-md-4 col-md-offset-4">
            <div class="login-panel panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">${STR_ADMIN_AREA}  ${SCRIPT_NAME}</h3>
                </div>
                <div class="panel-body">
                    <form role="form"  method="post">
                        <fieldset>
                            <div class="form-group">
                                <input class="form-control" type="text" name="login" value="${LOGIN}" placeholder="${STR_LOGIN}" autofocus>
                            </div>
                            <div class="form-group">
                                <input class="form-control" type="password" name="password" placeholder="${STR_PASSWORD}">
                            </div>
                            <input type="submit" class="btn btn-primary" name="admin" value=" OK ">
                        </fieldset>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- jQuery -->
<script src="./templates/assets/vendor/jquery/jquery.min.js"></script>
<!-- Bootstrap Core JavaScript -->
<script src="./templates/assets/vendor/bootstrap/js/bootstrap.min.js"></script>
</body>
</html>