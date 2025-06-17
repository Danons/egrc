<div class="row">
<?php if(count($breadcrumb)>1){ ?>
    <div class="breadcrumbs">

    <div class="pull-left">
    <?php if($page_title){ ?>
      <h1>
          <?=$page_title?>
          <?=$rowheader['nama']?>
      </h1>
    <?php } ?>
    <br/>
    <br/>
    <ol class="breadcrumb" style="margin-top: -8px;">
        <li>
            <a href="<?=site_url("panelbackend/mt_kategori")?>">Kategori</a>
        </li>
        <?php
        foreach($kategoriarr as $r){ 
        ?>
        <li>
          <?=strtoupper($r['nama'])?>
        </li>
        <?php } ?>
        <li>
        	<?=$row['kode']?>
        	<?=strtoupper($row['nama'])?>
        </li>
    </ol>
</div>
    <div class="pull-right" style="width: 35%;text-align: right;">
        <?=UI::showButtonMode($mode, $row[$pk]);?>
    </div>
    <div style="clear: both;"></div>
    </div>
<?php } ?>

<div style="clear:both;"></div>

<?php  if(is_array($_SESSION[SESSION_APP]['loginas']) && count($_SESSION[SESSION_APP]['loginas'])){ ?>
<div class="alert alert-warning">
  Anda sedang mengakses user lain. <a href="<?=base_url("panelbackend/home/loginasback")?>" class="alert-link">Kembali</a>.
</div>
<?php }?>

<?=FlashMsg()?>


<b>Tabel Kriteria Tambahan</b><br/><br/>
<table class="table">
	<thead>
		<tr>
			<?php foreach($rowsattribute as $r){ 
			?>
				<th <?php if($r['data_type']==2){ echo "style='width:150px'"; }else{ echo "style='width:auto'"; } ?>>
					<a href="<?=site_url('panelbackend/mt_kriteria_attribute/edit/'.$row['id_kriteria'].'/'.$r['id_kriteria_attribute'])?>"><span class="glyphicon glyphicon-pencil"></span></a>
					<a href="<?=site_url('panelbackend/mt_kriteria_attribute/delete/'.$row['id_kriteria'].'/'.$r['id_kriteria_attribute'])?>"><span class="glyphicon glyphicon-trash"></span></a>
					<br/>
					<?=$r['nama']?>
				</th>
			<?php } ?>
			<th style="text-align: right;"><a href="<?=site_url('panelbackend/mt_kriteria_attribute/add/'.$row['id_kriteria'])?>" class="btn-xs btn btn-primary"><span class="glyphicon glyphicon-plus"></span></a></th>
		</tr>
	</thead>
	<?php
	$from = function($val=null, $edited, $k=0, $ci){
		$from = null;
		foreach($ci->data['rowsattribute'] as $r){
			$from .= "<td style='position:relative'>";

			$from .= UI::createTextBox("kriteria_detail[$k][$r[id_kriteria_attribute]]",$val[$r['id_kriteria_attribute']],'200','100',$edited,'form-control '.($r['data_type']=='2'?'datepicker':''),"style='width:100%'");

			$from .= "</td>";
		}

        $from .= UI::createTextHidden("kriteria_detail[$k][id_kriteria_detail]",$val['id_kriteria_detail'],$edited);

		$from .= "<td style='position:relative; text-align:right'>";

		return $from;
	};

	echo UI::AddFormTable('kriteria_detail', $row['kriteria_detail'], $from, $edited, $this);
	?>
</table>

<div style="clear: both;"></div>
<?php if(count($row['kriteria_detail'])){ ?>
<div style="text-align: right;"><?=UI::showButtonMode("save", null, $edited);?></div>
<?php } ?>
</div>
<style type="text/css">
    table.dataTable {
    clear: both;
    margin-bottom: 6px !important;
    max-width: none !important;
}
.table th, .table td{
    padding:5px !important;
    font-size: 12px;
}
</style>