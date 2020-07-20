<script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
<ul class="page-breadcrumb breadcrumb">
	<li>
		<span>Pengaturan</span>
		<i class="fa fa-circle"></i>
	</li>
	<li>
		<span><a href='<?= site_url('/admin_side/upload_setting'); ?>'>Format Upload</a></span>
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
		<p>1. Isian dengan tanda bintang (<font color='red'>*</font>) adalah wajib untuk di isi.</p>
		<p>2. Kolom tidak boleh ada yang sama.</p>
	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="portlet light ">
				<div class="portlet-body">
                    <form role="form" class="form-horizontal" action="<?=base_url('admin_side/update_format_upload');?>" method="post" enctype='multipart/form-data'>
						<input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>">
						<div class="form-body">
                            <?php
                            foreach ($upload_setting as $key => $value) {
                            ?>
							<div class="form-group form-md-line-input has-danger">
								<label class="col-md-2 control-label" for="form_control_1"><?= $value->keterangan; ?> <span class="required"> * </span></label>
								<div class="col-md-3">
									<div class="input-icon">
										<select class='form-control' name='<?= $value->id; ?>' required>
                                            <option value=''>-- Pilih --</option>
                                            <?php
                                            for ($i='A'; $i <= 'Z'; $i++) {
                                                if(strlen($i)==1){
                                                    if($i==$value->kolom){
                                                        echo"<option value='".$i."' selected>Kolom ".$i."</option>";
                                                    }else{
                                                        echo"<option value='".$i."'>Kolom ".$i."</option>";
                                                    }
                                                }else{
                                                    if($i>'AZ'){
                                                        echo'';
                                                    }else{
                                                        if($i==$value->kolom){
                                                            echo"<option value='".$i."' selected>Kolom ".$i."</option>";
                                                        }else{
                                                            echo"<option value='".$i."'>Kolom ".$i."</option>";
                                                        }
                                                    }
                                                }
                                            }
                                            ?>
                                        </select>
										<div class="form-control-focus"> </div>
										<span class="help-block">Some help goes here...</span>
										<i class="icon-pin"></i>
									</div>
								</div>
                            </div>
                            <?php } ?>
						</div>
						<br>
						<div class="form-actions margin-top-10">
							<div class="row">
								<div class="col-md-offset-2 col-md-10">
									<button type="reset" class="btn default">Batal</button>
									<button type="submit" class="btn blue">Perbarui</button>
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>