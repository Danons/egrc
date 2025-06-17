
  <table class="table table-hover tree-tabletable tree-table">
    <tbody>
    <?php
    $i = $page;
    foreach($list['rows'] as $rows){
        $i++;
        echo "<tr  data-tt-id='$rows[id_kategori]' data-tt-parent-id='$rows[id_kategori_parent]'>";
        foreach($header as $rows1){
            $val = $rows[$rows1['name']];
            if($rows1['name']=='nama' && ($rows['id_kategori_jenis']=='1' or $rows['id_kategori_jenis']=='2' or $rows['id_kategori_jenis']=='3')){
                echo "<td><a href='".($url=base_url($page_ctrl."/detail/$rows[$pk]"))."'>$val</a></td>";   
            }elseif($rows1['name']=='isi'){
                echo "<td>".ReadMore($val,$url)."</td>";
            }else{
                switch ($rows1['type']) {
                    case 'list':
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
        }
        $add = array();

        if(!$rows['id_kategori_jenis']){
            $add = array(
                '<li><a href="'.site_url('panelbackend/mt_kategori/add/'.$rows['id_kategori']).'" class="waves-effect"><span class="glyphicon glyphicon-plus"></span> Add Sub</a> </li>'
            );
        }

        echo "<td style='text-align:right'  width='80px'>
        ".UI::showMenuMode('inlist', $rows[$pk],false,'','',null,null,$add)."
        </td>";
        echo "</tr>";
    }
    if(!count($list['rows'])){
        echo "<tr><td colspan='".(count($header)+2)."'>Data kosong</td></tr>";
    }
    ?>
    </tbody>
  </table>
<link href="<?=base_url()?>assets/plugins/treetable/jquery.treetable.theme.default.css" rel="stylesheet">
<link href="<?=base_url()?>assets/plugins/treetable/jquery.treetable.css" rel="stylesheet">
<script src="<?=base_url()?>assets/plugins/treetable/jquery.treetable.js"></script>