<script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
<style media="all" type="text/css">
    .alignCenter { text-align: center; }
</style>
<ul class="page-breadcrumb breadcrumb">
	<li>
		<span>Laporan</span>
		<i class="fa fa-circle"></i>
	</li>
	<li>
		<span>Data Belanja</span>
	</li>
</ul>
<script type="text/javascript">
	$(function(){
		$.ajaxSetup({
			type:"POST",
			url: "<?php echo site_url('/admin/Report/ajax_function')?>",
			cache: false,
		});
		$("#kode_kegiatan").change(function(){
			var value=$(this).val();
			$.ajax({
				data:{id:value,modul:'get_kode_output_by_kode_kegiatan'},
				success: function(respond){
					$("#kode_output").html(respond);
				}
			})
		});
        $("#kode_output").change(function(){
			var kode_output=$(this).val();
			var kode_kegiatan=$("#kode_kegiatan").val();
			$.ajax({
				data:{kode_output:kode_output,kode_kegiatan:kode_kegiatan,modul:'get_kode_sub_output_by_kode_output'},
				success: function(respond){
					$("#kode_sub_output").html(respond);
				}
			})
		});
        $("#kode_sub_output").change(function(){
			var kode_sub_output=$(this).val();
			var kode_kegiatan=$("#kode_kegiatan").val();
			var kode_output=$("#kode_output").val();
			$.ajax({
				data:{kode_sub_output:kode_sub_output,kode_output:kode_output,kode_kegiatan:kode_kegiatan,modul:'get_kode_komponen_by_kode_sub_output'},
				success: function(respond){
					$("#kode_komponen").html(respond);
				}
			})
		});
		$("#kode_komponen").change(function(){
			var kode_komponen=$(this).val();
			var kode_kegiatan=$("#kode_kegiatan").val();
			var kode_output=$("#kode_output").val();
			var kode_sub_output=$("#kode_sub_output").val();
			$.ajax({
				data:{kode_komponen:kode_komponen,kode_sub_output:kode_sub_output,kode_output:kode_output,kode_kegiatan:kode_kegiatan,modul:'get_kode_sub_komponen_by_kode_komponen'},
				success: function(respond){
					$("#kode_sub_komponen").html(respond);
				}
			})
		});
	})
