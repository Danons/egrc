
<div class="row">
<div class="col-sm-6">

<?php
/*$from = UI::createTextBox('nid',$row['nid'],'100','100',$edited,$class='form-control ',"style='width:100%'");
echo UI::createFormGroup($from, $rules["nid"], "nid", "NID");*/
?>

<?php
$from = UI::createTextBox('name',$row['name'],'200','100',$edited,$class='form-control ',"style='width:100%'");
echo UI::createFormGroup($from, $rules["name"], "name", "Name");
?>

<?php
// $from = UI::createSelect('id_jabatan',$jabatanarr,$row['id_jabatan'],$edited,$class='form-control ',"data-ajax--data-type=\"json\" data-ajax--url=\"".base_url('panelbackend/ajax/listjabatan')."\" style='width:100%;'");
// echo UI::createFormGroup($from, $rules["id_jabatan"], "id_jabatan", "Jabatan");
?>

<?php
$from = UI::createTextBox('username',$row['username'],'100','100',$edited,$class='form-control ',"style='width:100%'");
echo UI::createFormGroup($from, $rules["username"], "username", "NID/Username");
?>

<?php
$from = UI::createTextBox('email',$row['email'],'100','100',$edited,$class='form-control ',"style='width:100%'");
echo UI::createFormGroup($from, $rules["email"], "email", "Email");
?>

<?php
// $from = UI::createSelect('group_id',$publicsysgrouparr,$row['group_id'],$edited,$class='form-control ',"style='width:100%;'");
// echo UI::createFormGroup($from, $rules["group_id"], "group_id", "Group ID");
?>

<?php
$from = UI::createCheckBox('is_notification',1,$row['is_notification'],"Notifikasi Email",$edited,$class='iCheck-helper ',"");
echo UI::createFormGroup($from, $rules["is_notification"], "is_notification");
?>

<?php
if(!$row['tgl_mulai_aktif'])$row['tgl_mulai_aktif'] = $tgl_efektif;
// $from = UI::createCheckBox('is_active',1,$row['is_active'],"Active",$edited,$class='iCheck-helper ',"");
$from = UI::createTextBox('tgl_mulai_aktif',$row['tgl_mulai_aktif'],'100','100',$edited,$class='form-control datepicker',"style='width:100%' placeholder='Mulai...'");
$from .= 's/d'.UI::createTextBox('tgl_selesai_aktif',$row['tgl_selesai_aktif'],'100','100',$edited,$class='form-control datepicker',"style='width:100%' placeholder='Selesai...'");
echo UI::createFormGroup($from, $rules["is_active"], "is_active", "Durasi Aktif");
?>


<?php if(!$edited){?>
</div>
<div class="col-sm-6">
<?php } ?>

<table class="table">
	<thead>
	<tr>
		<th style='width:200px'>Group</th>
		<th style='width:auto'>Jabatan</th>
		<th style='width:0px'></th>
	</tr>
</thead>
<tbody>
	<?php 
	$from = function($val=null, $edited, $k=0, $ci){
		$jabatanarr = $ci->data['jabatanarr'];
		$publicsysgrouparr = $ci->data['publicsysgrouparr'];

		$from .= "<td >";
		$from .= UI::createSelect("group[$k][group_id]",$publicsysgrouparr,$val['group_id'],$edited,$class='form-control ',"style='width:100%;'");
		$from .= "</td>";
		$from .= "<td >";
		$from .= UI::createSelect("group[$k][id_jabatan]",$jabatanarr,$val['id_jabatan'],$edited,$class='form-control ',"data-ajax--data-type=\"json\" data-ajax--url=\"".base_url('panelbackend/ajax/listjabatan')."\"");
		$from .= "</td>";
		$from .= "<td align='right'>";

		return $from;
	};

	echo UI::AddFormTable('group', $row['group'], $from, $edited, $this);
	?>
</tbody>
</table>
<?php if($edited){?>
</div>
<div class="col-sm-6">
<?php } ?>

<?php if($edited){?>
<?php if($row[$this->pk]){ ?>
<?php
$from = "Kosongkan password apabila Anda tidak ingin merubahnya.";
echo UI::createFormGroup($from, null, null, "");
?>
<?php } ?>
<?php
$from = UI::createTextPassword('password','','','',$edited,$class='form-control ');
echo UI::createFormGroup($from, $rules["password"], "password", "Password");
?>
<?php
$from = UI::createTextPassword('confirmpassword','','','',$edited,$class='form-control');
echo UI::createFormGroup($from, $rules["confirmpassword"], "confirmpassword", "Confirm Password");
?>
<?php }?>

<?=UI::createFormGroup(UI::showButtonMode("save", null, $edited),null,null,null)?>
</div>
</div>

<?php if($edited){ ?>
<script type="text/javascript">
	$("#nid").on("select2:select", function (e) {
		$("#username").val($("#nid").val());
		$("#name").val($("#nid option:selected").text());
	});
</script>
<?php } ?>
