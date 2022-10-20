<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$bu=base_url()."adminlte310";

$data["title"]="BTS";
$data["menu"]="bts";
$data["pmenu"]="master";
$data["session"]=$session;
$data["bu"]=$bu;

$sql="select btsid,btsname,controllerid,lat,lng,rowid from t_bts";
$c="btsid,btsname,controllerid,lat,lng";
$t="t_bts";

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
              <li class="breadcrumb-item">Master Data</li>
              <li class="breadcrumb-item active">BTS</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
		<div class="card">
			<div class="card-header">
				<div class="card-tools">
					<button class="btn btn-success btn-sm" onclick="reloadTable()"><i class="fas fa-sync"></i></button>
					<button class="btn btn-primary btn-sm" onclick="openf()"><i class="fas fa-plus"></i></button>
				</div>
			</div>
			<div class="card-body table-responsive">
                <table id="example1" class="table table-sm table-bordered table-striped">
                  <thead>
					  <tr>
						<th>BTS ID</th>
						<th>BTS Name</th>
						<th>Controller SID</th>
						<th>Latitude</th>
						<th>Longitude</th>
					  </tr>
                  </thead>
                  <tbody>
				  </tbody>
				</table>
			</div>
		</div>
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <div class="modal fade" id="modal-frm">
	<div class="modal-dialog modal-lg">
	  <div class="modal-content">
		<div id="ovl" class="overlay" style="display:none;">
			<i class="fas fa-2x fa-sync fa-spin"></i>
		</div>
		<div class="modal-header">
		  <h4 class="modal-title"><?php echo $data['title']?> Form</h4>
		  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
			<span aria-hidden="true">×</span>
		  </button>
		</div>
		<div class="modal-body">
		  
		  <div class="card">
		  <!-- form start -->
		  <form id="myf" class="form-horizontal">
		  <input type="hidden" name="rowid" id="rowid" value="0">
		  <input type="hidden" name="flag" id="flag" value="SAVE">
		  <input type="hidden" name="table" value="<?php echo base64_encode($t)?>">
		  <input type="hidden" name="cols" value="<?php echo base64_encode($c)?>">
		  
			<div class="card-body">
			  <div class="form-group row">
				<label for="" class="col-sm-4 col-form-label">BTS ID</label>
				<div class="col-sm-8 input-group">
				  <input type="text" name="btsid" class="form-control form-control-sm" id="btsid" placeholder="...">
				</div>
			  </div>
			  <div class="form-group row">
				<label for="" class="col-sm-4 col-form-label">BTS Name</label>
				<div class="col-sm-8 input-group">
				  <input type="text" name="btsname" class="form-control form-control-sm" id="btsname" placeholder="...">
				</div>
			  </div>
			  <div class="form-group row">
				<label for="" class="col-sm-4 col-form-label">Controller SID</label>
				<div class="col-sm-8 input-group">
				  <!--input type="text" name="grpname" class="form-control form-control-sm" id="grpname" placeholder="..."-->
				  <select name="controllerid" class="form-control form-control-sm select2" id="controllerid" placeholder="..." onchange="">
				  </select>
				</div>
			  </div>
			  <div class="form-group row">
				<label for="" class="col-sm-4 col-form-label">Coordinate</label>
				<div class="col-sm-3 input-group">
				  <input type="text" name="lat" class="form-control form-control-sm" id="lat" placeholder="Latitude">
				</div>
				<div class="col-sm-3 input-group">
				  <input type="text" name="lng" class="form-control form-control-sm" id="lng" placeholder="Longitude">
				</div>
				<div class="col-sm-2 input-group">
				  <button type="button" title="Show Map" class="btn btn-sm btn-info" onclick="mappicker('#lat','#lng');"><i class="fas fa-map-marked-alt"></i></button>
				</div>
			  </div>
			</div>
			<!-- /.card-body -->
		  </form>
		  </div>
		  <!-- /.card -->
		  
		</div>
		<div class="modal-footer pull-right">
		  <button type="button" id="btndel" class="btn btn-danger" onclick="savef(true)">Delete</button>
		  <button type="button" id="btnsave" class="btn btn-primary" onclick="savef();">Save</button>
		  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		</div>
	  </div>
	  <!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
  </div>

  
<?php
$this->load->view("_foot",$data);

$cc="sid as v,sid as t";
$ct="t_states";
$cw="1=1 order by sid";
?>
<script>
var  mytbl;
$(document).ready(function(){
	document_ready();
	mytbl = $("#example1").DataTable({
		serverSide: false,
		processing: true,
		ajax: {
			type: 'POST',
			url: bu+'md/datatable',
			data: function (d) {
				d.s= '<?php echo base64_encode($sql); ?>';
			}
		}
	});
	$("#myf").validate({
		rules: {
		  btsid: {
			required: true
		  },
		  upwd: {
			required: function(element){
					if($("#rowid").val()==0) return true;
					
					return false;
				}
		  },
		  btsname: {
			required: true
		  },
		  ugrp: {
			required: true
		  },
		  uaccess: {
			required: true
		  },
		  umail: {
			  required: true,
			  email: true
		  }
		}
	})
	
	getCombo("md/gets",'<?php echo base64_encode($ct)?>','<?php echo base64_encode($cc)?>','<?php echo base64_encode($cw)?>','#controllerid');
	
});

function reloadTable(frm){
	mytbl.ajax.reload();
}

function openf(id=0){
	$("#rowid").val(id);
	//$("#btnsave").show();
	openForm('#myf','#modal-frm','md/get','#ovl',id,'<?php echo base64_encode($t)?>','<?php echo base64_encode($c)?>')
}
function savef(del=false){
	$("#flag").val('SAVE');
	if(del) $("#flag").val('DEL');
	saveForm('#myf','md/sv','#ovl',del,'#modal-frm');
}

function formLoaded(frm,modal,overlay,data=""){
	$("#controllerid").trigger("change");
	//if($("#rowid").val()!="0") $("#btnsave").hide();
}

function mappicker(lat,lng){
	window.open(bu+"map?lat="+$(lat).val()+"&lng="+$(lng).val(),"MapWindow","width=830,height=500,location=no").focus();
}
</script>
</body>
</html>
