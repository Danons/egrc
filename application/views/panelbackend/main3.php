<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="">
  <meta name="author" content="">

  <title><?= Title($page_title) ?></title>
  <script src="<?php echo base_url() ?>assets/login/vendor/jquery/jquery-3.5.1.min.js"></script>
  <script src="<?php echo base_url() ?>assets/js/html-docx.js"></script>

  <link rel="shortcut icon" href="<?php echo base_url() ?>assets/images/favicon.ico" type="image/x-icon" />

  <!-- Bootstrap Core Css -->
  <link href="<?php echo base_url() ?>assets/css/bootstrap-old.css" rel="stylesheet">
  <link href="<?php echo base_url() ?>assets/css/bootstrap-icons-1.8.1/bootstrap-icons.css" rel="stylesheet">
  <script src="<?php echo base_url() ?>assets/plugins/sweetalert/sweetalert.min.js"></script>

  <!-- Custom Css -->
  <link href="<?php echo base_url() ?>assets/css/style.css" rel="stylesheet">
</head>

<body style="margin-top:0px; background-color: #fff; overflow:auto !Important;">
  <?php echo $content; ?>
</body>

</html>