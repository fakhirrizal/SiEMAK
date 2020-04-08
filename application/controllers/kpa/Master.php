<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Master extends CI_Controller {
	function __construct() {
		parent::__construct();
	}
	/* Kegiatan */
	public function kegiatan_data()
	{
		$data['parent'] = 'master';
		$data['child'] = 'master_kegiatan';
		$data['grand_child'] = '';
		$this->load->view('kpa/template/header',$data);
		$this->load->view('kpa/master/kegiatan_data',$data);
		$this->load->view('kpa/template/footer');
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
			$data_tampil[] = $isi;
		}
		$results = array(
			"sEcho" => 1,
			"iTotalRecords" => count($data_tampil),
			"iTotalDisplayRecords" => count($data_tampil),
			"aaData"=>$data_tampil);
		echo json_encode($results);
	}
	/* Output */
	public function output_data()
	{
		$data['parent'] = 'master';
		$data['child'] = 'master_output';
		$data['grand_child'] = '';
		$this->load->view('kpa/template/header',$data);
		$this->load->view('kpa/master/output_data',$data);
		$this->load->view('kpa/template/footer');
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
			$data_tampil[] = $isi;
		}
		$results = array(
			"sEcho" => 1,
			"iTotalRecords" => count($data_tampil),
			"iTotalDisplayRecords" => count($data_tampil),
			"aaData"=>$data_tampil);
		echo json_encode($results);
	}
	/* Sub Output */
	public function sub_output_data()
	{
		$data['parent'] = 'master';
		$data['child'] = 'master_sub_output';
		$data['grand_child'] = '';
		$this->load->view('kpa/template/header',$data);
		$this->load->view('kpa/master/sub_output_data',$data);
		$this->load->view('kpa/template/footer');
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
			$data_tampil[] = $isi;
		}
		$results = array(
			"sEcho" => 1,
			"iTotalRecords" => count($data_tampil),
			"iTotalDisplayRecords" => count($data_tampil),
			"aaData"=>$data_tampil);
		echo json_encode($results);
	}
	/* Komponen */
	public function komponen_data()
	{
		$data['parent'] = 'master';
		$data['child'] = 'master_komponen';
		$data['grand_child'] = '';
		$this->load->view('kpa/template/header',$data);
		$this->load->view('kpa/master/komponen_data',$data);
		$this->load->view('kpa/template/footer');
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
			$data_tampil[] = $isi;
		}
		$results = array(
			"sEcho" => 1,
			"iTotalRecords" => count($data_tampil),
			"iTotalDisplayRecords" => count($data_tampil),
			"aaData"=>$data_tampil);
		echo json_encode($results);
	}
	/* Sub Komponen */
	public function sub_komponen_data()
	{
		$data['parent'] = 'master';
		$data['child'] = 'master_sub_komponen';
		$data['grand_child'] = '';
		$this->load->view('kpa/template/header',$data);
		$this->load->view('kpa/master/sub_komponen_data',$data);
		$this->load->view('kpa/template/footer');
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
			$data_tampil[] = $isi;
		}
		$results = array(
			"sEcho" => 1,
			"iTotalRecords" => count($data_tampil),
			"iTotalDisplayRecords" => count($data_tampil),
			"aaData"=>$data_tampil);
		echo json_encode($results);
	}
	/* Departemen */
	public function departemen_data()
	{
		$data['parent'] = 'master';
		$data['child'] = 'master_departemen';
		$data['grand_child'] = '';
		$this->load->view('kpa/template/header',$data);
		$this->load->view('kpa/master/departemen_data',$data);
		$this->load->view('kpa/template/footer');
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
			$data_tampil[] = $isi;
		}
		$results = array(
			"sEcho" => 1,
			"iTotalRecords" => count($data_tampil),
			"iTotalDisplayRecords" => count($data_tampil),
			"aaData"=>$data_tampil);
		echo json_encode($results);
	}
}