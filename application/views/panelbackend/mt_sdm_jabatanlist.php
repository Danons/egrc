<?php
$rowmenu = $this->auth->GetParentMenu($page_ctrl);
$sidebarmenu = $this->auth->GetSideBar((int)$rowmenu['menu_id'], null, "<ul class=\"nav flex-column\">", $child_active, $pagetemp);
?>

<div id="layout1" class="container-fluid content <?php if ($_SESSION[SESSION_APP]['toggle'] || !$sidebarmenu) { ?> minimized <?php } else { ?> expanded <?php } ?>">
    <div class="row">
        <?php
        if ($sidebarmenu) { ?>
            <nav class="d-md-block bg-primary text-white sidebar collapse">

                <div class="container-area-decoration">

                </div>

                <div class="position-sticky pt-3">
                    <?php
                    $child_active = '';
                    $pagetemp = '';
                    if ($page_ctrl == 'panelbackend/kpi_config' || $page_ctrl == 'panelbackend/kpi_target') {
                        $page_ctrl = "panelbackend/kpi";
                        $pagetemp = base_url($page_ctrl);
                    }

                    if ($page_ctrl == 'panelbackend/kpi_target_realisasi') {
                        $page_ctrl = "panelbackend/kpi_target_unit";
                        $pagetemp = base_url($page_ctrl);
                    }

                    if ($page_ctrl == 'panelbackend/pemeriksaan') {
                        $page_ctrl = "panelbackend/pemeriksaan/index/" . $jenis;
                        $pagetemp = base_url($page_ctrl);
                    }
                    ?>
                    <h4 class="title-sidebar"><?= $rowmenu['label'] ?></h4>
                    <?= $sidebarmenu ?>
                    <br />
                    <div class="icon-expand-minimize-sidebar" onclick="goToggle()">
                        <div>
                            <i class="material-icons">remove</i>
                        </div>
                    </div>

                </div>

            </nav>

            <div class="overlay-sidebar-mobile">
                <div class="sidebar-mobile">
                    <h4 class="title-sidebar"><?= $rowmenu['label'] ?></h4>
                    <?= $sidebarmenu; ?>
                    <br />
                    <div class="icon-expand-minimize-sidebar" onclick="goToggle()">
                        <div>
                            <i class="material-icons">remove</i>
                        </div>
                    </div>
                </div>
                <div class="overlay-sidebar-right"></div>
            </div>
        <?php } ?>

        <main class="ms-sm-auto main-content">

            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center <?= ($broadcrum1 && count($broadcrum1) > 1 ? "pt-1" : "pt-3") ?> pb-2 mb-3 ">
                <div class="new-content-title" <?= (!$sidebarmenu ? "style='padding-left:0px !important;'" : "") ?>>

                    <?php if ($sidebarmenu) { ?>
                        <div class="icon-expand-minimize-sidebar icon-expanded" onclick="goToggle()">
                            <div>
                                <i class="material-icons">apps</i>
                            </div>
                        </div>
                    <?php } ?>

                    <?php

                    if (!$broadcrum)
                        $broadcrum = $rowheader['broadcrumscorecard'];
                    if (($broadcrum)) {
                        if (!$page_title) {
                            $sub_page_title = $page_title;
                            $page_title = $broadcrum[count($broadcrum) - 1]['label'];
                            unset($broadcrum[count($broadcrum) - 1]);
                        }

                        $broadcrum1 = array_merge(array(array('url' => site_url($page_ctrl), 'label' => '<span class="material-icons" style="font-size:19px !important;">home</span>')), $broadcrum);

                        $broadcrum1[] = array('url' => null, 'label' => $page_title);
                    }

                    if ($page_title) { ?>
                        <h4 class="h4">
                            <?= $page_title ?>
                            <?php if ($sub_page_title) { ?><br /><small style="color: #6b778c; font-size: 14px; font-weight: 500;"><?= $sub_page_title ?></small> <?php } ?>
                        </h4>
                    <?php } ?>
                    <?php
                    if ($broadcrum1) {
                    ?>
                        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb" style="margin-bottom: 0;">
                                    <?php
                                    foreach ($broadcrum1 as $v) {
                                        if ($v['url']) { ?>
                                            <li class="breadcrumb-item"><a href="<?= $v['url'] ?>"><?= $v['label'] ?></a></li>
                                        <?php } else { ?>
                                            <li class="breadcrumb-item"><?= $v['label'] ?></li>
                                    <?php }
                                    } ?>
                                </ol>
                            </nav>
                        </div>
                    <?php }
                    ?>
                </div>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <?= UI::createExportImport() ?>
                    <?php
                    $buttonMenu = "";
                    if (!$nobutton)
                        $buttonMenu = UI::showButtonMode($mode, $row[$pk]);
                    if ($buttonMenu || $addbutton) {
                        echo $addbutton . $buttonMenu;
                    }
                    ?>
                </div>
            </div>
            <div class="row">
                <?php if ($_SESSION[SESSION_APP]['loginas']) { ?>
                    <div class="alert alert-warning">
                        Anda sedang mengakses user lain. <a href="<?= site_url("panelbackend/home/loginasback") ?>" class="alert-link">Kembali</a>.
                    </div>
                <?php } ?>

                <?= FlashMsg() ?>

                <div class="col-sm-12" id="contentajax">

                    <div style="display: flex;">
                        <span style="
    font-weight: bold;
    padding-top: 7px;
