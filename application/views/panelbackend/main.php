<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title><?= Title($page_title) ?></title>

    <!-- Favicon-->
    <!-- <link rel="shortcut icon" href="<?php echo base_url() ?>assets/images/e-grc.png" type="image/x-icon" /> -->
    <link rel="shortcut icon" href="<?php echo base_url() ?>assets/images/favicon.ico" type="image/x-icon" />

    <!-- Bootstrap CSS -->
    <link href="<?php echo base_url() ?>assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" />

    <!-- ICON -->
    <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css"> -->
    <link rel="stylesheet" href="<?php echo base_url() ?>assets/css/bootstrap-icons-1.8.1/bootstrap-icons.css">
    <link rel="stylesheet" href="<?php echo base_url() ?>assets/css/material-icons/icon.css">
    <link href="<?php echo base_url() ?>assets/plugins/sweetalert/sweetalert.css" rel="stylesheet" />

    <!-- Custom Style -->
    <link href="<?php echo base_url() ?>assets/css/style.css" rel="stylesheet" />

    <!-- Bootstrap JS -->
    <script src="<?php echo base_url() ?>assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>

    <script src="<?php echo base_url() ?>assets/plugins/jquery/jquery.min.js"></script>
</head>

<body class="<?= $is_home_tr ? "is_home_tr" : "" ?>">
    <?php if ($is_home_tr) : ?>
        <div class="background-app-dashboard" style="z-index: 1;background-image: url(<?php echo base_url(); ?>/assets/images/tr-bg-dashboard.jpg);"></div>
    <?php endif; ?>

    <header <?= $is_home_tr ? 'style="position: relative; z-index: 4;"' : '' ?>>
        <!-- Fixed navbar -->
        <nav class="navbar navbar-expand-md navbar-light fixed-top baner">
            <div class="container-fluid">
                <a class="navbar-brand" href="<?= site_url() ?>">
                    <i class="home-logo" style="background-image: url(<?php echo base_url(); ?>/assets/images/favicon.ico);"></i>
                    <div class="container-title-logo">
                        <div class="title-logo">E-GRC</div>
                        <!-- <div class="sub-title-logo">Perum Jasa Tirta II</div> -->
                        <!-- <div class="sub-title-logo">Manajemen Risiko</div> -->

                    </div>
                    <!-- <div class="title-logo"></div> -->
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarCollapse">
                    <?php
                    $datamenu = $this->auth->GetMenu();
                    if (!$datamenu) {
                        echo "<ul class=\"navbar-nav me-auto mb-2 mb-md-0\"><li class=\"nav-item active\"><a class=\"nav-link active\" href='" . site_url() . "' data-nama-menu='Menu Utama'>Menu Utama</a></li></ul>";
                    } else {
                        echo $datamenu;
                    }
                    ?>

                    <!-- <?php // $this->auth->GetSideBar((int)$rowmenu['menu_id']); 
                            ?> -->




                    <form class="d-flex">
                        <input class="form-control form-cari-risiko" type="search" placeholder="Search" aria-label="Search" name="cari_risikoinput" id="cari_risikoinput">
                    </form>

                    <ul class="navbar-nav mb-2 mb-md-0 nav-right">
                        <li class="nav-item">
                            <?php $d_task = $this->auth->GetTask(); ?>
                            <div class="dropdown user-info">
                                <a href="#" class="nav-link dropdown-toggle" id="dropdownUser3" data-bs-toggle="dropdown" aria-expanded="false">
                                    <span class="material-icons" aria-current="page" title="Tugas Anda" data-bs-toggle="tooltip" data-bs-placement="right">flag</span>
                                    <span class="label-count" id="task_count" style="color: #fff"><?= $d_task['count'] ?></span>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end" style="min-width: 500px;">
                                    <li class="header">TASKS</li>
                                    <li class="body">
                                        <ul class="menu" id="task_data">
                                            <?php foreach ($d_task['content'] as $r) {  ?>
                                                <li>
                                                    <a href="<?= site_url($r['url']) ?>">
                                                        <div class="icon-circle text-<?= $r['bg'] ?>">
                                                            <i class="material-icons"><?= $r['icon'] ?></i>
                                                        </div>
                                                        <div class="menu-info">
                                                            <p class="info"><?= $r['info'] ?></p>
                                                            <p>
                                                                <i class="material-icons">access_time</i> <?= $r['time'] ?>
                                                                <i class="material-icons">account_circle</i> <?= $r['user'] ?>
                                                            </p>
                                                        </div>
                                                    </a>
                                                </li>
                                            <?php } ?>
                                        </ul>
                                    </li>
                                    <li class="footer">
                                        <a href="<?= site_url("panelbackend/risk_task") ?>">View All Tasks</a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <li class="nav-item">
                            <!-- <div class="dropdown user-info"> -->
                            <!-- <a href="<?= site_url("panelbackend/home/ug") ?>" target="_BLANK" class="nav-link" id="dropdownUser3" title="Bantuan" data-bs-toggle="tooltip" data-bs-placement="right">
                                <span class="material-icons">help</span>
                            </a> -->
                            <!-- <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownUser3">
                                    <li><a class="dropdown-item" href="<?= site_url("panelbackend/home/wf") ?>"> Alur</a></li>
                                    <li><a class="dropdown-item" href="<?= site_url("panelbackend/home/ug") ?>"> Panduan</a></li>
                                </ul> -->
                            <!-- </div> -->
                        </li>
                    </ul>
                    <div class="dropdown user-info">
                        <a href="#" alt="<?= $_SESSION[SESSION_APP]['name'] ?>" title="<?= $_SESSION[SESSION_APP]['name'] ?>" class="text-decoration-none dropdown-toggle" id="dropdownUser3" data-bs-toggle="dropdown" aria-expanded="false">
                            <?= (strlen($_SESSION[SESSION_APP]['name']) > 15 ? substr($_SESSION[SESSION_APP]['name'], 0, 15) . '...' : $_SESSION[SESSION_APP]['name']) . " (" . $_SESSION[SESSION_APP]['nama_group'] . ")"; ?>
                            <img src="https://github.com/mdo.png" alt="mdo" width="24" height="24" class="rounded-circle">
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownUser3">
                            <li><a class="dropdown-item" href="<?= site_url("panelbackend/home/profile") ?>"> Profile</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <?php if ($this->auth->GetAccessRole("panelbackend/loginas")) { ?>
                                <li><a class="dropdown-item" href="<?= site_url("panelbackend/loginas") ?>"> Login As</a></li>
                            <?php } ?>

                            <?php if ($_SESSION[SESSION_APP]['akses']) { ?>
                                <li><a class="dropdown-item" href="<?= site_url("panelbackend/login/akses") ?>"> Login Role</a></li>
                            <?php } ?>
                            <li><a class="dropdown-item" href="<?= site_url("panelbackend/login/logout") ?>"> Logout</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>
    </header>
    <!-- <div class="d-flex flex-column flex-shrink-0 bg-primary sidebarfix">
        <a href="<?= site_url() ?>" class="link-home-logo d-block p-3 link-dark text-decoration-none" title="Halaman Utama" data-bs-toggle="tooltip" data-bs-placement="right">
            <i class="home-logo"></i>
        </a>
         <ul class="nav nav-pills nav-flush flex-column mb-auto text-center">
            <li class="nav-item">
                <a href="#" class="nav-link py-2" aria-current="page" title="Pencarian" data-bs-toggle="tooltip" data-bs-placement="right">
                    <svg width="24" height="24" viewBox="0 0 24 24" role="presentation">
                        <path d="M16.436 15.085l3.94 4.01a1 1 0 01-1.425 1.402l-3.938-4.006a7.5 7.5 0 111.423-1.406zM10.5 16a5.5 5.5 0 100-11 5.5 5.5 0 000 11z" fill="currentColor" fill-rule="evenodd"></path>
                    </svg>
                </a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link py-2" aria-current="page" title="Tambah Baru" data-bs-toggle="tooltip" data-bs-placement="right">
                    <svg width="24" height="24" viewBox="0 0 24 24" role="presentation">
                        <path d="M13 11V3.993A.997.997 0 0012 3c-.556 0-1 .445-1 .993V11H3.993A.997.997 0 003 12c0 .557.445 1 .993 1H11v7.007c0 .548.448.993 1 .993.556 0 1-.445 1-.993V13h7.007A.997.997 0 0021 12c0-.556-.445-1-.993-1H13z" fill="currentColor" fill-rule="evenodd"></path>
                    </svg>
                </a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link py-2" aria-current="page" title="Tugas Anda" data-bs-toggle="tooltip" data-bs-placement="right">
                    <span class="material-icons">flag</span>
                </a>
            </li>
        </ul>
        <ul class="nav nav-pills nav-flush flex-column text-center">
            <li class="nav-item">
                <a href="#" class="nav-link py-2" aria-current="page" title="Laporan" data-bs-toggle="tooltip" data-bs-placement="right">
                    <span class="material-icons">print</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link py-2" aria-current="page" title="Pengaturan" data-bs-toggle="tooltip" data-bs-placement="right">
                    <span class="material-icons">settings</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link py-2" aria-current="page" title="Bantuan" data-bs-toggle="tooltip" data-bs-placement="right">
                    <span class="material-icons">help</span>
                </a>
            </li>
        </ul>
        <div class="dropdown user-info">
            <a href="#" class="d-flex align-items-center justify-content-center p-3 link-dark text-decoration-none dropdown-toggle" id="dropdownUser3" data-bs-toggle="dropdown" aria-expanded="false">
                <img src="https://github.com/mdo.png" alt="mdo" width="24" height="24" class="rounded-circle">
            </a>
            <ul class="dropdown-menu text-small shadow" aria-labelledby="dropdownUser3">
                <li><a class="dropdown-item" href="#">New project...</a></li>
                <li><a class="dropdown-item" href="#">Settings</a></li>
                <li><a class="dropdown-item" href="#">Profile</a></li>
                <li>
                    <hr class="dropdown-divider">
                </li>
                <li><a class="dropdown-item" href="#">Sign out</a></li>
            </ul>
        </div> 
    </div>-->
    <form <?= $is_home_tr ? 'style="position: relative; z-index: 3;"' : '' ?> method="post" enctype="multipart/form-data" id="main_form" class="form-horizontal">
        <input type="hidden" name="act" id="act" />
        <input type="hidden" name="go" id="go" />
        <input type="hidden" name="idkey" id="idkey" />
        <input type="hidden" name="cari_risiko" id="cari_risiko" />
        <div id="mainajax">
            <?= $content; ?>
        </div>
    </form>

    <div class="modal fade" id="modalcontent" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modaltitle" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modaltitle">Modal title</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="post" enctype="multipart/form-data" id="main_form_modal" class="form-horizontal" autocomplete="off">
                    <input type="hidden" name="form" id="form" />
                    <input type="hidden" name="urlajax" id="urlajax" />
                    <input type="hidden" name="key" id="key" />
                    <div class="modal-body" id="modalbody">
                    </div>
                </form>
                <div class="modal-footer">
                    <span id="btnbackmodal"></span>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <span id="btnsavemodal"></span>
                </div>
            </div>
        </div>
    </div>

    <script src="<?php echo base_url() ?>assets/js/autoNumeric.js"></script>

    <script src="<?php echo base_url() ?>assets/plugins/sweetalert/sweetalert.min.js"></script>
    <!-- JS Custom -->
    <?= $add_plugin ?>
    <script src="<?php echo base_url() ?>assets/js/script.js"></script>


    <?php if ($this->conn->db_debug) { ?>
        <button style="bottom:0; left:0; position:fixed; z-index:9999;" type="button" onclick="$('#cpd').show()">DEBUG</button>
        <div id="cpd" style="display:none;top:0; position:fixed; z-index:10000; background:#fff; height:100vh; overflow:scroll">
            <button type="button" onclick="$('#cpd').hide()" style="position: fixed;">Close</button>
            <?php
            dpr($_SESSION[SESSION_APP]);
            ?>
            <br />
            <div>
                <?= implode("<hr/>", $this->conn->queryarr) ?>
            </div>
        </div>
    <?php } ?>

    <script>
        const scrollViewSidebar = $('nav.d-md-block:not(.bg-light)')

        function expandCollapsed(thisx) {
            const ul = thisx.next()
            if (!ul.hasClass("collapsed")) {
                ul.addClass("collapsed")
                ul.parent().addClass("collapsed")
            } else {
                ul.removeClass("collapsed")
                ul.parent().removeClass("collapsed")

            }
        }

        $('nav.d-md-block:not(.bg-light) ul.nav a.nav-link[href="#"]').click(function() {
            // console.log("web expand collapse")
            expandCollapsed($(this))
        })
        $('.overlay-sidebar-mobile>.sidebar-mobile> ul.nav a.nav-link[href="#"]').click(function() {
            // console.log("mobile expand collapse")
            expandCollapsed($(this))
        })

        $(document).ready(function() {
            initMenuSidebar()
        })

        function initMenuSidebar() {
            $('nav.d-md-block:not(.bg-light) .position-sticky>ul.nav>.nav-item').each(function(i) {
                if ($(this).children().length === 1) {
                    $(this).children().first().addClass('menu-sidebar-empty-child')
                }

            })
        }
    </script>
</body>

</html>