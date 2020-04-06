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
		<span><a href='<?= site_url('/member_side/master_modul'); ?>'>Hasil Ujian</a></span>
		<!-- <i class="fa fa-circle"></i> -->
	</li>
	<!-- <li>
		<span>Detil Data</span>
	</li> -->
</ul>
<?php
$id_global = '';
$module = '';
$last_id_soal = '';
?>
<?= $this->session->flashdata('sukses') ?>
<?= $this->session->flashdata('gagal') ?>
<div class="page-content-inner">
	<div class="m-heading-1 border-yellow m-bordered" style="background-color:#FAD405;">
		<h3>Catatan</h3>
		<p> 1. Soal yang sudah terjawab berwarna biru.</p>
		<p> 2. Jika merasa telah selesai silahkan klik tombol <b>Selesai Ujian</b>.</p>
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
                                $id_global = $row->id_siswa_to_modul;
                                $module = $row->id_modul;
                                $last_id_soal = $row->last_id_soal;
						?>
								<div class="col-md-6">
									<table class="table">
										<tbody>
                                            <tr>
												<td> Modul </td>
												<td> : </td>
												<td><?php echo $row->judul; ?></td>
											</tr>
                                            <tr>
												<td> Jumlah Soal </td>
												<td> : </td>
												<td><?php echo $row->jumlah_soal.' Soal'; ?></td>
                                            </tr>
                                            <tr>
												<td> Durasi </td>
												<td> : </td>
												<td><?php echo $row->durasi.' Menit'; ?></td>
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
									</table>
								</div>
						<?php }} ?>
					</div>
					<div class='row'>
						<div class="col-md-12" >
                            <div class="portlet light ">
                                <div class="portlet-title">
                                    <div class="caption">
                                        <i class="icon-share font-red-sunglo"></i>
                                        <span class="caption-subject font-red-sunglo bold">Daftar Soal</span>
                                    </div>
                                </div>
                                <div class="portlet-body">
                                    <div>
                                        <ul class="pagination">
                                            <?php
                                            $no = 1;
                                            // print_r($soal);
                                            foreach ($soal as $key => $value) {
                                                $get_jawaban_soal = $this->Main_model->getSelectedData('detail_ujian c', 'c.jawaban AS pilihan_jawaban', array('c.id_siswa_to_modul'=>$id_global,'c.id_soal_to_modul'=>$value->id_soal_to_modul))->row();
                                                if($get_jawaban_soal->pilihan_jawaban==NULL){
                                                    echo'
                                                    <li>
                                                        <a href="javascript:;" class="detaildata" id="'.$id_global.'_'.md5($value->id_soal_to_modul).'"> '.$no++.' </a>
                                                    </li>
                                                    ';
                                                }else{
                                                    echo'
                                                    <li class="active">
                                                        <a href="javascript:;" class="detaildata" id="'.$id_global.'_'.md5($value->id_soal_to_modul).'"> '.$no++.' </a>
                                                    </li>
                                                    ';
                                                }
                                            }
                                            ?>&nbsp;&nbsp;<a class="btn red" onclick="return confirm('Anda yakin?')" href='<?= base_url('member_side/selesai_ujian/'.md5($id_global)); ?>'>Selesai Ujian</a>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div id='halaman_soal'>
                                <form role="form" class="form-horizontal" action="<?=base_url('member_side/simpan_jawaban');?>" method="post" enctype='multipart/form-data'>
                                    <input type='hidden' name='identity' value='<?= $id_global; ?>' />
                                    <input type='hidden' name='modul' value='<?= $module; ?>' />
                                    <?php
                                    if($last_id_soal!=''){
                                        foreach ($soal as $key => $value) {
                                            $get_jawaban_soal = $this->Main_model->getSelectedData('detail_ujian c', 'c.jawaban AS pilihan_jawaban,c.keyakinan_1,c.alasan,c.keyakinan_2', array('c.id_siswa_to_modul'=>$id_global,'c.id_soal_to_modul'=>$value->id_soal_to_modul))->row();
                                            if($last_id_soal==$value->id_soal){
                                                echo '
                                                <input type="hidden" name="soal_to_modul" value="'.$value->id_soal_to_modul.'" />
                                                <input type="hidden" name="soal" value="'.$value->id_soal.'" />
                                                <input type="hidden" name="jawaban" value="'.$value->jawaban.'" />
                                                <input type="hidden" name="alasan_benar" value="'.$value->alasan_benar.'" />
                                                <div class="form-group form-md-line-input has-danger">
                                                    <label class="col-md-2 control-label" for="form_control_1">Pertanyaan</label>
                                                    <div class="col-md-10">
                                                        '.$value->pertanyaan.'
                                                    </div>
                                                </div>
                                                <div class="form-group form-md-line-input has-danger">
                                                    <label class="col-md-2 control-label" for="form_control_1"></label>
                                                    <div class="col-md-10">
                                                        <div class="form-group form-md-radios">
                                                            <div class="md-radio-list">
                                                                <div class="md-radio has-success">
                                                                    <input type="radio" id="radio_a" name="jwb" value="A" class="md-radiobtn" '; if($get_jawaban_soal->pilihan_jawaban=="A"){echo'checked';}else{echo'';} echo'>
                                                                    <label for="radio_a">
                                                                        <span></span>
                                                                        <span class="check"></span>
                                                                        <span class="box"></span> A. '.$value->pilihan_1.' </label>
                                                                </div>
                                                                <div class="md-radio has-success">
                                                                    <input type="radio" id="radio_b" name="jwb" value="B" class="md-radiobtn" '; if($get_jawaban_soal->pilihan_jawaban=="B"){echo'checked';}else{echo'';} echo'>
                                                                    <label for="radio_b">
                                                                        <span></span>
                                                                        <span class="check"></span>
                                                                        <span class="box"></span> B. '.$value->pilihan_2.' </label>
                                                                </div>
                                                            ';
                                                            if($value->pilihan_3==NULL){
                                                                echo'';
                                                            }else{
                                                                if($get_jawaban_soal->pilihan_jawaban=="C"){
                                                                    echo'<div class="md-radio has-success">
                                                                        <input type="radio" id="radio_c" name="jwb" value="C" class="md-radiobtn" checked>
                                                                        <label for="radio_c">
                                                                            <span></span>
                                                                            <span class="check"></span>
                                                                            <span class="box"></span> C. '.$value->pilihan_3.' </label>
                                                                    </div>';
                                                                }else{
                                                                    echo'<div class="md-radio has-success">
                                                                        <input type="radio" id="radio_c" name="jwb" value="C" class="md-radiobtn">
                                                                        <label for="radio_c">
                                                                            <span></span>
                                                                            <span class="check"></span>
                                                                            <span class="box"></span> C. '.$value->pilihan_3.' </label>
                                                                    </div>';
                                                                }
                                                            }
                                                            if($value->pilihan_4==NULL){
                                                                echo'';
                                                            }else{
                                                                if($get_jawaban_soal->pilihan_jawaban=="D"){
                                                                    echo'<div class="md-radio has-success">
                                                                        <input type="radio" id="radio_d" name="jwb" value="D" class="md-radiobtn" checked>
                                                                        <label for="radio_d">
                                                                            <span></span>
                                                                            <span class="check"></span>
                                                                            <span class="box"></span> D. '.$value->pilihan_4.' </label>
                                                                    </div>';
                                                                }else{
                                                                    echo'<div class="md-radio has-success">
                                                                        <input type="radio" id="radio_d" name="jwb" value="D" class="md-radiobtn">
                                                                        <label for="radio_d">
                                                                            <span></span>
                                                                            <span class="check"></span>
                                                                            <span class="box"></span> D. '.$value->pilihan_4.' </label>
                                                                    </div>';
                                                                }
                                                            }    
                                                            if($value->pilihan_5==NULL){
                                                                echo'';
                                                            }else{
                                                                if($get_jawaban_soal->pilihan_jawaban=="E"){
                                                                    echo'<div class="md-radio has-success">
                                                                        <input type="radio" id="radio_e" name="jwb" value="E" class="md-radiobtn" checked>
                                                                        <label for="radio_e">
                                                                            <span></span>
                                                                            <span class="check"></span>
                                                                            <span class="box"></span> E. '.$value->pilihan_5.' </label>
                                                                    </div>';
                                                                }else{
                                                                    echo'<div class="md-radio has-success">
                                                                        <input type="radio" id="radio_e" name="jwb" value="E" class="md-radiobtn">
                                                                        <label for="radio_e">
                                                                            <span></span>
                                                                            <span class="check"></span>
                                                                            <span class="box"></span> E. '.$value->pilihan_5.' </label>
                                                                    </div>';
                                                                }
                                                            }    
                                                        echo'</div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group form-md-line-input has-danger">
                                                    <label class="col-md-2 control-label" for="form_control_1">Keyakinan Jawaban</label>
                                                    <div class="col-md-10">
                                                        <div class="form-group form-md-radios">
                                                            <div class="md-radio-list">
                                                                <div class="md-radio has-success">
                                                                    <input type="radio" id="radio_yakin_jawaban_1" name="radio_yakin_jawaban" value="1" class="md-radiobtn" '; if($get_jawaban_soal->keyakinan_1=="1"){echo'checked';}else{echo'';} echo'>
                                                                    <label for="radio_yakin_jawaban_1">
                                                                        <span></span>
                                                                        <span class="check"></span>
                                                                        <span class="box"></span> Yakin </label>
                                                                </div>
                                                                <div class="md-radio has-success">
                                                                    <input type="radio" id="radio_yakin_jawaban_0" name="radio_yakin_jawaban" value="0" class="md-radiobtn" '; if($get_jawaban_soal->keyakinan_1=="0"){echo'checked';}else{echo'';} echo'>
                                                                    <label for="radio_yakin_jawaban_0">
                                                                        <span></span>
                                                                        <span class="check"></span>
                                                                        <span class="box"></span> Tidak Yakin </label>
                                                                </div>
                                                            ';   
                                                        echo'</div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group form-md-line-input has-danger">
                                                    <label class="col-md-2 control-label" for="form_control_1">Alasan</label>
                                                    <div class="col-md-10">
                                                        <div class="form-group form-md-radios">
                                                            <div class="md-radio-list">
                                                                <div class="md-radio has-success">
                                                                    <input type="radio" id="radio_a1" name="alasan" value="A" class="md-radiobtn" '; if($value->get_jawaban_soal=="A"){echo'checked';}else{echo'';} echo'>
                                                                    <label for="radio_a1">
                                                                        <span></span>
                                                                        <span class="check"></span>
                                                                        <span class="box"></span> A. '.$value->alasan_1.' </label>
                                                                </div>
                                                                <div class="md-radio has-success">
                                                                    <input type="radio" id="radio_b2" name="alasan" value="B" class="md-radiobtn" '; if($value->get_jawaban_soal=="B"){echo'checked';}else{echo'';} echo'>
                                                                    <label for="radio_b2">
                                                                        <span></span>
                                                                        <span class="check"></span>
                                                                        <span class="box"></span> B. '.$value->alasan_2.' </label>
                                                                </div>
                                                            ';
                                                            if($value->alasan_3==NULL){
                                                                echo'';
                                                            }else{
                                                                if($value->get_jawaban_soal=="C"){
                                                                    echo'<div class="md-radio has-success">
                                                                        <input type="radio" id="radio_c3" name="alasan" value="C" class="md-radiobtn" checked>
                                                                        <label for="radio_c3">
                                                                            <span></span>
                                                                            <span class="check"></span>
                                                                            <span class="box"></span> C. '.$value->alasan_3.' </label>
                                                                    </div>';
                                                                }else{
                                                                    echo'<div class="md-radio has-success">
                                                                        <input type="radio" id="radio_c3" name="alasan" value="C" class="md-radiobtn">
                                                                        <label for="radio_c3">
                                                                            <span></span>
                                                                            <span class="check"></span>
                                                                            <span class="box"></span> C. '.$value->alasan_3.' </label>
                                                                    </div>';
                                                                }
                                                            }
                                                            if($value->alasan_4==NULL){
                                                                echo'';
                                                            }else{
                                                                if($value->get_jawaban_soal=="D"){
                                                                    echo'<div class="md-radio has-success">
                                                                        <input type="radio" id="radio_d4" name="alasan" value="D" class="md-radiobtn" checked>
                                                                        <label for="radio_d4">
                                                                            <span></span>
                                                                            <span class="check"></span>
                                                                            <span class="box"></span> D. '.$value->alasan_4.' </label>
                                                                    </div>';
                                                                }else{
                                                                    echo'<div class="md-radio has-success">
                                                                        <input type="radio" id="radio_d4" name="alasan" value="D" class="md-radiobtn">
                                                                        <label for="radio_d4">
                                                                            <span></span>
                                                                            <span class="check"></span>
                                                                            <span class="box"></span> D. '.$value->alasan_4.' </label>
                                                                    </div>';
                                                                }
                                                            }    
                                                            if($value->alasan_5==NULL){
                                                                echo'';
                                                            }else{
                                                                if($value->get_jawaban_soal=="E"){
                                                                    echo'<div class="md-radio has-success">
                                                                        <input type="radio" id="radio_e5" name="alasan" value="E" class="md-radiobtn" checked>
                                                                        <label for="radio_e5">
                                                                            <span></span>
                                                                            <span class="check"></span>
                                                                            <span class="box"></span> E. '.$value->alasan_5.' </label>
                                                                    </div>';
                                                                }else{
                                                                    echo'<div class="md-radio has-success">
                                                                        <input type="radio" id="radio_e5" name="alasan" value="E" class="md-radiobtn">
                                                                        <label for="radio_e5">
                                                                            <span></span>
                                                                            <span class="check"></span>
                                                                            <span class="box"></span> E. '.$value->alasan_5.' </label>
                                                                    </div>';
                                                                }
                                                            }    
                                                        echo'</div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group form-md-line-input has-danger">
                                                    <label class="col-md-2 control-label" for="form_control_1">Keyakinan Alasan</label>
                                                    <div class="col-md-10">
                                                        <div class="form-group form-md-radios">
                                                            <div class="md-radio-list">
                                                                <div class="md-radio has-success">
                                                                    <input type="radio" id="radio_yakin_jawaban_1a" name="radio_yakin_alasan" value="1" class="md-radiobtn" '; if($get_jawaban_soal->keyakinan_2=="1"){echo'checked';}else{echo'';} echo'>
                                                                    <label for="radio_yakin_jawaban_1a">
                                                                        <span></span>
                                                                        <span class="check"></span>
                                                                        <span class="box"></span> Yakin </label>
                                                                </div>
                                                                <div class="md-radio has-success">
                                                                    <input type="radio" id="radio_yakin_jawaban_0a" name="radio_yakin_alasan" value="0" class="md-radiobtn" '; if($get_jawaban_soal->keyakinan_2=="0"){echo'checked';}else{echo'';} echo'>
                                                                    <label for="radio_yakin_jawaban_0a">
                                                                        <span></span>
                                                                        <span class="check"></span>
                                                                        <span class="box"></span> Tidak Yakin </label>
                                                                </div>
                                                            ';   
                                                        echo'</div>
                                                        </div>
                                                    </div>
                                                </div>
                                                ';
                                            }else{echo'';}
                                        }
                                    }else{
                                        $urut = 1;
                                        foreach ($soal as $key => $value) {
                                            $get_jawaban_soal = $this->Main_model->getSelectedData('detail_ujian c', 'c.jawaban AS pilihan_jawaban,c.keyakinan_1,c.alasan,c.keyakinan_2', array('c.id_siswa_to_modul'=>$id_global,'c.id_soal_to_modul'=>$value->id_soal_to_modul))->row();
                                            if($urut=='1'){
                                                echo '
                                                <input type="hidden" name="soal_to_modul" value="'.$value->id_soal_to_modul.'" />
                                                <input type="hidden" name="soal" value="'.$value->id_soal.'" />
                                                <input type="hidden" name="jawaban" value="'.$value->jawaban.'" />
                                                <input type="hidden" name="alasan_benar" value="'.$value->alasan_benar.'" />
                                                <div class="form-group form-md-line-input has-danger">
                                                    <label class="col-md-2 control-label" for="form_control_1">Pertanyaan</label>
                                                    <div class="col-md-10">
                                                        '.$value->pertanyaan.'
                                                    </div>
                                                </div>
                                                <div class="form-group form-md-line-input has-danger">
                                                    <label class="col-md-2 control-label" for="form_control_1"></label>
                                                    <div class="col-md-10">
                                                        <div class="form-group form-md-radios">
                                                            <div class="md-radio-list">
                                                                <div class="md-radio has-success">
                                                                    <input type="radio" id="radio_a" name="jwb" value="A" class="md-radiobtn" '; if($get_jawaban_soal->pilihan_jawaban=="A"){echo'checked';}else{echo'';} echo'>
                                                                    <label for="radio_a">
                                                                        <span></span>
                                                                        <span class="check"></span>
                                                                        <span class="box"></span> A. '.$value->pilihan_1.' </label>
                                                                </div>
                                                                <div class="md-radio has-success">
                                                                    <input type="radio" id="radio_b" name="jwb" value="B" class="md-radiobtn" '; if($get_jawaban_soal->pilihan_jawaban=="B"){echo'checked';}else{echo'';} echo'>
                                                                    <label for="radio_b">
                                                                        <span></span>
                                                                        <span class="check"></span>
                                                                        <span class="box"></span> B. '.$value->pilihan_2.' </label>
                                                                </div>
                                                            ';
                                                            if($value->pilihan_3==NULL){
                                                                echo'';
                                                            }else{
                                                                if($get_jawaban_soal->pilihan_jawaban=="C"){
                                                                    echo'<div class="md-radio has-success">
                                                                        <input type="radio" id="radio_c" name="jwb" value="C" class="md-radiobtn" checked>
                                                                        <label for="radio_c">
                                                                            <span></span>
                                                                            <span class="check"></span>
                                                                            <span class="box"></span> C. '.$value->pilihan_3.' </label>
                                                                    </div>';
                                                                }else{
                                                                    echo'<div class="md-radio has-success">
                                                                        <input type="radio" id="radio_c" name="jwb" value="C" class="md-radiobtn">
                                                                        <label for="radio_c">
                                                                            <span></span>
                                                                            <span class="check"></span>
                                                                            <span class="box"></span> C. '.$value->pilihan_3.' </label>
                                                                    </div>';
                                                                }
                                                            }
                                                            if($value->pilihan_4==NULL){
                                                                echo'';
                                                            }else{
                                                                if($get_jawaban_soal->pilihan_jawaban=="D"){
                                                                    echo'<div class="md-radio has-success">
                                                                        <input type="radio" id="radio_d" name="jwb" value="D" class="md-radiobtn" checked>
                                                                        <label for="radio_d">
                                                                            <span></span>
                                                                            <span class="check"></span>
                                                                            <span class="box"></span> D. '.$value->pilihan_4.' </label>
                                                                    </div>';
                                                                }else{
                                                                    echo'<div class="md-radio has-success">
                                                                        <input type="radio" id="radio_d" name="jwb" value="D" class="md-radiobtn">
                                                                        <label for="radio_d">
                                                                            <span></span>
                                                                            <span class="check"></span>
                                                                            <span class="box"></span> D. '.$value->pilihan_4.' </label>
                                                                    </div>';
                                                                }
                                                            }    
                                                            if($value->pilihan_5==NULL){
                                                                echo'';
                                                            }else{
                                                                if($get_jawaban_soal->pilihan_jawaban=="E"){
                                                                    echo'<div class="md-radio has-success">
                                                                        <input type="radio" id="radio_e" name="jwb" value="E" class="md-radiobtn" checked>
                                                                        <label for="radio_e">
                                                                            <span></span>
                                                                            <span class="check"></span>
                                                                            <span class="box"></span> E. '.$value->pilihan_5.' </label>
                                                                    </div>';
                                                                }else{
                                                                    echo'<div class="md-radio has-success">
                                                                        <input type="radio" id="radio_e" name="jwb" value="E" class="md-radiobtn">
                                                                        <label for="radio_e">
                                                                            <span></span>
                                                                            <span class="check"></span>
                                                                            <span class="box"></span> E. '.$value->pilihan_5.' </label>
                                                                    </div>';
                                                                }
                                                            }    
                                                        echo'</div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group form-md-line-input has-danger">
                                                    <label class="col-md-2 control-label" for="form_control_1">Keyakinan Jawaban</label>
                                                    <div class="col-md-10">
                                                        <div class="form-group form-md-radios">
                                                            <div class="md-radio-list">
                                                                <div class="md-radio has-success">
                                                                    <input type="radio" id="radio_yakin_jawaban_1" name="radio_yakin_jawaban" value="1" class="md-radiobtn" '; if($get_jawaban_soal->keyakinan_1=="1"){echo'checked';}else{echo'';} echo'>
                                                                    <label for="radio_yakin_jawaban_1">
                                                                        <span></span>
                                                                        <span class="check"></span>
                                                                        <span class="box"></span> Yakin </label>
                                                                </div>
                                                                <div class="md-radio has-success">
                                                                    <input type="radio" id="radio_yakin_jawaban_0" name="radio_yakin_jawaban" value="0" class="md-radiobtn" '; if($get_jawaban_soal->keyakinan_1=="0"){echo'checked';}else{echo'';} echo'>
                                                                    <label for="radio_yakin_jawaban_0">
                                                                        <span></span>
                                                                        <span class="check"></span>
                                                                        <span class="box"></span> Tidak Yakin </label>
                                                                </div>
                                                            ';   
                                                        echo'</div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group form-md-line-input has-danger">
                                                    <label class="col-md-2 control-label" for="form_control_1">Alasan</label>
                                                    <div class="col-md-10">
                                                        <div class="form-group form-md-radios">
                                                            <div class="md-radio-list">
                                                                <div class="md-radio has-success">
                                                                    <input type="radio" id="radio_a1" name="alasan" value="A" class="md-radiobtn" '; if($value->get_jawaban_soal=="A"){echo'checked';}else{echo'';} echo'>
                                                                    <label for="radio_a1">
                                                                        <span></span>
                                                                        <span class="check"></span>
                                                                        <span class="box"></span> A. '.$value->alasan_1.' </label>
                                                                </div>
                                                                <div class="md-radio has-success">
                                                                    <input type="radio" id="radio_b2" name="alasan" value="B" class="md-radiobtn" '; if($value->get_jawaban_soal=="B"){echo'checked';}else{echo'';} echo'>
                                                                    <label for="radio_b2">
                                                                        <span></span>
                                                                        <span class="check"></span>
                                                                        <span class="box"></span> B. '.$value->alasan_2.' </label>
                                                                </div>
                                                            ';
                                                            if($value->alasan_3==NULL){
                                                                echo'';
                                                            }else{
                                                                if($value->get_jawaban_soal=="C"){
                                                                    echo'<div class="md-radio has-success">
                                                                        <input type="radio" id="radio_c3" name="alasan" value="C" class="md-radiobtn" checked>
                                                                        <label for="radio_c3">
                                                                            <span></span>
                                                                            <span class="check"></span>
                                                                            <span class="box"></span> C. '.$value->alasan_3.' </label>
                                                                    </div>';
                                                                }else{
                                                                    echo'<div class="md-radio has-success">
                                                                        <input type="radio" id="radio_c3" name="alasan" value="C" class="md-radiobtn">
                                                                        <label for="radio_c3">
                                                                            <span></span>
                                                                            <span class="check"></span>
                                                                            <span class="box"></span> C. '.$value->alasan_3.' </label>
                                                                    </div>';
                                                                }
                                                            }
                                                            if($value->alasan_4==NULL){
                                                                echo'';
                                                            }else{
                                                                if($value->get_jawaban_soal=="D"){
                                                                    echo'<div class="md-radio has-success">
                                                                        <input type="radio" id="radio_d4" name="alasan" value="D" class="md-radiobtn" checked>
                                                                        <label for="radio_d4">
                                                                            <span></span>
                                                                            <span class="check"></span>
                                                                            <span class="box"></span> D. '.$value->alasan_4.' </label>
                                                                    </div>';
                                                                }else{
                                                                    echo'<div class="md-radio has-success">
                                                                        <input type="radio" id="radio_d4" name="alasan" value="D" class="md-radiobtn">
                                                                        <label for="radio_d4">
                                                                            <span></span>
                                                                            <span class="check"></span>
                                                                            <span class="box"></span> D. '.$value->alasan_4.' </label>
                                                                    </div>';
                                                                }
                                                            }    
                                                            if($value->alasan_5==NULL){
                                                                echo'';
                                                            }else{
                                                                if($value->get_jawaban_soal=="E"){
                                                                    echo'<div class="md-radio has-success">
                                                                        <input type="radio" id="radio_e5" name="alasan" value="E" class="md-radiobtn" checked>
                                                                        <label for="radio_e5">
                                                                            <span></span>
                                                                            <span class="check"></span>
                                                                            <span class="box"></span> E. '.$value->alasan_5.' </label>
                                                                    </div>';
                                                                }else{
                                                                    echo'<div class="md-radio has-success">
                                                                        <input type="radio" id="radio_e5" name="alasan" value="E" class="md-radiobtn">
                                                                        <label for="radio_e5">
                                                                            <span></span>
                                                                            <span class="check"></span>
                                                                            <span class="box"></span> E. '.$value->alasan_5.' </label>
                                                                    </div>';
                                                                }
                                                            }    
                                                        echo'</div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group form-md-line-input has-danger">
                                                    <label class="col-md-2 control-label" for="form_control_1">Keyakinan Alasan</label>
                                                    <div class="col-md-10">
                                                        <div class="form-group form-md-radios">
                                                            <div class="md-radio-list">
                                                                <div class="md-radio has-success">
                                                                    <input type="radio" id="radio_yakin_jawaban_1a" name="radio_yakin_alasan" value="1" class="md-radiobtn" '; if($get_jawaban_soal->keyakinan_2=="1"){echo'checked';}else{echo'';} echo'>
                                                                    <label for="radio_yakin_jawaban_1a">
                                                                        <span></span>
                                                                        <span class="check"></span>
                                                                        <span class="box"></span> Yakin </label>
                                                                </div>
                                                                <div class="md-radio has-success">
                                                                    <input type="radio" id="radio_yakin_jawaban_0a" name="radio_yakin_alasan" value="0" class="md-radiobtn" '; if($get_jawaban_soal->keyakinan_2=="0"){echo'checked';}else{echo'';} echo'>
                                                                    <label for="radio_yakin_jawaban_0a">
                                                                        <span></span>
                                                                        <span class="check"></span>
                                                                        <span class="box"></span> Tidak Yakin </label>
                                                                </div>
                                                            ';   
                                                        echo'</div>
                                                        </div>
                                                    </div>
                                                </div>
                                                ';
                                            }else{echo'';}
                                            $urut++;
                                        }
                                    }
                                    ?>
                                    <br>
                                    <div class="form-group form-md-line-input has-danger">
                                        <label class="col-md-2 control-label" for="form_control_1"></label>
                                        <div class="col-md-10">
                                            <button type="submit" class="btn blue">Simpan Jawaban</button>
                                        </div>
                                    </div>
                                <form>
                            </div>
						</div>
						<div class="col-md-12" >
						<hr><a href="<?php echo base_url()."member_side/master_modul"; ?>" class="btn btn-info" role="button"><i class="fa fa-angle-double-left"></i> Kembali</a></div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
    $(document).ready(function(){
        $.ajaxSetup({
            type:"POST",
            url: "<?php echo site_url(); ?>member/Master/ajax_function",
            cache: false,
        });
        $('.detaildata').click(function(){
        var id = $(this).attr("id");
        var modul = 'detail_soal_ujian';
        var nilai_token = '<?php echo $this->security->get_csrf_hash();?>';
        $.ajax({
            data: {id:id,modul:modul,<?php echo $this->security->get_csrf_token_name();?>:nilai_token},
            success:function(data){
                $('#halaman_soal').html(data);
            }
        });
        });
    });
</script>