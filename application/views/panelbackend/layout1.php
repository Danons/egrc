<div id="layout1" class="container-fluid content <?php if ($_SESSION[SESSION_APP]['toggle']) { ?> minimized <?php } else { ?> expanded <?php } ?>">
  <div class="row">
    <nav class="d-md-block bg-primary text-white sidebar collapse">
      <!-- <nav class="d-md-block text-white sidebar collapse"> -->

      <div class="container-area-decoration" style="background-image: url('<?php echo base_url() ?>assets/images/decor.png');">

      </div>

      <div class="position-sticky pt-3">
        <?php
        $child_active = '';
        $pagetemp = '';
        if ($page_ctrl == 'panelbackend/kpi_config' || $page_ctrl == 'panelbackend/kpi_target') {
          $page_ctrl = "panelbackend/kpi";
        }

        if ($page_ctrl == 'panelbackend/kpi_target_realisasi') {
          $page_ctrl = "panelbackend/kpi_target_unit";
        }

        if ($page_ctrl == 'panelbackend/pemeriksaan') {
          $page_ctrl = "panelbackend/pemeriksaan/index/" . $jenis;
        }

        if ($page_ctrl == 'panelbackend/mt_kriteria') {
          $page_ctrl = "panelbackend/mt_kriteria/index/" . $id_kategori;
        }
        $pagetemp = base_url($page_ctrl);

        $rowmenu = $this->auth->GetParentMenu($page_ctrl);
        ?>
        <h4 class="title-sidebar"><?= $rowmenu['label'] ?></h4>
        <?= $sidebarmenu = $this->auth->GetSideBar(
          (int)$rowmenu['menu_id'],
          null,
          "<ul class=\"nav  flex-column\">",
          $child_active,
          $pagetemp
        ); ?>
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

    <main class="ms-sm-auto main-content">

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

        $broadcrum1[] = array('url' => null, 'label' => null);
        if ($broadcrum1) {
      ?>
          <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3">
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb" style="margin-bottom: 0;">
                <?php
                foreach ($broadcrum1 as $v) { ?>
                  <li class="breadcrumb-item"><a href="<?= $v['url'] ?>"><?= $v['label'] ?></a></li>
                <?php } ?>
              </ol>
            </nav>
          </div>
      <?php }
      } ?>
      <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center <?= ($broadcrum1 && count($broadcrum1) > 1 ? "pt-1" : "pt-3") ?> pb-2 mb-3 ">
        <div class="new-content-title">
          <div class="icon-expand-minimize-sidebar icon-expanded" onclick="goToggle()">
            <div>
              <i class="material-icons">apps</i>
            </div>
          </div>

          <?php if ($page_title) { ?>
            <h4 class="h4" style="display: flex;">
              <?= $page_title ?>
              <?php if ($sub_page_title) { ?><br /><small style="color: #6b778c; font-size: 14px; font-weight: 500;"><?= $sub_page_title ?></small> <?php } ?>
            </h4>
          <?php } ?>
        </div>
        <div class="btn-toolbar mb-2 mb-md-0">
          <?php
          $buttonMenu = "";
          if (!$nobutton)
            $buttonMenu = UI::showButtonMode($mode, $row[$pk], $edited);
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

        <div class="col-sm-12" id="contentajax">

          <?= FlashMsg() ?>
          <?= $content1 ?>
        </div>
      </div>
    </main>
  </div>
</div>


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