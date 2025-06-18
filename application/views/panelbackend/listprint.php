
<table class="tableku" border="1">
    <thead>
          <tr>
            <th style="width:10px">No</th>
            <input type='hidden' name='list_sort' id='list_sort'>
            <input type='hidden' name='list_order' id='list_order'>
            <?php foreach($header as $rows){
                if($rows['type']=='list' or $rows['type']=='implodelist'){
                       echo "<th style='max-width:$rows[width]'>$rows[label]</th>";
                }else{
                   echo "<th style='text-align:center; max-width:$rows[width];'>$rows[label]</th>";
                }
            }
            ?>
          </tr>
    </thead>
    <tbody>
        <?php
        $i = $page;
        foreach($list['rows'] as $rows){
            
        	$i++;
        	echo "<tr>";
        	echo "<td align='center'>$i</td>";
        	foreach($header as $rows1){
        		$val = $rows[$rows1['name']];
                switch ($rows1['type']) {
                    case 'list':
                        if($rows1['name']=='id_status')
                            echo "<td><span class='badge bg-{$rows['status']}'>".$rows1["value"][$val]."</span></td>";
                        else
                            echo "<td>".$rows1["value"][$val]."</td>";
                        break;
                    case 'number':
                        echo "<td style='text-align:right'>".rupiah($val)."</td>";
                    break;
                    case 'date':
                        echo "<td>".Eng2Ind($val,false)."</td>";
                        break;
                    case 'datetime':
                        echo "<td>".Eng2Ind($val)."</td>";
                        break;
                    default :
                        echo "<td>$val</td>";
                        break;
                }
        	}
        	echo "</tr>";
        }
        if(!count($list['rows'])){
            echo "<tr><td colspan='".(count($header)+1)."'>Data kosong</td></tr>";
        }
        ?>
        <!--<tfoot>
            <tr>
                <td colspan="4" style="text-align: center;"><b>Total</b></td>
                <?php foreach($tot as $k=>$v){ ?>
                    <td style="text-align: right;"><b><?=(($k<>'harga_jual')?rupiah($v):null)?></b></td>
                <?php } ?>
            </tr>
        </tfoot>-->
    </tbody>
</table>