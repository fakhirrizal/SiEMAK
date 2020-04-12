<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Report extends CI_Controller {
    function __construct() {
        parent::__construct();
	}
	/* Pendapatan */
	public function pendapatan(){
        $data['parent'] = 'laporan';
		$data['child'] = 'pendapatan';
		$data['grand_child'] = '';
		$this->load->view('admin/template/header',$data);
		$this->load->view('admin/report/pendapatan',$data);
		$this->load->view('admin/template/footer');
    }
	/* Pengeluaran */
    public function penggunaan_anggaran(){
        $data['parent'] = 'laporan';
		$data['child'] = 'penggunaan_anggaran';
		$data['grand_child'] = '';
		$data['kode_kegiatan_where'] = 'All';
        $data['kode_output_where'] = 'All';
        $data['kode_sub_output_where'] = 'All';
        $data['kode_komponen_where'] = 'All';
        $data['kode_sub_komponen_where'] = 'All';
        $data['kode_beban_where'] = 'All';
        if($this->input->post('kode_kegiatan_where')==NULL){
            echo'';
        }else{
            $data['kode_kegiatan_where'] = $this->input->post('kode_kegiatan_where');
        }
        if($this->input->post('kode_output_where')==NULL){
            echo'';
        }else{
            $data['kode_output_where'] = $this->input->post('kode_output_where');
        }
        if($this->input->post('kode_sub_output_where')==NULL){
            echo'';
        }else{
            $data['kode_sub_output_where'] = $this->input->post('kode_sub_output_where');
		}
		if($this->input->post('kode_komponen_where')==NULL){
            echo'';
        }else{
            $data['kode_komponen_where'] = $this->input->post('kode_komponen_where');
		}
		if($this->input->post('kode_sub_komponen_where')==NULL){
            echo'';
        }else{
            $data['kode_sub_komponen_where'] = $this->input->post('kode_sub_komponen_where');
		}
		if($this->input->post('kode_beban_where')==NULL){
            echo'';
        }else{
            $data['kode_beban_where'] = $this->input->post('kode_beban_where');
        }
		$this->load->view('admin/template/header',$data);
		$this->load->view('admin/report/penggunaan_anggaran',$data);
		$this->load->view('admin/template/footer');
    }
    public function json_penggunaan_anggaran(){
		$data_where = array();
		if($this->input->post('kode_kegiatan_where')=='All'){
			echo'';
		}else{
			$data_where['kode_kegiatan'] = $this->input->post('kode_kegiatan_where');
		}
		if($this->input->post('kode_output_where')=='All'){
			echo'';
		}else{
			$data_where['kode_output'] = $this->input->post('kode_output_where');
		}
		if($this->input->post('kode_sub_output_where')=='All'){
			echo'';
		}else{
			$data_where['kode_sub_output'] = $this->input->post('kode_sub_output_where');
		}
		if($this->input->post('kode_komponen_where')=='All'){
			echo'';
		}else{
			$data_where['kode_komponen'] = $this->input->post('kode_komponen_where');
		}
		if($this->input->post('kode_sub_komponen_where')=='All'){
			echo'';
		}else{
			$data_where['kode_sub_komponen'] = $this->input->post('kode_sub_komponen_where');
		}
		if($this->input->post('kode_beban_where')=='All'){
			echo'';
		}else{
			$data_where['kode_beban'] = $this->input->post('kode_beban_where');
		}
		$data_where['bulan'] = $this->Main_model->get_where_bulan();
		$get_data = $this->Main_model->getSelectedData('tbl_belanja a', 'a.*', $data_where)->result();
		$data_tampil = array();
		$no = 1;
		foreach ($get_data as $key => $value) {
			$isi['number'] = $no++.'.';
			$isi['jenis'] = $value->kode_jenis_belanja;
			$isi['beban'] = $value->kode_beban;
			$isi['kode_sub_komponen'] = $value->kode_sub_komponen;
			$get_sub_komponen = $this->Main_model->getSelectedData('tbl_sub_komponen', '*', array('kode_sub_komponen'=>$value->kode_sub_komponen,'kode_komponen'=>$value->kode_komponen,'kode_sub_output'=>$value->kode_sub_output,'kode_output'=>$value->kode_output,'kode_kegiatan'=>$value->kode_kegiatan))->row();
			$isi['sub_komponen'] = $get_sub_komponen->sub_komponen;
			$isi['komponen'] = $value->kode_komponen;
			$isi['ket'] = $value->keterangan;
			$get_jenis_belanja = $this->Main_model->getSelectedData('tbl_jenis_belanja', '*', array('kode_jenis_belanja'=>$value->kode_jenis_belanja,'kode_beban'=>$value->kode_beban,'kode_sub_komponen'=>$value->kode_sub_komponen,'kode_komponen'=>$value->kode_komponen,'kode_sub_output'=>$value->kode_sub_output,'kode_output'=>$value->kode_output,'kode_kegiatan'=>$value->kode_kegiatan))->row();
			$isi['pagu'] = 'Rp '.number_format($get_jenis_belanja->pagu,2);
			$isi['realisasi'] = 'Rp '.number_format($value->realisasi,2);
			$sisa = ($get_jenis_belanja->pagu)-($value->realisasi);
			$isi['sisa'] = 'Rp '.number_format($sisa,2);
			$return_on_click = "return confirm('Anda yakin?')";
			$isi['action'] =	'
			<a href="'.site_url('admin_side/detail_belanja/'.md5($value->id_belanja)).'" class="btn btn-outline btn-circle red btn-sm blue"><i class="fa fa-share"></i> Detail </a>
			<br><br>
			<a onclick="'.$return_on_click.'" href="'.site_url('admin_side/hapus_data_belanja/'.md5($value->id_belanja)).'" class="btn btn-outline btn-circle dark btn-sm black"><i class="fa fa-trash-o"></i> Hapus </a>
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
	public function import_serapan(){
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
			$id_belanja = '';
			foreach($sheet as $row){
				if($numrow > 1){
					$get_id = $this->Main_model->getLastID('tbl_belanja','id_belanja');
					$id_belanja = $get_id['id_belanja']+1;
					// $pecah_kegiatan = explode(' ',$row['B'],2);
					if($row['N']>0 AND $row['G']!=NULL){
						$cek_ada = $this->Main_model->getSelectedData('tbl_belanja', '*', array('kode_jenis_belanja'=>$row['G'],'kode_beban'=>$row['F'],'kode_sub_komponen'=>$row['E'],'kode_komponen'=>$row['D'],'kode_sub_output'=>$row['C'],'kode_output'=>$row['B'],'kode_kegiatan'=>$row['A'],'bulan' => $this->input->post('bulan')))->row();
						if($cek_ada==NULL){
							$data_insert1 = array(
								'id_belanja' => $id_belanja,
								'kode_jenis_belanja' => $row['G'],
								'kode_beban' => $row['F'],
								'kode_sub_komponen' => $row['E'],
								'kode_komponen' => $row['D'],
								'kode_sub_output' => $row['C'],
								'kode_output' => $row['B'],
								'kode_kegiatan' => $row['A'],
								'realisasi' => $row['N'],
								'keterangan' => substr($row['Q'],52,10000),
								'bulan' => $this->input->post('bulan')
							);
							$this->Main_model->insertData('tbl_belanja',$data_insert1);
							// print_r($data_insert1);
						}else{
							$data_insert1 = array(
								'realisasi' => $row['N'],
								'keterangan' => substr($row['Q'],52,10000)
							);
							$this->Main_model->updateData('tbl_belanja',$data_insert1,array('id_belanja'=>$cek_ada->id_belanja));
							// print_r($data_insert1);
						}
					}else{
						echo'';
					}
				}
				$numrow++;
			}
			$this->Main_model->log_activity($this->session->userdata('id'),'Importing data',"Import data serapan anggaran",$this->session->userdata('location'));
			$this->session->set_flashdata('sukses','<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button><strong></i>Yeah! </strong>data telah berhasil ditambahkan.<br /></div>' );
			echo "<script>window.location='".base_url()."admin_side/penggunaan_anggaran/'</script>";
		}else{
			$this->session->set_flashdata('gagal','<div class="alert alert-warning"><button type="button" class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button><strong></i>Oops! </strong>data gagal ditambahkan.<br /></div>' );
			echo "<script>window.location='".base_url()."admin_side/penggunaan_anggaran/'</script>";
		}
	}
	public function detail_belanja()
	{
		$data['parent'] = 'laporan';
		$data['child'] = 'penggunaan_anggaran';
		$data['grand_child'] = '';
		$data['data_utama'] = $this->Main_model->getSelectedData('tbl_belanja a', 'a.*', array('md5(a.id_belanja)'=>$this->uri->segment(3)))->result();
		$this->load->view('admin/template/header',$data);
		$this->load->view('admin/report/detail_belanja',$data);
		$this->load->view('admin/template/footer');
	}
	public function hapus_data_belanja(){
		$this->db->trans_start();
		$track_id = '';
		$name = '';
		$get_data = $this->Main_model->getSelectedData('tbl_belanja a', 'a.*', array('md5(a.id_belanja)'=>$this->uri->segment(3)))->row();
		$track_id = $get_data->id_belanja;
		$name = $get_data->keterangan;

		$this->Main_model->deleteData('tbl_belanja',array('id_belanja'=>$track_id));

		$this->Main_model->log_activity($this->session->userdata('id'),"Deleting data","Menghapus data belanja (".$name.")",$this->session->userdata('location'));
		$this->db->trans_complete();
		if($this->db->trans_status() === false){
			$this->session->set_flashdata('gagal','<div class="alert alert-warning"><button type="button" class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button><strong></i>Oops! </strong>data gagal dihapus.<br /></div>' );
			echo "<script>window.location='".base_url()."admin_side/penggunaan_anggaran/'</script>";
		}
		else{
			$this->session->set_flashdata('sukses','<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button><strong></i>Yeah! </strong>data telah berhasil dihapus.<br /></div>' );
			echo "<script>window.location='".base_url()."admin_side/penggunaan_anggaran/'</script>";
		}
	}
	/* Other Function */
	public function ajax_function(){
		if($this->input->post('modul')=='get_kode_output_by_kode_kegiatan'){
			$get_data = $this->Main_model->getSelectedData('tbl_output a', 'a.*', array('a.kode_kegiatan'=>$this->input->post('id')))->result();
			echo"<option value='All'>All</option>";
			foreach ($get_data as $key => $value) {
				echo'<option value="'.$value->kode_output.'">'.$value->output.'</option>';
			}
		}elseif($this->input->post('modul')=='get_kode_sub_output_by_kode_output'){
			$get_data = $this->Main_model->getSelectedData('tbl_sub_output a', 'a.*', array('a.kode_output'=>$this->input->post('kode_output'),'a.kode_kegiatan'=>$this->input->post('kode_kegiatan')))->result();
			echo"<option value='All'>All</option>";
			foreach ($get_data as $key => $value) {
				echo'<option value="'.$value->kode_sub_output.'">'.$value->sub_output.'</option>';
			}
		}elseif($this->input->post('modul')=='get_kode_komponen_by_kode_sub_output'){
			$get_data = $this->Main_model->getSelectedData('tbl_komponen a', 'a.*', array('a.kode_sub_output'=>$this->input->post('kode_sub_output'),'a.kode_output'=>$this->input->post('kode_output'),'a.kode_kegiatan'=>$this->input->post('kode_kegiatan')))->result();
			echo"<option value='All'>All</option>";
			foreach ($get_data as $key => $value) {
				echo'<option value="'.$value->kode_komponen.'">'.$value->komponen.'</option>';
			}
		}elseif($this->input->post('modul')=='get_kode_sub_komponen_by_kode_komponen'){
			$get_data = $this->Main_model->getSelectedData('tbl_sub_komponen a', 'a.*', array('a.kode_komponen'=>$this->input->post('kode_komponen'),'a.kode_sub_output'=>$this->input->post('kode_sub_output'),'a.kode_output'=>$this->input->post('kode_output'),'a.kode_kegiatan'=>$this->input->post('kode_kegiatan')))->result();
			echo"<option value='All'>All</option>";
			foreach ($get_data as $key => $value) {
				echo'<option value="'.$value->kode_sub_komponen.'">'.$value->sub_komponen.'</option>';
			}
		}
	}
}