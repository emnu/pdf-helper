<?php

require_once('PdfHelper.php');

$pdf = new PdfHelper();
// $pdf = new PdfHelper(array('template' => 'page_syabas.pdf'));

$pdf->setOptions(array(
		'generatedCaption' => 'This is computer generated',
		'pageNum' => true,
		'pageNumFormat' => '#P of #T',
	));

$pdf->add();

for($i=0;$i<14;$i++)
	$pdf->nl();

$pdf->write('regular string', array('span'=>12));

$pdf->write('Bold string', array('style'=>'B', 'span'=>12));

$pdf->nl();
$pdf->write('straight line', array('span'=>12));
$pdf->line();
$pdf->nl();
$pdf->write('dash line', array('span'=>12));
$pdf->lineDash();
$pdf->nl();
$pdf->write('double line', array('span'=>12));
$pdf->lineDouble();
$pdf->setOptions(array(
		'columns' => 16,
	));
$pdf->nl();
$pdf->write('font style(size & font family)		:', array('family'=>'helvetica', 'span'=>12, 'size'=>13));
$pdf->write('font style(size & font )			:', array('family'=>'helvetica', 'span'=>12, 'size'=>13));
$pdf->setOptions(array(
		'columns' => 12,
	));
$pdf->nl();
$pdf->write('column 1', array('span'=>6, 'border'=>1));
$pdf->write('column 2', array('span'=>6, 'border'=>1));

$pdf->nl();
$pdf->write('multicell', array('span'=>12, 'style'=>'BIU', 'size'=>15));
$pdf->write('Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.', array('span'=>12, 'multi'=>true, 'ishtml'=>true));

$pdf->write('multicell with html element', array('span'=>12, 'style'=>'BIU', 'size'=>15));
$pdf->write('Lorem Ipsum is simply dummy text of the <span style="color:red;">printing and typesetting industry</span>. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, <strong>when an unknown printer took a galley of type and scrambled it to make a type specimen book.</strong> It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more <strike>recently with desktop publishing software like Aldus PageMaker</strike> including versions of Lorem Ipsum.', array('span'=>12, 'multi'=>true, 'ishtml'=>true));

$pdf->write('', array('span'=>12));
$pdf->write('multicell with 3 column justify', array('style'=>'B', 'span'=>12));
$pdf->line(1,3);
$pdf->write('Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.', array('span'=>4, 'multi'=>true));
$pdf->write('Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.', array('span'=>4, 'multi'=>true, 'align'=>'J'));
$pdf->write('Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker.', array('span'=>4, 'multi'=>true, 'align'=>'R'));

$pdf->write('', array('span'=>12));
$pdf->write('1D barcode', array('span'=>12));
$pdf->barcode('123456789', array('span'=>12));

$pdf->write('', array('span'=>12));
$pdf->write('2 1D barcode in 1 line', array('span'=>12));
$pdf->barcode('123456789', array('span'=>5));
$pdf->barcode('123456789', array('span'=>7));

$pdf->add();
for($i=0;$i<14;$i++)
	$pdf->nl();
$pdf->write('New PAGE', array('style'=>'B', 'span'=>12, 'size'=>22, 'align'=>'C'));

$pdf->add();
for($i=0;$i<14;$i++)
	$pdf->nl();
$pdf->write('New PAGE2', array('style'=>'B', 'span'=>12, 'size'=>22, 'align'=>'C'));

$pdf->out();