<div id="container" class="container" <?= ($width_page ? "style='max-width:$width_page'" : "") ?>>
    <center>
        <div class="notshow">
            <?php /*if ($excel !== false) { ?>
				<a download="<?= $page_title ?>.xls" class="btn btn-sm btn-primary" href="#" onclick="return ExcellentExport.excel(this, 'datatable', '<?= strip_tags(str_replace("\n", "", $page_title)) ?>');">
					<span class="bi bi-th"></span> Excel</a>
				&nbsp;
			<?php }*/ ?>
            <a class="btn btn-sm btn-primary" onclick="window.print()">
                <span class="bi bi-printer"></span>
                Print
            </a>
        </div>
    </center>

    <div id="datatable">
        <table class="tableku">
            <thead>
                <tr>
                    <th colspan="6">
                        PERUMDA AIR MINUM TIRTA RAHARJA
                        <br />
                        SATUAN PENGAWASAN INTERN
                        <br />
                    </th>
                </tr>
            </thead>
            <tr>
                <td colspan="6">
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

    .tableku>tbody>tr>td,
    .tableku>tr>td {
        border: 1px solid #fff;
        padding: 0px 0px;
        vertical-align: top;
    }

    .tableku>thead>tr>th,
    .tableku>tr>th,
    .tableku>thead>tr>td,
    .tableku>tr>td {
        border: 1px solid #fff;
        padding: 0px 3px;
    }

    .tableku thead {
        page-break-before: auto;
    }



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