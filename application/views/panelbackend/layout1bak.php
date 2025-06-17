        <div class="container-fluid">
          <!-- <?php if ($page_title) { ?>
            <div class="block-header">
              <h2>
                <?= $page_title ?>
                <?php if ($sub_page_title) { ?> <small><?= $sub_page_title ?></small> <?php } ?></h2>
            </div>
          <?php } ?> -->

          <!-- <div class="area-header-card mt-3 mb-3">
            <div class="area-left">
              <h2>
                <?= $page_title ?>
                <?php if ($sub_page_title) { ?> <small><?= $sub_page_title ?></small> <?php } ?></h2>
            </div>
            <div class="area-right">
              <?php
              $buttonMenu = "";
              $buttonMenu = UI::showButtonMode($mode, $row[$pk]);

              if ($buttonMenu) {
              ?>
                <div class="float-right">
                  <?= $buttonMenu ?>
                </div>
              <?php } ?>
            </div>
          </div> -->
          <!-- Basic Table -->
          <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
              <div class="card">
                <?php
                $buttonMenu = "";
                $buttonMenu = UI::showButtonMode($mode, $row[$pk]);

                if ($buttonMenu) {
                ?>
                  <div class="header">
                    <div class="float-right">
                      <?= $buttonMenu ?>
                    </div>
                    <div style="clear: both;"></div>
                  </div>
                <?php } ?>
                <div class="body table-responsive">

                  <?php if ($_SESSION[SESSION_APP]['loginas']) { ?>
                    <div class="alert alert-warning">
                      Anda sedang mengakses user lain. <a href="<?= site_url("panelbackend/home/loginasback") ?>" class="alert-link">Kembali</a>.
                    </div>
                  <?php } ?>

                  <?= FlashMsg() ?>
                  <div class="row">
                  <?php echo $content1; ?>
                </div>
                  <div style="clear: both;"></div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <style type="text/css">
          /* table.dataTable {
            clear: both;
            margin-top: -15px !important;
            margin-bottom: 6px !important;
            max-width: none !important;
          } */
        </style>