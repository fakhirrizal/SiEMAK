<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class App extends CI_Controller {

    function __construct() {
        parent::__construct();
	}
	public function launcher()
	{
		// $this->load->view('ppk/template/header',$data);
		$this->load->view('ppk/app/launcher');
		// $this->load->view('ppk/template/footer');
	}
    public function home()
	{
		$data['parent'] = 'home';
		$data['child'] = '';
		$data['grand_child'] = '';
		$this->load->view('ppk/template/header',$data);
		$this->load->view('ppk/app/home',$data);
		$this->load->view('ppk/template/footer');
	}
	public function menu()
	{
		$data['parent'] = 'menu';
		$data['child'] = '';
		$data['grand_child'] = '';
		$data['clinic_center_menu'] = $this->Main_model->getSelectedData('menu a', '*', array("parent_id" => "", "a.app_key" => "clinic_center", "a.menu_status" => '1', 'deleted' => '0'), 'a.menu_order ASC','','','','')->result();
		$this->load->view('ppk/template/header',$data);
		$this->load->view('ppk/app/menu',$data);
		$this->load->view('ppk/template/footer');
	}
	public function log_activity()
	{
		$data['parent'] = 'log_activity';
		$data['child'] = '';
		$data['grand_child'] = '';
		$data['data_tabel'] = $this->Main_model->getSelectedData('activity_logs a', 'a.*,b.fullname', '', "a.activity_time DESC",'','','',array(
			'table' => 'user b',
			'on' => 'a.user_id=b.id',
			'pos' => 'LEFT'
		))->result();
		$this->load->view('ppk/template/header',$data);
		$this->load->view('ppk/app/log_activity',$data);
		$this->load->view('ppk/template/footer');
	}
	public function cleaning_log(){
		$this->db->trans_start();
		$this->Main_model->cleanData('activity_logs');
		$this->db->trans_complete();
		if($this->db->trans_status() === false){
			$this->session->set_flashdata('gagal','<div class="alert alert-warning"><button type="button" class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button><strong></i>Oops! </strong>data gagal dihapus.<br /></div>' );
			echo "<script>window.location='".base_url()."ppk_side/log_activity/'</script>";
		}
		else{
			$this->session->set_flashdata('sukses','<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button><strong></i>Yeah! </strong>data telah berhasil dihapus.<br /></div>' );
			echo "<script>window.location='".base_url()."ppk_side/log_activity/'</script>";
		}
	}
	public function about()
	{
		$data['parent'] = 'about';
		$data['child'] = '';
		$data['grand_child'] = '';
		$this->load->view('ppk/template/header',$data);
		$this->load->view('ppk/app/about',$data);
		$this->load->view('ppk/template/footer');
	}
	public function helper()
	{
		$data['parent'] = 'helper';
		$data['child'] = '';
		$data['grand_child'] = '';
		$this->load->view('ppk/template/header',$data);
		$this->load->view('ppk/app/helper',$data);
		$this->load->view('ppk/template/footer');
	}
	/* Menu setting and user's permission */
	public function ajax_function(){
		if($this->input->post('modul')=='modul_detail_log_aktifitas'){
			$data['data_utama'] = $this->Main_model->getSelectedData('activity_logs a', 'a.*,b.fullname', array('md5(a.activity_id)'=>$this->input->post('id')), "",'','','',array(
				'table' => 'user b',
				'on' => 'a.user_id=b.id',
				'pos' => 'LEFT'
			))->result();
			$this->load->view('ppk/app/ajax_detail_log_aktifitas',$data);
		}
	}
}