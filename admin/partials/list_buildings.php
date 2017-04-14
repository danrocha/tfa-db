<h2>Buildings</h2>

<table id="buildings-data">
	<tr>
		<th>Building Name</th><th>Architect(s)</th><th>Location</th>
	</tr>
	
	
	<?php
	
	//get buildings
	$rows_buildings = $this->list_buildings( );
	
	//print_r( $rows_buildings );
	
	foreach( $rows_buildings as $row_building ) {
		
		echo "<tr>";
		echo "<td><a href='$row_building[building_website_official]'' title='$row_building[building_website_official]'>
					$row_building[building_name]
				</a></td>";
		echo "<td>";
		
		//get architects
		$rows_architects = $this->get_architects( $row_building[building_id] );
		//print_r( $rows_architects );
		foreach ( $rows_architects as $row_architect ) {
			echo "<a href='$row_architect[architect_website]'' title='$row_architect[architect_website]'>
					$row_architect[architect_name]</a><br/>";
		}
		echo "<td><a href='$row_building[gmaps_link]'>Google Maps</a> / $row_building[city] ($row_building[country_code])</td>";
		echo "</tr>";
	}


	?>
	
</table>