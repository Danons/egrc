<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title><?=Title($page_title)?></title>
  <link rel="shortcut icon" href="<?php echo base_url()?>assets/images/favicon.ico" type="image/x-icon" />
  

    <script src="<?php echo base_url() ?>assets/plugins/jquery/jquery.min.js"></script>
    <script> var base_url = '<?=base_url()?>';</script>
    <script src="<?=base_url()?>assets/pdf/pdfjs2.js"></script>
    <script src="<?=base_url()?>assets/pdf/pdf.js"></script>
    <!-- Bootstrap Core JavaScript -->
    <!-- <script src="<?php echo base_url() ?>assets/template/backend/plugins/bootstrap/js/bootstrap.bundle.min.js"></script> -->
</head>

<body style="margin-top:0px"> <!--<body tabindex="1">-->
<form method="post" class="search" enctype="multipart/form-data" id="main_form" >
<input type="hidden" name="act" id="act">
<?php echo $content;?> 
</form>
</body>

</html>