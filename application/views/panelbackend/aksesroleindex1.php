<?php

$rowsg = $this->conn->GetArray("select group_id, name from public_sys_group");

$rowsgg = $this->conn->GetArray("select group_id, menu_id, action_id 
from public_sys_group_menu a left join public_sys_group_action b 
on a.group_menu_id = b.group_menu_id
");

$arr = [];
$arr1 = [];
foreach ($rowsgg as $rg) {
    $arr1[$rg['group_id']][$rg['menu_id']] = '✔';
    $arr[$rg['group_id']][$rg['menu_id']][$rg['action_id']] = '✔';
}

?>


<table border='1' class="tableku">
    <thead>
        <tr>
            <th rowspan="2" colspan="3">Menu</th>
            <th rowspan="2">Akses</th>
            <th colspan="<?= count($rowsg) ?>" style="text-align:center">Group</th>
        </tr>
        <tr>
            <?php foreach ($rowsg as $rg) { ?>
                <th style="width: 80px;"><?= $rg['name'] ?></th>
            <?php } ?>
        </tr>
    </thead>
    <tbody>
        <?php
        foreach ($fiturs as $r) {

            if ($r['parent_id'] == 974 && $r['menu_id'] <> 984)
                $r['menu_id'] = 937;

            $ractions = [];
            if ($r['level'] > 1)
                $ractions = $this->conn->GetArray("select * from public_sys_action where name<>'index' and name<>'loginasback' and menu_id = " . $this->conn->escape($r['menu_id']));
        ?>
            <tr>
                <?php if ($r['level'] == 1) { ?>
                    <td rowspan="<?= count($ractions) + 1 ?>" colspan="<?=count($rowsg) + 4?>"><?= $r['label'] ?></td>
                <?php } ?>
                <?php if ($r['level'] == 2) { ?>
                    <td></td>
                    <td rowspan="<?= count($ractions) + 1 ?>" colspan="2"><?= $r['label'] ?></td>
                <?php } ?>
                <?php if ($r['level'] == 3) { ?>
                    <td></td>
                    <td></td>
                    <td rowspan="<?= count($ractions) + 1 ?>"><?= $r['label'] ?></td>
                <?php } ?>

                <?php if ($r['level'] > 1) { ?>
                    <td>view</td>
                    <?php foreach ($rowsg as $rg) { ?>
                        <td><?= $arr1[$rg['group_id']][$r['menu_id']] ?></td>
                    <?php } ?>
                <?php } else { ?>
                <?php } ?>
            </tr>
            <?php if ($ractions)
                foreach ($ractions as $ra) { ?>
                <tr>
                    <?php if ($r['level'] == 2) { ?>
                        <td></td>
                    <?php } ?>
                    <?php if ($r['level'] == 3) { ?>
                        <td></td>
                        <td></td>
                    <?php } ?>
                    <td><?= str_replace("loginasback", "", $ra['name']) ?></td>
                    <?php foreach ($rowsg as $rg) { ?>
                        <td><?= $arr[$rg['group_id']][$r['menu_id']][$ra['action_id']] ?></td>
                    <?php } ?>
                </tr>
            <?php } ?>
        <?php } ?>
    </tbody>
</table>