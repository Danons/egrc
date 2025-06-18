<?php if(Access("edit","panelbackend/risk_scorecard")){ ?>
<div  class="header">
  <div class="float-left">
<?php

echo $from = UI::InputFile(
array(
    "nameid"=>"proses",
    "edit"=>true,
  "add"=>"onchange='goSubmit(\"save_file\")' style='width:auto;display:inline;'"
    )
);
echo " <label class='badge bg-info'>pdf. Ukuran Maksimal ".(round($configfile['max_size']/1000))." mb </label>";
?>

  </div>
  <div class="float-right">
  </div>
    <div style="clear: both;"></div>
</div>
<?php } ?>
<div class="body table-responsive" id="body-risiko">
	<iframe src="<?=site_url("panelbackend/risk_scorecard/preview_file/$rowheader[id_scorecard]/1")?>" width='100%' height='500px' style="border:none"></iframe>
</div>