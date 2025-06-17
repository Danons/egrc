  <table class="table table-hover dataTable">
    <thead>
    <?=UI::showHeader($header, $filter_arr, $list_sort, $list_order, ($list['total']>$limit || true))?>
    </thead>
    <tbody>
    <?php
    $i = $page;
    foreach($list['rows'] as $rows){
    	$i++;
    	echo "<tr>";
    	echo "<td>$i</td>";
    	foreach($header as $rows1){
    		$val = $rows[$rows1['name']];
            if($rows1['name']=='nama'){
                echo "<td>$val ($rows[id_unit])</td>";   
            // }elseif($rows1['name']=='isi'){
            //     echo "<td>".ReadMore($val,$url)."</td>";
            }else{
                switch ($rows1['type']) {
                    case 'list':
                        echo "<td>".$rows1["value"][$val]."</td>";
                        break;
                    default :
                        echo "<td>$val</td>";
                        break;
                }
            }
    	}
    	echo '<td style="text-align:right"><input class="btn  btn-sm btn-sm btn-primary" value="LOGIN" onclick="return goLogin(\''.$rows['user_id'].'\',\''.$rows['id_jabatan'].'\',\''.$rows['group_id'].'\')" type="button"></td>';
    	echo "</tr>";
    }
    if(!$list['rows']){
        echo "<tr><td colspan='".(count($header)+2)."'>Data kosong</td></tr>";
    }
    ?>
    </tbody>
  </table>
  <?php echo $list['total']>$limit || true?UI::showPaging($paging,$page, $limit_arr,$limit,$list):null?>
<input type="hidden" name="user_id" id="user_id"/>
<input type="hidden" name="id_jabatan" id="id_jabatan"/>
<input type="hidden" name="group_id" id="group_id"/>
  <script>
function goLogin(user_id, id_jabatan, group_id){
    $("#act").val("loginas");
    $("#user_id").val(user_id);
    $("#id_jabatan").val(id_jabatan);
    $("#group_id").val(group_id);
    $("#main_form").submit();
}
  </script>