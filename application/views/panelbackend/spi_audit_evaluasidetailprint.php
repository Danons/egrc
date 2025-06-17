<div id="content" style="width: 100%;">
    <div class="row" style=" width: 100%; padding: 5px 0px;">
        <div style="margin: 20px; border-bottom: 2px solid black;">
            <div class="" style="display: flex; justify-content: center; gap: 20px;">
                <img src="../../../assets/images/logo.png" alt="logo1" class="img-fluid" style="width: 130px;">
                <div style="display: flex; flex-direction: column; justify-content: center;">
                    <h1 class="font-weight-bold" style="font-size: 25px; margin: 0;">PERUMDA AIR MINUM TIRTA RAHARJA</h1>
                    <p class="" style="font-size: 14px;  margin: 0; font-weight: bold;">SATUAN PENGAWAS INTERN</p>
                </div>
            </div>
            <p class="text-center" style="font-size: 13px;">Dengan Pelayanan Prima Menjadi Perumda Air Minum Termaju, Dinamis, dan Berkelanjutan</p>
        </div>
    </div>
    <div class="row" style=" width: 100%;">
        <div style=" display: flex; justify-content: space-between; width: 100%; font-size: 15px;">
            <table style="margin: 20px 20px ; ">
                <tr>
                    <td style="font-size: 15px; vertical-align:top">Nomor</td>
                    <td style="font-size: 15px;vertical-align:top">:</td>
                    <td style="font-size: 15px; vertical-align:top"><?= $row['nomor']; ?></td>
                </tr>
                <tr>
                    <td style="font-size: 15px; vertical-align:top">Lampiran</td>
                    <td style="font-size: 15px; vertical-align:top">:</td>
                    <td style="font-size: 15px; vertical-align:top"><?= $row['lampiran']; ?></td>
                </tr>
                <tr>
                    <td style="font-size: 15px; vertical-align:top">Hal</td>
                    <td style="font-size: 15px; vertical-align:top">:</td>
                    <td style="font-size: 15px; vertical-align:top"><?= $row['hal']; ?> WIB</td>
                </tr>
            </table>

            <p style="margin: 20px 20px ;">

                tanggal : </br> <?= $hari_ini . "&nbsp;" . Eng2Ind($row['tanggal']); ?>
            </p>

        </div>
        <h1 style="font-weight: bold; text-align: center; font-size: 15px;">BAB I </br>
            SIMPULAN DAN SARAN</h1>
        <div class="parentp" style="margin: 20px 20px ; padding-left: 3px; font-size: 15px;">
            <p style="font-weight: bolder;">SIMPULAN</p>
            <?= $row['simpulan'] ?>
            <p style="font-weight: bolder; margin-top: 5px;">SARAN</p>
            <?= $row['saran'] ?>
        </div>
        <div style=" display: flex; justify-content: end; width: 100%; font-size: 15px;">
            <div style="display: flex; flex-direction: column; align-items: center; margin: 20px 20px ;">
                <p>MANAJER SENIOR SPI,</p>
                <p style="margin-top: 60px;"><?= $manajerspi ?></p>
            </div>

        </div>

        <h1 style="font-weight: bold; text-align: center; font-size: 15px;">BAB II </br>
            URAIAN HASIL EVALUASI/AUDIT/REVIU</h1>
        <div class="parentp" style="margin: 20px 20px ; padding-left: 3px; font-size: 15px;">
            <p style="font-weight: bolder;">A. DASAR TUGAS</p>
            <?= $row['dasar_tugas'] ?>
            <p style="font-weight: bolder; margin-top: 5px;">B. DASAR EVALUASI/AUDIT</p>
            <?= $row['dasar_evaluasi'] ?>
            <p style="font-weight: bolder; margin-top: 5px;">C. CAKUPAN EVALUASI/AUDIT</p>
            <?= $row['cakupan_evaluasi'] ?>
            <p style="font-weight: bolder; margin-top: 5px;">D. INFORMASI UMUM</p>
            <?= $row['informasi_umum'] ?>
            <p style="font-weight: bolder; margin-top: 5px;">E. HASIL EVALUASI/AUDIT</p>
            <?= $row['hasil_evaluasi'] ?>

        </div>

    </div>
</div>

<script src="<?php echo base_url() ?>assets/js/html-docx.js"></script>
<script>
    // var documentElement = document.getElementById("content");

    // function exportDocxFile() {

    //     if (!window.Blob) {
    //         alert('Your legacy browser does not support this action.');
    //         return;
    //     }
    //     var processedDocumentElement = convertImagesToBase64(documentElement);
    //     var html = processedDocumentElement.innerHTML;
    //     var html = documentElement.innerHTML;
    //     var blob = htmlDocx.asBlob(html);
    //     var url = URL.createObjectURL(blob);
    //     var link = document.createElement('A');
    //     link.href = url; // Set default file name. 
    //     // Word will append file extension - do not add an extension here.        link.download = 'Document.docx';
    //     document.body.appendChild(link);
    //     // console.log(window.navigator.msSaveOrOpenBlob)
    //     if (window.navigator.msSaveOrOpenBlob) {
    //         navigator.msSaveOrOpenBlob(blob, 'document.docx'); // IE10-11        } else {
    //         link.click(); // other browsers        }
    //         document.body.removeChild(link);
    //     } else {
    //         link.download = 'Document.docx';
    //         link.click();
    //         URL.revokeObjectURL(link.href)
    //         link.remove();
    //     }

    //     function convertImagesToBase64(targetDocumentElement) {
    //         var clonedDocumentElement = targetDocumentElement.cloneNode(true);

    //         var regularImages = targetDocumentElement.querySelectorAll("img");
    //         var clonedImages = clonedDocumentElement.querySelectorAll("img");
    //         var canvas = document.createElement('canvas');
    //         var ctx = canvas.getContext('2d');
    //         for (var i = 0; i < regularImages.length; i++) {
    //             var regularImgElement = regularImages[i];
    //             var clonedImgElement = clonedImages[i];
    //             ctx.clearRect(0, 0, canvas.width, canvas.height);
    //             canvas.width = regularImgElement.width;
    //             canvas.height = regularImgElement.height;
    //             ctx.scale(regularImgElement.width / regularImgElement.naturalWidth, regularImgElement.height / regularImgElement.naturalHeight);
    //             ctx.drawImage(regularImgElement, 0, 0);
    //             // by default toDataURL() produces png image, but you can also export to jpeg
    //             // checkout function's documentation for more details            
    //             var dataURL = canvas.toDataURL();
    //             clonedImgElement.setAttribute('src', dataURL);
    //         }
    //         canvas.remove();
    //         return clonedDocumentElement;
    //     }
    // }

    // function exportDocxFile() {
    //     const link = document.getElementById("content")
    //     const blob = htmlDocx.asBlob(document.documentElement.outerHTML)
    //     link.href = URL.createObjectURL(blob)
    // }
</script>
<style>
    * {
        margin: 0px;
    }

    .parentp>p {
        margin: 0px;
    }
</style>