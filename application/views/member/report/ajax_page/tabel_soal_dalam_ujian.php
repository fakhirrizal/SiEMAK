<div class="portlet box yellow">
    <div class="portlet-title">
        <div class="caption">
            <i class="icon-list"></i>Daftar Soal </div>
        <div class="tools">
            <a href="javascript:;" class="expand" data-original-title="" title=""> </a>
        </div>
    </div>
    <div class="portlet-body" style="display: none;">
        <div class="panel-group accordion" id="accordion3">
            <?php
            foreach ($soal as $key => $value) {
            ?>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a class="accordion-toggle accordion-toggle-styled collapsed" data-toggle="collapse" data-parent="#accordion3" href="#collapse_3_<?= $value->id_soal; ?>" aria-expanded="false"> <?= $value->pertanyaan; ?> </a>
                    </h4>
                </div>
                <div id="collapse_3_<?= $value->id_soal; ?>" class="panel-collapse collapse" aria-expanded="false">
                    <div class="panel-body">
                        <h4><u>Jawaban</u></h4>
                        <?php
                        if($value->pilihan_1!=NULL){
                            echo'<p><b>A</b>           '.$value->pilihan_1.'</p>';
                        }
                        if($value->pilihan_2!=NULL){
                            echo'<p><b>B</b>           '.$value->pilihan_2.'</p>';
                        }
                        if($value->pilihan_3!=NULL){
                            echo'<p><b>C</b>           '.$value->pilihan_3.'</p>';
                        }
                        if($value->pilihan_4!=NULL){
                            echo'<p><b>D</b>           '.$value->pilihan_4.'</p>';
                        }
                        if($value->pilihan_5!=NULL){
                            echo'<p><b>E</b>           '.$value->pilihan_5.'</p>';
                        }
                        ?>
                        <hr>
                        <p>Jawaban Anda : <b><?= $value->pilihan_jawaban; ?></b> <?php if($value->pilihan_jawaban==$value->jawaban){echo '(Benar)';}else{echo '(Salah, jawaban yang benar adalah <b>'.$value->jawaban.'</b>)';} ?></p>
                        <p>Keyakinan Anda terhadap jawaban : <?php if($value->keyakinan_1){echo'Yakin';}else{echo'Tidak Yakin';} ?></p>
                        <hr>
                        <h4><u>Alasan</u></h4>
                        <?php
                        if($value->alasan_1!=NULL){
                            echo'<p><b>A</b>           '.$value->alasan_1.'</p>';
                        }
                        if($value->alasan_2!=NULL){
                            echo'<p><b>B</b>           '.$value->alasan_2.'</p>';
                        }
                        if($value->alasan_3!=NULL){
                            echo'<p><b>C</b>           '.$value->alasan_3.'</p>';
                        }
                        if($value->alasan_4!=NULL){
                            echo'<p><b>D</b>           '.$value->alasan_4.'</p>';
                        }
                        if($value->alasan_5!=NULL){
                            echo'<p><b>E</b>           '.$value->alasan_5.'</p>';
                        }
                        ?>
                        <hr>
                        <p>Alasan Anda : <b><?= $value->alasan; ?></b> <?php if($value->alasan==$value->alasan_benar){echo '(Benar)';}else{echo '(Salah, alasan yang benar adalah <b>'.$value->alasan_benar.'</b>)';} ?></p>
                        <p>Keyakinan Anda terhadap alasan : <?php if($value->keyakinan_2){echo'Yakin';}else{echo'Tidak Yakin';} ?></p>
                    </div>
                </div>
            </div>
            <?php } ?>
        </div>
    </div>
</div>