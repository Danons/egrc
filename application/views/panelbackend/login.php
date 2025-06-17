
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

	<?php
	/*
	$rows = $this->conn->GetArray("select a.label, b.name, a.menu_id, b.action_id
from public_sys_menu a join public_sys_action b on a.menu_id = b.menu_id
left join public_sys_menu c on a.parent_id = c.menu_id
where a.visible=1 
order by ifnull(a.sort,0), a.menu_id, a.sort, a.label, b.name");

	$rowsg = $this->conn->GetArray("select group_id, name from public_sys_group");

	$rowsgg = $this->conn->GetArray("select group_id, menu_id, action_id 
from public_sys_group_menu a join public_sys_group_action b 
on a.group_menu_id = b.group_menu_id
");

	$arr = [];
	foreach ($rowsgg as $rg) {
		$arr[$rg['group_id']][$rg['menu_id']][$rg['action_id']] = 'Ok';
	}


	?>
	<table border='1'>
		<thead>
			<tr>
				<th rowspan="2">Menu</th>
				<th rowspan="2">Akses</th>
				<th colspan="<?= count($rowsg) ?>">Group</th>
			</tr>
			<tr>
				<?php foreach ($rowsg as $rg) { ?>
					<th><?= $rg['name'] ?></th>
				<?php } ?>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($rows as $rgg) { ?>
				<tr>
					<td><?= $rgg['label'] ?></td>
					<td><?= $rgg['name'] ?></td>
					<?php foreach ($rowsg as $rg) { ?>
						<td><?= $arr[$rg['group_id']][$rgg['menu_id']][$rgg['action_id']] ?></td>
					<?php } ?>
				</tr>
			<?php } ?>
		</tbody>
	</table>

	<?php die(); */ ?>

	<div class="limiter">
		<div class="container-login100">
			<div class="wrap-login100">
				<form class="login100-form validate-form" role="form" id="login" method="post" accept-charset="UTF-8" action="<?php echo site_url("panelbackend/login/auth") ?>">

					<!-- <div class="container-area-decoration" style="background-image: url('<?php echo base_url() ?>assets/images/decor3.png');background-position-x: -80px;background-position-y: 70px;">

					</div> -->

					<div class="d-flex justify-content-center mb-4">
						<img src="<?php echo base_url(); ?>/assets/images/logo.png" class="logo-login" style="width: 196px !important;" />
					</div>

					<!-- <span class="login100-form-title p-b-20 text-center">
						Halaman Login
					</span> -->
					<span class="login100-form-title text-center">
						Halaman Login
					</span>
					<div class="text-center login100-form-subtitle mt-2">
						Silahkan masuk menggunakan username dan password Anda!
					</div>

					<?php if ($_SESSION[SESSION_APP]['error_login']) { ?>
						<div id="respon-msg" role="alert" class="alert alert-danger"><?= $_SESSION[SESSION_APP]['error_login'];
																						unset($_SESSION[SESSION_APP]['error_login']); ?></div>
					<?php } else { ?>
						<div id="respon-msg" style="display:none" role="alert"></div>
					<?php } ?>


					<!-- <div class="wrap-input100 validate-input" data-validate="Username is required">
						<input class="input100" type="text" name="username" id="username">
						<span class="focus-input100"></span>
						<span class="label-input100">Username</span>
					</div>


					<div class="wrap-input100 validate-input" data-validate="Password is required">
						<input class="input100" type="password" name="password" id="password">
						<span class="focus-input100"></span>
						<span class="label-input100">Password</span>
					</div> -->


					<div class="form-group">
						<label for="username">Username</label>
						<input type="text" name="username" class="form-control" id="username" placeholder="Username" />
					</div>

					<div class="form-group">
						<label for="password">Password</label>
						<input type="password" name="password" class="form-control" id="password" placeholder="Password" />
					</div>

					<!-- <div class="flex-sb-m w-full p-t-3 p-b-32">


						<div>
							<a target='_blank' href="https://wa.me/6287882917312/?text=Salam..%2C+saya+lupa+kata+sandi+" class="txt2">
								Lupa password?
							</a>
						</div>
						<div class="contact100-form-checkbox">
							<input class="input-checkbox100" id="ckb1" type="checkbox" name="remember-me">
							<label class="label-checkbox100" for="ckb1">
								Ingat saya
							</label>
						</div>
					</div> -->
					<!-- <hr /> -->
					<div class="flex-sb-m w-full p-t-3 p-b-32">

						<!-- <div>
							<a href="<?= base_url("panelbackend/register") ?>" class="txt3">
								Belum terdaftar ?
							</a>
						</div> -->

						<!-- <div style="min-width: 40%;text-align: end;z-index: 9999;">
							<button type="submit" class="login100-form-btn">
								Masuk
							</button>
						</div> -->
						<!-- <div style="z-index: 9999;">
							<button type="submit" class="login100-form-btn">
								Masuk
							</button>
						</div> -->
						<button type="submit" class="login100-form-btn width100percent btn-blue">
							Masuk
						</button>
					</div>





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

						<!-- <div class="d-flex align-items-center area-logo-partners">
							<img alt="logo1" src="<?php echo base_url(); ?>/assets/images/logo.png" />

							<img alt="logo2" src="<?php echo base_url(); ?>/assets/images/partner1.png" />
							<img alt="logo3" src="<?php echo base_url(); ?>/assets/images/partner2.png" />
						</div> -->

						<div class="item-logo-login">
							<!-- <div class="item-logo-login-i">

								<img src="<?php echo base_url(); ?>/assets/images/favicon.ico" style="width: 70px;" />
								<div style="padding-left: 20px;">
									<div class="logo-title-e-grc">E-GRC</div>

								</div>
							</div> -->

							<!-- <div class="e-grc-name">E-GOVERNANCE RISK COMPLIANCE</div> -->

							<!-- <div class="d-flex justify-content-center mb-4">
								<img src="<?php echo base_url(); ?>/assets/images/logo.png" class="logo-login" style="width: 196px !important;" />
							</div> -->
							<div class="e-grc-banner-title">E-GRC</div>
							<div class="e-grc-banner-subtitle">E-GOVERNANCE RISK COMPLIANCE</div>

							<div class="e-grc-banner-line"></div>
						</div>


						<div class="d-flex align-items-center area-logo-partners">
							<img alt="logo1" src="<?php echo base_url(); ?>/assets/images/logo.png" />

							<img alt="logo2" src="<?php echo base_url(); ?>/assets/images/partner1.png" />
							<img alt="logo3" src="<?php echo base_url(); ?>/assets/images/partner2.png" />
						</div>

						<div style="height: 40px;"></div>


						<div class="container-login-footer">

							<!-- <span class="txt2">
								&copy; <?= date('Y') ?>. PT Aktivitas Insani Madani
							</span> -->
						</div>

					</div>

					<!-- <div class="area-logo-partners-footer">
						<div class="d-flex align-items-center area-logo-partners">
							<img alt="logo1" src="<?php echo base_url(); ?>/assets/images/logo.png" />

							<img alt="logo2" src="<?php echo base_url(); ?>/assets/images/partner1.png" />
							<img alt="logo3" src="<?php echo base_url(); ?>/assets/images/partner2.png" />
						</div>
					</div> -->

					<div style="background-image: url(<?php echo base_url(); ?>/assets/images/wave.svg);" class="img-login-banner-wave">

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
	<script>
		$("#login").submit(function() {

			if ($("input#username").val().trim() != '' && $("input#password").val().trim() != '') {
				$('.login100-form-btn').attr('disable', 'disable');
				$('.login100-form-btn').text('Loading...');
				$('.login100-form-btn').attr('style', 'background: #1416d3; pointer: not-allowed');
				$.ajax({
					url: $(this).attr("action"),
					type: "post",
					data: $(this).serialize(),
					dataType: "json",
					cache: false,
					success: function(data) {
						if (data.error) {
							$("#respon-msg").text(data.error).fadeOut('500');
							$("#respon-msg").attr("class", "alert alert-danger");
							$("#respon-msg").text(data.error).fadeIn('500');
							$("#username").val('');
							$("#password").val('');

						} else {
							$("#respon-msg").text(data.success).fadeOut('500');
							$("#respon-msg").attr("class", "alert alert-success");
							$("#respon-msg").text(data.success).fadeIn('500');
							window.location = "<?php echo site_url($_SESSION[SESSION_APP]['curr_page']); ?>";
						}
						$('.login100-form-btn').removeAttr('disable');
						$('.login100-form-btn').removeAttr('style');

						$('.login100-form-btn').text('Login');
					}
				});
			}
			return false;
		});
		$(function() {
			$("#username").focus()
		});
	</script>
</body>

</html>