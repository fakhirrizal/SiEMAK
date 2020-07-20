<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Settings extends CI_Controller {
    function __construct() {
        parent::__construct();
	}
	/* Upload Setting */
	public function upload_setting(){
        $data['parent'] = 'setting';
		$data['child'] = 'upload_setting';
        $data['grand_child'] = '';
        $data['upload_setting'] = $this->Main_model->getSelectedData('upload_setting a', 'a.*')->result();
		$this->load->view('admin/template/header',$data);
		$this->load->view('admin/setting/upload_setting',$data);
		$this->load->view('admin/template/footer');
    }
    public function format_upload(){
        $data['parent'] = 'setting';
		$data['child'] = 'upload_setting';
        $data['grand_child'] = '';
        $data['upload_setting'] = $this->Main_model->getSelectedData('upload_setting a', 'a.*')->result();
		$this->load->view('admin/template/header',$data);
		$this->load->view('admin/setting/format_upload',$data);
		$this->load->view('admin/template/footer');
    }
    public function update_format_upload(){
        $this->db->trans_start();
        $upload_setting = $this->Main_model->getSelectedData('upload_setting a', 'a.*')->result();
        foreach ($upload_setting as $key => $value) {
            $this->Main_model->updateData('upload_setting',array('kolom'=>$this->input->post($value->id)),array('keterangan'=>$value->keterangan));
        }
        $this->Main_model->log_activity($this->session->userdata('id'),'Updating data',"Mengubah format upload data",$this->session->userdata('location'));
        $this->db->trans_complete();
        if($this->db->trans_status() === false){
            $this->session->set_flashdata('gagal','<div class="alert alert-warning"><button type="button" class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button><strong></i>Oops! </strong>data gagal diperbarui.<br /></div>' );
            echo "<script>window.location='".base_url()."admin_side/format_upload/'</script>";
        }
        else{
            $this->session->set_flashdata('sukses','<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button><strong></i>Yeah! </strong>data telah berhasil diperbarui.<br /></div>' );
            echo "<script>window.location='".base_url()."admin_side/upload_setting/'</script>";
        }
    }
	/* Other Function */
	public function ajax_function(){

    }
}