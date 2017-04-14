<h2>Links</h2>

<?php 
if ( !isset( $_POST['building_id'] ) ) {

	echo '<form action="' . esc_url( $_SERVER['REQUEST_URI'] ) . '" method="post">';
	echo '<p>Pick a building to add/edit/remove links to:</p>';

	$buildings = $this->get_building_names( );
	//print_r( $buildings );
	echo "<select name='building_id'>";

    foreach ($buildings as $row) {
				//echo "oi";
				//print_r($row);

                  unset($id, $name);
                  $id = $row['building_id'];
                  $name = $row['building_name']; 
		
                  echo '<option value="' . $id . '|' . $name . '" >' . $name . '</option>';
				
                 
	}

    echo "</select>";
	echo '<p><input type="submit" name="cf-submitted" value="OK"></p>';
	echo '</form>';


}else{
	
	$building_data = explode('|', $_POST['building_id']);
	$building_id = $building_data[0];
	$building_name = $building_data[1];
	
	//add links if any
	if ( $_POST['link_url'] ) {
		$this->add_building_link( $building_id, $_POST["link_title"], $_POST["link_url"] );
	}
	if ( $_POST['delete'] ) {
		$this->delete_building_link( $_POST['delete'] );
	}
	
	echo "<p>Selected building:</p>";
	echo $building_id . " - " . $building_name;
	echo "<h3>Manage Links</h3>";
	echo '<form action="' . esc_url( $_SERVER['REQUEST_URI'] ) . '" method="post">';
	
	echo "<table><tr><th>Delete?</th><th>Link Title</th><th>URL</th></tr>";
	
	$links = $this->get_building_links( $building_id );
	//echo "hello";
	//print_r( $links );

	if ( $links ){
		foreach( $links as $link){
			echo "<tr>";
			echo '<td><input name="delete[' . $link['id'] . ']" type="checkbox"></td>';
			echo "<td>". $link["title"] . "</td>";
			echo '<td><a href=' . $link["url"] . '" target="_blank">' . $link["url"] . '</a></td>';
			echo "</tr>";
		}
		
	}
	
	echo "<tr>";
	echo '<td><input type="text" name="link_title" pattern="[a-zA-Z0-9 ]+" value="" size="20" /></td>';
	echo '<td><input type="text" name="link_url" pattern="[a-zA-Z0-9 ]+" value="" size="40" /></td>';
	echo '</tr>';
	
	echo '<input type="hidden" name="building_id" value="' . $_POST['building_id'] . '">';
	echo "</table>";
	echo '<p><input type="submit" name="cf-submitted" value="Add"></p>';
	echo "</form>";
	
}
?>
