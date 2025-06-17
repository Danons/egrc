
<html lang="en">

<head>
	<title>Login</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!--===============================================================================================-->
	<!-- <link rel="icon" type="image/png" href="images/icons/favicon.ico" /> -->
	<!-- <link rel="shortcut icon" href="<?php echo base_url() ?>assets/images/e-grc.png" type="image/x-icon" /> -->
	<link rel="shortcut icon" href="<?php echo base_url() ?>assets/images/favicon.ico" type="image/x-icon" />
	<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>assets/login/vendor/bootstrap/css/bootstrap.min.css">
	<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>assets/login/fonts/font-awesome-4.7.0/css/font-awesome.min.css">
	<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>assets/login/fonts/Linearicons-Free-v1.0.0/icon-font.min.css">
	<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>assets/login/vendor/animate/animate.css">
	<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>assets/login/vendor/css-hamburgers/hamburgers.min.css">
	<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>assets/login/vendor/animsition/css/animsition.min.css">
	<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>assets/login/vendor/select2/select2.min.css">
	<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>assets/login/vendor/daterangepicker/daterangepicker.css">
	<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>assets/login/css/util.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>assets/login/css/main.css">
	<!--===============================================================================================-->
</head>

<body style="background-color: #666666;">

	<div class="limiter">
		<div class="container-login100">
			<div class="wrap-login100">
				<form class="login100-form validate-form" role="form" id="login" method="post" accept-charset="UTF-8" action="<?php echo site_url("panelbackend/login/akses") ?>">
					<input type="hidden" name="idkey" id="idkey">
					<input type="hidden" name="act" id="act">

					<div class="container-area-decoration" style="background-image: url('<?php echo base_url() ?>assets/images/decor3.png');background-position-x: -80px;background-position-y: 70px;">

					</div>

					<span class="login100-form-title p-b-20">
						Masuk aplikasi E-GRC
					</span>
					<?php if ($_SESSION[SESSION_APP]['error_login']) { ?>
						<div id="respon-msg" role="alert" class="alert alert-danger"><?= $_SESSION[SESSION_APP]['error_login'];
																						unset($_SESSION[SESSION_APP]['error_login']); ?></div>
					<?php } else { ?>
						<div id="respon-msg" style="display:none" role="alert"></div>
					<?php } ?>


					<?php foreach ($_SESSION[SESSION_APP]['akses'] as $i => $r) { ?>
						<div class="flex-sb-m w-full">
							<div class="container-login100-form-btn" style="z-index: 9999;">
								<button style="display: inline-block;" type="button" onclick="$('#act').val('set_akses'); $('#idkey').val(<?= $i ?>); $('#login').submit();" class="login100-form-btn">
									<?= $r['nama_group'] ?><br />
									<div><small><?= $r['name'] ?></small></div>
								</button>
							</div>
						</div>
						<br />
					<?php } ?>




				</form>

				<!-- <div class="login100-more" style="background-image: url('<?php echo base_url() ?>assets/images/bg-login.jpg');">
					<img src="<?php echo base_url(); ?>/assets/images/LOGO-White-SMALL.png" class="logo-login" />
					<div style="color: #fff; font-size: 36px; text-align: center;">Manajemen Risiko</div>

					<div style="color: #fff; font-size: 14px; text-align: center;">Garda terdepan untuk keberlangsungan perusahaan Anda</div>


					<div style="padding: 50px; justify-content: center; flex: 1; display: flex; align-items: flex-start; justify-content: center;">

						<a target='_blank' class="contact-btn" href="https://wa.me/6287882917312/?text=Salam...%2C">
							HUBUNGI KAMI
						</a>

						<a class="main-btn" href="https://manrisk.id">
							KE WEB UTAMA
						</a>
					</div>


					<div class="text-center p-t-46 p-b-20">
						<span class="txt2">
							&copy; <?= date('Y') ?>. PT Aktivitas Insani Madani
						</span>
					</div>
				</div> -->

				<div class="login100-more" style="background-image: url('<?php echo base_url() ?>assets/images/bg-side-login.jpg');">

					<!-- <img src="<?php echo base_url(); ?>/assets/images/e-grc.png" class="logo-login" /> -->
					<!-- <div style="color: #fff; font-size: 36px; text-align: center;">E-GRC</div> -->

					<!-- <div style="color: #fff; font-size: 14px; text-align: center;">Garda terdepan untuk keberlangsungan perusahaan Anda</div> -->

					<div class="container-logo-login">

						<div class="item-logo-login">
							<div class="item-logo-login-i">

								<img src="<?php echo base_url(); ?>/assets/images/favicon.ico" style="width: 70px;" />
								<div style="padding-left: 20px;">
									<div class="logo-title-e-grc">E-GRC</div>
									<!-- <div class="logo-subtitle-e-grc">Manajemen Risiko</div> -->

								</div>
							</div>

							<div class="e-grc-name">E-GOVERNANCE RISK COMPLIANCE</div>
						</div>

						<div class="container-login-footer">

							<!-- <span class="txt2">
								&copy; <?= date('Y') ?>. PT Aktivitas Insani Madani
							</span> -->
						</div>

					</div>


				</div>
			</div>
		</div>
	</div>





	<!--===============================================================================================-->
	<script src="<?php echo base_url() ?>assets/login/vendor/jquery/jquery-3.5.1.min.js"></script>
	<!--===============================================================================================-->
	<script src="<?php echo base_url() ?>assets/login/vendor/animsition/js/animsition.min.js"></script>
	<!--===============================================================================================-->
	<script src="<?php echo base_url() ?>assets/login/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
	<!--===============================================================================================-->
	<script src="<?php echo base_url() ?>assets/login/vendor/select2/select2.min.js"></script>
	<!--===============================================================================================-->
	<script src="<?php echo base_url() ?>assets/login/vendor/daterangepicker/moment.min.js"></script>
	<script src="<?php echo base_url() ?>assets/login/vendor/daterangepicker/daterangepicker.js"></script>
	<!--===============================================================================================-->
	<script src="<?php echo base_url() ?>assets/login/vendor/countdowntime/countdowntime.js"></script>
	<!--===============================================================================================-->
	<script src="<?php echo base_url() ?>assets/login/js/main.js"></script>
</body>

</html>