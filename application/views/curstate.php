<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$bu=base_url()."adminlte310";

$data["title"]="Current State";
$data["menu"]="curstate";
$data["pmenu"]="reports";
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
              <li class="breadcrumb-item">Reports</li>
              <li class="breadcrumb-item active">Current State</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
		<div class="card hidden"><div class="card-body">
			<div class="row">
			<div class="form-group col-md-4">
				<label for="" class="col-form-label">Client</label>
				<select class="form-control form-control-sm select2" id="clnt" placeholder="...">
				</select>
			</div>
			<div class="form-group col-md-6">
				<label for="" class="col-form-label">From - To</label>
				<div class="row">
				<div id="dari" class="col-md-5 input-group date" data-target-input="nearest">
				  
					<input type="text" id="df" class="form-control datetimepicker-input form-control-sm" data-target="#dari">
					<div class="input-group-append" data-target="#dari" data-toggle="datetimepicker">
						<div class="input-group-text"><i class="fas fa-calendar-alt"></i></div>
					</div>
				
				</div>
				<div id="sampai" class="col-md-5 input-group date" data-target-input="nearest">
				  
					<input type="text" id="dt" class="form-control datetimepicker-input form-control-sm" data-target="#sampai">
					<div class="input-group-append" data-target="#sampai" data-toggle="datetimepicker">
						<div class="input-group-text"><i class="fas fa-calendar-alt"></i></div>
					</div>
				
				</div>
				<div class="col-md-1">
					<button class="btn btn-success btn-sm" onclick="reloadTable()"><i class="fas fa-paper-plane"></i></button>
				</div>
				</div>
			</div>
			</div>
		</div></div>
		<div class="card">
			<!--div class="card-header">
				<div class="card-tools">
					<button class="btn btn-success btn-sm" onclick="reloadTable()"><i class="fas fa-sync"></i></button>
					<button class="btn btn-primary btn-sm" onclick="openf()"><i class="fas fa-plus"></i></button>
				</div>
			</div-->
			<div class="card-body table-responsive">
                <table id="example1" class="table table-sm table-bordered table-striped">
                  <thead>
					  <!--tr>
						<th style="padding-right: 4px;"></th>
						<th style="padding-right: 4px;"></th>
						<th style="padding-right: 4px;"></th>
						<th style="padding-right: 4px;"></th>
						<th style="padding-right: 4px;"></th>
						<th style="padding-right: 4px;"></th>
						<th style="padding-right: 4px;"></th>
						<th style="padding-right: 4px;"></th>
					  </tr-->
					  <tr>
						<th>BTS ID</th>
						<th>BTS Name</th>
						<th>Controller SID</th>
						<th>PLN</th>
						<th>Power</th>
						<th>Door</th>
						<th>Temperature</th>
						<th>Battery</th>
						<th>Last Updated</th>
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

<?php
$this->load->view("_foot",$data);

$sql="";
$tbl="";

$cc="clientname as v,clientname as t";
$ct="t_clients";
$cw=$session['ugrp']==''?'1=1':"clientid in (".$session['ugrp'].")";

?>
<script>
var mytbl;

function getW(){
	var w, df, dt, clnt;
	df=$("#df").val();dt=$("#dt").val(); w=[];
	clnt=$("#clnt").val();
	
	if(df!="") w.push("subdt>='"+df+"'");
	if(dt!="") w.push("subdt<='"+dt+"'");
	if(clnt!="") w.push("clientname='"+clnt+"'");
	
	return btoa(w.join(" and "));
}

$(document).ready(function(){
	$("#df").val("<?php echo date('Y-m-').'01'?>"); $("#dt").val("<?php echo date('Y-m-t')?>");
	document_ready();
	mytbl = $("#example1").DataTable({
		serverSide: false,
		processing: true,
		buttons: ["copy", 
		{
			text: 'Excel',
			action: function ( e, dt, node, config ) {
				//alert( 'Button activated' );
				window.open('data:application/vnd.ms-excel,' + encodeURIComponent( document.getElementById('example1').outerHTML));
			}
        }],
		/*{
            extend: 'excelHtml5',
            customize: function(xlsx) {
                var sheet = xlsx.xl.worksheets['sheet1.xml'];

				// Loop over all cells in sheet
				$('row c', sheet).each( function () {

					// if cell starts with http
					if ( $('is t', this).text().indexOf("http") === 0 ) {
						
						

						// (2.) change the type to `str` which is a formula
						$(this).attr('t', 'str');
						//append the formula
						$(this).append('<f>' + 'HYPERLINK("'+$('is t', this).text()+'","'+$('is t', this).text().substr(20)+'")'+ '</f>');
						//remove the inlineStr
						$('is', this).remove();
						// (3.) underline
						$(this).attr( 's', '4' );
					}
				});
            }
        }],*/
		ajax: {
			type: 'POST',
			url: bu+'r/datatable',
			data: function (d) {
				//d.s= '<?php echo base64_encode($sql); ?>',
				d.t= '<?php echo base64_encode("v_curstate"); ?>',
				//d.w= getW(),
				d.r= '<?php echo base64_encode($data["menu"]); ?>';
			}
		},
		initComplete: function(){
			//filterDatatable(mytbl,[1,2]);
			mytbl.buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
		}
	});
	setTimeout(reloadTable,60*1000);
	//initDatePicker(["#dari","#sampai"]);
	//getCombo("md/gets",'<?php echo base64_encode($ct)?>','<?php echo base64_encode($cc)?>','<?php echo base64_encode($cw)?>','#clnt','','--- All ---');
})

function reloadTable(frm=''){
	mytbl.ajax.reload();
	
	setTimeout(reloadTable,60*1000);
}
</script>
</body>
</html>
