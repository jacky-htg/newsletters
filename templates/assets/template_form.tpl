<script type="text/javascript" src="./templates/js/ckeditor/ckeditor.js"></script>
<script>
  $(document).on( "click", ".remove_attach", function() {

  var Id_attach = $(this).attr('data-num');

    $.ajax({
      type: "GET",
      url: "./?t=ajax&action=remove_attach&id=" + Id_attach,
      dataType: "json",
      success: function(data){
        if (data.result == 'yes'){
          $("#attach_" + Id_attach).remove()
        }
      }
    });
  });
  
    function changeDesign(){
        if ($('#designId').val() == 1) {
            var html = '<label>${STR_FORM_CONTENT}: </label><textarea style="display:none;" class="form-control form-dark" rows="5" id="tmplBody" name="body">${CONTENT}</textarea><p class="help-block">${STR_FORM_NOTE}: ${STR_SUPPORTED_TAGS_LIST}</p>';
            $('#content-body').html(html);
            CKEDITOR.replace('tmplBody');
        }
        else if ($('#designId').val() == 2) {
            var html = '<label>${STR_FORM_CONTENT}: </label>'+$('#usaid-cegah-content').html()+'</p>';
            html += '<textarea style="display:none;" name="body">'+$('#usaid-cegah-content').html()+'</textarea>';
            $('#content-body').html(html);
        }
  }
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
  ${MSG_ALERT}
</div>
<!-- END IF -->
<script type="text/javascript">//<![CDATA[
  window.CKEDITOR_BASEPATH='./templates/js/ckeditor/';
  CKEDITOR.lang.languages={"${LANGUAGE}":1};
  //]]></script>
<form id="tmplForm" enctype="multipart/form-data" action="${ACTION}" method="post">
  <!-- IF '${ID_TEMPLATE}' != '' -->
  <input type="hidden" name="id_template" value="${ID_TEMPLATE}">
  <!-- END IF -->
  <div class="form-group">
    <label class="control-label" for="name">${STR_FORM_SUBJECT}:</label>
    <input id="tmplName" name="name" type="text" value="${NAME}" class="form-control" />
  </div>
  
  
  <!-- IF '${ID_TEMPLATE}' != '' -->
    <input type="hidden" name="design_id" value="${ID_DESIGN}">
  <!-- ELSE -->
  <div class="form-group">
    <label class="control-label" for="design_id">Template:</label>
    <select id="designId" name="design_id" class="form-control" onchange="changeDesign();">
        <option value="1">Basic Template</option>
        <option value="2">CEGAH USAID</option>
    </select>
  </div>
  <!-- END IF -->
  
  <div id="content-body" class="form-group">
      <label>
          ${STR_FORM_CONTENT}: 
          <!-- IF '${ID_TEMPLATE}' != '' AND '${ID_DESIGN}' == '2' -->
          <a href="?t=update_template&id_template=${ID_TEMPLATE}">User Friendly Update Content</a>
          <!-- END IF -->
      </label>
      <!-- IF '${ID_TEMPLATE}' != '' AND '${ID_DESIGN}' == '2' -->
          ${CONTENT_TEMP}
      <!-- ELSE -->
    <textarea style="display:none;" class="form-control form-dark" rows="5" id="tmplBody" name="body">${CONTENT_TEMP}</textarea>
    <script type="text/javascript">//<![CDATA[
      CKEDITOR.replace('tmplBody');
      //]]></script>
    <p class="help-block">${STR_FORM_NOTE}: ${STR_SUPPORTED_TAGS_LIST}</p>
    <!-- END IF -->
  </div>
  
  <!-- BEGIN attach_list -->
  <div class="form-group">
    <label class="control-label" for="attach_list">${STR_ATTACH_LIST}:</label>
    <div class="controls inline">
      <!-- BEGIN row -->
      <span id="attach_${ID_ATTACHMENT}">${ATTACHMENT_FILE} <a href="#" data-num="${ID_ATTACHMENT}" class="remove_attach" title="${STR_REMOVE}"> X </a>&nbsp;&nbsp;</span>
      <!-- END row -->
    </div>
  </div>
  <!-- END attach_list -->
  <div class="form-group">
    <label for="attachfile[]" class="control-label">${STR_FORM_ATTACH_FILE}:</label>
    <div class="controls">
      <div id="loadfile_0">
        <input type="file" name="attachfile[]" class="input" multiple="true">
      </div>
    </div>
  </div>
  <div class="form-group">
    <label for="id_cat">${STR_FORM_CATEGORY_SUBSCRIBERS}</label>
    <select class="span3 form-control" name="id_cat">
      <option value="0" <!-- IF '${POST_ID_CAT}' == 0 || '${POST_ID_CAT}' == '' -->selected="selected"<!-- END IF -->> -- ${STR_SEND_TO_ALL} -- </option>
      <!-- BEGIN categories_row -->
      <option value="${ID_CAT}" <!-- IF '${POST_ID_CAT}' == '${ID_CAT}' -->selected="selected"<!-- END IF -->>${NAME}</option>
      <!-- END categories_row -->
    </select>
  </div>
  <div class="form-group">
    <label for="exampleInputFile">${STR_FORM_PRIORITY}:</label>
    <div class="controls">
      <label> <input type="radio" name="prior" value="3" <!-- IF '${PRIOR}' == 3 -->checked="checked"<!-- END IF -->> ${STR_FORM_PRIORITY_NORMAL} </label>
      <label> <input type="radio" name="prior" value="2" <!-- IF '${PRIOR}' == 2 -->checked="checked"<!-- END IF -->> ${STR_FORM_PRIORITY_LOW} </label>
      <label> <input type="radio" name="prior" value="1" <!-- IF '${PRIOR}' == 1 -->checked="checked"<!-- END IF -->> ${STR_FORM_PRIORITY_HIGH} </label>
    </div>
  </div>
  <div class="form-group">
    <div class="controls">
      <input type="submit" class="btn btn-success" name="action" value="${BUTTON}">
    </div>
  </div>
  <h4>${STR_SEND_TEST_EMAIL}:</h4>
  <div class="input-group" style="margin-bottom:20px;">
    <input type="text" value="" id="tmplEmail" name="email" class="span3 form-control" />
    <span class="input-group-btn">
    <input type="button" id="send_test_email" class="btn btn-info" value="${BUTTON_SEND}" />
    </span> </div>
  <div id="resultSend"></div>
