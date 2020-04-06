<script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
<style media="all" type="text/css">
    .alignCenter { text-align: center; }
</style>
<ul class="page-breadcrumb breadcrumb">
	<li>
		<span>Master</span>
		<i class="fa fa-circle"></i>
	</li>
	<li>
		<span>Data Komponen</span>
	</li>
</ul>
<?= $this->session->flashdata('sukses') ?>
<?= $this->session->flashdata('gagal') ?>
<div class="page-content-inner">
	<div class="m-heading-1 border-yellow m-bordered" style="background-color:#FAD405;">
		<h3>Catatan</h3>
	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="portlet light ">
				<div class="portlet-body">
					<table class="table table-striped table-bordered table-hover order-column" id="tbl">
						<thead>
							<tr>
								
								<th style="text-align: center;" width="4%"> # </th>
								<th style="text-align: center;"> Kode Komponen </th>
								<th style="text-align: center;"> Kode Sub Output </th>
								<th style="text-align: center;"> Kode Output </th>
								<th style="text-align: center;"> Kode Kegiatan </th>
								<th style="text-align: center;"> Komponen </th>
								<th style="text-align: center;"> Pagu </th>
								<th style="text-align: center;"> Realisasi </th>
								<th style="text-align: center;"> Sisa </th>
							</tr>
						</thead>
					</table>
					<script type="text/javascript" language="javascript" >
						$(document).ready(function(){
							$('#tbl').dataTable({
								"order": [[ 0, "asc" ]],
								"bProcessing": true,
								"ajax" : {
									url:"<?= site_url('ppk/Master/json_komponen'); ?>"
								},
								"aoColumns": [
											{ mData: 'number', sClass: "alignCenter" },
											{ mData: 'kode', sClass: "alignCenter" } ,
											{ mData: 'sub_output', sClass: "alignCenter" } ,
											{ mData: 'output', sClass: "alignCenter" } ,
											{ mData: 'kegiatan', sClass: "alignCenter" } ,
											{ mData: 'komponen', sClass: "alignCenter" },
											{ mData: 'pagu', sClass: "alignCenter" },
											{ mData: 'realisasi', sClass: "alignCenter" },
											{ mData: 'sisa', sClass: "alignCenter" }
										]
							});

						});
					</script>
				</div>
			</div>
		</div>
	</div>
</div>