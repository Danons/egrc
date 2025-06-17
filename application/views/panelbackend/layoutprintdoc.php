<div class="notshow">
    <center>
        <a class="btn btn-sm btn-primary" onclick="window.print()">
            <span class="bi bi-printer"></span>
            Print
        </a>
        <!-- <button class="btn btn-sm btn-primary" onclick="Export2Word('content', 'word-content');">Export as .docx</button> -->
        <button class="btn btn-sm btn-primary" onclick="exportDocxFile();">Export as .docx</button>
    </center>
</div>
<div style="max-width: 800px;margin:auto" id="content">
    <?php echo $content1; ?>
</div>
<script>
    function Export2Word(element, filename = '') {
        //  _html_ will be replace with custom html
        var meta = "Mime-Version: 1.0\nContent-Base: " + location.href + "\nContent-Type: Multipart/related; boundary=\"NEXT.ITEM-BOUNDARY\";type=\"text/html\"\n\n--NEXT.ITEM-BOUNDARY\nContent-Type: text/html; charset=\"utf-8\"\nContent-Location: " + location.href + "\n\n\n<html>\n_html_</html>";
        //  _styles_ will be replaced with custome css
        var head = "<head>\n<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">\n<style>\n_styles_\n</style>\n</head>\n";

        var html = document.getElementById(element).innerHTML;

        var blob = new Blob(['\ufeff', html], {
            type: 'application/msword'
        });

        var css = (
            '<style>' +
            'img {width:300px;}table {border-collapse: collapse; border-spacing: 0;}td{padding: 6px;}' +
            '</style>'
        );
        //  Image Area %%%%
        var options = {
            maxWidth: 624
        };
        var images = Array();
        var img = $("#" + element).find("img");
        for (var i = 0; i < img.length; i++) {
            // Calculate dimensions of output image
            var w = Math.min(img[i].width, options.maxWidth);
            var h = img[i].height * (w / img[i].width);
            // Create canvas for converting image to data URL
            var canvas = document.createElement("CANVAS");
            canvas.width = w;
            canvas.height = h;
            // Draw image to canvas
            var context = canvas.getContext('2d');
            context.drawImage(img[i], 0, 0, w, h);
            // Get data URL encoding of image
            var uri = canvas.toDataURL("image/png");
            $(img[i]).attr("src", img[i].src);
            img[i].width = w;
            img[i].height = h;
            // Save encoded image to array
            images[i] = {
                type: uri.substring(uri.indexOf(":") + 1, uri.indexOf(";")),
                encoding: uri.substring(uri.indexOf(";") + 1, uri.indexOf(",")),
                location: $(img[i]).attr("src"),
                data: uri.substring(uri.indexOf(",") + 1)
            };
        }

        // Prepare bottom of mhtml file with image data
        var imgMetaData = "\n";
        for (var i = 0; i < images.length; i++) {
            imgMetaData += "--NEXT.ITEM-BOUNDARY\n";
            imgMetaData += "Content-Location: " + images[i].location + "\n";
            imgMetaData += "Content-Type: " + images[i].type + "\n";
            imgMetaData += "Content-Transfer-Encoding: " + images[i].encoding + "\n\n";
            imgMetaData += images[i].data + "\n\n";

        }
        imgMetaData += "--NEXT.ITEM-BOUNDARY--";
        // end Image Area %%

        var output = meta.replace("_html_", head.replace("_styles_", css) + html) + imgMetaData;

        var url = 'data:application/vnd.ms-word;charset=utf-8,' + encodeURIComponent(output);


        filename = filename ? filename + '.doc' : 'document.doc';


        var downloadLink = document.createElement("a");

        document.body.appendChild(downloadLink);

        if (navigator.msSaveOrOpenBlob) {
            navigator.msSaveOrOpenBlob(blob, filename);
        } else {

            downloadLink.href = url;
            downloadLink.download = filename;
            downloadLink.click();
        }

        document.body.removeChild(downloadLink);
    }

    var documentElement = document.getElementById("content");

    function exportDocxFile() {
        if (!window.Blob) {
            alert('Your legacy browser does not support this action.');
            return;
        }

        // var processedDocumentElement = convertImagesToBase64(documentElement);

        // var html = processedDocumentElement.innerHTML;
        var html = documentElement.innerHTML;
        var blob = htmlDocx.asBlob(html);

        var url = URL.createObjectURL(blob);
        var link = document.createElement('A');

        link.href = url;
        // Set default file name. 
        // Word will append file extension - do not add an extension here.
        link.download = 'Document';

        document.body.appendChild(link);

        if (navigator.msSaveOrOpenBlob) {
            navigator.msSaveOrOpenBlob(blob, 'LHP <?= date('Y-m-d') ?>.docx'); // IE10-11
        } else {
            link.click(); // other browsers
        }

        document.body.removeChild(link);
    }

    function convertImagesToBase64(targetDocumentElement) {
        var clonedDocumentElement = targetDocumentElement.cloneNode(true);

        var regularImages = targetDocumentElement.querySelectorAll("img");
        var clonedImages = clonedDocumentElement.querySelectorAll("img");
        var canvas = document.createElement('canvas');
        var ctx = canvas.getContext('2d');

        for (var i = 0; i < regularImages.length; i++) {
            var regularImgElement = regularImages[i];
            var clonedImgElement = clonedImages[i];

            ctx.clearRect(0, 0, canvas.width, canvas.height);

            canvas.width = regularImgElement.width;
            canvas.height = regularImgElement.height;

            ctx.scale(regularImgElement.width / regularImgElement.naturalWidth, regularImgElement.height / regularImgElement.naturalHeight);
            ctx.drawImage(regularImgElement, 0, 0);

            // by default toDataURL() produces png image, but you can also export to jpeg
            // checkout function's documentation for more details
            var dataURL = canvas.toDataURL();

            clonedImgElement.setAttribute('src', dataURL);
        }

        canvas.remove();

        return clonedDocumentElement;
    }
</script>
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