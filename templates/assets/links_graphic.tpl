<!-- INCLUDE header.tpl -->
<script type="text/javascript">

function PnumberChange()
{
	var pnumber = document.getElementById("pnumber").value;
	document.cookie = "pnumber_subscribers=" + pnumber;
	location.reload();
}

</script>
<!-- IF '${INFO_ALERT}' != '' -->
<div class="alert alert-info">${INFO_ALERT}</div>
<!-- END IF -->
<!-- IF '${MSG_ALERT}' != '' -->
<div class="alert alert-success alert-dismissable">
  <button class="close" aria-hidden="true" data-dismiss="alert" type="button">×</button>
  ${MSG_ALERT}
</div>
<!-- END IF -->
<div class="row">
  <div class="col-lg-12">
    <div class="BtnPanelIcon">
     <a class="btn btn-outline btn-default btn-lg" title="${PROMPT_EXPORT_LINKS}" href="./?t=links&type=detail"> <span class="fa fa-info-circle fa-2x"></span> <span class="IconText">${STR_EXPORT_LINKS}</span> </a>
    </div>
  </div>
</div>
    
<script src="./templates/js/highcharts.js"></script>
<script src="./templates/js/exporting.js"></script>

<div id="container" style="min-width: 310px; max-width: 800px; height: 400px; margin: 0 auto"></div>



		<script type="text/javascript">
                    var categories = [];
                    var data = [];
                    <!-- BEGIN cities -->
                    <!-- BEGIN data -->
                    categories.push("${CITY}");
                    data.push(${TOTAL});
                    <!-- END data -->
                    <!-- END cities -->
Highcharts.chart('container', {
    chart: {
        type: 'bar'
    },
    title: {
        text: 'Demografi Pembaca Newsletter'
    },
    xAxis: {
        categories: categories,
        title: {
            text: null
        }
    },
    yAxis: {
        min: 0,
        title: {
            text: 'Jumlah (satuan)',
            align: 'high'
        },
        labels: {
            overflow: 'justify'
        }
    },
    tooltip: {
        valueSuffix: ' '
    },
    plotOptions: {
        bar: {
            dataLabels: {
                enabled: true
            }
        }
    },
    legend: {
        layout: 'vertical',
        align: 'right',
        verticalAlign: 'top',
        x: -40,
        y: 80,
        floating: true,
        borderWidth: 1,
        backgroundColor: ((Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'),
        shadow: true
    },
    credits: {
        enabled: false
    },
    series: [{
        name: 'Jumlah Pembaca',
        data: data
    }]
});
		</script>
<!-- IF '${ERROR_ALERT}' != '' -->
<div class="alert alert-danger alert-dismissable">
  <button class="close" aria-hidden="true" data-dismiss="alert">×</button>
  <strong>${STR_ERROR}!</strong> ${ERROR_ALERT}
</div>
<!-- END IF -->
<!-- IF '${MSG_ALERT}' != '' -->
<div class="alert alert-success alert-dismissable">
  <button class="close" aria-hidden="true" data-dismiss="alert" type="button">×</button>
  ${MSG_ALERT}
</div>
<!-- END IF -->
<!-- BEGIN row -->
  <table class="table table-bordered table-hover">
    <thead>
      <tr>
        <th>CITY</th>
        <th>COUNTRY</th>
        <th>TOTAL</th>
      </tr>
    </thead>
    <tbody>
      <!-- BEGIN column -->
      <tr <!-- IF '${STATUS_CLASS}' == 'noactive' -->class="danger"<!-- END IF -->>
        <td style="vertical-align: middle;">${CITY}</td>
        <td style="vertical-align: middle;">${COUNTRY}</td>
        <td style="vertical-align: middle;">${TOTAL}</td>
      </tr>
      <!-- END column -->
    </tbody>
  </table>
  
<!-- END row -->
<!-- IF '${EMPTY_LIST}' != '' -->
<div class="warning_msg">${EMPTY_LIST}</div>
<!-- END IF -->
<!-- BEGIN notfound -->
<div class="alert">
  <button class="close" data-dismiss="alert">×</button>
  ${MSG_NOTFOUND} </div>
<!-- END notfound -->
<!-- INCLUDE footer.tpl -->
