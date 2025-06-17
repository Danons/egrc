
<html lang="en">

<head>
	<title>Login Akses</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!--===============================================================================================-->
	<link rel="icon" type="image/png" href="images/icons/favicon.ico" />
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
					<span class="login100-form-title p-b-20">
						Login ERM
					</span>
					<?php if ($_SESSION[SESSION_APP]['error_login']) { ?>
						<div id="respon-msg" role="alert" class="alert alert-danger"><?= $_SESSION[SESSION_APP]['error_login'];
																						unset($_SESSION[SESSION_APP]['error_login']); ?></div>
					<?php } else { ?>
						<div id="respon-msg" style="display:none" role="alert"></div>
					<?php } ?>
					<?php foreach ($_SESSION[SESSION_APP]['akses'] as $i => $r) { ?>
						<div class="container-login100-form-btn">
							<button type="button" onclick="$('#act').val('set_akses'); $('#idkey').val(<?= $i ?>); $('#login').submit();" class="login100-form-btn">
								<?= $r['nama_group'] ?><br />
								<div><small><?= $r['name'] ?></small></div>
							</button>
						</div>
						<br />
					<?php } ?>

					<!-- <div class="text-center p-t-46 p-b-20">
						<span class="txt2">
							&copy; <?= date('Y') ?>. PT. Eksekusi Teknologi Indonesia
						</span>
					</div> -->

				</form>

				<div class="login100-more" style="background-image: url('<?php echo base_url() ?>assets/images/bg-login.jpg');">
					<img src="<?php echo base_url(); ?>/assets/images/LOGO-White-SMALL.png" class="logo-login" />
				</div>
			</div>
		</div>
	</div>





	<!--===============================================================================================-->

	<script src="<?php echo base_url() ?>assets/login/vendor/jquery/jquery-3.5.1.min.js"></script>
	<!--===============================================================================================-->
	<script src="<?php echo base_url() ?>assets/login/vendor/animsition/js/animsition.min.js"></script>
	<!--===============================================================================================-->
	<script src="<?php echo base_url() ?>assets/template/backend/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
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