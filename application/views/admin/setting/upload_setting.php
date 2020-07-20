<script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
<style>
    td, th {
        border: 1px solid #999;
        padding: 0.5rem;
    }
</style>
<ul class="page-breadcrumb breadcrumb">
	<li>
		<span>Pengaturan</span>
		<i class="fa fa-circle"></i>
	</li>
	<li>
		<span>Format Upload</span>
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
					<div class="table-toolbar">
						<div class="row">
							<div class="col-md-8">
								<a href="<?=base_url('admin_side/format_upload');?>" class="btn green uppercase">Ubah Data <i class="fa fa-pencil"></i> </a>
							</div>
						</div>
					</div>
                    <table>
                        <thead>
                            <tr>
                                <th style='text-align:center'>Keterangan</th>
                                <th style='text-align:center'>Kolom</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($upload_setting as $key => $value) {
                            ?>
                            <tr>
                                <td style='text-align:center'><?= $value->keterangan; ?></td>
                                <td style='text-align:center'><?= 'Kolom '.$value->kolom; ?></td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
				</div>
			</div>
		</div>
	</div>
</div>