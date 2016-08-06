function makePDF($module,$date) {
 $.ajax({
  async:false,
  type:'POST',
  dataType:'text',
  contentType:'application/x-www-form-urlencoded',
  url:'./includes/pdf/pdf.js',
  error:function(){
  },
  success:function($data){
   eval($data);
  }
 });
}

function makePDF2($date) {
    var pdf = new jsPDF('l','px'),
        source = $('div[id=ui-window-'+$date+']');

    pdf.addHTML(
          source, 0, 0, {
              pagesplit: true
          },
          function(dispose){
              pdf.save('test.pdf');
          }
      );
}

function makePDF3($module,$date) {
    var canvasToImage = function(canvas){
        var img = new Image();
        var dataURL = canvas.toDataURL('image/png');
        img.src = dataURL;
        return img;
    };
    var canvasShiftImage = function(oldCanvas,shiftAmt){
        shiftAmt = parseInt(shiftAmt) || 0;
        if(!shiftAmt){ return oldCanvas; }

        var newCanvas = document.createElement('canvas');
        newCanvas.height = oldCanvas.height - shiftAmt;
        newCanvas.width = oldCanvas.width;
        var ctx = newCanvas.getContext('2d');

        var img = canvasToImage(oldCanvas);
        ctx.drawImage(img,0, shiftAmt, img.width, img.height, 0, 0, img.width, img.height);

        return newCanvas;
    };


    var canvasToImageSuccess = function(canvas){
        $orientation=$('div[id=ui-window-'+$date+']').attr('pageOrientation');
        if($orientation=='p'||$orientation=='portrait')
         $orientation=='p';
        else $orientation=='l';
        var pdf = new jsPDF($orientation,'pt','a4',true),
            pdfInternals = pdf.internal,
            pdfPageSize = pdfInternals.pageSize,
            pdfScaleFactor = pdfInternals.scaleFactor,
            pdfPageWidth = pdfPageSize.width,
            pdfPageHeight = pdfPageSize.height,
            totalPdfHeight = 0,
            htmlPageHeight = canvas.height,
            htmlScaleFactor = canvas.width / (pdfPageWidth * pdfScaleFactor),
            safetyNet = 0;

        while(totalPdfHeight < htmlPageHeight && safetyNet < 15){
            var newCanvas = canvasShiftImage(canvas, totalPdfHeight);
            pdf.addImage(newCanvas, 'png', 0, 0, pdfPageWidth, 0, null, 'SLOW');
            totalPdfHeight+=(pdfPageHeight * pdfScaleFactor * htmlScaleFactor);

            if(totalPdfHeight < htmlPageHeight){
                pdf.addPage();
            }
            safetyNet++;
        }

        pdf.save($module+'_'+$date+'.pdf');
    };

    html2canvas($('div[id=ui-window-'+$date+']'), {
        onrendered: function(canvas){
            canvasToImageSuccess(canvas);
        }
    });
}