<div  class="header">
    <div class="float-left">
        <h2>MITIGASI</h2>
    </div>
    <div class="float-right">
    <?php echo UI::showButtonMode($mode)?>
    </div>
    <div style="clear: both;"></div>
</div>
<div class="body table-responsive">
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
                  echo "<td><a href='".($url=base_url($page_ctrl."/detail/$rowheader1[id_risiko]/$rows[$pk]"))."'>$val</a></td>";
            }elseif($rows1['name']=='isi'){
                echo "<td>".ReadMore($val,$url)."</td>";
            }elseif($rows1['name']=='id_status'){
                echo "<td>".labelstatus($val)."</td>";
            }elseif($rows1['name']=='is_efektif'){
                echo "<td>".labelefektifitas($val)."</td>";
            }else{
                switch ($rows1['type']) {
                    case 'list':
                        echo "<td>".$rows1["value"][$val]."</td>";
                        break;
                    case 'number':
                        echo "<td style='text-align:right'>$val</td>";
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
        }
        if(accessbystatus($rows['id_status'])){
            echo "<td style='text-align:left'>
            ".UI::getButton('edit', $rows[$pk], 'class="btn btn-xs btn-warning"')."
            ".UI::getButton('delete', $rows[$pk], 'class="btn btn-xs btn-danger"')."
            </td>";
        }else{
            echo "<td></td>";
        }
        echo "</tr>";
    }
    if(!$list['rows']){
        echo "<tr><td colspan='".(count($header)+2)."'>Data kosong</td></tr>";
    }
    ?>
    </tbody>
  </table>
  <?php echo $list['total']>$limit || true?UI::showPaging($paging,$page, $limit_arr,$limit,$list):null?>
</div>
