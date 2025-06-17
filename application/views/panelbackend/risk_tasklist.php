<?php
if($list['rows']){ 
?>
<ul class="list-task">
<?php foreach($list['rows'] as $r){ ?>
    <li>
        <a href="<?=site_url($r['url'])?>" class=" btn-sm waves-block">
            <div class="icon-circle text-<?=$r['bg']?>">
                <i class="material-icons"><?=$r['icon']?></i>
            </div>
            <div class="menu-info">
                <p class="info"><?=$r['info']?></p>
                <p>
                    <i class="material-icons">access_time</i> <?=$r['time']?> 
                    <i class="material-icons">account_circle</i> <?=$r['user']?>
                </p>
            </div>
        </a>
    </li>
<?php } ?>
</ul>
  <?php echo $list['total']>$limit || true?UI::showPaging($paging,$page, $limit_arr,$limit,$list):null?>
<?php } else{ ?>
<i>Tidak ada task</i>
<?php
}
?>