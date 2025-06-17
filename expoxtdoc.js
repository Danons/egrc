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
        navigator.msSaveOrOpenBlob(blob, 'LHP 2023-12-09.docx'); // IE10-11
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
