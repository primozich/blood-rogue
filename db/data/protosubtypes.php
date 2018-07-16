<?php
error_reporting(E_ERROR);
//require_once ('excel/reader.php');
require_once ('excel/excel_reader2.php');
require_once ('db_utils.php');


$data = new Spreadsheet_Excel_Reader("data.xls");

$debug = 0;
$tableName = 'm_item_protosubtypes';
$sheetNum = 2;
$colCount = $data->colcount($sheetNum);
$rowCount = $data->rowcount($sheetNum);

if ($debug)
{
	echo 'Number of columns: ' . $colCount . "\n";
	echo 'Number of rows: ' . $rowCount . "\n";
	exit;
}

// Assume 1st row is db col names and data starts on 2nd row

// Use 1st row to get col list
$colList = getDelimitedList($data->sheets[$sheetNum]['cells'][1], $colCount);
//print($colList);exit;

for($i= 2; $i<= $rowCount; $i++) {
	$sql = '';
	$values = getDelimitedList($data->sheets[$sheetNum]['cells'][$i], $colCount, true);

	$sql .= "insert into ".$tableName." (".$colList.", created, updated) values (".$values.", now(), now());";
	print($sql."\n");
}

function getUuid()
{
	$sql = "select uuid() as uuid";
	$result = mysql_fetch_assoc(db_execute_return($sql));
	$uuid = $result['uuid'];
	return $uuid;
}

function getDelimitedList($row, $colCount, $addQuotes = false)
{
/*
print('---------');
print("\n");
print_r($row);
print("\n");
*/
	$colList = '';
	for($j= 1; $j <= $colCount; $j++)
	{
		if ($j > 1)
		{
			$colList .= ', ';
		}
		if (!isset($row[$j]))
		{
			$colList .= "''";
		}
		else
		{
			if ($addQuotes)
			{
				$colList .= '\'';
			}
			$colList .= str_replace("'", "\'", $row[$j]);
			if ($addQuotes)
			{
				$colList .= '\'';
			}
		}
	}
	return $colList;
}

?>