</form>
<script type="text/javascript">
  $(document).ready(function(){
    $('#send_test_email').click(function(){
      var content = $('#content').html();//CKEDITOR.instances["tmplBody"].getData();

      $("#div1").text($("#tmplForm").serialize());
      var arr = $("#tmplForm").serializeArray();
      var aParams = new Array();

      for (var i=0, count=arr.length; i<count; i++) {
        var sParam = encodeURIComponent(arr[i].name);

        if (sParam == 'body'){
          sParam += "=";
          sParam += encodeURIComponent(content);
        }
        else{
          sParam += "=";
          sParam += encodeURIComponent(arr[i].value);
        }

        aParams.push(sParam);
      }

      var sendData = aParams.join("&");

      $.ajax({
        type: "POST",
        url: "./?t=ajax&action=sendtest",
        data: sendData,
        dataType: "json",
        success: function(data){
          var alert_msg = '';

          if (data.result == 'success'){
            alert_msg += '<div class="alert alert-success alert-dismissable">';
            alert_msg += '<button class="close" aria-hidden="true" data-dismiss="alert" type="button">×</button>';
            alert_msg += data.msg;
            alert_msg += '</div>';
          } else if (data.result == 'error'){
            alert_msg += '<div class="alert alert-danger alert-dismissable">';
            alert_msg += '<button class="close" aria-hidden="true" data-dismiss="alert" type="button">×</button>';
            alert_msg += '<strong>${STR_ERROR}!</strong>';
            alert_msg += data.msg;
            alert_msg += '</div>';
          } else if (data.result == 'errors'){
            alert_msg += '<div class="alert alert-danger alert-dismissable">';
            alert_msg += '<button class="close" aria-hidden="true" data-dismiss="alert">×</button>';
            alert_msg += '<strong><h4 class="alert-heading">${STR_IDENTIFIED_FOLLOWING_ERRORS}:</h4></strong>';
            alert_msg += '<ul>';

            var arr = data.msg.split(',');

            for (var i = 0; i < arr.length; i++){
              alert_msg += '<li> ' + arr[i] + '</li>';
            }

            alert_msg += '</ul>';
            alert_msg += '</div>';
          }

          $("#resultSend").html(alert_msg);
        }
      });
    });
  });

</script>

<div style="display:none;" id="usaid-cegah-content">
    <div style="display:flex; flex-direction: column; align-items: center; justify-content: center;">
        <div style="width:900px;display:flex; flex-direction: column;background:#d3d3d3;border-radius: 0 0 50px 0;">
            <div style="display:flex;flex-direction: row;">
                <div style="flex:75%;"><img src="http://newsletter.independen.id/img/usaid.png"></div>

                <div style="flex:25%; display: flex; flex-direction: row;padding:1%;">
                    <div style="flex: 1;"><img src="http://newsletter.independen.id/img/aji.jpg" style="width:70px;"></div>
                    <div style="text-align:right;"><div class="editable" data-type="text" data-mode="edit-only" id="edition-text">Agustus 2017</div><br><img src="http://newsletter.independen.id/img/independen.jpg">
                    </div>
                </div>
            </div>

            <div><img src="http://newsletter.independen.id/img/usaid_cegah.png"></div>
        </div>

        <div style="width:900px; display:flex; flex-direction: row; margin-top:1%;">
            <div id="sortable3" class="connectedSortable" style="padding:1%; display:table-cell;flex:40%;align-items: flex-start;">
                <p><img src="http://newsletter.independen.id/img/id.jpg" style="width:100%;"></p>
                
                <div style="border:1px solid #ccc; background:#efefef; padding:3%; margin-top: 40px;">Untuk saran dan masukan silahkan klik<br>
                <a class="mute-link" href="http://newsletter.independen.id/feedback.php?id=%USERID%&amp;token=%USERTOKEN%">link komentar</a> berikut.</div>
            </div>

            <div id="sortable2" class="connectedSortable ui-sortable" style="padding:1% 1% 1% 2%; flex:60%; align-items: flex-start; border-left:1px dashed #ccc;">
                
            </div>
        </div>

        <div style="margin-top:20px;width:900px;border-top:1px dashed #ccc;padding:1%;">
            <p style="font-size:70%">Publikasi ini dimungkinkan karena dukungan Rakyat Amerika melalui United States Agency for International Development (USAID). Isi dari newsletter ini tidak merepresentasikan pandangan pendapat resmi dari USAID atau Pemerintah Amerika Serikat.</p>
        </div>

        <div style="width:900px;background:#d22027; border-radius:50px 0 0 0; color:#fff; text-align:right;padding:1%;">copyright © 2017 By independen ID</div>
    </div>
</div>