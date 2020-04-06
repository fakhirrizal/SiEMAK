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
		<span><a href='<?= site_url('/kpa_side/penggunaan_anggaran'); ?>'>Data Belanja</a></span>
		<i class="fa fa-circle"></i>
	</li>
	<li>
		<span>Detil Data</span>
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
					<div class='row'>
						<?php
						if(isset($data_utama)){
							foreach($data_utama as $row)
							{
						?>
								<div class="col-md-6">
									<table class="table">
										<tbody>
											<tr>
												<td> Kode Jenis Belanja </td>
												<td> : </td>
												<td><?php echo $row->kode_jenis_belanja; ?></td>
											</tr>
											<tr>
												<td> Kode Beban </td>
												<td> : </td>
												<td><?php echo $row->kode_beban; ?></td>
											</tr>
											<tr>
												<td> Kode Sub Komponen </td>
												<td> : </td>
												<td><?php echo $row->kode_sub_komponen; ?></td>
											</tr>
											<tr>
												<td> Kode Komponen </td>
												<td> : </td>
												<td><?php echo $row->kode_komponen; ?></td>
											</tr>
											<tr>
												<td> Kode Sub Output </td>
												<td> : </td>
												<td><?php echo $row->kode_sub_output; ?></td>
											</tr>
											<tr>
												<td> Kode Output </td>
												<td> : </td>
												<td><?php echo $row->kode_output; ?></td>
											</tr>
											<tr>
												<td> </td>
												<td> </td>
												<td> </td>
											</tr>
										</tbody>
									</table>
								</div>
								<div class="col-md-6">
									<table class="table">
										<tbody>
											<tr>
												<td> Kode Kegiatan </td>
												<td> : </td>
												<td><?php echo $row->kode_kegiatan; ?></td>
											</tr>
											<tr>
												<td> Realisasi </td>
												<td> : </td>
												<td><?php echo 'Rp '.number_format($row->realisasi,2); ?></td>
											</tr>
											<tr>
												<td> Keterangan </td>
												<td> : </td>
												<td><?php echo $row->keterangan; ?></td>
											</tr>
											<tr>
												<td> Bulan </td>
												<td> : </td>
												<td><?php echo $this->Main_model->convert_bulan_tahun($row->bulan); ?></td>
											</tr>
											<tr>
												<td> </td>
												<td> </td>
												<td> </td>
											</tr>
										</tbody>
									</table>
								</div>
						<?php }} ?>
						<div class="col-md-12" >
						<hr><a href="<?php echo base_url()."kpa_side/penggunaan_anggaran"; ?>" class="btn btn-info" role="button"><i class="fa fa-angle-double-left"></i> Kembali</a></div>
					</div>
				</div>
			</div>
		</div>
	</div>