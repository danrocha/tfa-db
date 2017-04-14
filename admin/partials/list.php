<?php
/**
 * Displays a list of the required data
 *
 * Long description
 *
 * @package    SPMM
 */


$capitalized_area = ucfirst($this->area);
$table_name = "tfa_$this->area";

echo "<h2>$capitalized_area</h2>";
 
$rows = $this->list_data( $table_name ); 

//Resets the internal pointer, return the first elem.
$first_row = reset($rows); 

//define header with the column names
$header_str = '<th>' . implode('</th><th>', array_keys($first_row)) . '</th>';

//assemble body array
$table_body_rows = array();
foreach($rows as $row){
	$table_body_rows[] = 	'<td>' . 
							implode('</td><td>', $row) . 
							'</td>';
}

$body_str = '<tr>' . implode('</tr><tr>', $table_body_rows) . '</tr>';

$tbl = "<table><thead><tr>$header_str</tr></thead><tbody>$body_str</tbody></table>";

echo $tbl;

?>