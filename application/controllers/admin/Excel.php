<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Excel extends CI_Controller {
	
	public function __construct()
	{
		parent::__construct();
	}

	function index()
	{
		// $this->excel_reader->read(ADMIN_EXCEL_PATH . '/test.xls');
		$this->excel_reader->read(ADMIN_EXCEL_PATH . '/politicians.csv');

		// Get the contents of the first worksheet
		$worksheet = $this->excel_reader->worksheets[0];

		var_dump($worksheet);

	/*
		var_dump (debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS));
		$this->read(ADMIN_ASSETS . 'excel/p1.xlsx');   // reads and stores the excel file data
		// $this->read('test.xls');   // reads and stores the excel file data

		// Test to see the excel data stored in $sheets property
		echo '<pre>';
		var_export($this->sheets);*/
		// echo '</pre>';
	}
	

}