">Tgl. Efektif : </span>
                        <input type="text" name="tgl_efektif" id="tgl_efektif" value="<?= $tgl_efektif ?>" class="form-control datepicker" maxlength="10" size="10" style="width:100px;display:inline" onchange="goSubmit('filter')" autocomplete="off">
                    </div>
                    <table class="table table-hover dataTable treetable">
                        <thead>
                            <?= UI::showHeader($header, $filter_arr, $list_sort, $list_order, true, false, false) ?>
                        </thead>
                        <tbody>
                            <?php
                            $i = $page;
                            foreach ($list['rows'] as $rows) {
                                $i++;
                            ?>
                                <tr data-tt-id='<?= $rows['id'] ?>' data-tt-parent-id='<?= $rows['id_parent'] ?>'>


                                <?php
                                foreach ($header as $rows1) {
                                    $val = $rows[$rows1['name']];
                                    if ($rows1['name'] == 'nama') {


                                        if ($rows['level'] <> $rows['levelsdm'] && $rows['levelsdm']) {
                                            $paddingleft = ($rows['levelsdm'] - $rows['level']) * 19;
                                            echo "<td " . ($paddingleft ? "style='padding-left:calc(1em + " . $paddingleft . "px) !important'" : "") . "><a href='" . ($url = base_url($page_ctrl . "/detail/$rows[$pk]")) . "'>$val</a></td>";
                                        } else {
                                            echo "<td><a href='" . ($url = base_url($page_ctrl . "/detail/$rows[$pk]")) . "'>$val</a></td>";
                                        }
                                    } elseif ($rows1['name'] == 'isi') {
                                        echo "<td>" . ReadMore($val, $url) . "</td>";
                                    } else {
                                        switch ($rows1['type']) {
                                            case 'list':
                                                echo "<td>" . $rows1["value"][$val] . "</td>";
                                                break;
                                            case 'number':
                                                echo "<td style='text-align:right'>$val</td>";
                                                break;
                                            case 'date':
                                                echo "<td>" . Eng2Ind($val, false) . "</td>";
                                                break;
                                            case 'datetime':
                                                echo "<td>" . Eng2Ind($val) . "</td>";
                                                break;
                                            default:
                                                echo "<td>$val</td>";
                                                break;
                                        }
                                    }
                                }
                                echo "<td style='text-align:left'>";
                                // echo $rows['level'];
                                // echo "-";
                                // echo $rows['levelsdm'];
                                echo UI::showMenuMode('inlist', $rows[$pk]);
                                echo "</td>";
                                echo "</tr>";
                            }
                            if (!$list['rows']) {
                                echo "<tr><td colspan='" . (count($header) + 2) . "'>Data kosong</td></tr>";
                            }
                                ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
</div>


<?php if ($sidebarmenu) { ?>
    <script>
        function goToggle() {
            // alert('asd');
            $.ajax({
                url: "<?= site_url("panelbackend/ajax/set_toggle") ?>",
                data: {
                    collapse: ($("#layout1").hasClass("minimized") ? 0 : 1)
                }
            });
        }
    </script>
<?php } ?>