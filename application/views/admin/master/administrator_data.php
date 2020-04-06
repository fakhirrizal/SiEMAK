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
		<span>Data Admin</span>
	</li>
</ul>
<?= $this->session->flashdata('sukses') ?>
<?= $this->session->flashdata('gagal') ?>
<div class="page-content-inner">
	<div class="m-heading-1 border-yellow m-bordered" style="background-color:#FAD405;">
		<h3>Catatan</h3>
		<p> 1. Ketika mengklik <b>Atur Ulang Sandi</b>, maka kata sandi otomatis menjadi "<b>1234</b>"</p>
	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="portlet light ">
				<div class="portlet-body">
					<div class="table-toolbar">
						<div class="row">
							<div class="col-md-8">
								<a href="<?=base_url('admin_side/tambah_data_admin');?>" class="btn green uppercase">Tambah Data <i class="fa fa-plus"></i> </a>
							</div>
						</div>
					</div>
					<table class="table table-striped table-bordered table-hover order-column" id="tbl">
						<thead>
							<tr>
								
								<th style="text-align: center;" width="4%"> # </th>
								<th style="text-align: center;"> Nama </th>
								<th style="text-align: center;"> Username </th>
								<th style="text-align: center;"> Total Login </th>
								<th style="text-align: center;"> Last Activity </th>
								<th style="text-align: center;" width="7%"> Aksi </th>
							</tr>
						</thead>
					</table>
					<script type="text/javascript" language="javascript" >
						$(document).ready(function(){
							$('#tbl').dataTable({
								"order": [[ 0, "asc" ]],
								"bProcessing": true,
								"ajax" : {
									url:"<?= site_url('admin/Master/json_admin'); ?>"
								},
								"aoColumns": [
											{ mData: 'number', sClass: "alignCenter" },
											{ mData: 'nama' } ,
											{ mData: 'username', sClass: "alignCenter" } ,
											{ mData: 'total_login', sClass: "alignCenter" },
											{ mData: 'last_activity', sClass: "alignCenter" },
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