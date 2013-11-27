This is the modified version of the A4_barcode_1x16.php layout file located in /var/www/openbiblio/layouts/default/A4_barcode_1x16.php


Values will be diferent , provided the fact that you will use a diferent printer than in this setup. 

Our labels dimensions:
- height => 2inches = 5cm = 144pt
- width => 3inches = 7.5cm = 216pt 

While browsing through layout files we can see that openbiblio coders used several measuring units acros their files and not a generic one. It is important to know that you can use pt (points), in(inches), cm(centimeters) and mm (milimeters). I'm not sure if you can use others, but i only came acros these (and really, who would need more?)

I modified dimensions and margins so that our labels will be printed nicely. Check lines 37, 38, 43, 50, 54, 62 and 67.


<?php
/* This file is part of a copyrighted work; it is distributed with NO WARRANTY.
 * See the file COPYRIGHT.html for more details.
 */

// Multi A4 1x16 Avery L7674

require_once('../classes/Lay.php');

class Layout_A4_barcode_1x16 {
  var $p;
  function paramDefs() {
    return array(
      array('string', 'skip', array('title'=>'Skip Labels', 'default'=>'0')),
    );
  }
  function init($params) {
    $this->p = $params;
  }
  function render($rpt) {
    $lay = new Lay('A4');
      $lay->container('Columns', array(
        'margin-top'=>'2mm', 'margin-bottom'=>'2mm',
        'margin-left'=>'2mm', 'margin-right'=>'2mm',
      ));
        list(, $skip) = $this->p->getFirst('skip');
        for ($i = 0; $i < $skip; $i++) {
          $lay->container('Line', array(
            'height'=>'25mm', 'width'=>'144.8mm',
          ));
          $lay->close();
        }
     
  while ($row = $rpt->each()) {
            $lay->container('Column', array(
              'height'=>'2in', 'width'=>'3in',
              'y-align'=>'center',
            ));
              $lay->container('TextLine', array('x-align'=>'center'));
                $lay->pushFont('Times-Roman', 20);
                  if (strlen($row['title']) > 30) {
                    $row['title'] = substr($row['title'], 0, 30)."...";
                  }
                  $lay->text($row['title']);
                $lay->popFont();
              $lay->close();
              $lay->container('TextLine', array('x-align'=>'center'));
                $lay->pushFont('Code39JK', 48);
                  $lay->text('*'.strtoupper($row['barcode_nmbr']).'*');
                $lay->popFont();
              $lay->close();
              $lay->container('TextLine', array('x-align'=>'center'));
                $lay->pushFont('Courier', 20);
                  $lay->text(strtoupper($row['barcode_nmbr']));
                $lay->popFont();
              $lay->close();
            $lay->close();
          }
          
      $lay->close();
    $lay->close();
  }
}

?>
