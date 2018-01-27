<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>Independen Newsletter | ${TITLE_PAGE}</title>
	<!-- Bootstrap Core CSS -->
	<link href="./templates/assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
	<!-- MetisMenu CSS -->
	<link href="./templates/assets/vendor/metisMenu/metisMenu.min.css" rel="stylesheet">
	<!-- DataTables CSS -->
	<link href="./templates/assets/vendor/datatables-plugins/dataTables.bootstrap.css" rel="stylesheet">
	<!-- DataTables Responsive CSS -->
	<link href="./templates/assets/vendor/datatables-responsive/dataTables.responsive.css" rel="stylesheet">
	<!-- Custom CSS -->
	<link href="./templates/assets/dist/css/sb-admin-2.css" rel="stylesheet">
	<!-- Custom Fonts -->
	<link href="./templates/assets/vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
	<link href="./templates/assets/styles/styles.css" rel="stylesheet">
	<link href="./templates/assets/styles/css/uploadfile.css" rel="stylesheet">
	<link href="./templates/assets/styles/jquery-ui-1.8.16.custom.css" rel="stylesheet">
	<script src="./templates/js/jquery.min.js"></script>
	<script src="./templates/assets/vendor/bootstrap/js/bootstrap.min.js"></script>
	<script src="./templates/js/jquery.hide_alertblock.js"></script>
	<script src="./templates/js/jquery.cookie.js"></script>
</head>
<body>
<script type="text/javascript">
	$(document).ready(function(){
		$.ajax({
			cache: false,
			url: './?t=ajax&action=alert_update',
			dataType: "json",
			success: function(data){
				if (data.msg != '' && $.cookie('alertshow') != 'no'){
					$('#alert_msg_block').fadeIn('700');
					$("#alert_warning_msg").append(data.msg);
				}
			}
		});

		setInterval(function() {
			$.ajax({
				type: "GET",
				cache: false,
				url: "./?t=ajax&action=daemonstat",
				dataType: "json",
				success: function(data) {
					if (data.status != ''){
						if (data.status == 'start'){
							$("#mailing_status").html('<span title="${STR_LAUNCHEDMAILING}" id="startmailing" class="startmailing"></span>');
						}
						else{
							$("#mailing_status").html('<span title="${STR_STOPMAILING}" class="stopmailing"></span>');
						}
					}
				}
			});
		}, 5000);
		
		
	});

	$(document).on( "click", ".startmailing", function() {
		$.ajax({
			type: "GET",
			url: "./?t=ajax&action=process&status=stop",
			dataType: "json",
			success: function(data){
				$("#mailing_status").html('<span title="${STR_STOPMAILING}" class="stopmailing"></span>');
			}
		});
	});
	
	$(document).on( "click", "a.opislink:not(.active)", function() {
	$(this).addClass('active');
			$(this).parent().find('div.opis').slideDown(760);
			return false;	
	});
	
	$(document).on( "click", "a.opislink.active", function() {
	$(this).removeClass('active');
			$(this).parent().find('div.opis').slideUp(760);
			return false;	
	});	

