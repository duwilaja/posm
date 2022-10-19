<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$bu=base_url()."adminlte310";

$data["title"]="Home";
$data["menu"]="home";
$data["pmenu"]="";
$data["session"]=$session;
$data["bu"]=$bu;

$this->load->view("_head",$data);
$this->load->view("_navbar",$data);
$this->load->view("_sidebar",$data);
?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0"><?php echo $data["title"]?></h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#"></a></li>
              <li class="breadcrumb-item active"></li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
	  
        <!-- Info boxes -->
        <div class="row">
          <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
              <span class="info-box-icon bg-info elevation-1"><i class="fas fa-broadcast-tower"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Total BTS</span>
                <span class="info-box-number" id="bts">0</span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->
          <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
              <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-hdd"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Total Controller</span>
                <span class="info-box-number" id="controller">0</span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->

          <!-- fix for small devices only -->
          <div class="clearfix hidden-md-up"></div>

          <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
              <span class="info-box-icon bg-success elevation-1"><i class="far fa-lightbulb"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">ON</span>
                <span class="info-box-number" id="on">0</span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->
		  <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
              <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-lightbulb"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">OFF</span>
                <span class="info-box-number" id="off">0</span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
		
		<div class="row">
			<div class="col-12">
				<div id="leafletMap" style="width:100%; height:450px;"></div>
			</div>
		</div>
		
        <div class="row hidden">
          <div class="col-md-6">
            <!-- PIE CHART -->
            <div class="card card-success">
              <div class="card-header">
                <h3 class="card-title">Mediaplan By Client</h3>

                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                  <!--button type="button" class="btn btn-tool" data-card-widget="remove">
                    <i class="fas fa-times"></i>
                  </button-->
                </div>
              </div>
              <div class="card-body">
                <canvas id="pieChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->

          </div>
          <!-- /.col (LEFT) -->
          <div class="col-md-6">
            <!-- LINE CHART -->
            <div class="card card-info">
              <div class="card-header">
                <h3 class="card-title">Monthly Invoice vs Bill</h3>

                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                  <!--button type="button" class="btn btn-tool" data-card-widget="remove">
                    <i class="fas fa-times"></i>
                  </button-->
                </div>
              </div>
              <div class="card-body">
                <div class="chart">
                  <canvas id="lineChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                </div>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->

          </div>
          <!-- /.col (RIGHT) -->
        </div>
        <!-- /.row -->
	  
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

<?php
$this->load->view("_foot",$data);
?>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css"/>
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
<script>
var map, markers;
var markersx=null;

$(document).ready(function(){
	document_ready();
	map = L.map('leafletMap').setView([-2,118], 5);
	
	L.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png', {
	attribution: '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors'
	}).addTo(map);
	
	loadMarker();
	tot();
});

function tot(){
	$.ajax({
		type: 'POST',
		url: bu+'welcome/tot',
		data: {},
		success: function(datax){
			var data=JSON.parse(datax);
			$.each(data,function(key,val){
				$("#"+key).html(val);
			})
		}
	})
	setTimeout(tot,60*1000);
}

function buildPopup(dat){
	var r='BTS ID: '+dat['btsid']+'<br />'+
			'BTS Name: '+dat['btsname']+'<br />'+
			'Controller SID: '+dat['sid']+'<br />'+
			'PLN: '+dat['pln']+'<br />'+
			'Power: '+dat['pwr']+'<br />'+
			'Door: '+dat['d']+'<br />'+
			'Temperature: '+dat['t']+'<br />'+
			'Battery: '+dat['batt']+'<br />'+
			'Last Updated: '+dat['lastupd'];
			
	return r;
}
function getIcon(dat){
	var r='0';
	if(dat['pln']=='on'&&dat['pwr']=='on') r='1';
	
	return r;
}
function loadMarker(){
	markers=L.layerGroup();
	var b=bu+'adminlte310/my/img/';
	$.ajax({
		type: 'POST',
		url: bu+'welcome/map',
		data: {},
		success: function(datax){
			var data=JSON.parse(datax);
			for(var i=0;i<data.length;i++){
				var popup = buildPopup(data[i]);
				var ico = getIcon(data[i]);
				if(ico=="0"){
					var icon = L.icon({iconUrl:b+ico+'.png',iconSize:[30,30],iconAnchor:[15,30]});
				}else{
					var icon = L.icon({iconUrl:b+ico+'.png',iconSize:[34,34],iconAnchor:[17,34]});
				}
				L.marker(new L.LatLng(data[i]['lat'],data[i]['lng']), { icon: icon }).bindPopup(popup).addTo(markers);
				//L.marker(new L.LatLng(data[i]['lat'],data[i]['lng']), {}).addTo(map).bindPopup(popup);
				
			}
			markers.addTo(map);
			setTimeout(reloadMap,60*1000);
		},
		error: function(xhr){
			console.log(xhr);
			if(markersx!=null) {
				markers=markersx;
				markers.addTo(map);
			}
			
			setTimeout(reloadMap,60*1000);
		}
	});
}
function reloadMap(){
	markersx=markers;
	map.removeLayer(markers);
	loadMarker();
}

</script>
</body>
</html>
