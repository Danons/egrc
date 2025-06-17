        <div class="container-fluid">
        <!-- <?php if($page_title){ ?>
            <div class="block-header">
                <h2>
        <?=$page_title?>
        <?php if($sub_page_title){ ?> <small><?=$sub_page_title?></small> <?php }?></h2>
            </div>
            <?php } ?> -->
            <!-- Basic Table -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <!-- <div class="card-header">
                        <?php if($page_title){ ?>
            <div class="block-header">
                <h2>
        <?=$page_title?>
        <?php if($sub_page_title){ ?> <small class="font-poppins"><?=$sub_page_title?></small> <?php }?></h2>
            </div>
            <?php } ?>
                        </div> -->
                        <div class="body table-responsive">

                      <?php  if(($_SESSION[SESSION_APP]['loginas'])){ ?>
                      <div class="alert alert-warning">
                          Anda sedang mengakses user lain. <a href="<?=site_url("panelbackend/home/loginasback")?>" class="alert-link">Kembali</a>.
                      </div>
                      <?php }?>

                      <?=FlashMsg()?>
                      <?php echo $content1;?>
                      <div style="clear: both;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>