<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Report extends CI_Controller {
    function __construct() {
        parent::__construct();
    }
    /* Other Function */
    public function ajax_page(){
		if($this->input->post('modul')=='tabel_soal_dalam_ujian'){
            $getdata = $this->Main_model->getSelectedData('siswa_to_modul a', 'a.*', array('md5(a.id_siswa_to_modul)'=>$this->input->post('data')))->row();
			$data['soal'] = $this->Main_model->getSelectedData('soal a', 'a.*,c.jawaban AS pilihan_jawaban,c.keyakinan_1,c.alasan,c.keyakinan_2', array('b.id_modul'=>$getdata->id_modul), '', '', '', '', array(
                array(
                    'table' => 'soal_to_modul b',
                    'on' => 'a.id_soal=b.id_soal',
                    'pos' => 'RIGHT'
                ),array(
                    'table' => 'detail_ujian c',
                    'on' => 'b.id_soal_to_modul=c.id_soal_to_modul',
                    'pos' => 'LEFT'
                )
            ))->result();
			$this->load->view('member/report/ajax_page/tabel_soal_dalam_ujian',$data);
		}elseif($this->input->post('modul')=='daftar_soal_dalam_modul'){
			$data['soal'] = $this->Main_model->getSelectedData('soal_to_modul a', 'b.*,a.id_soal_to_modul', array('md5(a.id_modul)'=>$this->input->post('data')), '', '', '', '', array(
				'table' => 'soal b',
				'on' => 'a.id_soal=b.id_soal',
				'pos' => 'LEFT'
			))->result();
			$data['id_mod'] = $this->input->post('data');
			$data['daftar_soal'] = $this->Main_model->getSelectedData('soal a', 'a.*', array('a.deleted'=>'0'))->result();
			$this->load->view('admin/master/ajax_page/form_daftar_soal_dalam_modul',$data);
		}elseif($this->input->post('modul')=='daftar_peserta_dalam_suatu_ujian'){
			$data['siswa'] = $this->Main_model->getSelectedData('siswa_to_modul a', 'b.*,a.id_siswa_to_modul', array('md5(a.id_modul)'=>$this->input->post('data')), '', '', '', '', array(
				'table' => 'siswa b',
				'on' => 'a.id_siswa=b.id_siswa',
				'pos' => 'LEFT'
			))->result();
			$data['id_mod'] = $this->input->post('data');
			$data['daftar_siswa'] = $this->Main_model->getSelectedData('siswa a', 'a.*')->result();
			$this->load->view('admin/master/ajax_page/form_daftar_peserta_ujian',$data);
		}
	}
	public function ajax_function(){
		if($this->input->post('modul')=='modul_ubah_data_status_pengajuan_ktp'){
            $get_data1 = $this->Main_model->getSelectedData('data_ktp a', 'a.*', array('md5(a.id_data_ktp)'=>$this->input->post('id')))->row();
            echo'
            <form role="form" class="form-horizontal" action="'.base_url('member_side/perbarui_data_pengajuan_ktp').'" method="post" >
                <input type="hidden" name="id_data_ktp" value="'.md5($get_data1->id_data_ktp).'">
                <input type="hidden" name="nik" value="'.$get_data1->nik.'">
                <div class="form-body">
                    <div class="form-group form-md-line-input has-danger">
                        <label class="col-md-2 control-label" for="form_control_1">NIK</label>
                        <div class="col-md-10">
                            '.$get_data1->nik.'
                        </div>
                    </div>
                    <div class="form-group form-md-line-input has-danger">
                        <label class="col-md-2 control-label" for="form_control_1">Keterangan <span class="required"> * </span></label>
                        <div class="col-md-10">
                            <div class="input-icon">
                                <select name="keterangan" class="form-control select2-allow-clear" required>
                                    <option value="">-- Pilih --</option>';
                                    if($get_data1->keterangan=='Foto baru'){
                                        echo'<option value="Foto baru" selected>Foto baru</option>
                                            <option value="Perubahan">Perubahan</option>
                                            <option value="Kehilangan">Kehilangan</option>';
                                    }elseif($get_data1->keterangan=='Perubahan'){
                                        echo'<option value="Foto baru">Foto baru</option>
                                            <option value="Perubahan" selected>Perubahan</option>
                                            <option value="Kehilangan">Kehilangan</option>';
                                    }elseif($get_data1->keterangan=='Kehilangan'){
                                        echo'<option value="Foto baru">Foto baru</option>
                                            <option value="Perubahan" selected>Perubahan</option>
                                            <option value="Kehilangan">Kehilangan</option>';
                                    }else{
                                        echo'<option value="Foto baru">Foto baru</option>
                                            <option value="Perubahan">Perubahan</option>
                                            <option value="Kehilangan">Kehilangan</option>';
                                    }
                            echo'</select>
                                <i class="icon-pin"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <br>
                <div class="form-actions margin-top-9">
                    <div class="row">
                        <div class="col-md-offset-2 col-md-9">
                            <button type="reset" class="btn default">Batal</button>
                            <button type="submit" class="btn blue">Perbarui</button>
                        </div>
                    </div>
                </div>
            </form>
            ';
		}
		elseif($this->input->post('modul')=='modul_ubah_data_status_antrean_kk'){
            $get_data1 = $this->Main_model->getSelectedData('data_kk a', 'a.*', array('md5(a.id_data_kk)'=>$this->input->post('id')))->row();
            echo'
            <form role="form" class="form-horizontal" action="'.base_url('member_side/perbarui_data_antrean_kk').'" method="post" >
                <input type="hidden" name="id_data_kk" value="'.md5($get_data1->id_data_kk).'">
                <input type="hidden" name="no_kk" value="'.$get_data1->no_kk.'">
                <input type="hidden" name="nama" value="'.$get_data1->nama.'">
                <input type="hidden" name="from" value="report">
                <div class="form-body">
                    <div class="form-group form-md-line-input has-danger">
                        <label class="col-md-2 control-label" for="form_control_1">NIK</label>
                        <div class="col-md-10">
                            '.$get_data1->nik.'
                        </div>
                    </div>
                    <div class="form-group form-md-line-input has-danger">
                        <label class="col-md-2 control-label" for="form_control_1">Nomor KK</label>
                        <div class="col-md-10">
                            '.$get_data1->no_kk.'
                        </div>
                    </div>
                    <div class="form-group form-md-line-input has-danger">
                        <label class="col-md-2 control-label" for="form_control_1">Nama</label>
                        <div class="col-md-10">
                            '.$get_data1->nama.' ('.$get_data1->jk.')
                        </div>
                    </div>
                    <div class="form-group form-md-line-input has-danger">
                        <label class="col-md-2 control-label" for="form_control_1">TTL</label>
                        <div class="col-md-10">
                            '.$get_data1->tempat_lahir.', '.$this->Main_model->convert_tanggal($get_data1->tgl_lahir).'
                        </div>
                    </div>
                    <div class="form-group form-md-line-input has-danger">
                        <label class="col-md-2 control-label" for="form_control_1">Status <span class="required"> * </span></label>
                        <div class="col-md-10">
                            <div class="input-icon">
                                <select name="status" class="form-control select2-allow-clear" required>
                                    <option value="">-- Pilih --</option>';
                                    if($get_data1->status=='Proses'){
                                        echo'<option value="Proses" selected>Proses</option>
                                            <option value="Selesai">Selesai</option>';
                                    }elseif($get_data1->status=='Selesai'){
                                        echo'<option value="Proses">Proses</option>
                                            <option value="Selesai" selected>Selesai</option>';
                                    }else{
                                        echo'<option value="Proses">Proses</option>
                                            <option value="Selesai">Selesai</option>';
                                    }
                            echo'</select>
                                <i class="icon-pin"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <br>
                <div class="form-actions margin-top-9">
                    <div class="row">
                        <div class="col-md-offset-2 col-md-9">
                            <button type="reset" class="btn default">Batal</button>
                            <button type="submit" class="btn blue">Perbarui</button>
                        </div>
                    </div>
                </div>
            </form>
            ';
		}
	}
}