</script>
<div id="wrapper">
	<!-- Navigation -->
	<nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
		<div class="navbar-header"> <span class="logo"></span></div>
		<!-- /.navbar-header -->
		<ul class="nav navbar-top-links navbar-right">
			<!-- /.dropdown -->
			<li id="mailing_status">
				<!-- IF '${MAILING_STATUS}' == 'start' -->
				<span title="${STR_LAUNCHEDMAILING}" id="startmailing" class="startmailing"></span>
				<!-- ELSE -->
				<span title="${STR_STOPMAILING}" class="stopmailing"></span>
				<!-- END IF -->
			</li>
			<li class="dropdown"> <a class="dropdown-toggle" data-toggle="dropdown" href="#"> <i class="fa fa-user fa-fw"></i> <i class="fa fa-caret-down"></i> </a>
				<ul class="dropdown-menu dropdown-user">
					<li><a href="./?t=accounts"><i class="fa fa-user fa-fw"></i> ${ACCOUNT_LOGIN}</a></li>
					<li class="divider"></li>
					<li><a href="./?t=logout"><i class="fa fa-sign-out fa-fw"></i> ${STR_LOGOUT}</a> </li>
				</ul>
				<!-- /.dropdown-user -->
			</li>
			<!-- /.dropdown -->
		</ul>
		<!-- /.navbar-top-links -->
		<div class="navbar-default sidebar" role="navigation">
			<div class="sidebar-nav navbar-collapse">
				<ul class="nav" id="side-menu">
					<li><a	<!-- IF '${ACTIVE_MENU}' == '' -->class="active"<!-- END IF -->	href="./" title="${MENU_TEMPLATES_TITLE}"><i class="fa fa-envelope"></i> ${MENU_TEMPLATES}</a></li>
					<li><a	<!-- IF '${ACTIVE_MENU}' == 'create_template' -->class="active"<!-- END IF -->	href="./?t=create_template" title="${MENU_CREATE_NEW_TEMPLATE_TITLE}"><i class="fa fa-plus"></i> ${MENU_CREATE_NEW_TEMPLATE}</a><span class="menu-create-tmpl-icon"></span></li>
					<!-- IF '${ACCOUNT_ROLE}' == 'admin' || '${ACCOUNT_ROLE}' == 'moderator' -->
					<li><a	<!-- IF '${ACTIVE_MENU}' == 'subscribers' -->class="active"<!-- END IF -->	href="./?t=subscribers" title="${MENU_SUBSCRIBERS_TITLE}"><i class="fa fa-users"></i> ${MENU_SUBSCRIBERS}</a></li>
					<!-- END IF -->
					<!-- IF '${ACCOUNT_ROLE}' == 'admin' || '${ACCOUNT_ROLE}' == 'moderator' -->
					<li><a	<!-- IF '${ACTIVE_MENU}' == 'category' -->class="active"<!-- END IF -->	href="./?t=category" title="${MENU_CATEGORY_TITLE}"><i class="fa fa-list"></i> ${MENU_CATEGORY}</a></li>
					<!-- END IF -->
					<!-- IF '${ACCOUNT_ROLE}' == 'admin' || '${ACCOUNT_ROLE}' == 'moderator' -->
					<li><a	<!-- IF '${ACTIVE_MENU}' == 'feedback' -->class="active"<!-- END IF -->	href="./?t=feedback" title="${MENU_FEEDBACK_TITLE}"><i class="fa fa-comments-o"></i> ${MENU_FEEDBACK}</a></li>
					<!-- END IF -->
					<li><a	<!-- IF '${ACTIVE_MENU}' == 'log' -->class="active"<!-- END IF -->	href="./?t=log" title="${MENU_LOG_TITLE}"><i class="fa fa-area-chart"></i> ${MENU_LOG}</a></li>
					<!-- IF '${ACCOUNT_ROLE}' == 'admin' -->
					<li><a	<!-- IF '${ACTIVE_MENU}' == 'settings' -->class="active"<!-- END IF -->	href="./?t=settings" title="${MENU_SETTINGS_TITLE}"><i class="fa fa-gear"></i> ${MENU_SETTINGS}</a></li>
					<!-- END IF -->
					<!-- IF '${ACCOUNT_ROLE}' == 'admin' -->
					<li><a	<!-- IF '${ACTIVE_MENU}' == 'accounts' -->class="active"<!-- END IF -->	href="./?t=accounts" title="${MENU_ACCOUNTS_TITLE}"><i class="fa fa-key"></i> ${MENU_ACCOUNTS}</a></li>
					<!-- END IF -->
				</ul>
			</div>
			<!-- /.sidebar-collapse -->
		</div>
		<!-- /.navbar-static-side -->
	</nav>
	<div id="page-wrapper">
		<div class="row">
			<div class="col-lg-12">
				<h1 class="page-header">${TITLE}</h1>
			</div>
			<!-- /.col-lg-12 -->
		</div>
		<!-- /.row -->
		<div class="row">
			<div class="col-lg-12">
				<!-- IF '${SYS_ERROR_MSG}' != '' -->
				<div class="alert alert-danger alert-dismissable" id="alert_error_block">
					<button class="close" aria-hidden="true" data-dismiss="alert" type="button">×</button>
					<h4 class="alert-heading">${STR_ERROR}!</h4>
					<span>${SYS_ERROR_MSG}</span>
				</div>
				<!-- END IF -->
				<div class="alert alert-danger alert-dismissable" id="alert_error_block" style="display:none;">
					<button class="close" aria-hidden="true" data-dismiss="alert" type="button">×</button>
					<h4 class="alert-heading">${STR_ERROR}!</h4>
					<span id="alert_error_msg">${PAGE_ALERT_ERROR_MSG}</span>
				</div>
				<!-- IF '${ALERT_EXPIRE_LICENSE_MSG}' != '' -->
				<div class="alert alert-warning alert-dismissable">
					<button class="close" aria-hidden="true" data-dismiss="alert" type="button">×</button>
					<h4 class="alert-heading">${STR_WARNING}!</h4>
					<span>${ALERT_EXPIRE_LICENSE_MSG}</span>
				</div>
				<!-- END IF -->
				<div class="alert alert-warning alert-dismissable" id="alert_msg_block" style="display:none;">
					<button class="close" aria-hidden="true" data-dismiss="alert" onClick="$.cookie('alertshow', 'no');" type="button">×</button>
					<h4 class="alert-heading">${STR_WARNING}!</h4>
					<span id="alert_warning_msg">${PAGE_ALERT_WARNING_MSG}</span>
				</div>
