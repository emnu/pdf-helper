<?php
/**
* Required TCPDF (http://www.tcpdf.org/)
* put tcpdf folder at APP/VENDORS
* Required FPDI (https://www.setasign.com/products/fpdi/about/)
* put fpdi folder at APP/VENDORS
*/

App::uses('Helper', 'View');
App::import('Vendor', 'Tcpdf', array('file' => 'tcpdf/tcpdf.php'));
App::import('Vendor', 'Fpdi', array('file' => 'fpdi/fpdi.php'));

class PdfHelper extends Helper {

	public $helpers = array('Html');

	protected $pdf = null;

	protected $cells = 0;

	protected $columns = 12;

	protected $generatedCaption = 'This is computer generated';

	protected $generatedAllPage = true;

	protected $width = 0;

	protected $pageNum = false;

	protected $letterHead = true;

	/**
	* #P = current page
	* #T = number of pages
	*/
	protected $pageNumFormat = '#P/#T';

	protected $pageNumAlign = 'C';

	protected $pageOrientation = 'P'; //potrait

	protected $pdfUnit = 'mm'; //milimeter

	protected $pageFormat = 'A4'; //paper size

	protected $author = 'CakePHP';

	protected $title = 'CakePHP PDF Helper';

	protected $subject = 'Air Selangor Receipt/Bill';

	protected $keywords = 'Air Selangor, CakePHP, Receipt, Bill';

	protected $template = 'page_airselangor.pdf';

	protected $font = array(
			'family' => 'courier',
			'style' => '', // ('', B, I, U, D, O)
			'size' => 8.2
		);

	protected $lineStyle = array(
			'width' => 0.2, 
			'cap' => 'butt', // (butt, round, square)
			'join' => 'miter', // (miter, round, bevel)
			'dash' => 0, 
			'phase' => 0, 
			'color' => array(0, 0, 0)
		);

	protected $defaultsKey = array('generatedCaption', 'generatedAllPage', 'title', 
								'columns', 'pageNum', 'letterHead', 'pageNumFormat', 
								'pageNumAlign', 'pageOrientation', 'pdfUnit', 'pageFormat', 
								'author', 'subject', 'keywords', 'template', 'font', 'lineStyle'
							);

	private $assetDir;

	public function __construct(View $View, $settings = array()) {
		$this->assetDir = APP.'files'.DS.'templates'.DS;
		$this->setOptions($settings);

		// $this->pdf = new TCPDF($this->pageOrientation, $this->pdfUnit, $this->pageFormat, true, 'UTF-8', false);
		$this->pdf = new FPDI($this->pageOrientation, $this->pdfUnit, $this->pageFormat, true, 'UTF-8', false);
		$this->pdf->setSourceFile($this->assetDir.$this->template);

		$this->pdf->SetCreator(PDF_CREATOR);
		$this->pdf->SetAuthor($this->author);
		$this->pdf->SetTitle($this->title);
		$this->pdf->SetSubject($this->subject);
		$this->pdf->SetKeywords($this->keywords);

		$this->pdf->setPrintHeader(false);
		$this->pdf->setPrintFooter(false);

		$this->pdf->setFont($this->font['family'], $this->font['style'], $this->font['size']);

		$margins = $this->pdf->getMargins();
		$this->width = ($this->pdf->getPageWidth() - $margins['left'] - $margins['right']) / $this->columns;
	}

	/**
	* Override default setiings
	*
	* @param array $options see $defaultsKey
	* @return void
	*/
	public function setOptions($options) {
		foreach ($options as $key => $value) {
			if(in_array($key, $this->defaultsKey)) {
				if(is_array($this->{$key})) {
					$this->{$key} = array_merge($this->{$key}, $value);
				}
				else {
					$this->{$key} = $value;
				}
			}
		}

		if(!empty($this->pdf)) {
			$this->pdf->setFont($this->font['family'], $this->font['style'], $this->font['size']);

			$margins = $this->pdf->getMargins();
			$this->width = ($this->pdf->getPageWidth() - $margins['left'] - $margins['right']) / $this->columns;
		}
	}

	/**
	* Add new page
	*
	* @return void
	*/
	public function add() {
		$this->cells = 0;

		$this->pdf->AddPage();
		if($this->letterHead) {
			// $this->pdf->Image(APP.'webroot/img/letter_bg.png', 0, 65, 53, 200);
			// $this->pdf->Image(APP.'webroot/img/letter_footer.png', 77, 268, 50, 5);
			if($this->pdf->getPage() == 1) {
				$this->pdf->useTemplate($this->pdf->importPage(1));
				// $this->pdf->Image(APP.'webroot/img/topright2_23022016.png', 150, 10, 50, 66);
			}
			else {
				$this->pdf->useTemplate($this->pdf->importPage(2));
				// $this->pdf->Image(APP.'webroot/img/topright2_23022016.png', 150, 10, 50, 66);
				// $this->pdf->Image(APP.'webroot/img/topright_23022016_pg2.png', 150, 10, 50, 66);
			}
		}

		if(!empty($this->generatedCaption)) {
			if(!$this->generatedAllPage && $this->pdf->getPage() != 1) {
				$this->write('', array('span'=>$this->columns));
			}
			else {
				$this->write($this->generatedCaption, array('span'=>$this->columns, 'textColor'=>array(160,160,160)));
			}
		}
		else{
			$this->write('', array('span'=>$this->columns));
		}
	}

