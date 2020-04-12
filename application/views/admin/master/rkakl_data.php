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
		<span>RKAKL</span>
	</li>
</ul>
<?= $this->session->flashdata('sukses') ?>
<?= $this->session->flashdata('gagal') ?>
<div class="page-content-inner">
	<div class="m-heading-1 border-yellow m-bordered" style="background-color:#FAD405;">
		<h3>Catatan</h3>
		<p> 1. Kolom isian dengan tanda bintang (<font color='red'>*</font>) adalah wajib untuk di isi.</p>
		<p> 2. Ketentuan file yang diupload:</p>
		<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- Format berupa file <b>.pdf</b></p>
		<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- Ukuran maksimum file <b>3 MB</b></p>
	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="portlet light ">
				<div class="portlet-body">
					<div class="table-toolbar">
						<div class="row">
							<div class="col-md-12">
                                <form role="form" class="form-horizontal" action="<?=base_url('admin_side/perbarui_data_rkakl');?>" method="post" enctype='multipart/form-data'>
                                    <div class="form-group form-md-line-input has-danger">
                                        <div class="col-md-2"></div>
                                        <label class="col-md-2 control-label" for="form_control_1">File RKAKL Terbaru <span class="required"> * </span></label>
                                        <div class="col-md-4">
                                            <div class="input-icon">
                                                <input type="file" class="form-control" name="file" required>
                                                <div class="form-control-focus"> </div>
                                                <span class="help-block">Some help goes here...</span>
                                                <i class="icon-pin"></i>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <button type="submit" class="btn blue">Perbarui</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <hr>
                            <hr>
                            <hr>
                            <hr>
                            <?php
                            $get_file = $this->Main_model->getSelectedData('rkakl a', 'a.*',array('a.is_active'=>'1'))->row();
                            ?>
                            <a href='<?=base_url()?>data_upload/rkakl/<?= $get_file->file; ?>' class='btn green'>Unduh Dokumen RKAKL</a><br><br>
                            <iframe height="600" width="100%" src="<?=base_url()?>data_upload/rkakl/<?= $get_file->file; ?>"></iframe>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>