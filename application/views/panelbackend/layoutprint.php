    <div class="notshow">
        <center>
            <a class="btn btn-sm btn-primary" onclick="window.print()">
                <span class="bi bi-printer"></span>
                Print
            </a>
        </center>
    </div>
    <div style="max-width: 800px;margin:auto">
        <?php echo $content1; ?>
    </div>
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

        .tableku td {
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
            page-break-before: always;
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