<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// class Pdf extends PD_Pdf2text {
class Pdf extends PD_Pdfparser {
	
	public function __construct()
	{
		parent::__construct();
	}

	function index()
	{
		$file = PD_Pdfparser::parseFile(ADMIN_ASSETS . 'pdf/p1.pdf');
		// $file = PD_Pdfparser::parseContent(ADMIN_ASSETS . 'pdf/p1.pdf');
		print_r($file);
		// $a = new PDF2Text();
		/*$this->setFilename(ADMIN_ASSETS . 'pdf/p1.pdf');
		$this->decodePDF();
		echo $this->output();*/
	}
	

}
