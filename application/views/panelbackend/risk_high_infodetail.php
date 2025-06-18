<div  class="header">
    <div class="float-left">
    <h2>Informasi Data Pendukung
    </h2>

    </div>
    <div class="float-right">
    <?php echo UI::showButtonMode($mode,$row[$pk])?>
    <?php 
    if(($this->access_role['view_all'] or $this->access_role['view_all_unit']) && $row['is_lock']=='1'){
    ?>
    <button type="button" class="btn  btn-sm btn-warning" onclick="goSubmitValue('unlock',<?=$row[$pk]?>)" ><span class="bi bi-lock"></span> Unlock</button>
    <?php
    }
    ?>
    </div>
    <div style="clear: both;"></div>
</div>
<div class="body table-responsive">

<?php 
$from = UI::createTextEditor('keterangan',$row['keterangan'],'','',$edited,$class='form-control contents',"");
echo UI::createFormGroup($from, $rules["keterangan"], "keterangan", "", true);
?>
				

<?php 
$from = UI::showButtonMode("save", null, $edited);
echo UI::createFormGroup($from, null, null, null, true);
?>
</div>
<br/>
<script src="<?=base_url()?>assets/js/tinymce/tinymce.min.js"></script>
<script src="<?=base_url()?>assets/js/cms.js"></script>