	/**
	* write text to document
	*
	* @param text $text text to write
	* @param array $options see $defaults
	* @return void
	*/
	public function write($text, $options = array()) {
		$defaults = array('span'=>1, 'textColor'=>array(0,0,0), 'style'=>'', 'size'=>'', 'family'=>'', 'border'=>0, 'ln'=>0, 'align'=>'L', 'fill'=>false, 'link'=>'', 'stretch'=>0, 'ignore_min_height'=>false, 'calign'=>'T', 'valign'=>'M', 'multi'=>false, 'x'=>'', 'y'=>'', 'reseth'=>true, 'stretch'=>0, 'ishtml'=>false, 'autopadding'=>true, 'maxh'=>0, 'fitcell'=>false);
		$options = array_merge($defaults, $options);

		$this->cells += $options['span'];

		list($colorR, $colorB, $colorG) = $options['textColor'];
		$this->pdf->SetTextColor($colorR, $colorB, $colorG);

		$family = (empty($options['family']))?$this->font['family']:$options['family'];
		$size = (empty($options['size']))?$this->font['size']:$options['size'];
		$style = (empty($options['style']))?$this->font['style']:$options['style'];
		$this->pdf->setFont($family, $style, $size);

		if($options['multi']) {
			$this->pdf->MultiCell($this->width*$options['span'], 0, $text, $options['border'], $options['align'], $options['fill'], $options['ln'], $options['x'], $options['y'], $options['reseth'], $options['stretch'], $options['ishtml'], $options['autopadding'], $options['maxh'], $options['valign'], $options['fitcell']);
		}
		else {
			$this->pdf->Cell($this->width*$options['span'], 0, $text, $options['border'], $options['ln'], $options['align'], $options['fill'], $options['link'], $options['stretch'], $options['ignore_min_height'], $options['calign'], $options['valign']);
		}

		if($this->cells >= $this->columns) {
			$this->nl();
		}

		if($options['ln'] == 1) {
			$this->cells = 0;
		}
	}

	/**
	* draw a straight line
	*
	* @param integer $column_from start column number
	* @param integer $column_to end column number
	* @return void
	*/
	public function line($column_from = 0, $column_to = 0, $styles = array(), $options = array()) {
		$defaults = array('y'=>0);
		$options = array_merge($defaults, $options);

		$margins = $this->pdf->getMargins();
		$y = $this->pdf->GetY() + $options['y'];

		if(!empty($column_from) && !empty($column_to)) {
			$this->pdf->Line($margins['left']+($this->width*($column_from-1)), $y, $margins['left']+($this->width*$column_to), $y, $styles);
		}
		else {
			$this->pdf->Line($margins['left'], $y, $margins['left']+($this->width*$this->columns), $y, $styles);
		}
		// set to default
		$this->pdf->SetLineStyle($this->lineStyle);
	}

	/**
	* draw a dash line
	*
	* @param integer $column_from start column number
	* @param integer $column_to end column number
	* @return void
	*/
	public function lineDash($column_from = 0, $column_to = 0, $styles = array()) {
		$styles['dash']  = '5,2';
		$this->line($column_from, $column_to, $styles);
	}

	/**
	* draw a double line
	*
	* @param integer $column_from start column number
	* @param integer $column_to end column number
	* @return void
	*/
	public function lineDouble($column_from = 0, $column_to = 0, $styles = array()) {
		$this->line($column_from, $column_to, $styles);
		$this->line($column_from, $column_to, $styles, array('y'=>0.5));
	}

	/**
	* set document pointer to new line
	*
	* @return void
	*/
	public function nl() {
		// $this->write('', array('span'=>$this->columns));
		$this->pdf->Ln();
		$this->cells = 0;
	}

	/**
	* Send the document to browser
	*
	* @param string $name set document name
	* @return void
	*/
	public function out($name = 'doc.pdf') {
		if($this->pageNum) {
			$margins = $this->pdf->getMargins();
			$pageTotal = $this->pdf->getNumPages();
			for($currentPage = 1; $currentPage <= $pageTotal; $currentPage++) {
				$this->pdf->setPage($currentPage, true);
				$this->pdf->SetY($this->pdf->getPageHeight() - $margins['bottom'] - 4);
				$pageStr = str_replace(array('#P', '#T'), array($currentPage, $pageTotal), $this->pageNumFormat);

				$this->write($pageStr, array('span'=>12, 'align'=>$this->pageNumAlign));
			}
		}
		$this->pdf->Output($name);
	}

	/**
	* generate a 1D barcode
	*
	* @param string $string barcode value
	* @param array $options see $defaults
	* @return void
	*/
	public function barcode($string, $options = array()) {
		$defaults = array('span'=>1, 'type'=>'C128', 'x'=>'', 'y'=>'', 'w'=>'', 'h'=>4, 'xres'=>'', 'style'=>'', 'align'=>'');
		$options = array_merge($defaults, $options);

		if(empty($options['x'])){
			$margins = $this->pdf->getMargins();
			$options['x'] = ($this->cells * $this->width) + $margins['left'];
		}

		$this->pdf->write1DBarcode($string, $options['type'], $options['x'], $options['y'], $options['w'], $options['h'], $options['xres'], $options['style'], $options['align']);

		$this->cells += $options['span'];
		if($this->cells >= $this->columns) {
			$this->nl();
		}
	}

}

