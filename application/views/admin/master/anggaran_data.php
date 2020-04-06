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
		<span>Data Anggaran</span>
	</li>
</ul>
<?= $this->session->flashdata('sukses') ?>
<?= $this->session->flashdata('gagal') ?>
<div class="page-content-inner">
	<div class="m-heading-1 border-yellow m-bordered" style="background-color:#FAD405;">
		<h3>Catatan</h3>
		<p> 1. Ketentuan file yang diupload:</p>
		<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- Format berupa file <b>.xlsx</b></p>
		<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- Ukuran maksimum file <b>3 MB</b></p>
	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="portlet light ">
				<div class="portlet-body">
					<div class="table-toolbar">
						<div class="row">
							<div class="col-md-8">
								<a href="<?=base_url('admin_side/tambah_data_anggaran');?>" class="btn green uppercase">Tambah Data <i class="fa fa-plus"></i> </a>
							</div>
							<div class="col-md-4" style='text-align: right;'>
								<a href="#" class="btn btn-info" data-toggle="modal" data-target="#fi">Impor Data <i class="fa fa-cloud-upload"></i></a>
								<a href="<?=base_url()?>import_data_template/template_anggaran.xlsx" class="btn btn-warning">Unduh Template</a>
							</div>
						</div>
					</div>
					<table class="table table-striped table-bordered table-hover table-checkable order-column" id="tbl">
						<thead>
							<tr>
								<th style="text-align: center;" width="1%"> # </th>
								<th style="text-align: center;"> Kode Kegiatan </th>
								<th style="text-align: center;"> Nama Kegiatan </th>
								<th style="text-align: center;"> Pagu </th>
								<th style="text-align: center;"> Departemen </th>
								<th style="text-align: center;" width="1%"> Aksi </th>
							</tr>
						</thead>
					</table>
					<script type="text/javascript" language="javascript" >
						$(document).ready(function(){
							$('#tbl').dataTable({
								"order": [[ 0, "asc" ]],
								"bProcessing": true,
								"ajax" : {
									url:"<?= site_url('admin/Master/json_anggaran'); ?>"
								},
								"aoColumns": [
											{ mData: 'no', sClass: "alignCenter" },
											{ mData: 'kode', sClass: "alignCenter" } ,
											{ mData: 'nama', sClass: "alignCenter" },
											{ mData: 'pagu', sClass: "alignCenter" },
											{ mData: 'real', sClass: "alignCenter" },
											{ mData: 'action' }
										]
							});
						});
					</script>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="fi" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Form Impor Data</h4>
			</div>
			<form role="form" action="<?php echo base_url()."admin/Master/import_jenis_belanja"; ?>" method='post' enctype="multipart/form-data">
				<div class="modal-body">
					<div class="form-body">
						<div class="form-group form-md-line-input has-danger">
							<label class="col-md-3 control-label" for="form_control_1">File Import <span class="required"> * </span></label>
							<div class="col-md-9">
								<div class="input-icon">
									<input class="form-control" type="file" name='fmasuk' required>
									<input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>">
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
					<button type="submit" class="btn btn-primary">Unggah</button>
				</div>
			</form>
		</div>
	</div>
</div>