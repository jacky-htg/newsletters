<!-- INCLUDE header.tpl -->
<script type="text/javascript" src="./templates/js/jquery-ui-1.8.16.custom.min.js"></script>
<script type="text/javascript">
  $(function () {
    // Tabs
    $('#tabs').tabs();

    //hover states on the static widgets
    $('#dialog_link, #modal_link, ul#icons li').hover(

       function () {
         $(this).addClass('ui-state-hover');
       }, function () {
         $(this).removeClass('ui-state-hover');
    });
  });
</script>
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
  ${MSG_ALERT} </div>
<!-- END IF -->
<form class="form-horizontal" action="${ACTION}" method="post">
  <div id="tabs">
    <ul>
      <li><a href="#interface" data-toggle="tab">${SET_INTERFACE_SETTINGS}</a></li>
      <li><a href="#smtp" data-toggle="tab">${SET_SMTP_HOST}</a></li>
      <li><a href="#options" data-toggle="tab">${SET_SEND_PARAMETERS}</a></li>
    </ul>
    <br>
    <div id="interface">
      <div class="form-group">
        <label class="col-lg-3 control-label" for="language">${SET_LANGUAGE}</label>
        <div class="col-lg-7">
          <select class="form-control form-primary" name="language">
            <option value="ru"  <!-- IF '${OPTION_LANG}' == 'ru' -->selected="selected"<!-- END IF -->>${SET_OPTION_RU}</option>
            <option value="en"  <!-- IF '${OPTION_LANG}' == 'en' -->selected="selected"<!-- END IF -->>${SET_OPTION_EN}</option>
          </select>
        </div>
      </div>
      <div class="form-group">
        <label class="col-lg-3 control-label" for="email">${SET_EMAIL}</label>
        <div class="col-lg-7">
          <input class="form-control" type="text" value="${EMAIL}" name="email">
        </div>
      </div>
      <div class="form-group">
        <label class="col-lg-3 control-label" for="show_email">${SET_SHOW_EMAIL}</label>
        <div class="col-lg-7">
          <div class="checkbox">
            <label> <input  type="checkbox" name="show_email" <!-- IF '${SHOW_EMAIL}' == 'yes' -->checked="checked"<!-- END IF -->> </label>
          </div>
        </div>
      </div>
      <div class="form-group">
        <label class="col-lg-3 control-label" for="newsubscribernotify">${SET_SUBSCRIBER_NOTIFY}</label>
        <div class="col-lg-7">
          <div class="checkbox">
            <label> <input  type="checkbox" name="newsubscribernotify"  <!-- IF '${SUBSCRIBER_NOTIFY}' == 'yes' -->checked="checked" <!-- END IF -->> </label>
          </div>
        </div>
      </div>
      <div class="form-group">
        <label class="col-lg-3 control-label" for="email_name">${SET_EMAIL_NAME}</label>
        <div class="col-lg-7">
          <input class="form-control" type="text" value="${EMAIL_NAME}" name="email_name">
        </div>
      </div>
      <div class="form-group">
        <label class="col-lg-3 control-label" for="return_path">${SET_RETURN_PATH}</label>
        <div class="col-lg-7">
          <input class="form-control" type="text" value="${RETURN_PATH}" name="return_path">
        </div>
      </div>
      <div class="form-group">
        <label class="col-lg-3 control-label" for="list_owner">${SET_LIST_OWNER}</label>
        <div class="col-lg-7">
          <input class="form-control" type="text" value="${LIST_OWNER}" name="list_owner">
        </div>
      </div>
      <div class="form-group">
        <label class="col-lg-3 control-label" for="organization">${SET_ORGANIZATION}</label>
        <div class="col-lg-7">
          <input class="form-control" type="text" value="${ORGANIZATION}" name="organization">
        </div>
      </div>
      <div class="form-group">
        <label class="col-lg-3 control-label" for="subjecttextconfirm">${SET_SUBJECT_TEXTCONFIRM}</label>
        <div class="col-lg-7">
          <input class="form-control" value="${SUBJECTTEXTCONFIRM}" name="subjecttextconfirm">
        </div>
      </div>
      <div class="form-group">
        <label class="col-lg-3 control-label" for="subjecttextconfirm">${SET_TEXT_CONFIRMATION}</label>
        <div class="col-lg-7">
          <textarea class="form-control form-dark" rows="4" name="textconfirmation">${TEXTCONFIRMATION}</textarea>
        </div>
      </div>
      <div class="form-group">
        <label class="col-lg-3 control-label" for="require_confirmation">${SET_REQUIRE_CONFIRMATION}</label>
        <div class="col-lg-7">
          <div class="checkbox">
            <label> <input  type="checkbox" name="require_confirmation"  <!-- IF '${REQUIRE_CONFIRMATION}' == 'yes' -->checked="checked"<!-- END IF -->> </label>
          </div>
        </div>
      </div>
      <div class="form-group">
        <label class="col-lg-3 control-label" for="unsublink">${SET_UNSUBLINK}</label>
        <div class="col-lg-7">
          <textarea class="form-control form-dark" rows="4" name="unsublink">${UNSUBLINK}</textarea>
        </div>
      </div>
    </div>
    <div id="smtp">
      <div class="form-group">
        <label class="col-lg-3 control-label" for="smtp_host">${SET_SMTP_HOST}</label>
        <div class="col-lg-7">
          <input class="form-control" type="text" value="${SMTP_HOST}" name="smtp_host">
        </div>
      </div>
      <div class="form-group">
        <label class="col-lg-3 control-label" for="smtp_username">${SET_SMTP_USERNAME}</label>
        <div class="col-lg-7">
          <input class="form-control" type="text" value="${SMTP_USERNAME}" name="smtp_username">
        </div>
      </div>
      <div class="form-group">
        <label class="col-lg-3 control-label" for="smtp_username">${SET_SMTP_PASSWORD}</label>
        <div class="col-lg-7">
          <input class="form-control" type="text" value="${SMTP_PASSWORD}" name="smtp_password">
        </div>
      </div>
      <div class="form-group">
        <label class="col-lg-3 control-label" for="smtp_username">${SET_SMTP_PORT}</label>
        <div class="col-lg-7">
          <input class="form-control" type="text" value="${SMTP_PORT}" name="smtp_port">
        </div>
      </div>
      <div class="form-group">
        <label class="col-lg-3 control-label" for="smtp_timeout">${SET_SMTP_TIMEOUT}</label>
        <div class="col-lg-7">
          <input class="form-control" type="text" value="${SMTP_TIMEOUT}" name="smtp_timeout">
        </div>
      </div>
      <div class="form-group">
        <label class="col-lg-3 control-label" for="smtp_secure">${SET_SMTP_SSL}</label>
        <div class="col-lg-7">
          <label class="radio-inline"> <input type="radio" <!-- IF '${SMTP_SECURE}' == 'no' -->checked="checked"<!-- END IF -->value="no" name="smtp_secure">${STR_NO} </label>
          <label class="radio-inline"> <input type="radio" value="ssl" <!-- IF '${SMTP_SECURE}' == 'ssl' -->checked="checked"<!-- END IF --> name="smtp_secure">${SMTP_SECURE_SSL} </label>
          <label class="radio-inline"> <input type="radio" value="tls" <!-- IF '${SMTP_SECURE}' == 'tls' -->checked="checked"<!-- END IF --> name="smtp_secure">${SMTP_SECURE_TLS} </label>
        </div>
      </div>
      <div class="form-group">
        <label class="col-lg-3 control-label" for="smtp_aut">${SET_SMTP_AUT}</label>
        <div class="col-lg-7">
          <label class="radio-inline"> <input type="radio" <!-- IF '${SMTP_AUT}' == 'no' -->checked="checked"<!-- END IF --> value="no" name="smtp_aut">${SET_SMTP_AUT_LOGIN} </label>
          <label class="radio-inline"> <input type="radio" value="plain" <!-- IF '${SMTP_AUT}' == 'plain' -->checked="checked"<!-- END IF --> name="smtp_aut">${SET_SMTP_AUT_PLAIN} </label>
          <label class="radio-inline"> <input type="radio" value="cram-md5" <!-- IF '${SMTP_AUT}' == 'cram-md5' -->checked="checked"<!-- END IF --> name="smtp_aut">${SET_SMTP_AUT_CRAM} </label>
        </div>
      </div>
    </div>
    <div id="options">
      <div class="form-group">
        <label class="col-lg-3 control-label" for="show_unsubscribe_link">${SET_SHOW_UNSUBSCRIBE_LINK}</label>
        <div class="col-lg-7">
          <div class="checkbox">
            <label> <input  type="checkbox" name="show_unsubscribe_link"  <!-- IF '${SHOW_UNSUBSCRIBE_LINK}' == 'yes' -->checked="checked"<!-- END IF -->> </label>
          </div>
        </div>
      </div>
      <div class="form-group">
        <label class="col-lg-3 control-label" for="request_reply">${SET_REPLY}</label>
        <div class="col-lg-7">
          <div class="checkbox">
            <label> <input type=checkbox name="request_reply"  <!-- IF '${REQUEST_REPLY}' == 'yes' -->checked="checked"<!-- END IF -->> </label>
          </div>
        </div>
      </div>
	  <div class="form-group">
        <label class="col-lg-3 control-label" for="interval_number">${SET_INTERVAL_TYPE}</label>
        <div class="col-lg-7">
          <div class="row">
            <div class="col-xs-2">
              <input type="text" class="form-control" name="interval_number" value="${INTERVAL_NUMBER}">
            </div>
            <div class="col-xs-9">
              <select class="form-control form-primary" name="interval_type">
                <option value="0" <!-- IF '${INTERVAL_TYPE}' == 'no' -->selected="selected" <!-- END IF -->>${SET_INTERVAL_TYPE_NO} </option>
                <option value="1" <!-- IF '${INTERVAL_TYPE}' == 'm' -->selected="selected" <!-- END IF -->>${SET_INTERVAL_TYPE_M} </option>
                <option value="2" <!-- IF '${INTERVAL_TYPE}' == 'h' -->selected="selected" <!-- END IF -->>${SET_INTERVAL_TYPE_H} </option>
                <option value="3" <!-- IF '${INTERVAL_TYPE}' == 'd' -->selected="selected"
                <!-- END IF -->>${SET_INTERVAL_TYPE_D}
                </option>
              </select>
            </div>
          </div>
        </div>
      </div>
      <div class="form-group">
        <label class="col-lg-3 control-label" for="re_send">${SET_RE_SEND}</label>
        <div class="col-lg-7">
          <div class="checkbox">
            <label> <input  type="checkbox" name="re_send" <!-- IF '${RE_SEND}' == 'yes' -->checked="checked"<!-- END IF -->> </label>
          </div>
        </div>
      </div>
      <div class="form-group">
        <label class="col-lg-3 control-label" for="limit_number">${SET_NUMBER_LIMIT}</label>
        <div class="col-lg-7">
          <div class="row">
            <div class="col-xs-9">
              <input class="form-control" type="text" value="${LIMIT_NUMBER}" name="limit_number">
            </div>
            <div class="col-xs-2">
              <div class="checkbox"> <input class="flat-checkbox" type="checkbox" <!-- IF '${MAKE_LIMIT_SEND}' == 'yes' -->checked="checked"<!-- END IF -->name="make_limit_send"> </div>
            </div>
          </div>
        </div>
      </div>
      <div class="form-group">
        <label class="col-lg-3 control-label" for="sleep">${SET_SLEEP}</label>
        <div class="col-lg-7">
          <input class="form-control" type="text" value="${SLEEP}" name="sleep">
        </div>
      </div>
      <div class="form-group">
        <label class="col-lg-3 control-label" for="random">${SET_RANDOM}</label>
        <div class="col-lg-7">
          <div class="checkbox">
            <label><input type="checkbox" name="random"  <!-- IF '${RANDOM}' == 'yes' -->checked="checked"<!-- END IF -->></label>
          </div>
        </div>
      </div>
      <div class="form-group">
        <label class="col-lg-3 control-label" for="precedence">Precedence</label>
        <div class="col-lg-7">
          <select name="precedence" class="form-control form-primary">
            <option value="no" <!-- IF '${PRECEDENCE}' == 'no' -->selected="selected"<!-- END IF -->>${SET_IPRECEDENCE_NO}</option>
            <option value="bulk"  <!-- IF '${PRECEDENCE}' == 'bulk' -->selected="selected"<!-- END IF -->>bulk</option>
            <option value="junk" <!-- IF '${PRECEDENCE}' == 'junk' -->selected="selected"<!-- END IF -->>junk</option>
            <option value="list" <!-- IF '${PRECEDENCE}' == 'list' -->selected="selected"<!-- END IF -->>list</option>
          </select>
        </div>
      </div>
      <div class="form-group">
        <label class="col-lg-3 control-label" for="id_charset">${SET_CHARSET}</label>
        <div class="col-lg-7">
          <select class="form-control form-primary" name="id_charset">
            <!-- BEGIN charsetlist_row -->
            <option value="${KEY}" <!-- IF '${KEY}' == '${ID_CHARSET}' -->selected="selected"<!-- END IF -->>${VALUE}</option>
            <!-- END charsetlist_row -->
          </select>
        </div>
      </div>
      <div class="form-group">
        <label class="col-lg-3 control-label" for="content_type">${SET_CONTENT_TYPE}</label>
        <div class="col-lg-7">
          <select class="form-control form-primary" name="content_type">
            <option value="1" <!-- IF '${CONTENT_TYPE}' == 1 -->selected="selected"<!-- END IF -->>plain</option>
            <option value="2" <!-- IF '${CONTENT_TYPE}' == 2 -->selected="selected"<!-- END IF -->>html</option>
          </select>
        </div>
      </div>
      <div class="form-group">
        <label class="col-lg-3 control-label" for="how_to_send">${SET_HOW_TO_SEND}</label>
        <div class="col-lg-7">
          <label class="radio-inline"> <input type="radio" <!-- IF '${HOW_TO_SEND}' == 1 -->checked="checked"<!-- END IF -->  value="1" name="how_to_send">${SET_HOW_TO_SEND_OPTION_1} </label>
          <label class="radio-inline"> <input type="radio" value="2" <!-- IF '${HOW_TO_SEND}' == 2 -->checked="checked"<!-- END IF --> name="how_to_send">${SET_HOW_TO_SEND_OPTION_2} </label>
          <label class="radio-inline"> <input type="radio" value="3" <!-- IF '${HOW_TO_SEND}' == 3 -->checked="checked"<!-- END IF --> name="how_to_send">${SET_HOW_TO_SEND_OPTION_3} </label>
        </div>
      </div>
      <div class="form-group">
        <label class="col-lg-3 control-label" for="sendmail">${SET_SENDMAIL_PATH}</label>
        <div class="col-lg-7">
          <input class="form-control" type="text" value="${SENDMAIL}" name="sendmail">
        </div>
      </div>
      <div class="form-group">
        <label class="col-lg-3 control-label" for="path">${SET_PATH}</label>
        <div class="col-lg-7">
          <input class="form-control" type="text" value="${PATH}" name="path">
        </div>
      </div>
      <div class="form-group">
        <label class="col-lg-3 control-label" for="add_dkim">${SET_ADD_DKIM}</label>
        <div class="col-lg-7">
          <div class="checkbox">
            <label><input lass="flat-checkbox" type="checkbox" name="add_dkim"  <!-- IF '${ADD_DKIM}' == 'yes' -->checked="checked"<!-- END IF -->> </label>
          </div>
        </div>
      </div>
      <div class="form-group">
        <label class="col-lg-3 control-label" for="dkim_domain">${SET_DKIM_DOMEN}</label>
        <div class="col-lg-7">
          <input class="form-control" type="text" value="${DKIM_DOMEN}" name="dkim_domain">
        </div>
      </div>
      <div class="form-group">
        <label class="col-lg-3 control-label" for="dkim_domain" for="dkim_selector">
          ${SET_DKIM_SELECTOR}
        </label>
        <div class="col-lg-7">
          <input type="text" class="form-control" value="${DKIM_SELECTOR}" name="dkim_selector">
        </div>
      </div>
      <div class="form-group">
        <label class="col-lg-3 control-label" for="dkim_domain" for="dkim_private">
          ${SET_DKIM_PRIVATE}
        </label>
        <div class="col-lg-7">
          <input type="text" class="form-control" value="${DKIM_PRIVATE}" name="dkim_private">
        </div>
      </div>
      <div class="form-group">
        <label class="col-lg-3 control-label" for="dkim_passphrase">${SET_DKIM_PASSPHRASE}</label>
        <div class="col-lg-7">
          <input type="text" class="form-control" value="${DKIM_PASSPHRASE}" name="dkim_passphrase">
        </div>
      </div>
      <div class="form-group">
        <label class="col-lg-3 control-label" for="dkim_identity">${SET_DKIM_IDENTITY}</label>
        <div class="col-lg-7">
          <input type="text" class="form-control" value="${DKIM_IDENTITY}" name="dkim_identity">
        </div>
      </div>
    </div>
  </div>
  </div>
  <div class="form-group">
    <input class="btn btn-success" name="action" type="submit" value="${BUTTON_APPLY}">
  </div>
</form>
<!-- INCLUDE footer.tpl -->