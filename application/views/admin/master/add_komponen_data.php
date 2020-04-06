<script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
<ul class="page-breadcrumb breadcrumb">
	<li>
		<span>Master</span>
		<i class="fa fa-circle"></i>
	</li>
	<li>
		<span>Data Komponen</span>
		<i class="fa fa-circle"></i>
	</li>
	<li>
		<span>Tambah Data</span>
	</li>
</ul>
<script type="text/javascript">
	$(function(){
		$.ajaxSetup({
			type:"POST",
			url: "<?php echo site_url('/admin/Master/ajax_function')?>",
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
	})
</script>
<?= $this->session->flashdata('sukses') ?>
<?= $this->session->flashdata('gagal') ?>
<div class="page-content-inner">
	<div class="m-heading-1 border-yellow m-bordered" style="background-color:#FAD405;">
		<h3>Catatan</h3>
		<p> Kolom isian dengan tanda bintang (<font color='red'>*</font>) adalah wajib untuk di isi.</p>
	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="portlet light ">
				<div class="portlet-body">
					<form role="form" class="form-horizontal" action="<?=base_url('admin_side/simpan_data_komponen');?>" method="post" enctype='multipart/form-data'>
						<div class="form-body">
							<div class="form-group form-md-line-input has-danger">
								<label class="col-md-2 control-label" for="form_control_1">Kode Komponen <span class="required"> * </span></label>
								<div class="col-md-10">
									<div class="input-icon">
										<input type="text" class="form-control" name="kode_komponen" placeholder="Type something" required>
										<div class="form-control-focus"> </div>
										<span class="help-block">Some help goes here...</span>
										<i class="icon-pin"></i>
									</div>
								</div>
                            </div>
                            <div class="form-group form-md-line-input has-danger">
								<label class="col-md-2 control-label" for="form_control_1">Komponen <span class="required"> * </span></label>
								<div class="col-md-10">
									<div class="input-icon">
										<input type="text" class="form-control" name="komponen" placeholder="Type something" required>
										<div class="form-control-focus"> </div>
										<span class="help-block">Some help goes here...</span>
										<i class="icon-pin"></i>
									</div>
								</div>
							</div>
                            <div class="form-group form-md-line-input has-danger">
								<label class="col-md-2 control-label" for="form_control_1">Kegiatan <span class="required"> * </span></label>
								<div class="col-md-10">
									<div class="input-icon">
										<select class="form-control" id='kode_kegiatan' name="kode_kegiatan" required>
											<option value=''>-- Pilih --</option>
                                            <?php
                                            $keg = $this->Main_model->getSelectedData('tbl_kegiatan a', 'a.*')->result();
                                            foreach ($keg as $key => $value) {
                                                echo"<option value='".$value->kode_kegiatan."'>".$value->kegiatan."</option>";
                                            }
                                            ?>
										</select>
										<div class="form-control-focus"> </div>
										<i class="icon-pin"></i>
									</div>
								</div>
							</div>
                            <div class="form-group form-md-line-input has-danger">
								<label class="col-md-2 control-label" for="form_control_1">Output <span class="required"> * </span></label>
								<div class="col-md-10">
									<div class="input-icon">
										<select class="form-control" id='kode_output' name="kode_output" required>
											<option value=''>-- Pilih --</option>
										</select>
										<div class="form-control-focus"> </div>
										<i class="icon-pin"></i>
									</div>
								</div>
                            </div>
                            <div class="form-group form-md-line-input has-danger">
								<label class="col-md-2 control-label" for="form_control_1">Sub Output <span class="required"> * </span></label>
								<div class="col-md-10">
									<div class="input-icon">
										<select class="form-control" id='kode_sub_output' name="kode_sub_output" required>
											<option value=''>-- Pilih --</option>
										</select>
										<div class="form-control-focus"> </div>
										<i class="icon-pin"></i>
									</div>
								</div>
							</div>
                            <div class="form-group form-md-line-input has-danger">
								<label class="col-md-2 control-label" for="form_control_1">Pagu Anggaran <span class="required"> * </span></label>
								<div class="col-md-10">
									<div class="input-icon">
										<input type="number" class="form-control" name="pagu" placeholder="Type something" required>
										<div class="form-control-focus"> </div>
										<span class="help-block">Some help goes here...</span>
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
									<button type="submit" class="btn blue">Simpan</button>
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>