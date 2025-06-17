<!DOCTYPE html>
<html lang="en">

<head>
	<title>Register</title>
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
				<form class="login100-form" role="form" id="form1" method="post" accept-charset="UTF-8">

					<span class="login100-form-title p-b-20">
						1 / 3 Isi dengan data instansi Anda
					</span>

					<div class="progress" style="height:5px">
						<div class="progress-bar" role="progressbar" style="width: 33.33%" aria-valuenow="33.33" aria-valuemin="0" aria-valuemax="100"></div>
					</div>

					<br />

					<div class="wrap-input100 validate-input" data-validate="Nama instansi wajib di isi">
						<input class="input100" type="text" name="instansi" id="instansi">
						<span class="focus-input100"></span>
						<span class="label-input100">Nama Instansi</span>
					</div>

					<div class="row">
						<div class="col-sm-6">
							<div class="wrap-input100 validate-input" data-validate="Nama Propinsi wajib di isi">
								<input class="input100" type="text" name="propinsi" id="propinsi">
								<span class="focus-input100"></span>
								<span class="label-input100">Propinsi</span>
							</div>
						</div>
						<div class="col-sm-6">
							<div class="wrap-input100 validate-input" data-validate="Nama Kota/Kab. wajib di isi">
								<input class="input100" type="text" name="kota" id="kota">
								<span class="focus-input100"></span>
								<span class="label-input100">Kota/Kab.</span>
							</div>
						</div>
					</div>

					<div class="wrap-input100 validate-input" data-validate="Alamat instansi wajib di isi">
						<textarea class="textarea100" name="alamat" id="alamat"></textarea>
						<span class="focus-input100"></span>
						<span class="label-input100">Alamat instansi</span>
					</div>

					<hr />
					<div class="flex-sb-m w-full p-t-3 p-b-32">

						<div>
							<a href="<?= base_url("panelbackend/login") ?>" class="txt3">
								Sudah terdaftar ?
							</a>
						</div>

						<div style="min-width: 40%;text-align: end;">
							<button type="submit" class="login100-form-btn">
								LANJUT
							</button>
						</div>
					</div>


					<!-- </div> -->
				</form>

				<form class="login100-form" role="form" id="form2" method="post" accept-charset="UTF-8">

					<span class="login100-form-title p-b-20">
						2 / 3 Lengkapi data diri Anda
					</span>

					<div class="progress" style="height:5px">
						<div class="progress-bar" role="progressbar" style="width: 66.66%" aria-valuenow="66.66" aria-valuemin="0" aria-valuemax="100"></div>
					</div>

					<br />

					<div class="wrap-input100 validate-input" data-validate="Nama Lengkap wajib di isi">
						<input class="input100" type="text" name="nama" id="nama">
						<span class="focus-input100"></span>
						<span class="label-input100">Nama Lengkap</span>
					</div>


					<div class="wrap-input100 validate-input" data-validate="Email wajib di isi">
						<input class="input100" type="email" name="email" id="email">
						<span class="focus-input100"></span>
						<span class="label-input100">Email</span>
					</div>


					<div class="wrap-input100 validate-input" data-validate="Nomor Telepon wajib di isi">
						<input class="input100" type="text" name="phone" id="phone">
						<span class="focus-input100"></span>
						<span class="label-input100">Nomor Telepon</span>
					</div>

					<hr />
					<div class="flex-sb-m w-full p-t-3 p-b-32">

						<div style="min-width: 40%;">
						</div>

						<div style="min-width: 40%;text-align: end;">
							<button type="button" onclick="$('.login100-form').hide(); $('#form1').show();" class="login100-form-btn" style="background:#fff;color:#11047a;border:1px solid #11047a;">
								KEMBALI
							</button>
							<button type="submit" class="login100-form-btn">
								LANJUT
							</button>
						</div>
					</div>


					<!-- </div> -->
				</form>

				<form class="login100-form" role="form" id="form3" method="post" accept-charset="UTF-8">

					<span class="login100-form-title p-b-20">
						3 / 3 Pastikan data Anda sudah benar
					</span>

					<div class="progress" style="height:5px">
						<div class="progress-bar" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
					</div>

					<br />


					<?php if ($_SESSION[SESSION_APP]['error_login']) { ?>
						<div id="respon-msg" role="alert" class="alert alert-danger"><?= $_SESSION[SESSION_APP]['error_login'];
																						unset($_SESSION[SESSION_APP]['error_login']); ?></div>
					<?php } else { ?>
						<div id="respon-msg" style="display:none" role="alert"></div>
					<?php } ?>


					<ul style="margin-left: 20px;">
						<li style="list-style-type: decimal;padding-bottom: 10px;">
							<p style="color: #007bff;font-size:18px"><b>Informasi Instansi</b></p>
							<p><b>Nama Instansi : </b><span id="instansi_s"></span></p>
							<p><b>Kota / Provinsi : </b><span id="kota_s"></span>, <span id="propinsi_s"></span></p>
							<p><b>Alamat : </b><span id="alamat_s"></span></p>
						</li>
						<li style="list-style-type: decimal;padding-bottom: 10px;">
							<p style="color: #007bff;font-size:18px"><b>Data Penanggung Jawab</b></p>
							<p><b>Nama Lengkap : </b><span id="nama_s"></span></p>
							<p><b>Email : </b><span id="email_s"></span></p>
							<p><b>Nomor Telepon : </b><span id="phone_s"></span></p>
						</li>
					</ul>

					<hr />
					<div class="flex-sb-m w-full p-t-3 p-b-32">

						<div>

						</div>

						<div style="min-width: 40%;text-align: end;">
							<button type="button" onclick="$('.login100-form').hide(); $('#form2').show();" class="login100-form-btn" style="background:#fff;color:#11047a;border:1px solid #11047a;">
								KEMBALI
							</button>
							<button type="submit" class="login100-form-btn">
								KIRIM
							</button>
						</div>
					</div>


					<!-- </div> -->
				</form>

				<div class="login100-more" style="background-image: url('<?php echo base_url() ?>assets/images/bg-login.jpg');">
					<img src="<?php echo base_url(); ?>/assets/images/LOGO-White-SMALL.png" class="logo-login" />
					<div style="color: #fff; font-size: 36px; text-align: center;">Manajemen Risiko</div>

					<div style="color: #fff; font-size: 14px; text-align: center;">Garda terdepan untuk keberlangsungan perusahaan Anda</div>


					<div style="padding: 50px; justify-content: center; flex: 1; display: flex; align-items: flex-start; justify-content: center;">

						<a target='_blank' class="contact-btn" href="https://wa.me/6287882917312/?text=Salam...%2C">
							KONSULTASI DI WA
						</a>

						<a class="main-btn" href="<?= base_url("home") ?>">
							KE WEB UTAMA
						</a>
					</div>


					<div class="text-center p-t-46 p-b-20">
						<span class="txt2">
							&copy; <?= date('Y') ?>. PT. Eksekusi Teknologi Indonesia
						</span>
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
	<script>
		function validate(input) {
			if ($(input).attr('type') == 'email' || $(input).attr('name') == 'email') {
				if ($(input).val().trim().match(/^([a-zA-Z0-9_\-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([a-zA-Z0-9\-]+\.)+))([a-zA-Z]{1,5}|[0-9]{1,3})(\]?)$/) == null) {
					return false;
				}
			} else {
				if ($(input).val().trim() == '') {
					return false;
				}
			}
		}

		function showValidate(input) {
			var thisAlert = $(input).parent();

			$(thisAlert).addClass('alert-validate');
		}

		function hideValidate(input) {
			var thisAlert = $(input).parent();

			$(thisAlert).removeClass('alert-validate');
		}

		$(function() {
			$("input").change(function() {
				var name = $(this).attr("name");

				$("#" + name + "_s").text($(this).val())
			})

			$("textarea").change(function() {
				var name = $(this).attr("name");

				$("#" + name + "_s").text($(this).val())
			})

			$(".login100-form").hide();
			$("#form1").show();
			$("#instansi").focus();


			var input1 = $('#form1 .input100, #form1 .textarea100');

			$('#form1 .input100, #form1 .textarea100').each(function() {
				$(this).focus(function() {
					hideValidate(this);
				});
			});

			$('#form1').on('submit', function() {
				var check = true;

				for (var i = 0; i < input1.length; i++) {
					if (validate(input1[i]) == false) {
						showValidate(input1[i]);
						check = false;
					}
				}

				if (check == true) {
					$(".login100-form").hide();
					$("#form2").show();
				}

				return false;
			});

			var input2 = $('#form2 .input100, #form2 .textarea100');

			$('#form2 .input100, #form2 .textarea100').each(function() {
				$(this).focus(function() {
					hideValidate(this);
				});
			});

			$('#form2').on('submit', function() {
				var check = true;

				for (var i = 0; i < input2.length; i++) {
					if (validate(input2[i]) == false) {
						showValidate(input2[i]);
						check = false;
					}
				}

				if (check == true) {
					$(".login100-form").hide();
					$("#form3").show();
				}

				return false;
			});

			var input3 = $('#form2 .input100, #form2 .textarea100');

			$('#form2 .input100, #form2 .textarea100').each(function() {
				$(this).focus(function() {
					hideValidate(this);
				});
			});

			$('#form3').on('submit', function() {
				var instansi = $("#instansi_s").text();
				var kota = $("#kota_s").text();
				var propinsi = $("#propinsi_s").text();
				var alamat = $("#alamat_s").text();
				var nama = $("#nama_s").text();
				var email = $("#email_s").text();
				var phone = $("#phone_s").text();
				window.location = "https://wa.me/6287882917312/?text=Salam" + encodeURI(",\nKami tertarik untuk bergabung \n*Nama Instansi :* " + instansi + "\n" +
					"*Kota/Propinsi :* " + kota + ", " + propinsi + "\n" +
					"*Alamat :* " + alamat + "\n" +
					"*Nama Lengkap :* " + nama + "\n" +
					"*Email :* " + email + "\n" +
					"*Phone :* " + phone + "\n" +
					"")
				return false;
			});
		});
	</script>
</body>

</html>