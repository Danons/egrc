<div id="container" class="container" <?= ($width_page ? "style='max-width:$width_page'" : "") ?>>
	<center>
		<div class="notshow">
			<?php if ($excel !== false) { ?>
				<a download="<?= $page_title ?>.xls" class="btn btn-sm btn-primary" href="#" onclick="return ExcellentExport.excel(this, 'datatable', '<?= strip_tags(str_replace("\n", "", $page_title)) ?>');">
					<span class="bi bi-th"></span> Excel</a>
				&nbsp;
			<?php } ?>
			<a class="btn btn-sm btn-primary" onclick="window.print()">
				<span class="bi bi-printer"></span>
				Print
			</a>
		</div>
	</center>

	<div id="datatable">
		<table class="tableku" border="1">
			<?php
			if (!$header)
				$header = array(array(), array());

			if (!$no_header) { ?>
				<tr>
					<td rowspan="2" align="center" style="width: 120px; vertical-align:middle">
						<img src="<?= base_url() ?>assets/images/logo.png" width="150px">
						<?php if ($namaunit) { ?>
							<b style="font-size: 16px;"><?= $namaunit ?></b>
						<?php } ?>
					</td>
					<td align="center" style="padding: 5px;" colspan="<?= count($header) - 1 ?>">
						<h4 style="font-weight: bold;">Perumda Trita Raharja</h4>
					</td>
				</tr>
				<!-- <tr>
					<td align="center" style="padding: 10px;" colspan="<?= count($header) - 1 ?>">
						<h4 style="font-weight: bold;">ENTERPRISE RISK MANAGEMENT SYSTEM</h4>
					</td>
				</tr> -->
				<tr>
					<td align="center" style="background-color: #666;padding: 10px;" colspan="<?= count($header) - 1 ?>">
						<h4 style="font-weight: bold; color:#fff !important;"><?= $page_title ?></h4>
					</td>
				</tr>
			<?php } ?>
			<?php if ($no_header && !$no_title) { ?>
				<tr>
					<td colspan="<?= count($header) ?>">
						<ins>
							<h4 style="padding:10px;text-align: center;">
								<b><?= $page_title ?></b>
							</h4>
						</ins>
					</td>
				</tr>
			<?php } ?>
			<?php if (!$no_header || !$no_title) { ?>
				<tr>
					<td colspan="<?= count($header) ?>">
					</td>
				</tr>
			<?php } ?>
			<tr>
				<td colspan="<?= count($header) ?>">
					<?php echo $content1; ?>
				</td>
			</tr>
		</table>
	</div>
</div>
<script src="<?php echo base_url() ?>assets/js/excellentexport.min.js"></script>
<style>
	.notshow {
		margin-top: 10px;
	}

	@media print {

		.notshow {
			display: none;
		}

		body {
			margin: 0px;
			padding: 10px 5px;
		}

		html {
			margin: 0px;
			padding: 0px;
		}
	}

	#container {
		max-width: 100%;
		width: 100%;
		font-size: 14px;
		font-family: Arial, Helvetica, sans-serif;
	}

	td,
	th {
		padding: 3px;
		font-size: 12px;
		vertical-align: text-center;
	}

	/* .h4,
	.h5,
	.h6,
	h4,
	h5,
	h6,
	hr {
		margin-top: 5px;
		margin-bottom: 5px;
	} */

	.tableku {
		margin-top: 20px;
		width: 100%;
		border: 1px solid #555;
	}

	.tableku>tr>td {
		border: 1px solid #555;
		padding: 0px 0px;
		vertical-align: top;
	}

	.tableku thead th {
		border: 1px solid #555;
		border-bottom: 2px solid #555;
		padding: 0px 3px;
	}

	.tableku th {
		border: 1px solid #555;
		padding: 0px 3px;
	}

	.tableku thead,
	.tableku1 thead {
		border: 1px solid #555;
		page-break-before: auto;
	}

	hr {
		border-color: #555;
	}

	.tableku1 {
		margin-top: 0px;
		width: 100%;
		border: 1px solid #555;
	}

	.tableku1 td {
		border: 1px solid #555;
		padding: 3px 5px;
		vertical-align: top;
	}

	.tableku1 thead th {
		border: 1px solid #555;
		border-bottom: 2px solid #555;
		padding: 3px 5px;
		text-align: center;
	}

	.tableku1 th {
		border: 1px solid #555;
		padding: 0px 3px;
		text-align: center;
	}

	h4 small {
		color: #ccc;
	}
</style>