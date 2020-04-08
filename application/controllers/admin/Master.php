<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Master extends CI_Controller {
	function __construct() {
		parent::__construct();
	}
	/* RKAKL */
	public function rkakl_data()
	{
		$data['parent'] = 'master';
		$data['child'] = 'master_rkakl';
		$data['grand_child'] = '';
		$this->load->view('admin/template/header',$data);
		$this->load->view('admin/master/rkakl_data',$data);
		$this->load->view('admin/template/footer');
	}
	public function update_rkakl_data(){
		$nmfile = "file_".time(); // nama file saya beri nama langsung dan diikuti fungsi time
		$config['upload_path'] = dirname($_SERVER["SCRIPT_FILENAME"]).'/data_upload/rkakl/'; // path folder
		$config['allowed_types'] = 'pdf'; // type yang dapat diakses bisa anda sesuaikan
		$config['max_size'] = '3072'; // maksimum besar file 3M
		$config['file_name'] = $nmfile; // nama yang terupload nantinya

		$this->upload->initialize($config);
		if(isset($_FILES['file']['name']))
		{
			if(!$this->upload->do_upload('file'))
			{
				$a = $this->upload->display_errors();
				$this->session->set_flashdata('gagal','<div class="alert alert-warning"><button type="button" class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button><strong></i>Oops! </strong>'.$a.'<br /></div>' );
				echo "<script>window.location='".base_url()."admin_side/tambah_data_kegiatan/'</script>";
			}
			else
			{
				$this->db->trans_start();
				$f = $this->upload->data();
				$this->Main_model->updateData('rkakl',array('file'=>$f['file_name']),array('is_active'=>'1'));
				$this->db->trans_complete();
				if($this->db->trans_status() === false){
					$this->session->set_flashdata('gagal','<div class="alert alert-warning"><button type="button" class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button><strong></i>Oops! </strong>data gagal diperbarui.<br /></div>' );
					echo "<script>window.location='".base_url()."admin_side/rkakl/'</script>";
				}
				else{
					$this->session->set_flashdata('sukses','<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button><strong></i>Yeah! </strong>data telah berhasil diperbarui.<br /></div>' );
					echo "<script>window.location='".base_url()."admin_side/rkakl/'</script>";
				}
			}
		}else{echo'';}
	}
	/* Kegiatan */
	public function kegiatan_data()
	{
		$data['parent'] = 'master';
		$data['child'] = 'master_kegiatan';
		$data['grand_child'] = '';
		$this->load->view('admin/template/header',$data);
		$this->load->view('admin/master/kegiatan_data',$data);
		$this->load->view('admin/template/footer');
	}
	public function json_kegiatan(){
		$get_data = $this->Main_model->getSelectedData('tbl_kegiatan a', 'a.*')->result();
		$data_tampil = array();
		$no = 1;
		foreach ($get_data as $key => $value) {
			$isi['number'] = $no++.'.';
			$isi['kode'] = $value->kode_kegiatan;
			$isi['kegiatan'] = $value->kegiatan;
			$isi['pagu'] = 'Rp '.number_format($value->pagu,2);
			$get_data_belanja = $this->Main_model->getSelectedData('tbl_belanja a', 'a.*', array('a.kode_kegiatan'=>$value->kode_kegiatan,'a.bulan'=>$this->Main_model->get_where_bulan()))->result();
			$realisasi = 0;
			foreach ($get_data_belanja as $key => $row) {
				$realisasi += $row->realisasi;
			}
			$isi['realisasi'] = 'Rp '.number_format($realisasi,2);
			$sisa = ($value->pagu)-$realisasi;
			$isi['sisa'] = 'Rp '.number_format($sisa,2);
			$return_on_click = "return confirm('Anda yakin?')";
			$isi['action'] =	'
								<div class="btn-group" style="text-align: center;">
									<button class="btn btn-xs green dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false"> Aksi
										<i class="fa fa-angle-down"></i>
									</button>
									<ul class="dropdown-menu" role="menu">
										<li>
											<a href="'.site_url('admin_side/ubah_data_kegiatan/'.md5($value->id_kegiatan)).'">
												<i class="icon-wrench"></i> Ubah Data </a>
										</li>
										<li>
											<a onclick="'.$return_on_click.'" href="'.site_url('admin_side/hapus_data_kegiatan/'.md5($value->id_kegiatan)).'">
												<i class="icon-trash"></i> Hapus Data </a>
										</li>
									</ul>
								</div>
								';
			$data_tampil[] = $isi;
		}
		$results = array(
			"sEcho" => 1,
			"iTotalRecords" => count($data_tampil),
			"iTotalDisplayRecords" => count($data_tampil),
			"aaData"=>$data_tampil);
		echo json_encode($results);
	}
	public function add_kegiatan_data()
	{
		$data['parent'] = 'master';
		$data['child'] = 'master_kegiatan';
		$data['grand_child'] = '';
		$this->load->view('admin/template/header',$data);
		$this->load->view('admin/master/add_kegiatan_data',$data);
		$this->load->view('admin/template/footer');
	}
	public function save_kegiatan_data(){
		$cek_ = $this->Main_model->getSelectedData('tbl_kegiatan a', 'a.*',array('a.kode_kegiatan'=>$this->input->post('kode_kegiatan')))->row();
		if($cek_==NULL){
			$this->db->trans_start();
			$get_id_kegiatan = $this->Main_model->getLastID('tbl_kegiatan','id_kegiatan');

			$data_insert1 = array(
				'id_kegiatan' => $get_id_kegiatan['id_kegiatan']+1,
				'kode_kegiatan' => $this->input->post('kode_kegiatan'),
				'kegiatan' => $this->input->post('kegiatan'),
				'pagu' => $this->input->post('pagu')
			);
			$this->Main_model->insertData('tbl_kegiatan',$data_insert1);
			// print_r($data_insert1);

			$this->Main_model->log_activity($this->session->userdata('id'),'Adding data',"Menambahkan data kegiatan (".$this->input->post('kegiatan').")",$this->session->userdata('location'));
			$this->db->trans_complete();
			if($this->db->trans_status() === false){
				$this->session->set_flashdata('gagal','<div class="alert alert-warning"><button type="button" class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button><strong></i>Oops! </strong>data gagal ditambahkan.<br /></div>' );
				echo "<script>window.location='".base_url()."admin_side/tambah_data_kegiatan/'</script>";
			}
			else{
				$this->session->set_flashdata('sukses','<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button><strong></i>Yeah! </strong>data telah berhasil ditambahkan.<br /></div>' );
				echo "<script>window.location='".base_url()."admin_side/kegiatan/'</script>";
			}
		}else{
			$this->session->set_flashdata('gagal','<div class="alert alert-warning"><button type="button" class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button><strong></i>Oops! </strong>Kode kegiatan telah digunakan.<br /></div>' );
			echo "<script>window.location='".base_url()."admin_side/tambah_data_kegiatan/'</script>";
		}
	}
	public function edit_kegiatan_data()
	{
		$data['parent'] = 'master';
		$data['child'] = 'master_kegiatan';
		$data['grand_child'] = '';
		$data['data_utama'] = $this->Main_model->getSelectedData('tbl_kegiatan a', 'a.*', array('md5(a.id_kegiatan)'=>$this->uri->segment(3)))->row();
		$this->load->view('admin/template/header',$data);
		$this->load->view('admin/master/edit_kegiatan_data',$data);
		$this->load->view('admin/template/footer');
	}
	public function update_kegiatan_data(){
		if($this->input->post('kode_kegiatan')!=$this->input->post('kode_kegiatan_old')){
			$cek_ = $this->Main_model->getSelectedData('tbl_kegiatan a', 'a.*',array('a.kode_kegiatan'=>$this->input->post('kode_kegiatan')))->row();
			if($cek_==NULL){
				$this->db->trans_start();

				$data_insert1 = array(
					'kode_kegiatan' => $this->input->post('kode_kegiatan'),
					'kegiatan' => $this->input->post('kegiatan'),
					'pagu' => $this->input->post('pagu')
				);
				$this->Main_model->updateData('tbl_kegiatan',$data_insert1,array('md5(id_kegiatan)'=>$this->input->post('id')));
				$this->Main_model->updateData('tbl_belanja',array('kode_kegiatan'=>$this->input->post('kode_kegiatan')),array('kode_kegiatan'=>$this->input->post('kode_kegiatan_old')));
				$this->Main_model->updateData('tbl_jenis_belanja',array('kode_kegiatan'=>$this->input->post('kode_kegiatan')),array('kode_kegiatan'=>$this->input->post('kode_kegiatan_old')));
				$this->Main_model->updateData('tbl_komponen',array('kode_kegiatan'=>$this->input->post('kode_kegiatan')),array('kode_kegiatan'=>$this->input->post('kode_kegiatan_old')));
				$this->Main_model->updateData('tbl_output',array('kode_kegiatan'=>$this->input->post('kode_kegiatan')),array('kode_kegiatan'=>$this->input->post('kode_kegiatan_old')));
				$this->Main_model->updateData('tbl_sub_komponen',array('kode_kegiatan'=>$this->input->post('kode_kegiatan')),array('kode_kegiatan'=>$this->input->post('kode_kegiatan_old')));
				$this->Main_model->updateData('tbl_sub_output',array('kode_kegiatan'=>$this->input->post('kode_kegiatan')),array('kode_kegiatan'=>$this->input->post('kode_kegiatan_old')));
				// print_r($data_insert1);

				$this->Main_model->log_activity($this->session->userdata('id'),'Updating data',"Mengubah data kegiatan (".$this->input->post('kegiatan').")",$this->session->userdata('location'));
				$this->db->trans_complete();
				if($this->db->trans_status() === false){
					$this->session->set_flashdata('gagal','<div class="alert alert-warning"><button type="button" class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button><strong></i>Oops! </strong>data gagal diubah.<br /></div>' );
					echo "<script>window.location='".base_url()."admin_side/ubah_data_kegiatan/".md5($this->input->post('id'))."'</script>";
				}
				else{
					$this->session->set_flashdata('sukses','<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button><strong></i>Yeah! </strong>data telah berhasil diubah.<br /></div>' );
					echo "<script>window.location='".base_url()."admin_side/kegiatan/'</script>";
				}
			}else{
				$this->session->set_flashdata('gagal','<div class="alert alert-warning"><button type="button" class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button><strong></i>Oops! </strong>Kode kegiatan telah digunakan.<br /></div>' );
				echo "<script>window.location='".base_url()."admin_side/ubah_data_kegiatan/".$this->input->post('id')."'</script>";
			}
		}else{
			$this->db->trans_start();

			$data_insert1 = array(
				'kegiatan' => $this->input->post('kegiatan'),
				'pagu' => $this->input->post('pagu')
			);
			$this->Main_model->updateData('tbl_kegiatan',$data_insert1,array('md5(id_kegiatan)'=>$this->input->post('id')));
			// print_r($data_insert1);

			$this->Main_model->log_activity($this->session->userdata('id'),'Updating data',"Mengubah data kegiatan (".$this->input->post('kegiatan').")",$this->session->userdata('location'));
			$this->db->trans_complete();
			if($this->db->trans_status() === false){
				$this->session->set_flashdata('gagal','<div class="alert alert-warning"><button type="button" class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button><strong></i>Oops! </strong>data gagal diubah.<br /></div>' );
				echo "<script>window.location='".base_url()."admin_side/ubah_data_kegiatan/".$this->input->post('id')."'</script>";
			}
			else{
				$this->session->set_flashdata('sukses','<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button><strong></i>Yeah! </strong>data telah berhasil diubah.<br /></div>' );
				echo "<script>window.location='".base_url()."admin_side/kegiatan/'</script>";
			}
		}
	}
	public function delete_kegiatan_data(){
		$this->db->trans_start();
		$track_id = '';
		$name = '';
		$get_data = $this->Main_model->getSelectedData('tbl_kegiatan a', 'a.*',array('md5(a.id_kegiatan)'=>$this->uri->segment(3)))->row();
		$track_id = $get_data->kode_kegiatan;
		$name = $get_data->kegiatan;

		$this->Main_model->deleteData('tbl_kegiatan',array('kode_kegiatan'=>$track_id));
		$this->Main_model->deleteData('tbl_output',array('kode_kegiatan'=>$track_id));
		$this->Main_model->deleteData('tbl_sub_output',array('kode_kegiatan'=>$track_id));
		$this->Main_model->deleteData('tbl_komponen',array('kode_kegiatan'=>$track_id));
		$this->Main_model->deleteData('tbl_sub_komponen',array('kode_kegiatan'=>$track_id));
		$this->Main_model->deleteData('tbl_jenis_belanja',array('kode_kegiatan'=>$track_id));
		$this->Main_model->deleteData('tbl_belanja',array('kode_kegiatan'=>$track_id));

		$this->Main_model->log_activity($this->session->userdata('id'),"Deleting data","Menghapus data kegiatan (".$name.")",$this->session->userdata('location'));
		$this->db->trans_complete();
		if($this->db->trans_status() === false){
			$this->session->set_flashdata('gagal','<div class="alert alert-warning"><button type="button" class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button><strong></i>Oops! </strong>data gagal dihapus.<br /></div>' );
			echo "<script>window.location='".base_url()."admin_side/kegiatan/'</script>";
		}
		else{
			$this->session->set_flashdata('sukses','<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button><strong></i>Yeah! </strong>data telah berhasil dihapus.<br /></div>' );
			echo "<script>window.location='".base_url()."admin_side/kegiatan/'</script>";
		}
	}
	/* Output */
	public function output_data()
	{
		$data['parent'] = 'master';
		$data['child'] = 'master_output';
		$data['grand_child'] = '';
		$this->load->view('admin/template/header',$data);
		$this->load->view('admin/master/output_data',$data);
		$this->load->view('admin/template/footer');
	}
	public function json_output(){
		$get_data = $this->Main_model->getSelectedData('tbl_output a', 'a.*')->result();
		$data_tampil = array();
		$no = 1;
		foreach ($get_data as $key => $value) {
			$isi['number'] = $no++.'.';
			$isi['kode'] = $value->kode_output;
			$isi['kegiatan'] = $value->kode_kegiatan;
			$isi['output'] = $value->output;
			$isi['pagu'] = 'Rp '.number_format($value->pagu,2);
			$get_data_belanja = $this->Main_model->getSelectedData('tbl_belanja a', 'a.*', array('a.kode_output'=>$value->kode_output,'a.kode_kegiatan'=>$value->kode_kegiatan,'a.bulan'=>$this->Main_model->get_where_bulan()))->result();
			$realisasi = 0;
			foreach ($get_data_belanja as $key => $row) {
				$realisasi += $row->realisasi;
			}
			$isi['realisasi'] = 'Rp '.number_format($realisasi,2);
			$sisa = ($value->pagu)-$realisasi;
			$isi['sisa'] = 'Rp '.number_format($sisa,2);
			$return_on_click = "return confirm('Anda yakin?')";
			$isi['action'] =	'
								<div class="btn-group" style="text-align: center;">
									<button class="btn btn-xs green dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false"> Aksi
										<i class="fa fa-angle-down"></i>
									</button>
									<ul class="dropdown-menu" role="menu">
										<li>
											<a href="'.site_url('admin_side/ubah_data_output/'.md5($value->id_output)).'">
												<i class="icon-wrench"></i> Ubah Data </a>
										</li>
										<li>
											<a onclick="'.$return_on_click.'" href="'.site_url('admin_side/hapus_data_output/'.md5($value->id_output)).'">
												<i class="icon-trash"></i> Hapus Data </a>
										</li>
									</ul>
								</div>
								';
			$data_tampil[] = $isi;
		}
		$results = array(
			"sEcho" => 1,
			"iTotalRecords" => count($data_tampil),
			"iTotalDisplayRecords" => count($data_tampil),
			"aaData"=>$data_tampil);
		echo json_encode($results);
	}
	public function add_output_data()
	{
		$data['parent'] = 'master';
		$data['child'] = 'master_output';
		$data['grand_child'] = '';
		$this->load->view('admin/template/header',$data);
		$this->load->view('admin/master/add_output_data',$data);
		$this->load->view('admin/template/footer');
	}
	public function save_output_data(){
		$cek_ = $this->Main_model->getSelectedData('tbl_output a', 'a.*',array('a.kode_output'=>$this->input->post('kode_output'),'a.kode_kegiatan'=>$this->input->post('kode_kegiatan')))->row();
		if($cek_==NULL){
			$this->db->trans_start();
			$get_id_output = $this->Main_model->getLastID('tbl_output','id_output');

			$data_insert1 = array(
				'id_output' => $get_id_output['id_output']+1,
				'kode_output' => $this->input->post('kode_output'),
				'kode_kegiatan' => $this->input->post('kode_kegiatan'),
				'output' => $this->input->post('output'),
				'pagu' => $this->input->post('pagu')
			);
			$this->Main_model->insertData('tbl_output',$data_insert1);
			// print_r($data_insert1);

			$this->Main_model->log_activity($this->session->userdata('id'),'Adding data',"Menambahkan data output (".$this->input->post('output').")",$this->session->userdata('location'));
			$this->db->trans_complete();
			if($this->db->trans_status() === false){
				$this->session->set_flashdata('gagal','<div class="alert alert-warning"><button type="button" class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button><strong></i>Oops! </strong>data gagal ditambahkan.<br /></div>' );
				echo "<script>window.location='".base_url()."admin_side/tambah_data_output/'</script>";
			}
			else{
				$this->session->set_flashdata('sukses','<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button><strong></i>Yeah! </strong>data telah berhasil ditambahkan.<br /></div>' );
				echo "<script>window.location='".base_url()."admin_side/output/'</script>";
			}
		}else{
			$this->session->set_flashdata('gagal','<div class="alert alert-warning"><button type="button" class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button><strong></i>Oops! </strong>Kode output telah digunakan.<br /></div>' );
			echo "<script>window.location='".base_url()."admin_side/tambah_data_output/'</script>";
		}
	}
	public function edit_output_data()
	{
		$data['parent'] = 'master';
		$data['child'] = 'master_output';
		$data['grand_child'] = '';
		$data['data_utama'] = $this->Main_model->getSelectedData('tbl_output a', 'a.*', array('md5(a.id_output)'=>$this->uri->segment(3)))->row();
		$this->load->view('admin/template/header',$data);
		$this->load->view('admin/master/edit_output_data',$data);
		$this->load->view('admin/template/footer');
	}
	public function update_output_data(){
		if($this->input->post('kode_output')!=$this->input->post('kode_output_old')){
			$cek_ = $this->Main_model->getSelectedData('tbl_output a', 'a.*',array('a.kode_output'=>$this->input->post('kode_output'),'a.kode_kegiatan'=>$this->input->post('kode_kegiatan')))->row();
			if($cek_==NULL){
				$this->db->trans_start();
				$data_insert1 = array(
					'kode_output' => $this->input->post('kode_output'),
					'kode_kegiatan' => $this->input->post('kode_kegiatan'),
					'output' => $this->input->post('output'),
					'pagu' => $this->input->post('pagu')
				);
				$this->Main_model->updateData('tbl_output',$data_insert1,array('md5(id_output)'=>$this->input->post('id')));
				$this->Main_model->updateData('tbl_belanja',array('kode_output'=>$this->input->post('kode_output')),array('kode_output'=>$this->input->post('kode_output_old'),'kode_kegiatan'=>$this->input->post('kode_kegiatan')));
				$this->Main_model->updateData('tbl_jenis_belanja',array('kode_output'=>$this->input->post('kode_output')),array('kode_output'=>$this->input->post('kode_output_old'),'kode_kegiatan'=>$this->input->post('kode_kegiatan')));
				$this->Main_model->updateData('tbl_komponen',array('kode_output'=>$this->input->post('kode_output')),array('kode_output'=>$this->input->post('kode_output_old'),'kode_kegiatan'=>$this->input->post('kode_kegiatan')));
				$this->Main_model->updateData('tbl_sub_komponen',array('kode_output'=>$this->input->post('kode_output')),array('kode_output'=>$this->input->post('kode_output_old'),'kode_kegiatan'=>$this->input->post('kode_kegiatan')));
				$this->Main_model->updateData('tbl_sub_output',array('kode_output'=>$this->input->post('kode_output')),array('kode_output'=>$this->input->post('kode_output_old'),'kode_kegiatan'=>$this->input->post('kode_kegiatan')));
				// print_r($data_insert1);

				$this->Main_model->log_activity($this->session->userdata('id'),'Updating data',"Mengubah data output (".$this->input->post('output').")",$this->session->userdata('location'));
				$this->db->trans_complete();
				if($this->db->trans_status() === false){
					$this->session->set_flashdata('gagal','<div class="alert alert-warning"><button type="button" class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button><strong></i>Oops! </strong>data gagal diubah.<br /></div>' );
					echo "<script>window.location='".base_url()."admin_side/ubah_data_output/".md5($this->input->post('id'))."'</script>";
				}
				else{
					$this->session->set_flashdata('sukses','<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button><strong></i>Yeah! </strong>data telah berhasil diubah.<br /></div>' );
					echo "<script>window.location='".base_url()."admin_side/output/'</script>";
				}
			}else{
				$this->session->set_flashdata('gagal','<div class="alert alert-warning"><button type="button" class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button><strong></i>Oops! </strong>Kode output telah digunakan.<br /></div>' );
				echo "<script>window.location='".base_url()."admin_side/ubah_data_output/".$this->input->post('id')."'</script>";
			}
		}else{
			$this->db->trans_start();

			$data_insert1 = array(
				// 'kode_kegiatan' => $this->input->post('kode_kegiatan'),
				'output' => $this->input->post('output'),
				'pagu' => $this->input->post('pagu')
			);
			$this->Main_model->updateData('tbl_output',$data_insert1,array('md5(id_output)'=>$this->input->post('id')));
			// print_r($data_insert1);

			$this->Main_model->log_activity($this->session->userdata('id'),'Updating data',"Mengubah data output (".$this->input->post('output').")",$this->session->userdata('location'));
			$this->db->trans_complete();
			if($this->db->trans_status() === false){
				$this->session->set_flashdata('gagal','<div class="alert alert-warning"><button type="button" class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button><strong></i>Oops! </strong>data gagal diubah.<br /></div>' );
				echo "<script>window.location='".base_url()."admin_side/ubah_data_output/".$this->input->post('id')."'</script>";
			}
			else{
				$this->session->set_flashdata('sukses','<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button><strong></i>Yeah! </strong>data telah berhasil diubah.<br /></div>' );
				echo "<script>window.location='".base_url()."admin_side/output/'</script>";
			}
		}
	}
	public function delete_output_data(){
		$this->db->trans_start();
		$track_id = '';
		$name = '';
		$get_data = $this->Main_model->getSelectedData('tbl_output a', 'a.*',array('md5(a.id_output)'=>$this->uri->segment(3)))->row();
		$track_id = $get_data->kode_output;
		$name = $get_data->output;
		$keg = $get_data->kode_kegiatan;

		$this->Main_model->deleteData('tbl_output',array('kode_output'=>$track_id,'kode_kegiatan'=>$keg));
		$this->Main_model->deleteData('tbl_sub_output',array('kode_output'=>$track_id,'kode_kegiatan'=>$keg));
		$this->Main_model->deleteData('tbl_komponen',array('kode_output'=>$track_id,'kode_kegiatan'=>$keg));
		$this->Main_model->deleteData('tbl_sub_komponen',array('kode_output'=>$track_id,'kode_kegiatan'=>$keg));
		$this->Main_model->deleteData('tbl_jenis_belanja',array('kode_output'=>$track_id,'kode_kegiatan'=>$keg));
		$this->Main_model->deleteData('tbl_belanja',array('kode_output'=>$track_id,'kode_kegiatan'=>$keg));

		$this->Main_model->log_activity($this->session->userdata('id'),"Deleting data","Menghapus data output (".$name.")",$this->session->userdata('location'));
		$this->db->trans_complete();
		if($this->db->trans_status() === false){
			$this->session->set_flashdata('gagal','<div class="alert alert-warning"><button type="button" class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button><strong></i>Oops! </strong>data gagal dihapus.<br /></div>' );
			echo "<script>window.location='".base_url()."admin_side/output/'</script>";
		}
		else{
			$this->session->set_flashdata('sukses','<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button><strong></i>Yeah! </strong>data telah berhasil dihapus.<br /></div>' );
			echo "<script>window.location='".base_url()."admin_side/output/'</script>";
		}
	}
	/* Sub Output */
	public function sub_output_data()
	{
		$data['parent'] = 'master';
		$data['child'] = 'master_sub_output';
		$data['grand_child'] = '';
		$this->load->view('admin/template/header',$data);
		$this->load->view('admin/master/sub_output_data',$data);
		$this->load->view('admin/template/footer');
	}
	public function json_sub_output(){
		$get_data = $this->Main_model->getSelectedData('tbl_sub_output a', 'a.*')->result();
		$data_tampil = array();
		$no = 1;
		foreach ($get_data as $key => $value) {
			$isi['number'] = $no++.'.';
			$isi['kode'] = $value->kode_sub_output;
			$isi['output'] = $value->kode_output;
			$isi['kegiatan'] = $value->kode_kegiatan;
			$isi['sub_output'] = $value->sub_output;
			$isi['pagu'] = 'Rp '.number_format($value->pagu,2);
			$get_data_belanja = $this->Main_model->getSelectedData('tbl_belanja a', 'a.*', array('a.kode_sub_output'=>$value->kode_sub_output,'a.kode_output'=>$value->kode_output,'a.kode_kegiatan'=>$value->kode_kegiatan,'a.bulan'=>$this->Main_model->get_where_bulan()))->result();
			$realisasi = 0;
			foreach ($get_data_belanja as $key => $row) {
				$realisasi += $row->realisasi;
			}
			$isi['realisasi'] = 'Rp '.number_format($realisasi,2);
			$sisa = ($value->pagu)-$realisasi;
			$isi['sisa'] = 'Rp '.number_format($sisa,2);
			$return_on_click = "return confirm('Anda yakin?')";
			$isi['action'] =	'
			<a href="'.site_url('admin_side/ubah_data_sub_output/'.md5($value->id_sub_output)).'" class="btn btn-outline btn-circle btn-sm purple"><i class="fa fa-edit"></i> Ubah </a>
			<br><br>
			<a onclick="'.$return_on_click.'" href="'.site_url('admin_side/hapus_data_sub_output/'.md5($value->id_sub_output)).'" class="btn btn-outline btn-circle dark btn-sm black"><i class="fa fa-trash-o"></i> Hapus </a>
								';
			$data_tampil[] = $isi;
		}
		$results = array(
			"sEcho" => 1,
			"iTotalRecords" => count($data_tampil),
			"iTotalDisplayRecords" => count($data_tampil),
			"aaData"=>$data_tampil);
		echo json_encode($results);
	}
	public function add_sub_output_data()
	{
		$data['parent'] = 'master';
		$data['child'] = 'master_sub_output';
		$data['grand_child'] = '';
		$this->load->view('admin/template/header',$data);
		$this->load->view('admin/master/add_sub_output_data',$data);
		$this->load->view('admin/template/footer');
	}
	public function save_sub_output_data(){
		$cek_ = $this->Main_model->getSelectedData('tbl_sub_output a', 'a.*',array('a.kode_sub_output'=>$this->input->post('kode_sub_output'),'a.kode_output'=>$this->input->post('kode_output'),'a.kode_kegiatan'=>$this->input->post('kode_kegiatan')))->row();
		if($cek_==NULL){
			$this->db->trans_start();
			$get_id_sub_output = $this->Main_model->getLastID('tbl_sub_output','id_sub_output');

			$data_insert1 = array(
				'id_sub_output' => $get_id_sub_output['id_sub_output']+1,
				'kode_sub_output' => $this->input->post('kode_sub_output'),
				'kode_output' => $this->input->post('kode_output'),
				'kode_kegiatan' => $this->input->post('kode_kegiatan'),
				'sub_output' => $this->input->post('sub_output'),
				'pagu' => $this->input->post('pagu')
			);
			$this->Main_model->insertData('tbl_sub_output',$data_insert1);
			// print_r($data_insert1);

			$this->Main_model->log_activity($this->session->userdata('id'),'Adding data',"Menambahkan data sub output (".$this->input->post('sub_output').")",$this->session->userdata('location'));
			$this->db->trans_complete();
			if($this->db->trans_status() === false){
				$this->session->set_flashdata('gagal','<div class="alert alert-warning"><button type="button" class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button><strong></i>Oops! </strong>data gagal ditambahkan.<br /></div>' );
				echo "<script>window.location='".base_url()."admin_side/tambah_data_sub_output/'</script>";
			}
			else{
				$this->session->set_flashdata('sukses','<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button><strong></i>Yeah! </strong>data telah berhasil ditambahkan.<br /></div>' );
				echo "<script>window.location='".base_url()."admin_side/sub_output/'</script>";
			}
		}else{
			$this->session->set_flashdata('gagal','<div class="alert alert-warning"><button type="button" class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button><strong></i>Oops! </strong>Kode sub_output telah digunakan.<br /></div>' );
			echo "<script>window.location='".base_url()."admin_side/tambah_data_sub_output/'</script>";
		}
	}
	public function edit_sub_output_data()
	{
		$data['parent'] = 'master';
		$data['child'] = 'master_sub_output';
		$data['grand_child'] = '';
		$data['data_utama'] = $this->Main_model->getSelectedData('tbl_sub_output a', 'a.*', array('md5(a.id_sub_output)'=>$this->uri->segment(3)))->row();
		$this->load->view('admin/template/header',$data);
		$this->load->view('admin/master/edit_sub_output_data',$data);
		$this->load->view('admin/template/footer');
	}
	public function update_sub_output_data(){
		$this->db->trans_start();
		$data_insert1 = array(
			'sub_output' => $this->input->post('sub_output'),
			'pagu' => $this->input->post('pagu')
		);
		$this->Main_model->updateData('tbl_sub_output',$data_insert1,array('md5(id_sub_output)'=>$this->input->post('id')));
		// print_r($data_insert1);

		$this->Main_model->log_activity($this->session->userdata('id'),'Updating data',"Mengubah data sub output (".$this->input->post('sub_output').")",$this->session->userdata('location'));
		$this->db->trans_complete();
		if($this->db->trans_status() === false){
			$this->session->set_flashdata('gagal','<div class="alert alert-warning"><button type="button" class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button><strong></i>Oops! </strong>data gagal diubah.<br /></div>' );
			echo "<script>window.location='".base_url()."admin_side/ubah_data_sub_output/".md5($this->input->post('id'))."'</script>";
		}
		else{
			$this->session->set_flashdata('sukses','<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button><strong></i>Yeah! </strong>data telah berhasil diubah.<br /></div>' );
			echo "<script>window.location='".base_url()."admin_side/sub_output/'</script>";
		}
	}
	public function delete_sub_output_data(){
		$this->db->trans_start();
		$track_id = '';
		$name = '';
		$get_data = $this->Main_model->getSelectedData('tbl_sub_output a', 'a.*',array('md5(a.id_sub_output)'=>$this->uri->segment(3)))->row();
		$track_id = $get_data->kode_sub_output;
		$name = $get_data->sub_output;
		$out = $get_data->kode_output;
		$keg = $get_data->kode_kegiatan;

		$this->Main_model->deleteData('tbl_sub_output',array('kode_sub_output'=>$track_id,'kode_output'=>$out,'kode_kegiatan'=>$keg));
		$this->Main_model->deleteData('tbl_komponen',array('kode_sub_output'=>$track_id,'kode_output'=>$out,'kode_kegiatan'=>$keg));
		$this->Main_model->deleteData('tbl_sub_komponen',array('kode_sub_output'=>$track_id,'kode_output'=>$out,'kode_kegiatan'=>$keg));
		$this->Main_model->deleteData('tbl_jenis_belanja',array('kode_sub_output'=>$track_id,'kode_output'=>$out,'kode_kegiatan'=>$keg));
		$this->Main_model->deleteData('tbl_belanja',array('kode_sub_output'=>$track_id,'kode_output'=>$out,'kode_kegiatan'=>$keg));

		$this->Main_model->log_activity($this->session->userdata('id'),"Deleting data","Menghapus data sub output (".$name.")",$this->session->userdata('location'));
		$this->db->trans_complete();
		if($this->db->trans_status() === false){
			$this->session->set_flashdata('gagal','<div class="alert alert-warning"><button type="button" class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button><strong></i>Oops! </strong>data gagal dihapus.<br /></div>' );
			echo "<script>window.location='".base_url()."admin_side/sub_output/'</script>";
		}
		else{
			$this->session->set_flashdata('sukses','<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button><strong></i>Yeah! </strong>data telah berhasil dihapus.<br /></div>' );
			echo "<script>window.location='".base_url()."admin_side/sub_output/'</script>";
		}
	}
	/* Komponen */
	public function komponen_data()
	{
		$data['parent'] = 'master';
		$data['child'] = 'master_komponen';
		$data['grand_child'] = '';
		$this->load->view('admin/template/header',$data);
		$this->load->view('admin/master/komponen_data',$data);
		$this->load->view('admin/template/footer');
	}
	public function json_komponen(){
		$get_data = $this->Main_model->getSelectedData('tbl_komponen a', 'a.*')->result();
		$data_tampil = array();
		$no = 1;
		foreach ($get_data as $key => $value) {
			$isi['number'] = $no++.'.';
			$isi['kode'] = $value->kode_komponen;
			$isi['sub_output'] = $value->kode_sub_output;
			$isi['output'] = $value->kode_output;
			$isi['kegiatan'] = $value->kode_kegiatan;
			$isi['komponen'] = $value->komponen;
			$isi['pagu'] = 'Rp '.number_format($value->pagu,2);
			$get_data_belanja = $this->Main_model->getSelectedData('tbl_belanja a', 'a.*', array('a.kode_komponen'=>$value->kode_komponen,'a.kode_sub_output'=>$value->kode_sub_output,'a.kode_output'=>$value->kode_output,'a.kode_kegiatan'=>$value->kode_kegiatan,'a.bulan'=>$this->Main_model->get_where_bulan()))->result();
			$realisasi = 0;
			foreach ($get_data_belanja as $key => $row) {
				$realisasi += $row->realisasi;
			}
			$isi['realisasi'] = 'Rp '.number_format($realisasi,2);
			$sisa = ($value->pagu)-$realisasi;
			$isi['sisa'] = 'Rp '.number_format($sisa,2);
			$return_on_click = "return confirm('Anda yakin?')";
			$isi['action'] =	'
			<a href="'.site_url('admin_side/ubah_data_komponen/'.md5($value->id_komponen)).'" class="btn btn-outline btn-circle btn-sm purple"><i class="fa fa-edit"></i> Ubah </a>
			<br><br>
			<a onclick="'.$return_on_click.'" href="'.site_url('admin_side/hapus_data_komponen/'.md5($value->id_komponen)).'" class="btn btn-outline btn-circle dark btn-sm black"><i class="fa fa-trash-o"></i> Hapus </a>
								';
			$data_tampil[] = $isi;
		}
		$results = array(
			"sEcho" => 1,
			"iTotalRecords" => count($data_tampil),
			"iTotalDisplayRecords" => count($data_tampil),
			"aaData"=>$data_tampil);
		echo json_encode($results);
	}
	public function add_komponen_data()
	{
		$data['parent'] = 'master';
		$data['child'] = 'master_komponen';
		$data['grand_child'] = '';
		$this->load->view('admin/template/header',$data);
		$this->load->view('admin/master/add_komponen_data',$data);
		$this->load->view('admin/template/footer');
	}
	public function save_komponen_data(){
		$cek_ = $this->Main_model->getSelectedData('tbl_komponen a', 'a.*',array('a.kode_komponen'=>$this->input->post('kode_komponen'),'a.kode_sub_output'=>$this->input->post('kode_sub_output'),'a.kode_output'=>$this->input->post('kode_output'),'a.kode_kegiatan'=>$this->input->post('kode_kegiatan')))->row();
		if($cek_==NULL){
			$this->db->trans_start();
			$get_id_komponen = $this->Main_model->getLastID('tbl_komponen','id_komponen');

			$data_insert1 = array(
				'id_komponen' => $get_id_komponen['id_komponen']+1,
				'kode_komponen' => $this->input->post('kode_komponen'),
				'kode_sub_output' => $this->input->post('kode_sub_output'),
				'kode_output' => $this->input->post('kode_output'),
				'kode_kegiatan' => $this->input->post('kode_kegiatan'),
				'komponen' => $this->input->post('komponen'),
				'pagu' => $this->input->post('pagu')
			);
			$this->Main_model->insertData('tbl_komponen',$data_insert1);
			// print_r($data_insert1);

			$this->Main_model->log_activity($this->session->userdata('id'),'Adding data',"Menambahkan data komponen (".$this->input->post('komponen').")",$this->session->userdata('location'));
			$this->db->trans_complete();
			if($this->db->trans_status() === false){
				$this->session->set_flashdata('gagal','<div class="alert alert-warning"><button type="button" class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button><strong></i>Oops! </strong>data gagal ditambahkan.<br /></div>' );
				echo "<script>window.location='".base_url()."admin_side/tambah_data_komponen/'</script>";
			}
			else{
				$this->session->set_flashdata('sukses','<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button><strong></i>Yeah! </strong>data telah berhasil ditambahkan.<br /></div>' );
				echo "<script>window.location='".base_url()."admin_side/komponen/'</script>";
			}
		}else{
			$this->session->set_flashdata('gagal','<div class="alert alert-warning"><button type="button" class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button><strong></i>Oops! </strong>Kode komponen telah digunakan.<br /></div>' );
			echo "<script>window.location='".base_url()."admin_side/tambah_data_komponen/'</script>";
		}
	}
	public function edit_komponen_data()
	{
		$data['parent'] = 'master';
		$data['child'] = 'master_komponen';
		$data['grand_child'] = '';
		$data['data_utama'] = $this->Main_model->getSelectedData('tbl_komponen a', 'a.*', array('md5(a.id_komponen)'=>$this->uri->segment(3)))->row();
		$this->load->view('admin/template/header',$data);
		$this->load->view('admin/master/edit_komponen_data',$data);
		$this->load->view('admin/template/footer');
	}
	public function update_komponen_data(){
		$this->db->trans_start();
		$data_insert1 = array(
			'komponen' => $this->input->post('komponen'),
			'pagu' => $this->input->post('pagu')
		);
		$this->Main_model->updateData('tbl_komponen',$data_insert1,array('md5(id_komponen)'=>$this->input->post('id')));
		// print_r($data_insert1);

		$this->Main_model->log_activity($this->session->userdata('id'),'Updating data',"Mengubah data komponen (".$this->input->post('komponen').")",$this->session->userdata('location'));
		$this->db->trans_complete();
		if($this->db->trans_status() === false){
			$this->session->set_flashdata('gagal','<div class="alert alert-warning"><button type="button" class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button><strong></i>Oops! </strong>data gagal diubah.<br /></div>' );
			echo "<script>window.location='".base_url()."admin_side/ubah_data_komponen/".md5($this->input->post('id'))."'</script>";
		}
		else{
			$this->session->set_flashdata('sukses','<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button><strong></i>Yeah! </strong>data telah berhasil diubah.<br /></div>' );
			echo "<script>window.location='".base_url()."admin_side/komponen/'</script>";
		}
	}
	public function delete_komponen_data(){
		$this->db->trans_start();
		$track_id = '';
		$name = '';
		$get_data = $this->Main_model->getSelectedData('tbl_komponen a', 'a.*',array('md5(a.id_komponen)'=>$this->uri->segment(3)))->row();
		$track_id = $get_data->kode_komponen;
		$kode_sub_output = $get_data->kode_sub_output;
		$kode_output = $get_data->kode_output;
		$kode_kegiatan = $get_data->kode_kegiatan;
		$name = $get_data->komponen;

		$this->Main_model->deleteData('tbl_komponen',array('kode_komponen'=>$track_id,'kode_sub_output'=>$kode_sub_output,'kode_output'=>$kode_output,'kode_kegiatan'=>$kode_kegiatan));
		$this->Main_model->deleteData('tbl_sub_komponen',array('kode_komponen'=>$track_id,'kode_sub_output'=>$kode_sub_output,'kode_output'=>$kode_output,'kode_kegiatan'=>$kode_kegiatan));
		$this->Main_model->deleteData('tbl_jenis_belanja',array('kode_komponen'=>$track_id,'kode_sub_output'=>$kode_sub_output,'kode_output'=>$kode_output,'kode_kegiatan'=>$kode_kegiatan));
		$this->Main_model->deleteData('tbl_belanja',array('kode_komponen'=>$track_id,'kode_sub_output'=>$kode_sub_output,'kode_output'=>$kode_output,'kode_kegiatan'=>$kode_kegiatan));

		$this->Main_model->log_activity($this->session->userdata('id'),"Deleting data","Menghapus data komponen (".$name.")",$this->session->userdata('location'));
		$this->db->trans_complete();
		if($this->db->trans_status() === false){
			$this->session->set_flashdata('gagal','<div class="alert alert-warning"><button type="button" class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button><strong></i>Oops! </strong>data gagal dihapus.<br /></div>' );
			echo "<script>window.location='".base_url()."admin_side/komponen/'</script>";
		}
		else{
			$this->session->set_flashdata('sukses','<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button><strong></i>Yeah! </strong>data telah berhasil dihapus.<br /></div>' );
			echo "<script>window.location='".base_url()."admin_side/komponen/'</script>";
		}
	}
	/* Sub Komponen */
	public function sub_komponen_data()
	{
		$data['parent'] = 'master';
		$data['child'] = 'master_sub_komponen';
		$data['grand_child'] = '';
		$this->load->view('admin/template/header',$data);
		$this->load->view('admin/master/sub_komponen_data',$data);
		$this->load->view('admin/template/footer');
	}
	public function json_sub_komponen(){
		$get_data = $this->Main_model->getSelectedData('tbl_sub_komponen a', 'a.*')->result();
		$data_tampil = array();
		$no = 1;
		foreach ($get_data as $key => $value) {
			$isi['number'] = $no++.'.';
			$isi['kode'] = $value->kode_sub_komponen;
			$isi['komponen'] = $value->kode_komponen;
			$isi['sub_output'] = $value->kode_sub_output;
			$isi['output'] = $value->kode_output;
			$isi['kegiatan'] = $value->kode_kegiatan;
			$isi['sub_komponen'] = $value->sub_komponen;
			$isi['pagu'] = 'Rp '.number_format($value->pagu,2);
			$get_data_belanja = $this->Main_model->getSelectedData('tbl_belanja a', 'a.*', array('a.kode_sub_komponen'=>$value->kode_sub_komponen,'a.kode_komponen'=>$value->kode_komponen,'a.kode_sub_output'=>$value->kode_sub_output,'a.kode_output'=>$value->kode_output,'a.kode_kegiatan'=>$value->kode_kegiatan,'a.bulan'=>$this->Main_model->get_where_bulan()))->result();
			$realisasi = 0;
			foreach ($get_data_belanja as $key => $row) {
				$realisasi += $row->realisasi;
			}
			$isi['realisasi'] = 'Rp '.number_format($realisasi,2);
			$sisa = ($value->pagu)-$realisasi;
			$isi['sisa'] = 'Rp '.number_format($sisa,2);
			$return_on_click = "return confirm('Anda yakin?')";
			$isi['action'] =	'
			<a href="'.site_url('admin_side/ubah_data_sub_komponen/'.md5($value->id_sub_komponen)).'" class="btn btn-outline btn-circle btn-sm purple"><i class="fa fa-edit"></i> Ubah </a>
			<br><br>
			<a onclick="'.$return_on_click.'" href="'.site_url('admin_side/hapus_data_sub_komponen/'.md5($value->id_sub_komponen)).'" class="btn btn-outline btn-circle dark btn-sm black"><i class="fa fa-trash-o"></i> Hapus </a>
								';
			$data_tampil[] = $isi;
		}
		$results = array(
			"sEcho" => 1,
			"iTotalRecords" => count($data_tampil),
			"iTotalDisplayRecords" => count($data_tampil),
			"aaData"=>$data_tampil);
		echo json_encode($results);
	}
	public function add_sub_komponen_data()
	{
		$data['parent'] = 'master';
		$data['child'] = 'master_sub_komponen';
		$data['grand_child'] = '';
		$this->load->view('admin/template/header',$data);
		$this->load->view('admin/master/add_sub_komponen_data',$data);
		$this->load->view('admin/template/footer');
	}
	public function save_sub_komponen_data(){
		$cek_ = $this->Main_model->getSelectedData('tbl_sub_komponen a', 'a.*',array('a.kode_sub_komponen'=>$this->input->post('kode_sub_komponen'),'a.kode_komponen'=>$this->input->post('kode_komponen'),'a.kode_sub_output'=>$this->input->post('kode_sub_output'),'a.kode_output'=>$this->input->post('kode_output'),'a.kode_kegiatan'=>$this->input->post('kode_kegiatan')))->row();
		if($cek_==NULL){
			$this->db->trans_start();
			$get_id_sub_komponen = $this->Main_model->getLastID('tbl_sub_komponen','id_sub_komponen');

			$data_insert1 = array(
				'id_sub_komponen' => $get_id_sub_komponen['id_sub_komponen']+1,
				'kode_sub_komponen' => $this->input->post('kode_sub_komponen'),
				'kode_komponen' => $this->input->post('kode_komponen'),
				'kode_sub_output' => $this->input->post('kode_sub_output'),
				'kode_output' => $this->input->post('kode_output'),
				'kode_kegiatan' => $this->input->post('kode_kegiatan'),
				'sub_komponen' => $this->input->post('sub_komponen'),
				'pagu' => $this->input->post('pagu')
			);
			$this->Main_model->insertData('tbl_sub_komponen',$data_insert1);
			// print_r($data_insert1);

			$this->Main_model->log_activity($this->session->userdata('id'),'Adding data',"Menambahkan data sub komponen (".$this->input->post('sub_komponen').")",$this->session->userdata('location'));
			$this->db->trans_complete();
			if($this->db->trans_status() === false){
				$this->session->set_flashdata('gagal','<div class="alert alert-warning"><button type="button" class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button><strong></i>Oops! </strong>data gagal ditambahkan.<br /></div>' );
				echo "<script>window.location='".base_url()."admin_side/tambah_data_sub_komponen/'</script>";
			}
			else{
				$this->session->set_flashdata('sukses','<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button><strong></i>Yeah! </strong>data telah berhasil ditambahkan.<br /></div>' );
				echo "<script>window.location='".base_url()."admin_side/sub_komponen/'</script>";
			}
		}else{
			$this->session->set_flashdata('gagal','<div class="alert alert-warning"><button type="button" class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button><strong></i>Oops! </strong>Kode sub_komponen telah digunakan.<br /></div>' );
			echo "<script>window.location='".base_url()."admin_side/tambah_data_sub_komponen/'</script>";
		}
	}
	public function edit_sub_komponen_data()
	{
		$data['parent'] = 'master';
		$data['child'] = 'master_sub_komponen';
		$data['grand_child'] = '';
		$data['data_utama'] = $this->Main_model->getSelectedData('tbl_sub_komponen a', 'a.*', array('md5(a.id_sub_komponen)'=>$this->uri->segment(3)))->row();
		$this->load->view('admin/template/header',$data);
		$this->load->view('admin/master/edit_sub_komponen_data',$data);
		$this->load->view('admin/template/footer');
	}
	public function update_sub_komponen_data(){
		$this->db->trans_start();
		$data_insert1 = array(
			'sub_komponen' => $this->input->post('sub_komponen'),
			'pagu' => $this->input->post('pagu')
		);
		$this->Main_model->updateData('tbl_sub_komponen',$data_insert1,array('md5(id_sub_komponen)'=>$this->input->post('id')));
		// print_r($data_insert1);

		$this->Main_model->log_activity($this->session->userdata('id'),'Updating data',"Mengubah data sub komponen (".$this->input->post('sub_komponen').")",$this->session->userdata('location'));
		$this->db->trans_complete();
		if($this->db->trans_status() === false){
			$this->session->set_flashdata('gagal','<div class="alert alert-warning"><button type="button" class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button><strong></i>Oops! </strong>data gagal diubah.<br /></div>' );
			echo "<script>window.location='".base_url()."admin_side/ubah_data_sub_komponen/".md5($this->input->post('id'))."'</script>";
		}
		else{
			$this->session->set_flashdata('sukses','<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button><strong></i>Yeah! </strong>data telah berhasil diubah.<br /></div>' );
			echo "<script>window.location='".base_url()."admin_side/sub_komponen/'</script>";
		}
	}
	public function delete_sub_komponen_data(){
		$this->db->trans_start();
		$track_id = '';
		$name = '';
		$get_data = $this->Main_model->getSelectedData('tbl_sub_komponen a', 'a.*',array('md5(a.id_sub_komponen)'=>$this->uri->segment(3)))->row();
		$track_id = $get_data->kode_sub_komponen;
		$kode_komponen = $get_data->kode_komponen;
		$kode_sub_output = $get_data->kode_sub_output;
		$kode_output = $get_data->kode_output;
		$kode_kegiatan = $get_data->kode_kegiatan;
		$name = $get_data->sub_komponen;

		$this->Main_model->deleteData('tbl_sub_komponen',array('kode_sub_komponen'=>$track_id,'kode_komponen'=>$kode_komponen,'kode_sub_output'=>$kode_sub_output,'kode_output'=>$kode_output,'kode_kegiatan'=>$kode_kegiatan));
		$this->Main_model->deleteData('tbl_jenis_belanja',array('kode_sub_komponen'=>$track_id,'kode_komponen'=>$kode_komponen,'kode_sub_output'=>$kode_sub_output,'kode_output'=>$kode_output,'kode_kegiatan'=>$kode_kegiatan));
		$this->Main_model->deleteData('tbl_belanja',array('kode_sub_komponen'=>$track_id,'kode_komponen'=>$kode_komponen,'kode_sub_output'=>$kode_sub_output,'kode_output'=>$kode_output,'kode_kegiatan'=>$kode_kegiatan));

		$this->Main_model->log_activity($this->session->userdata('id'),"Deleting data","Menghapus data sub komponen (".$name.")",$this->session->userdata('location'));
		$this->db->trans_complete();
		if($this->db->trans_status() === false){
			$this->session->set_flashdata('gagal','<div class="alert alert-warning"><button type="button" class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button><strong></i>Oops! </strong>data gagal dihapus.<br /></div>' );
			echo "<script>window.location='".base_url()."admin_side/sub_komponen/'</script>";
		}
		else{
			$this->session->set_flashdata('sukses','<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button><strong></i>Yeah! </strong>data telah berhasil dihapus.<br /></div>' );
			echo "<script>window.location='".base_url()."admin_side/sub_komponen/'</script>";
		}
	}
	/* Administrator */
	public function administrator_data()
	{
		$data['parent'] = 'master';
		$data['child'] = 'master_admin';
		$data['grand_child'] = '';
		$this->load->view('admin/template/header',$data);
		$this->load->view('admin/master/administrator_data',$data);
		$this->load->view('admin/template/footer');
	}
	public function json_admin(){
		$get_data1 = $this->Main_model->getSelectedData('user a', 'a.*',array("a.is_active" => '1','a.deleted' => '0','b.role_id' => '1'),'','','','',array(
			'table' => 'user_to_role b',
			'on' => 'a.id=b.user_id',
			'pos' => 'LEFT'
		))->result();
		$data_tampil = array();
		$no = 1;
		foreach ($get_data1 as $key => $value) {
			$return_on_click = "return confirm('Anda yakin?')";
			if($value->id==$this->session->userdata('id')){
				echo'';
			}else{
				$isi['number'] = $no++.'.';
				$isi['nama'] = $value->fullname;
				$isi['username'] = $value->username;
				$isi['role'] = 'Admin';
				$isi['total_login'] = number_format($value->total_login,0).'x';
				if($value->last_activity==NULL){
					$isi['last_activity'] = '-';
				}else{
					$isi['last_activity'] = $this->Main_model->convert_datetime($value->last_activity);
				}
				$isi['action'] =	'
									<div class="btn-group" style="text-align: center;">
										<button class="btn btn-xs green dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false"> Aksi
											<i class="fa fa-angle-down"></i>
										</button>
										<ul class="dropdown-menu" role="menu">
											<li>
												<a href="'.site_url('admin_side/ubah_data_admin/'.md5($value->id)).'">
													<i class="icon-wrench"></i> Ubah Data </a>
											</li>
											<li>
												<a onclick="'.$return_on_click.'" href="'.site_url('admin_side/hapus_data_admin/'.md5($value->id)).'">
													<i class="icon-trash"></i> Hapus Data </a>
											</li>
											<li class="divider"> </li>
											<li>
												<a href="'.site_url('admin_side/atur_ulang_kata_sandi_admin/'.md5($value->id)).'">
													<i class="fa fa-refresh"></i> Atur Ulang Sandi
												</a>
											</li>
										</ul>
									</div>
									';
				$data_tampil[] = $isi;
			}
		}
		$get_data2 = $this->Main_model->getSelectedData('user a', 'a.*',array("a.is_active" => '1','a.deleted' => '0','b.role_id' => '2'),'','','','',array(
			'table' => 'user_to_role b',
			'on' => 'a.id=b.user_id',
			'pos' => 'LEFT'
		))->result();
		foreach ($get_data2 as $key => $value) {
			$isi['number'] = $no++.'.';
			$isi['nama'] = $value->fullname;
			$isi['username'] = $value->username;
			$isi['role'] = 'Admin KPA';
			$isi['total_login'] = number_format($value->total_login,0).'x';
			if($value->last_activity==NULL){
				$isi['last_activity'] = '-';
			}else{
				$isi['last_activity'] = $this->Main_model->convert_datetime($value->last_activity);
			}
			$return_on_click = "return confirm('Anda yakin?')";
			$isi['action'] =	'
								<div class="btn-group" style="text-align: center;">
									<button class="btn btn-xs green dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false"> Aksi
										<i class="fa fa-angle-down"></i>
									</button>
									<ul class="dropdown-menu" role="menu">
										<li>
											<a href="'.site_url('admin_side/ubah_data_admin/'.md5($value->id)).'">
												<i class="icon-wrench"></i> Ubah Data </a>
										</li>
										<li>
											<a onclick="'.$return_on_click.'" href="'.site_url('admin_side/hapus_data_admin/'.md5($value->id)).'">
												<i class="icon-trash"></i> Hapus Data </a>
										</li>
										<li class="divider"> </li>
										<li>
											<a href="'.site_url('admin_side/atur_ulang_kata_sandi_admin/'.md5($value->id)).'">
												<i class="fa fa-refresh"></i> Atur Ulang Sandi
											</a>
										</li>
									</ul>
								</div>
								';
			$data_tampil[] = $isi;
		}
		$get_data3 = $this->Main_model->getSelectedData('user a', 'a.*',array("a.is_active" => '1','a.deleted' => '0','b.role_id' => '3'),'','','','',array(
			'table' => 'user_to_role b',
			'on' => 'a.id=b.user_id',
			'pos' => 'LEFT'
		))->result();
		foreach ($get_data3 as $key => $value) {
			$isi['number'] = $no++.'.';
			$isi['nama'] = $value->fullname;
			$isi['username'] = $value->username;
			$isi['role'] = 'Admin PPK';
			$isi['total_login'] = number_format($value->total_login,0).'x';
			if($value->last_activity==NULL){
				$isi['last_activity'] = '-';
			}else{
				$isi['last_activity'] = $this->Main_model->convert_datetime($value->last_activity);
			}
			$return_on_click = "return confirm('Anda yakin?')";
			$isi['action'] =	'
								<div class="btn-group" style="text-align: center;">
									<button class="btn btn-xs green dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false"> Aksi
										<i class="fa fa-angle-down"></i>
									</button>
									<ul class="dropdown-menu" role="menu">
										<li>
											<a href="'.site_url('admin_side/ubah_data_admin/'.md5($value->id)).'">
												<i class="icon-wrench"></i> Ubah Data </a>
										</li>
										<li>
											<a onclick="'.$return_on_click.'" href="'.site_url('admin_side/hapus_data_admin/'.md5($value->id)).'">
												<i class="icon-trash"></i> Hapus Data </a>
										</li>
										<li class="divider"> </li>
										<li>
											<a href="'.site_url('admin_side/atur_ulang_kata_sandi_admin/'.md5($value->id)).'">
												<i class="fa fa-refresh"></i> Atur Ulang Sandi
											</a>
										</li>
									</ul>
								</div>
								';
			$data_tampil[] = $isi;
		}
		$results = array(
			"sEcho" => 1,
			"iTotalRecords" => count($data_tampil),
			"iTotalDisplayRecords" => count($data_tampil),
			"aaData"=>$data_tampil);
		echo json_encode($results);
	}
	public function add_administrator_data()
	{
		$data['parent'] = 'master';
		$data['child'] = 'master_admin';
		$data['grand_child'] = '';
		$this->load->view('admin/template/header',$data);
		$this->load->view('admin/master/add_administrator_data',$data);
		$this->load->view('admin/template/footer');
	}
	public function save_administrator_data(){
		$cek_ = $this->Main_model->getSelectedData('user a', 'a.*',array('a.username'=>$this->input->post('un')))->row();
		if($cek_==NULL){
			$this->db->trans_start();
			$get_user_id = $this->Main_model->getLastID('user','id');

			$data_insert1 = array(
				'id' => $get_user_id['id']+1,
				'username' => $this->input->post('un'),
				'pass' => $this->input->post('ps'),
				'fullname' => $this->input->post('nama'),
				'is_active' => '1',
				'created_by' => $this->session->userdata('id'),
				'created_at' => date('Y-m-d H:i:s')
			);
			$this->Main_model->insertData('user',$data_insert1);
			// print_r($data_insert1);

			$data_insert2 = array(
				'user_id' => $get_user_id['id']+1,
				'role_id' => $this->input->post('role_id')
			);
			$this->Main_model->insertData('user_to_role',$data_insert2);
			// print_r($data_insert2);

			$this->Main_model->log_activity($this->session->userdata('id'),'Adding data',"Menambahkan data Admin (".$this->input->post('nama').")",$this->session->userdata('location'));
			$this->db->trans_complete();
			if($this->db->trans_status() === false){
				$this->session->set_flashdata('gagal','<div class="alert alert-warning"><button type="button" class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button><strong></i>Oops! </strong>data gagal ditambahkan.<br /></div>' );
				echo "<script>window.location='".base_url()."admin_side/tambah_data_admin/'</script>";
			}
			else{
				$this->session->set_flashdata('sukses','<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button><strong></i>Yeah! </strong>data telah berhasil ditambahkan.<br /></div>' );
				echo "<script>window.location='".base_url()."admin_side/admin/'</script>";
			}
		}else{
			$this->session->set_flashdata('gagal','<div class="alert alert-warning"><button type="button" class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button><strong></i>Oops! </strong>Username telah digunakan.<br /></div>' );
			echo "<script>window.location='".base_url()."admin_side/tambah_data_admin/'</script>";
		}
	}
	public function edit_administrator_data()
	{
		$data['parent'] = 'master';
		$data['child'] = 'master_admin';
		$data['grand_child'] = '';
		$data['data_utama'] = $this->Main_model->getSelectedData('user a', 'a.*', array('md5(a.id)'=>$this->uri->segment(3),'a.deleted'=>'0'))->row();
		$this->load->view('admin/template/header',$data);
		$this->load->view('admin/master/edit_administrator_data',$data);
		$this->load->view('admin/template/footer');
	}
	public function update_administrator_data(){
		if($this->input->post('ps')!=NULL){
			$this->db->trans_start();

			$data_insert1 = array(
				'pass' => $this->input->post('ps'),
				'fullname' => $this->input->post('nama')
			);
			$this->Main_model->updateData('user',$data_insert1,array('md5(id)'=>$this->input->post('user_id')));
			// print_r($data_insert1);

			$this->Main_model->log_activity($this->session->userdata('id'),'Updating data',"Mengubah data Admin (".$this->input->post('nama').")",$this->session->userdata('location'));
			$this->db->trans_complete();
			if($this->db->trans_status() === false){
				$this->session->set_flashdata('gagal','<div class="alert alert-warning"><button type="button" class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button><strong></i>Oops! </strong>data gagal diubah.<br /></div>' );
				echo "<script>window.location='".base_url()."admin_side/ubah_data_admin/".$this->input->post('user_id')."'</script>";
			}
			else{
				$this->session->set_flashdata('sukses','<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button><strong></i>Yeah! </strong>data telah berhasil diubah.<br /></div>' );
				echo "<script>window.location='".base_url()."admin_side/admin/'</script>";
			}
		}else{
			$this->db->trans_start();

			$data_insert1 = array(
				'fullname' => $this->input->post('nama')
			);
			$this->Main_model->updateData('user',$data_insert1,array('md5(id)'=>$this->input->post('user_id')));
			// print_r($data_insert1);

			$this->Main_model->log_activity($this->session->userdata('id'),'Updating data',"Mengubah data Admin (".$this->input->post('nama').")",$this->session->userdata('location'));
			$this->db->trans_complete();
			if($this->db->trans_status() === false){
				$this->session->set_flashdata('gagal','<div class="alert alert-warning"><button type="button" class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button><strong></i>Oops! </strong>data gagal diubah.<br /></div>' );
				echo "<script>window.location='".base_url()."admin_side/ubah_data_admin/".$this->input->post('user_id')."'</script>";
			}
			else{
				$this->session->set_flashdata('sukses','<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button><strong></i>Yeah! </strong>data telah berhasil diubah.<br /></div>' );
				echo "<script>window.location='".base_url()."admin_side/admin/'</script>";
			}
		}
	}
	public function reset_password_administrator_account(){
		$this->db->trans_start();
		$user_id = '';
		$name = '';
		$get_data = $this->Main_model->getSelectedData('user a', 'a.*',array('md5(a.id)'=>$this->uri->segment(3)))->row();
		$user_id = $get_data->id;
		$name = $get_data->fullname;

		$this->Main_model->updateData('user',array('pass'=>'1234'),array('id'=>$user_id));

		$this->Main_model->log_activity($this->session->userdata('id'),"Update admin's data","Reset password admin's account (".$name.")",$this->session->userdata('location'));
		$this->db->trans_complete();
		if($this->db->trans_status() === false){
			$this->session->set_flashdata('gagal','<div class="alert alert-warning"><button type="button" class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button><strong></i>Oops! </strong>data gagal diubah.<br /></div>' );
			echo "<script>window.location='".base_url()."admin_side/admin/'</script>";
		}
		else{
			$this->session->set_flashdata('sukses','<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button><strong></i>Yeah! </strong>data telah berhasil diubah.<br /></div>' );
			echo "<script>window.location='".base_url()."admin_side/admin/'</script>";
		}
	}
	public function delete_administrator_data(){
		$this->db->trans_start();
		$user_id = '';
		$name = '';
		$get_data = $this->Main_model->getSelectedData('user a', 'a.*',array('md5(a.id)'=>$this->uri->segment(3)))->row();
		$user_id = $get_data->id;
		$name = $get_data->fullname;

		$this->Main_model->deleteData('user_to_role',array('user_id'=>$user_id));
		$this->Main_model->deleteData('user',array('id'=>$user_id));

		$this->Main_model->log_activity($this->session->userdata('id'),"Deleting admin's data","Delete admin's data (".$name.")",$this->session->userdata('location'));
		$this->db->trans_complete();
		if($this->db->trans_status() === false){
			$this->session->set_flashdata('gagal','<div class="alert alert-warning"><button type="button" class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button><strong></i>Oops! </strong>data gagal dihapus.<br /></div>' );
			echo "<script>window.location='".base_url()."admin_side/admin/'</script>";
		}
		else{
			$this->session->set_flashdata('sukses','<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button><strong></i>Yeah! </strong>data telah berhasil dihapus.<br /></div>' );
			echo "<script>window.location='".base_url()."admin_side/admin/'</script>";
		}
	}
	/* Departemen */
	public function departemen_data()
	{
		$data['parent'] = 'master';
		$data['child'] = 'master_departemen';
		$data['grand_child'] = '';
		$this->load->view('admin/template/header',$data);
		$this->load->view('admin/master/departemen_data',$data);
		$this->load->view('admin/template/footer');
	}
	public function json_departemen(){
		$get_data1 = $this->Main_model->getSelectedData('departemen a', 'a.*')->result();
		$data_tampil = array();
		$no = 1;
		foreach ($get_data1 as $key => $value) {
			$isi['number'] = $no++.'.';
			$isi['kode'] = $value->kode_departemen ;
			$isi['nama'] = $value->departemen;
			$isi['anggaran'] = $value->kode_anggaran;
			$get_pagu = $this->Main_model->getSelectedData('tbl_sub_komponen a', 'a.*')->result();
			$pagu = 0;
			$realisasi = 0;
			foreach ($get_pagu as $key => $row) {
				$cek_string = substr($row->kode_sub_komponen,0,1);
				if($cek_string==$value->kode_anggaran){
					$pagu += $row->pagu;
					$get_data_belanja = $this->Main_model->getSelectedData('tbl_belanja a', 'a.*', array('a.kode_sub_komponen'=>$row->kode_sub_komponen,'a.kode_komponen'=>$row->kode_komponen,'a.kode_sub_output'=>$row->kode_sub_output,'a.kode_output'=>$row->kode_output,'a.kode_kegiatan'=>$row->kode_kegiatan,'a.bulan'=>$this->Main_model->get_where_bulan()))->result();
					foreach ($get_data_belanja as $key => $g) {
						$realisasi += $g->realisasi;
					}
				}else{
					echo'';
				}
			}
			$isi['pagu'] = 'Rp '.number_format($pagu,2);
			$isi['realisasi'] = 'Rp '.number_format($realisasi,2);
			$sisa = $pagu-$realisasi;
			$isi['sisa'] = 'Rp '.number_format($sisa,2);
			$return_on_click = "return confirm('Anda yakin?')";
			$isi['action'] =	'
								<div class="btn-group" style="text-align: center;">
									<button class="btn btn-xs green dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false"> Aksi
										<i class="fa fa-angle-down"></i>
									</button>
									<ul class="dropdown-menu" role="menu">
										<li>
											<a href="'.site_url('admin_side/ubah_data_departemen/'.md5($value->id_departemen)).'">
												<i class="icon-wrench"></i> Ubah Data </a>
										</li>
										<li>
											<a onclick="'.$return_on_click.'" href="'.site_url('admin_side/hapus_data_departemen/'.md5($value->id_departemen)).'">
												<i class="icon-trash"></i> Hapus Data </a>
										</li>
									</ul>
								</div>
								';
			$data_tampil[] = $isi;
		}
		$results = array(
			"sEcho" => 1,
			"iTotalRecords" => count($data_tampil),
			"iTotalDisplayRecords" => count($data_tampil),
			"aaData"=>$data_tampil);
		echo json_encode($results);
	}
	public function add_departemen_data()
	{
		$data['parent'] = 'master';
		$data['child'] = 'master_departemen';
		$data['grand_child'] = '';
		$this->load->view('admin/template/header',$data);
		$this->load->view('admin/master/add_departemen_data',$data);
		$this->load->view('admin/template/footer');
	}
	public function save_departemen_data(){
		$this->db->trans_start();
		$get_last = $this->Main_model->getLastID('departemen','id_departemen');

		$data_insert1 = array(
			'id_departemen' => $get_last['id_departemen']+1,
			'kode_departemen' => $this->input->post('kode_departemen'),
			'departemen' => $this->input->post('departemen'),
			'kode_anggaran' => $this->input->post('kode_anggaran')
		);
		$this->Main_model->insertData('departemen',$data_insert1);
		// print_r($data_insert1);

		$this->Main_model->log_activity($this->session->userdata('id'),'Adding data',"Menambahkan data Departemen (".$this->input->post('departemen').")",$this->session->userdata('location'));
		$this->db->trans_complete();
		if($this->db->trans_status() === false){
			$this->session->set_flashdata('gagal','<div class="alert alert-warning"><button type="button" class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button><strong></i>Oops! </strong>data gagal ditambahkan.<br /></div>' );
			echo "<script>window.location='".base_url()."admin_side/tambah_data_departemen/'</script>";
		}
		else{
			$this->session->set_flashdata('sukses','<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button><strong></i>Yeah! </strong>data telah berhasil ditambahkan.<br /></div>' );
			echo "<script>window.location='".base_url()."admin_side/departemen/'</script>";
		}
	}
	public function edit_departemen_data()
	{
		$data['parent'] = 'master';
		$data['child'] = 'master_departemen';
		$data['grand_child'] = '';
		$data['data_utama'] = $this->Main_model->getSelectedData('departemen a', 'a.*', array('md5(a.id_departemen)'=>$this->uri->segment(3)))->row();
		$this->load->view('admin/template/header',$data);
		$this->load->view('admin/master/edit_departemen_data',$data);
		$this->load->view('admin/template/footer');
	}
	public function update_departemen_data(){
		$this->db->trans_start();

		$data_insert1 = array(
			'kode_departemen' => $this->input->post('kode_departemen'),
			'departemen' => $this->input->post('departemen'),
			'kode_anggaran' => $this->input->post('kode_anggaran')
		);
		$this->Main_model->updateData('departemen',$data_insert1,array('md5(id_departemen)'=>$this->input->post('id')));
		// print_r($data_insert1);

		$this->Main_model->log_activity($this->session->userdata('id'),'Updating data',"Mengubah data Departemen (".$this->input->post('departemen').")",$this->session->userdata('location'));
		$this->db->trans_complete();
		if($this->db->trans_status() === false){
			$this->session->set_flashdata('gagal','<div class="alert alert-warning"><button type="button" class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button><strong></i>Oops! </strong>data gagal diubah.<br /></div>' );
			echo "<script>window.location='".base_url()."admin_side/ubah_data_departemen/".$this->input->post('id')."'</script>";
		}
		else{
			$this->session->set_flashdata('sukses','<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button><strong></i>Yeah! </strong>data telah berhasil diubah.<br /></div>' );
			echo "<script>window.location='".base_url()."admin_side/departemen/'</script>";
		}
	}
	public function delete_departemen_data(){
		$this->db->trans_start();
		$id_departemen = '';
		$name = '';
		$get_data = $this->Main_model->getSelectedData('departemen a', 'a.*',array('md5(a.id_departemen)'=>$this->uri->segment(3)))->row();
		$id_departemen = $get_data->id_departemen;
		$name = $get_data->departemen;

		$this->Main_model->deleteData('departemen',array('id_departemen'=>$id_departemen));

		$this->Main_model->log_activity($this->session->userdata('id'),"Deleting departemen's data","Delete departemen's data (".$name.")",$this->session->userdata('location'));
		$this->db->trans_complete();
		if($this->db->trans_status() === false){
			$this->session->set_flashdata('gagal','<div class="alert alert-warning"><button type="button" class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button><strong></i>Oops! </strong>data gagal dihapus.<br /></div>' );
			echo "<script>window.location='".base_url()."admin_side/departemen/'</script>";
		}
		else{
			$this->session->set_flashdata('sukses','<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button><strong></i>Yeah! </strong>data telah berhasil dihapus.<br /></div>' );
			echo "<script>window.location='".base_url()."admin_side/departemen/'</script>";
		}
	}
	/* Anggaran */
	public function cek_ang(){
		$get_data = $this->Main_model->getSelectedData('kegiatan a', 'a.*')->result();
		foreach ($get_data as $key => $value) {
			// $result2 = substr($value->kegiatan,15);
			// echo $result2.'<br>';
			// $pecah = explode(' ',$result2,2);
			// echo $pecah[1].'<br>';
			// $kalimatbaru = str_replace(' ', '', $result2);
			// $result = preg_replace("/[^0-9]/", "", $value->pagu);
			// $tot = strlen($result);
			// $yg = $tot-2;
			// $result2 = substr($result,0,$yg);
			$ambil_kode = substr($value->kode_kegiatan,0,1);
			$get_data2 = $this->Main_model->getSelectedData('departemen a', 'a.*', array('a.kode_anggaran'=>$ambil_kode))->row();
			$this->Main_model->updateData('kegiatan',array('id_departemen'=>$get_data2->id_departemen),array('id_kegiatan'=>$value->id_kegiatan));
		}
	}
	public function anggaran_data()
	{
		$data['parent'] = 'master';
		$data['child'] = 'master_anggaran';
		$data['grand_child'] = '';
		$this->load->view('admin/template/header',$data);
		$this->load->view('admin/master/anggaran_data',$data);
		$this->load->view('admin/template/footer');
	}
	public function json_anggaran(){
		$get_data = $this->Main_model->getSelectedData('kegiatan a', 'a.*,b.kode_departemen', '', '', '', '', '', array(
			'table' => 'departemen b',
			'on' => 'a.id_departemen=b.id_departemen',
			'pos' => 'LEFT'
		))->result();
		$data_tampil = array();
		$no = 1;
		foreach ($get_data as $key => $value) {
			$isi['no'] = $no++.'.';
			$isi['kode'] = $value->kode_kegiatan;
			$isi['nama'] = $value->kegiatan;
			$isi['pagu'] = 'Rp '.number_format($value->pagu,2);
			$isi['real'] = $value->kode_departemen;
			$return_on_click = "return confirm('Anda yakin?')";
			$isi['action'] =	'
								<div class="btn-group" style="text-align: center;">
									<button class="btn btn-xs green dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false"> Aksi
										<i class="fa fa-angle-down"></i>
									</button>
									<ul class="dropdown-menu" role="menu">
										<li>
											<a href="'.site_url('admin_side/detail_data_anggaran/'.md5($value->id_kegiatan)).'">
												<i class="icon-eye"></i> Detail Data </a>
										</li>
										<li>
											<a href="'.site_url('admin_side/ubah_data_anggaran/'.md5($value->id_kegiatan)).'">
												<i class="icon-wrench"></i> Ubah Data </a>
										</li>
										<li>
											<a onclick="'.$return_on_click.'" href="'.site_url('admin_side//'.md5($value->id_kegiatan)).'">
												<i class="icon-trash"></i> Hapus Data </a>
										</li>
									</ul>
								</div>
								';
			$data_tampil[] = $isi;
		}
		$results = array(
			"sEcho" => 1,
			"iTotalRecords" => count($data_tampil),
			"iTotalDisplayRecords" => count($data_tampil),
			"aaData"=>$data_tampil);
		echo json_encode($results);
	}
	public function import_jenis_belanja(){
		include APPPATH.'third_party/PHPExcel/PHPExcel.php';
		$namafile = date('YmdHis').'.xlsx';
		$config['upload_path'] = 'data_upload/kegiatan/';
		$config['allowed_types'] = 'xlsx';
		$config['max_size']	= '17048';
		$config['overwrite'] = true;
		$config['file_name'] = $namafile;

		$this->upload->initialize($config);
		if($this->upload->do_upload('fmasuk')){
			$excelreader = new PHPExcel_Reader_Excel2007();
			$loadexcel = $excelreader->load('data_upload/kegiatan/'.$namafile);
			$sheet = $loadexcel->getActiveSheet()->toArray(null, true, true ,true);
			$numrow = 1;
			$id_kegiatan = '';
			foreach($sheet as $row){
				if($numrow > 1){
					$this->db->trans_start();
					$get_id = $this->Main_model->getLastID('tbl_jenis_belanja','id_jenis_belanja');
					$id_kegiatan = $get_id['id_jenis_belanja']+1;
					// $pecah_kegiatan = explode(' ',$row['B'],2);
					$data_insert1 = array(
						'id_jenis_belanja' => $id_kegiatan,
						'kode_jenis_belanja' => $row['G'],
						'kode_beban' => $row['F'],
						'kode_sub_komponen' => $row['E'],
						'kode_komponen' => $row['D'],
						'kode_sub_output' => $row['C'],
						'kode_output' => $row['B'],
						'kode_kegiatan' => $row['A'],
						'pagu' => $row['M'],
						'keterangan' => $row['U']
					);
					$this->Main_model->insertData('tbl_jenis_belanja',$data_insert1);
					print_r($data_insert1);
					$this->db->trans_complete();
				}
				$numrow++;
			}
			$this->Main_model->log_activity($this->session->userdata('id'),'Importing data',"Import data anggaran kegiatan",$this->session->userdata('location'));

			$this->session->set_flashdata('sukses','<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button><strong></i>Yeah! </strong>data telah berhasil ditambahkan.<br /></div>' );
			echo "<script>window.location='".base_url()."admin_side/anggaran/'</script>";
		}else{
			$this->session->set_flashdata('gagal','<div class="alert alert-warning"><button type="button" class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button><strong></i>Oops! </strong>data gagal ditambahkan.<br /></div>' );
			echo "<script>window.location='".base_url()."admin_side/anggaran/'</script>";
		}
	}
	public function import_anggaran_data(){
		include APPPATH.'third_party/PHPExcel/PHPExcel.php';
		$namafile = date('YmdHis').'.xlsx';
		$config['upload_path'] = 'data_upload/kegiatan/';
		$config['allowed_types'] = 'xlsx';
		$config['max_size']	= '17048';
		$config['overwrite'] = true;
		$config['file_name'] = $namafile;

		$this->upload->initialize($config);
		if($this->upload->do_upload('fmasuk')){
			$excelreader = new PHPExcel_Reader_Excel2007();
			$loadexcel = $excelreader->load('data_upload/kegiatan/'.$namafile);
			$sheet = $loadexcel->getActiveSheet()->toArray(null, true, true ,true);
			$numrow = 1;
			$id_kegiatan = '';
			foreach($sheet as $row){
				if($numrow > 1){
					$this->db->trans_start();
					$get_id = $this->Main_model->getLastID('kegiatan','id_kegiatan');
					$id_kegiatan = $get_id['id_kegiatan']+1;
					// $pecah_kegiatan = explode(' ',$row['B'],2);
					$data_insert1 = array(
						'id_kegiatan' => $id_kegiatan,
						'kode_kegiatan' => $row['B'],
						'kegiatan' => $row['B'],
						'pagu' => $row['C'],
						'id_departemen' => ''
					);
					$this->Main_model->insertData('kegiatan',$data_insert1);
					print_r($data_insert1);
					$this->db->trans_complete();
				}
				$numrow++;
			}
			$this->Main_model->log_activity($this->session->userdata('id'),'Importing data',"Import data anggaran kegiatan",$this->session->userdata('location'));

			$this->session->set_flashdata('sukses','<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button><strong></i>Yeah! </strong>data telah berhasil ditambahkan.<br /></div>' );
			echo "<script>window.location='".base_url()."admin_side/anggaran/'</script>";
		}else{
			$this->session->set_flashdata('gagal','<div class="alert alert-warning"><button type="button" class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button><strong></i>Oops! </strong>data gagal ditambahkan.<br /></div>' );
			echo "<script>window.location='".base_url()."admin_side/anggaran/'</script>";
		}
	}
	public function import_sub_komponen(){
		include APPPATH.'third_party/PHPExcel/PHPExcel.php';
		$namafile = date('YmdHis').'.xlsx';
		$config['upload_path'] = 'data_upload/kegiatan/';
		$config['allowed_types'] = 'xlsx';
		$config['max_size']	= '17048';
		$config['overwrite'] = true;
		$config['file_name'] = $namafile;

		$this->upload->initialize($config);
		if($this->upload->do_upload('fmasuk')){
			$excelreader = new PHPExcel_Reader_Excel2007();
			$loadexcel = $excelreader->load('data_upload/kegiatan/'.$namafile);
			$sheet = $loadexcel->getActiveSheet()->toArray(null, true, true ,true);
			$numrow = 1;
			$id_kegiatan = '';
			foreach($sheet as $row){
				if($numrow > 1){
					$this->db->trans_start();
					$get_id = $this->Main_model->getLastID('tbl_sub_komponen','id_sub_komponen');
					$id_kegiatan = $get_id['id_sub_komponen']+1;
					// $pecah_kegiatan = explode(' ',$row['B'],2);
					$data_insert1 = array(
						'id_sub_komponen' => $id_kegiatan,
						'kode_sub_komponen' => $row['E'],
						'kode_komponen' => $row['D'],
						'kode_sub_output' => $row['C'],
						'kode_output' => $row['B'],
						'kode_kegiatan' => '2132',
						'sub_komponen' => $row['L'],
						'pagu' => $row['I']
					);
					$this->Main_model->insertData('tbl_sub_komponen',$data_insert1);
					// print_r($data_insert1);
					$this->db->trans_complete();
				}
				$numrow++;
			}
			$this->Main_model->log_activity($this->session->userdata('id'),'Importing data',"Import data anggaran kegiatan",$this->session->userdata('location'));

			$this->session->set_flashdata('sukses','<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button><strong></i>Yeah! </strong>data telah berhasil ditambahkan.<br /></div>' );
			echo "<script>window.location='".base_url()."admin_side/anggaran/'</script>";
		}else{
			$this->session->set_flashdata('gagal','<div class="alert alert-warning"><button type="button" class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button><strong></i>Oops! </strong>data gagal ditambahkan.<br /></div>' );
			echo "<script>window.location='".base_url()."admin_side/anggaran/'</script>";
		}
	}
	public function import_belanja(){
		include APPPATH.'third_party/PHPExcel/PHPExcel.php';
		$namafile = date('YmdHis').'.xlsx';
		$config['upload_path'] = 'data_upload/kegiatan/';
		$config['allowed_types'] = 'xlsx';
		$config['max_size']	= '17048';
		$config['overwrite'] = true;
		$config['file_name'] = $namafile;

		$this->upload->initialize($config);
		if($this->upload->do_upload('fmasuk')){
			$excelreader = new PHPExcel_Reader_Excel2007();
			$loadexcel = $excelreader->load('data_upload/kegiatan/'.$namafile);
			$sheet = $loadexcel->getActiveSheet()->toArray(null, true, true ,true);
			$numrow = 1;
			$id_kegiatan = '';
			foreach($sheet as $row){
				if($numrow > 1){
					$this->db->trans_start();
					$get_id = $this->Main_model->getLastID('tbl_belanja','id_belanja');
					$id_kegiatan = $get_id['id_belanja']+1;
					// $pecah_kegiatan = explode(' ',$row['B'],2);
					$data_insert1 = array(
						'id_belanja' => $id_kegiatan,
						'kode_jenis_belanja' => $row['G'],
						'kode_beban' => $row['F'],
						'kode_sub_komponen' => $row['E'],
						'kode_komponen' => $row['D'],
						'kode_sub_output' => $row['C'],
						'kode_output' => $row['B'],
						'kode_kegiatan' => $row['A'],
						'realisasi' => $row['K'],
						'keterangan' => $row['Q'],
						'bulan' => '2020-03'
					);
					$this->Main_model->insertData('tbl_belanja',$data_insert1);
					// print_r($data_insert1);
					$this->db->trans_complete();
				}
				$numrow++;
			}
			$this->Main_model->log_activity($this->session->userdata('id'),'Importing data',"Import data anggaran kegiatan",$this->session->userdata('location'));

			$this->session->set_flashdata('sukses','<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button><strong></i>Yeah! </strong>data telah berhasil ditambahkan.<br /></div>' );
			echo "<script>window.location='".base_url()."admin_side/anggaran/'</script>";
		}else{
			$this->session->set_flashdata('gagal','<div class="alert alert-warning"><button type="button" class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button><strong></i>Oops! </strong>data gagal ditambahkan.<br /></div>' );
			echo "<script>window.location='".base_url()."admin_side/anggaran/'</script>";
		}
	}
	public function add_anggaran_data()
	{
		$data['parent'] = 'master';
		$data['child'] = 'master_anggaran';
		$data['grand_child'] = '';
		$this->load->view('admin/template/header',$data);
		$this->load->view('admin/master/add_anggaran_data',$data);
		$this->load->view('admin/template/footer');
	}
	public function save_anggaran_data(){
		$cek_ = $this->Main_model->getSelectedData('user a', 'a.*',array('a.username'=>$this->input->post('un')))->row();
		if($cek_==NULL){
			$this->db->trans_start();
			$get_user_id = $this->Main_model->getLastID('user','id');

			$data_insert1 = array(
				'id' => $get_user_id['id']+1,
				'username' => $this->input->post('un'),
				'pass' => $this->input->post('ps'),
				'fullname' => $this->input->post('nama'),
				'is_active' => '1',
				'created_by' => $this->session->userdata('id'),
				'created_at' => date('Y-m-d H:i:s')
			);
			$this->Main_model->insertData('user',$data_insert1);
			// print_r($data_insert1);

			if($this->input->post('role')=='2'){
				$data_insert2 = array(
					'user_id' => $get_user_id['id']+1,
					'nama' => $this->input->post('nama'),
					'alamat' => $this->input->post('alamat')
				);
				$this->Main_model->insertData('guru',$data_insert2);
				// print_r($data_insert2);
			}else{
				$data_insert2 = array(
					'user_id' => $get_user_id['id']+1,
					'nama' => $this->input->post('nama'),
					'alamat' => $this->input->post('alamat')
				);
				$this->Main_model->insertData('siswa',$data_insert2);
				// print_r($data_insert2);
			}
			
			$data_insert3 = array(
				'user_id' => $get_user_id['id']+1,
				'role_id' => $this->input->post('role')
			);
			$this->Main_model->insertData('user_to_role',$data_insert3);
			// print_r($data_insert3);

			$this->Main_model->log_activity($this->session->userdata('id'),'Adding data',"Menambahkan data Pengguna (".$this->input->post('nama').")",$this->session->userdata('location'));
			$this->db->trans_complete();
			if($this->db->trans_status() === false){
				$this->session->set_flashdata('gagal','<div class="alert alert-warning"><button type="button" class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button><strong></i>Oops! </strong>data gagal ditambahkan.<br /></div>' );
				echo "<script>window.location='".base_url()."admin_side/tambah_data_anggaran/'</script>";
			}
			else{
				$this->session->set_flashdata('sukses','<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button><strong></i>Yeah! </strong>data telah berhasil ditambahkan.<br /></div>' );
				echo "<script>window.location='".base_url()."admin_side/data_anggaran/'</script>";
			}
		}else{
			$this->session->set_flashdata('gagal','<div class="alert alert-warning"><button type="button" class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button><strong></i>Oops! </strong>Username telah digunakan.<br /></div>' );
			echo "<script>window.location='".base_url()."admin_side/tambah_data_anggaran/'</script>";
		}
		
	}
	public function detail_anggaran_data(){
		$data['parent'] = 'master';
		$data['child'] = 'master_anggaran';
		$data['grand_child'] = '';
		$cek_role = $this->Main_model->getSelectedData('user_to_role a', 'a.*', array('md5(a.user_id)'=>$this->uri->segment(3)))->row();
		if($cek_role->role_id=='3'){
			$data['data_utama'] = $this->Main_model->getSelectedData('siswa a', 'a.*', array('md5(a.user_id)'=>$this->uri->segment(3)))->result();
		}else{
			echo'';
		}
		$this->load->view('admin/template/header',$data);
		$this->load->view('admin/master/detail_anggaran_data',$data);
		$this->load->view('admin/template/footer');
	}
	public function edit_anggaran_data(){
		$data['parent'] = 'master';
		$data['child'] = 'master_anggaran';
		$data['grand_child'] = '';
		$cek_role = $this->Main_model->getSelectedData('user_to_role a', 'a.*', array('md5(a.user_id)'=>$this->uri->segment(3)))->row();
		if($cek_role->role_id=='3'){
			$data['data_utama'] = $this->Main_model->getSelectedData('siswa a', 'a.*', array('md5(a.user_id)'=>$this->uri->segment(3)))->result();
		}else{
			$data['data_utama'] = $this->Main_model->getSelectedData('guru a', 'a.*', array('md5(a.user_id)'=>$this->uri->segment(3)))->result();
		}
		$data['role'] = $cek_role->role_id;
		$this->load->view('admin/template/header',$data);
		$this->load->view('admin/master/edit_anggaran_data',$data);
		$this->load->view('admin/template/footer');
	}
	public function update_anggaran_data(){
		$this->db->trans_start();
		$user_id = '';
		$name = '';
		$get_data = $this->Main_model->getSelectedData('user a', 'a.*',array('md5(a.id)'=>$this->uri->segment(3)))->row();
		$user_id = $get_data->id;
		$name = $get_data->fullname;

		$this->Main_model->updateData('user',array('pass'=>'1234'),array('id'=>$user_id));

		$this->Main_model->log_activity($this->session->userdata('id'),"Update anggaran's data","Reset password anggaran's account (".$name.")",$this->session->userdata('location'));
		$this->db->trans_complete();
		if($this->db->trans_status() === false){
			$this->session->set_flashdata('gagal','<div class="alert alert-warning"><button type="button" class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button><strong></i>Oops! </strong>data gagal diubah.<br /></div>' );
			echo "<script>window.location='".base_url()."admin_side/data_anggaran/'</script>";
		}
		else{
			$this->session->set_flashdata('sukses','<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button><strong></i>Yeah! </strong>data telah berhasil diubah.<br /></div>' );
			echo "<script>window.location='".base_url()."admin_side/data_anggaran/'</script>";
		}
	}
	public function delete_anggaran_data(){
		$this->db->trans_start();
		$user_id = '';
		$name = '';
		$get_data = $this->Main_model->getSelectedData('user_to_role a', 'a.*,b.fullname', array('md5(a.user_id)'=>$this->uri->segment(3)), '', '', '', '', array(
			'table' => 'user b',
			'on' => 'a.user_id=b.id',
			'pos' => 'LEFT'
		))->row();
		$user_id = $get_data->user_id;
		$name = $get_data->fullname;

		if($get_data->role_id=='2'){
			$this->Main_model->deleteData('guru',array('user_id'=>$user_id));
		}else{
			$this->Main_model->deleteData('siswa',array('user_id'=>$user_id));
		}
		$this->Main_model->deleteData('user_to_role',array('user_id'=>$user_id));
		$this->Main_model->deleteData('user',array('id'=>$user_id));

		$this->Main_model->log_activity($this->session->userdata('id'),"Deleting anggaran's data","Delete anggaran's data (".$name.")",$this->session->userdata('location'));
		$this->db->trans_complete();
		if($this->db->trans_status() === false){
			$this->session->set_flashdata('gagal','<div class="alert alert-warning"><button type="button" class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button><strong></i>Oops! </strong>data gagal dihapus.<br /></div>' );
			echo "<script>window.location='".base_url()."admin_side/data_anggaran/'</script>";
		}
		else{
			$this->session->set_flashdata('sukses','<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button><strong></i>Yeah! </strong>data telah berhasil dihapus.<br /></div>' );
			echo "<script>window.location='".base_url()."admin_side/data_anggaran/'</script>";
		}
	}
	/* Other Function */
	public function ajax_function(){
		if($this->input->post('modul')=='get_kode_output_by_kode_kegiatan'){
			$get_data = $this->Main_model->getSelectedData('tbl_output a', 'a.*', array('a.kode_kegiatan'=>$this->input->post('id')))->result();
			echo'<option value="">-- Pilih --</option>';
			foreach ($get_data as $key => $value) {
				echo'<option value="'.$value->kode_output.'">'.$value->output.'</option>';
			}
		}elseif($this->input->post('modul')=='get_kode_sub_output_by_kode_output'){
			$get_data = $this->Main_model->getSelectedData('tbl_sub_output a', 'a.*', array('a.kode_output'=>$this->input->post('kode_output'),'a.kode_kegiatan'=>$this->input->post('kode_kegiatan')))->result();
			echo'<option value="">-- Pilih --</option>';
			foreach ($get_data as $key => $value) {
				echo'<option value="'.$value->kode_sub_output.'">'.$value->sub_output.'</option>';
			}
		}elseif($this->input->post('modul')=='get_kode_komponen_by_kode_sub_output'){
			$get_data = $this->Main_model->getSelectedData('tbl_komponen a', 'a.*', array('a.kode_sub_output'=>$this->input->post('kode_sub_output'),'a.kode_output'=>$this->input->post('kode_output'),'a.kode_kegiatan'=>$this->input->post('kode_kegiatan')))->result();
			echo'<option value="">-- Pilih --</option>';
			foreach ($get_data as $key => $value) {
				echo'<option value="'.$value->kode_komponen.'">'.$value->komponen.'</option>';
			}
		}
	}
}