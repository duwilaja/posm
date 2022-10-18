<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$bu=base_url()."adminlte310";

$data["title"]="Users";
$data["menu"]="users";
$data["pmenu"]="";
$data["session"]=$session;
$data["bu"]=$bu;

$sql="select uid,uname,umail,uaccess,ugrp,rowid from t_users";
$c="uid,uname,ugrp,umail,uaccess";
$t="t_users";

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
						<th>ID</th>
						<th>Name</th>
						<th>Email</th>
						<th>Access</th>
						<!--th>Group</th-->
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
	<div class="modal-dialog">
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
				<label for="" class="col-sm-4 col-form-label">ID</label>
				<div class="col-sm-8 input-group">
				  <input type="text" name="uid" class="form-control form-control-sm" id="uid" placeholder="...">
				</div>
			  </div>
			  <div class="form-group row">
				<label for="" class="col-sm-4 col-form-label">Name</label>
				<div class="col-sm-8 input-group">
				  <input type="text" name="uname" class="form-control form-control-sm" id="uname" placeholder="...">
				</div>
			  </div>
			  <div class="form-group row hidden">
				<label for="" class="col-sm-4 col-form-label">Group</label>
				<div class="col-sm-8 input-group">
				  <!--input type="text" name="ugrp" class="form-control form-control-sm" id="ugrp" placeholder="..."-->
				  <select name="ugrp" class="form-control form-control-sm" id="ugrp" placeholder="...">
				  </select>
				</div>
			  </div>
			  <div class="form-group row">
				<label for="" class="col-sm-4 col-form-label">Email</label>
				<div class="col-sm-8 input-group">
				  <input type="text" name="umail" class="form-control form-control-sm" id="umail" placeholder="...">
				</div>
			  </div>
			  <div class="form-group row">
				<label for="" class="col-sm-4 col-form-label">Access</label>
				<div class="col-sm-8 input-group">
				  <select name="uaccess" class="form-control form-control-sm" id="uaccess" placeholder="...">
					<option value=""></option>
					<option value="ADM">Admin</option>
					<option value="USR">User</option>
				  </select>
				</div>
			  </div>
			  <div class="form-group row">
				<label for="" class="col-sm-4 col-form-label">Set Password</label>
				<div class="col-sm-8 input-group">
				  <input type="password" name="upwd" class="form-control form-control-sm" id="upwdx" placeholder="...">
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
		  <button type="button" class="btn btn-primary" onclick="savef();">Save</button>
		  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		</div>
	  </div>
	  <!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
  </div>

  
<?php
$this->load->view("_foot",$data);
$cc="distinct grpid as v,grpid as t";
$ct="t_usergrp";
$cw="1=1";
?>
<script>
var  mytbl;
$(document).ready(function(){
	document_ready();
	mytbl = $("#example1").DataTable({
		serverSide: false,
		processing: true,
		buttons: ["copy", "excel"],
		ajax: {
			type: 'POST',
			url: bu+'md/datatable',
			data: function (d) {
				d.s= '<?php echo base64_encode($sql); ?>';
			}
		},
		initComplete: function(){
			//filterDatatable(mytbl,[1,2]);
			mytbl.buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
		}
	});
	$("#myf").validate({
		rules: {
		  uid: {
			required: true
		  },
		  upwd: {
			required: function(element){
					if($("#rowid").val()==0) return true;
					
					return false;
				}
		  },
		  uname: {
			required: true
		  },
		  ugrp: {
			required: function(element){
					//if($("#uaccess").val()=="USR") return true;
					
					return false;
				}
		  },
		  uaccess: {
			required: true
		  },
		  umail: {
			  required: true,
			  email: true
		  }
		}
	});
	
	//getCombo("md/gets",'<?php echo base64_encode($ct)?>','<?php echo base64_encode($cc)?>','<?php echo base64_encode($cw)?>','#ugrp');
});

function reloadTable(frm){
	mytbl.ajax.reload();
}

function openf(id=0){
	$("#rowid").val(id);
	openForm('#myf','#modal-frm','md/get','#ovl',id,'<?php echo base64_encode($t)?>','<?php echo base64_encode($c)?>')
}
function savef(del=false){
	$("#flag").val('SAVE');
	if(del) $("#flag").val('DEL');
	saveForm('#myf','md/sv','#ovl',del,'#modal-frm');
}
</script>
</body>
</html>
