<div class="col-sm-12 tr-col-sm-12-ar">

<?php
$form = UI::createSelect('group_id',$grouparr,$group_id,true,$class='form-control select2',"style='width:100%;' onchange='goSubmit(\"set_value\")'");
echo UI::FormGroup(array(
	'form'=>$form,
	'sm_label'=>1,
	'label'=>'Group'
	));
?>

<?php
ob_start();
if(($fiturs)){ 
?>

<table class="tree-table1 table table-hover dataTable" style="margin-top: 0px">
<tbody>
<tr data-tt-id='0'>
	<td>
		<?php echo UI::createCheckBox("idrootfiturs[0]",0,0,"Pilih Semua",true,"echeck1", "data-id='0'");?>
	</td>
</tr>
<?php 

// dpr($fiturs,1);
foreach($fiturs as $r){ 
	if(is_array($r)){ 
		$id_parent = '0';

		if($r['_parentid'])
			$id_parent = $r['_parentid'];

		$label = $r['text'];
		$id = $r['id'];

		if(!$row['fiturs'][$id] && $r['checked'])
			$row['fiturs'][$id] = $id;
		
		?>

	    <tr data-tt-id='<?=$id?>' data-tt-parent-id='<?=$id_parent?>'>
			<td>
				<?php echo UI::createCheckBox("fiturs[$id]",$id,$row['fiturs'][$id],$label,true,"echeck1 id$id idparent$id_parent", "data-id='$id' data-idparent='$id_parent'");?>
			</td>
	    </tr>
<?php } } ?>
</tbody>
</table>

<script type="text/javascript">
$(function(){
  $(".tree-table1").treetable({ expandable: true });
  $(".tree-table1").treetable('expandAll');
});

	$(".echeck1").change(function(){
		var id = $(this).attr("data-id");		
		var idparent = $(this).attr("data-idparent");	
		var child = $(".idparent"+id);

		if($(this).is(":checked")){
			child.prop("checked", true);
		}else{
			child.prop("checked", false);
		}

		child.change();

	})
</script>
<style type="text/css">
	.table tbody tr td, .table tbody tr th {
    padding: 0px;
    border-top: none;
    border-bottom: none;
}
table.dataTable {
	    margin-top: 7px !important;
	    margin-left: -20px;
	}
</style>
<?php  }

$form=ob_get_contents();
ob_end_clean();
?>

<?php 

echo UI::FormGroup(array(
	'form'=>$form,
	'sm_label'=>1,
	'label'=>'Item'
	));
?>

<?php
$form = UI::getButton('save', null, true);
echo UI::FormGroup(array(
	'form'=>$form,
	'sm_label'=>1,
	));
?>

</div>