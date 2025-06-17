
$(document).ready(function () {
  // url file pdf
  var url = $('.url').attr('id');

  $(document).ready(function () {
    $('#download').click(function () {
      var link = document.createElement('a');
      link.href = url;
      link.download = 'file.pdf';
      link.dispatchEvent(new MouseEvent('click'));
    })
  });

  // Loaded via <script> tag, create shortcut to access PDF.js exports.
  var pdfjsLib = window['pdfjs-dist/build/pdf'];

  // The workerSrc property shall be specified.
  // pdfjsLib.GlobalWorkerOptions.workerSrc = '//mozilla.github.io/pdf.js/build/pdf.worker.js';
  pdfjsLib.GlobalWorkerOptions.workerSrc = base_url+'assets/pdf/pdfjs.js';

  // Asynchronous download of PDF
  var loadingTask = pdfjsLib.getDocument(url);
  // console.log(loadingTask);
  loadingTask.promise.then(function (pdf) {
    var jumlah_halaman = pdf._pdfInfo.numPages;
    // console.log('PDF loaded');

    // Fetch the first page
    var pageNumber = 1;
    for (let pageNumber = 1; pageNumber < pdf._pdfInfo.numPages+1; pageNumber++) {


      var pageNumber2 = pageNumber + 1;
      var html = '<canvas id="the-canvas' + pageNumber2 + '" class="the-canvas"></canvas>';
      $('#the-canvas' + pageNumber).after(html);

      pdf.getPage(pageNumber).then(function (page) {

        var scale = 1.5;
        var viewport = page.getViewport({ scale: scale });

        // Prepare canvas using PDF page dimensions
        var canvas = document.getElementById('the-canvas' + pageNumber);
        var context = canvas.getContext('2d');
        canvas.height = viewport.height;
        canvas.width = viewport.width;

        // Render PDF page into canvas context
        var renderContext = {
          canvasContext: context,
          viewport: viewport
        };
        page.render(renderContext);
        // var renderTask = page.render(renderContext);
        // renderTask.promise.then(function () {
        //   // console.log('Page rendered');
        // });
      });
    }
    var remove = pdf._pdfInfo.numPages+1;
    $('#the-canvas'+remove).remove()
  }, function (reason) {
    // PDF loading error
    console.error(reason);
  });
})