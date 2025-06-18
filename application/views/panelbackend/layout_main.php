<div class="container-fluid content">
  <div class="row">
    <nav class="d-md-block bg-primary text-white sidebar collapse">
      <div class="position-sticky pt-3">
        <?php
        $rowmenu = $this->auth->GetParentMenu($page_ctrl);
        ?>
        <h4 class="title-sidebar"><?= $rowmenu['label'] ?></h4>
        <?= $this->auth->GetSideBar((int)$rowmenu['menu_id']); ?>
      </div>
    </nav>

    <main class="ms-sm-auto main-content">

      <?php if (($broadcrum)) {
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
        <div>
          <?php if ($page_title) { ?>
            <h1 class="h2">
              <?= $page_title ?>
              <?php if ($sub_page_title) { ?><br /><small style="color: #6b778c; font-size: 14px; font-weight: 500;"><?= $sub_page_title ?></small> <?php } ?>
            </h1>
          <?php } ?>
        </div>
        <div class="btn-toolbar mb-2 mb-md-0">
          <?php
          $buttonMenu = "";
          $buttonMenu = UI::showButtonMode($mode, $row[$pk]);
          if ($buttonMenu) {
            echo $buttonMenu;
          }
          ?>
        </div>
      </div>
      <div class="table-responsive row">
        <div class="col-sm-12">
          <?= $content1 ?>
        </div>
      </div>
    </main>
  </div>
</div>