</script>
<?= $this->session->flashdata('sukses') ?>
<?= $this->session->flashdata('gagal') ?>
<div class="page-content-inner">
	<div class="m-heading-1 border-yellow m-bordered" style="background-color:#FAD405;">
		<h3>Catatan</h3>
		<!-- <p> 1. Ketika mengklik <b>Atur Ulang Sandi</b>, maka kata sandi otomatis menjadi "<b>1234</b>"</p> -->
	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="portlet light ">
				<div class="portlet-body">
					<h3>Filter Pencarian</h3>
					<form role="form" class="form-horizontal" action="<?=base_url('admin_side/penggunaan_anggaran');?>" method="post" enctype='multipart/form-data'>
						<div class="form-body">
                            <div class="form-group form-md-line-input has-danger">
								<label class="col-md-2 control-label" for="form_control_1">Kegiatan</label>
								<div class="col-md-10">
									<div class="input-icon">
										<select class="form-control" id='kode_kegiatan' name="kode_kegiatan_where">
											<option value='All' <?php if($kode_kegiatan_where=='All'){echo'selected';}else{echo'';} ?>>All</option>
                                            <?php
                                            $keg = $this->Main_model->getSelectedData('tbl_kegiatan a', 'a.*')->result();
                                            foreach ($keg as $key => $value) {
												if($value->kode_kegiatan==$kode_kegiatan_where){
													echo"<option value='".$value->kode_kegiatan."' selected>".$value->kegiatan."</option>";
												}else{
													echo"<option value='".$value->kode_kegiatan."'>".$value->kegiatan."</option>";
												}
                                            }
                                            ?>
										</select>
										<div class="form-control-focus"> </div>
										<i class="icon-pin"></i>
									</div>
								</div>
							</div>
                            <div class="form-group form-md-line-input has-danger">
								<label class="col-md-2 control-label" for="form_control_1">Output</label>
								<div class="col-md-10">
									<div class="input-icon">
										<select class="form-control" id='kode_output' name="kode_output_where">
											<option value='All' <?php if($kode_output_where=='All'){echo'selected';}else{echo'';} ?>>All</option>
											<?php
											$out = $this->Main_model->getSelectedData('tbl_output a', 'a.*', array('a.kode_kegiatan'=>$kode_kegiatan_where))->result();
											foreach ($out as $key => $value) {
												if($value->kode_output==$kode_output_where){
													echo"<option value='".$value->kode_output."' selected>".$value->output."</option>";
												}else{
													echo"<option value='".$value->kode_output."'>".$value->output."</option>";
												}
											}
											?>
										</select>
										<div class="form-control-focus"> </div>
										<i class="icon-pin"></i>
									</div>
								</div>
                            </div>
                            <div class="form-group form-md-line-input has-danger">
								<label class="col-md-2 control-label" for="form_control_1">Sub Output</label>
								<div class="col-md-10">
									<div class="input-icon">
										<select class="form-control" id='kode_sub_output' name="kode_sub_output_where">
											<option value='All' <?php if($kode_sub_output_where=='All'){echo'selected';}else{echo'';} ?>>All</option>
											<?php
											$s_out = $this->Main_model->getSelectedData('tbl_sub_output a', 'a.*', array('a.kode_output'=>$kode_output_where,'a.kode_kegiatan'=>$kode_kegiatan_where))->result();
											foreach ($s_out as $key => $value) {
												if($value->kode_sub_output==$kode_sub_output_where){
													echo"<option value='".$value->kode_sub_output."' selected>".$value->sub_output."</option>";
												}else{
													echo"<option value='".$value->kode_sub_output."'>".$value->sub_output."</option>";
												}
											}
											?>
										</select>
										<div class="form-control-focus"> </div>
										<i class="icon-pin"></i>
									</div>
								</div>
                            </div>
                            <div class="form-group form-md-line-input has-danger">
								<label class="col-md-2 control-label" for="form_control_1">Komponen</label>
								<div class="col-md-10">
									<div class="input-icon">
										<select class="form-control" id='kode_komponen' name="kode_komponen_where">
											<option value='All' <?php if($kode_komponen_where=='All'){echo'selected';}else{echo'';} ?>>All</option>
											<?php
											$kom = $this->Main_model->getSelectedData('tbl_komponen a', 'a.*', array('a.kode_sub_output'=>$kode_sub_output_where,'a.kode_output'=>$kode_output_where,'a.kode_kegiatan'=>$kode_kegiatan_where))->result();
											foreach ($kom as $key => $value) {
												if($value->kode_komponen==$kode_komponen_where){
													echo"<option value='".$value->kode_komponen."' selected>".$value->komponen."</option>";
												}else{
													echo"<option value='".$value->kode_komponen."'>".$value->komponen."</option>";
												}
											}
											?>
										</select>
										<div class="form-control-focus"> </div>
										<i class="icon-pin"></i>
									</div>
								</div>
							</div>
							<div class="form-group form-md-line-input has-danger">
								<label class="col-md-2 control-label" for="form_control_1">Sub Komponen</label>
								<div class="col-md-10">
									<div class="input-icon">
										<select class="form-control" id='kode_sub_komponen' name="kode_sub_komponen_where">
											<option value='All' <?php if($kode_sub_komponen_where=='All'){echo'selected';}else{echo'';} ?>>All</option>
											<?php
											$s_kom = $this->Main_model->getSelectedData('tbl_sub_komponen a', 'a.*', array('a.kode_komponen'=>$kode_komponen_where,'a.kode_sub_output'=>$kode_sub_output_where,'a.kode_output'=>$kode_output_where,'a.kode_kegiatan'=>$kode_kegiatan_where))->result();
											foreach ($s_kom as $key => $value) {
												if($value->kode_sub_komponen==$kode_sub_komponen_where){
													echo"<option value='".$value->kode_sub_komponen."' selected>".$value->sub_komponen."</option>";
												}else{
													echo"<option value='".$value->kode_sub_komponen."'>".$value->sub_komponen."</option>";
												}
											}
											?>
										</select>
										<div class="form-control-focus"> </div>
										<i class="icon-pin"></i>
									</div>
								</div>
							</div>
							<div class="form-group form-md-line-input has-danger">
								<label class="col-md-2 control-label" for="form_control_1">Kode Beban</label>
								<div class="col-md-10">
									<div class="input-icon">
										<select class="form-control" id='kode_beban' name="kode_beban_where">
											<option value='All' <?php if($kode_beban_where=='All'){echo'selected';}else{echo'';} ?>>All</option>
											<?php
                                            $beb = $this->Main_model->getSelectedData('tbl_belanja a', 'a.*', '', '', '', '', 'a.kode_beban')->result();
                                            foreach ($beb as $key => $value) {
												if($value->kode_beban==$kode_beban_where){
													echo"<option value='".$value->kode_beban."' selected>".$value->kode_beban."</option>";
												}else{
													echo"<option value='".$value->kode_beban."'>".$value->kode_beban."</option>";
												}
                                            }
                                            ?>
										</select>
										<div class="form-control-focus"> </div>
										<i class="icon-pin"></i>
									</div>
								</div>
							</div>
						</div>
						<br>
						<div class="form-actions margin-top-10">
							<div class="row">
								<div class="col-md-offset-2 col-md-10">
									<button type="reset" class="btn default">Batal</button>
									<button type="submit" class="btn blue">Proses</button>
								</div>
							</div>
						</div>
					</form>
					<hr>
					<div class="table-toolbar">
						<div class="row">
							<div class="col-md-8">
								<a href="#" class="btn green uppercase">Tambah Data <i class="fa fa-plus"></i> </a>
							</div>
							<div class="col-md-4" style='text-align: right;'>
								<a href="#" class="btn btn-info" data-toggle="modal" data-target="#fi">Impor Data <i class="fa fa-cloud-upload"></i></a>
								<a href="<?=base_url()?>import_data_template/template_anggaran.xlsx" class="btn btn-warning">Unduh Template</a>
							</div>
						</div>
					</div>
					<table class="table table-striped table-bordered table-hover order-column" id="tbl">
						<thead>
							<tr>
								
								<th style="text-align: center;" width="4%"> # </th>
								<th style="text-align: center;"> Kode Jenis Belanja </th>
								<th style="text-align: center;"> Kode Beban </th>
								<th style="text-align: center;"> Kode Sub Komponen </th>
								<th style="text-align: center;"> Sub Komponen </th>
								<th style="text-align: center;"> Kode Komponen </th>
								<th style="text-align: center;"> Pagu </th>
								<th style="text-align: center;"> Realisasi </th>
								<th style="text-align: center;"> Sisa </th>
								<th style="text-align: center;" width="7%"> Aksi </th>
							</tr>
						</thead>
					</table>
					<script type="text/javascript" language="javascript" >
						$(document).ready(function(){
							var kode_kegiatan_where = '<?= $kode_kegiatan_where; ?>';
							var kode_output_where = '<?= $kode_output_where; ?>';
							var kode_sub_output_where = '<?= $kode_sub_output_where; ?>';
							var kode_komponen_where = '<?= $kode_komponen_where; ?>';
							var kode_sub_komponen_where = '<?= $kode_sub_komponen_where; ?>';
							var kode_beban_where = '<?= $kode_beban_where; ?>';
							$('#tbl').dataTable({
								"order": [[ 0, "asc" ]],
								"bProcessing": true,
								"ajax" : {
									type:"POST",
									url:"<?= site_url('admin/Report/json_penggunaan_anggaran'); ?>",
                                    data: {kode_kegiatan_where:kode_kegiatan_where,kode_output_where:kode_output_where,kode_sub_output_where:kode_sub_output_where,kode_komponen_where:kode_komponen_where,kode_sub_komponen_where:kode_sub_komponen_where,kode_beban_where:kode_beban_where},
								},
								"aoColumns": [
											{ mData: 'number', sClass: "alignCenter" },
											{ mData: 'jenis', sClass: "alignCenter" } ,
											{ mData: 'beban', sClass: "alignCenter" } ,
											{ mData: 'kode_sub_komponen', sClass: "alignCenter" } ,
											{ mData: 'sub_komponen', sClass: "alignCenter" } ,
											{ mData: 'komponen', sClass: "alignCenter" } ,
											{ mData: 'pagu', sClass: "alignCenter" },
											{ mData: 'realisasi', sClass: "alignCenter" },
											{ mData: 'sisa', sClass: "alignCenter" },
											{ mData: 'action', sClass: "alignCenter" }
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
			<form role="form" action="<?php echo base_url()."admin/Report/import_serapan"; ?>" method='post' enctype="multipart/form-data">
				<div class="modal-body">
					<div class="form-body">
						<div class="form-group form-md-line-input has-danger">
							<label class="col-md-3 control-label" for="form_control_1">Bulan <span class="required"> * </span></label>
							<div class="col-md-9">
								<div class="input-icon">
									<input type='month' class="form-control" name='bulan' required>
								</div>
							</div>
						</div>
						<div class="form-group form-md-line-input has-danger">
							<label class="col-md-3 control-label" for="form_control_1">File Import <span class="required"> * </span></label>
							<div class="col-md-9">
								<div class="input-icon">
									<input class="form-control" type="file" name='fmasuk' required>
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