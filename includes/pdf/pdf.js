
var $pdf=new jsPDF('l','px','a4',true);
$pdf.setProperties({
 title:'Test PDF Document',
 subject:'This is the subject',
 author:'German Bernhardt german.bernhardt@gmail.com',
 keywords:'getware',
 creator:'getware'
});
$pdf.setFont('helvetica');
$pdf.setFontType('bold');
$pdf.setFontSize(7);
$('div[id=ui-window-'+$date+'] table')
 .each(function(){  if(typeof($leftFirst)=='undefined'){   $left=20;$top=10;
   $leftFirst=parseInt($(this).offset().left);
   $topFirst=parseInt($(this).offset().top);
  }else{   $left=parseInt($(this).offset().left)-$leftFirst;
   $top=parseInt($(this).offset().top)-$topFirst;  }
  $width=$(this).width();
  $height=$(this).height();
  $pdf.text($left,$top,$(this).text());
  $pdf.rect($left,$top,$width,$height);
  /*
  var logo = new Image();
  logo.src = 'includes/barcode.php?code=000000000044512';
  $pdf.addImage(logo,'GIF', 10, 10, 125, 20);
  */
 });
$pdf.save($module+'.pdf');