<script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
<ul class="page-breadcrumb breadcrumb">
	<li>
		<span>Master</span>
		<i class="fa fa-circle"></i>
	</li>
	<li>
		<span>Data Output</span>
		<i class="fa fa-circle"></i>
	</li>
	<li>
		<span>Ubah Data</span>
	</li>
</ul>
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
					<form role="form" class="form-horizontal" action="<?=base_url('admin_side/perbarui_data_output');?>" method="post" enctype='multipart/form-data'>
						<input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>">
						<input type="hidden" name="id" value='<?= md5($data_utama->id_output); ?>'>
						<input type="hidden" name="kode_kegiatan" value='<?= $data_utama->kode_kegiatan; ?>'>
						<div class="form-body">
							<div class="form-group form-md-line-input has-danger">
								<label class="col-md-2 control-label" for="form_control_1">Kode Output <span class="required"> * </span></label>
								<div class="col-md-10">
									<div class="input-icon">
										<input type="text" class="form-control" name="kode_output" placeholder="Type something" value='<?= $data_utama->kode_output; ?>' required>
										<input type="hidden" name="kode_output_old" value='<?= $data_utama->kode_output; ?>'>
										<div class="form-control-focus"> </div>
										<span class="help-block">Some help goes here...</span>
										<i class="icon-pin"></i>
									</div>
								</div>
							</div>
                            <div class="form-group form-md-line-input has-danger">
								<label class="col-md-2 control-label" for="form_control_1">Kegiatan </label>
								<div class="col-md-10">
									<div class="input-icon">
										<select class="form-control" name="kode_kegiatan" disabled>
											<option value=''>-- Pilih --</option>
                                            <?php
                                            $keg = $this->Main_model->getSelectedData('tbl_kegiatan a', 'a.*')->result();
                                            foreach ($keg as $key => $value) {
                                                if($value->kode_kegiatan==$data_utama->kode_kegiatan){
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
								<label class="col-md-2 control-label" for="form_control_1">Output <span class="required"> * </span></label>
								<div class="col-md-10">
									<div class="input-icon">
										<input type="text" class="form-control" name="output" placeholder="Type something" value='<?= $data_utama->output; ?>' required>
										<div class="form-control-focus"> </div>
										<span class="help-block">Some help goes here...</span>
										<i class="icon-pin"></i>
									</div>
								</div>
							</div>
                            <div class="form-group form-md-line-input has-danger">
								<label class="col-md-2 control-label" for="form_control_1">Pagu Anggaran <span class="required"> * </span></label>
								<div class="col-md-10">
									<div class="input-icon">
										<input type="number" class="form-control" name="pagu" placeholder="Type something" value='<?= $data_utama->pagu; ?>' required>
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