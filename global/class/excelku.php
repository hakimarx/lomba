<?php

function exceltoarray($letakfile){

	//harus .xlsx

	// include the autoloader, so we can use PhpSpreadsheet
	require_once('../assets/vendor/autoload.php');
	# Create a new Xls Reader
	$reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();

	// Tell the reader to only read the data. Ignore formatting etc.
	$reader->setReadDataOnly(true);

	// Read the spreadsheet file.
	$spreadsheet = $reader->load($letakfile);

	$sheet = $spreadsheet->getSheet($spreadsheet->getFirstSheetIndex());
	$data = $sheet->toArray();

	// output the data to the console, so you can see what there is.
	array_shift($data);
	return $data;
}

?>