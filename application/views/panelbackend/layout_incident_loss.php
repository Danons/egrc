        <div class="container-fluid">
          <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
              <div class="card">
                <div class="header">
                    <div class="float-right" style="top: 17px;position: absolute;right: 70px;">

                            &nbsp;&nbsp;&nbsp; <a href="<?= site_url('panelbackend/lost_incident/index') ?>" class='btn  btn-sm btn-xs btn-default'><span class="bi bi-list"></span> List Temuan / Kejadian</a>
                    
                    </div>

                    <div style="font-size: 16px; margin-bottom: 10px;"><?=$rowheader['kronologi']?></div>
                    
                    <small style="color: #999;display: block;"><b>Tanggal : </b><?=Eng2Ind(($rowheader['waktu']?explode(" ",$rowheader['waktu'])[0]:null))?> <b>Lokasi : </b><?=$rowheader['lokasi']?></small>

                    <ul class="header-dropdown">
                        <li class="dropdown">
                            <a href="javascript:void(0);" class="dropdown" data-bs-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                <i class="material-icons">more_vert</i>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-right">
                                <?php
                                if ($this->access_role['edit'] && Access('edit', 'panelbackend/lost_incident')) { ?>
                                    <li><a href="<?= site_url('panelbackend/lost_incident/edit/' . $rowheader['id_lost_incident']) ?>" class="  btn-sm waves-block">Edit Temuan / Kejadian</a></li>
                                <?php } ?>
                                <?php
                                if (Access('delete', 'panelbackend/lost_incident')) { ?>
                                    <li><a href="<?= site_url('panelbackend/lost_incident/delete/' . $rowheader['id_lost_incident']) ?>" class="  btn-sm waves-block">Delete Temuan / Kejadian</a></li>
                                <?php } ?>
                            </ul>
                        </li>
                    </ul>
                  <div style="clear: both;"></div>
                </div>
              <?php
                $buttonMenu = "";
                $buttonMenu = UI::showButtonMode($mode, $row[$pk]);

                if ($buttonMenu) {
                ?>
                  <div class="header" style="background-color: #f1f1f1">
                    <div class="float-left"><h2><?=strtoupper($sub_page_title)?></h2></div>
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
        </div>
        <style type="text/css">
          /* table.dataTable {
            clear: both;
            margin-top: -15px !important;
            margin-bottom: 6px !important;
            max-width: none !important;
          } */
        </style>