<h1>Links</h1>

<?php
if ( !isset( $_POST[ 'action' ] ) ) {

	echo '<form action="' . esc_url( $_SERVER['REQUEST_URI'] ) . '" method="post">';
	echo '<p>Pick a building to add/edit/remove links to:</p>';

	$buildings = $this->list_buildings_names_ids();

	echo "<select name='building_id'>";

    foreach ($buildings as $row) {
				//echo "oi";
				//print_r($row);

        unset($id, $name);
        $id = $row[ 'id' ];
        $name = $row[ 'name' ];

        echo '<option value="' . $id . '" >' . $name . '</option>';
		}

  echo "</select>";
	echo '<p><button name="action" value="links">OK</button></p></form>';
	echo '</form>';


}else{

	$building_id = $_POST[ "building_id" ];
	$building_name = $this->get_building_name( $building_id );

	//add links if any
	if ( $_POST[ 'add' ] == 1 ) {
		$result = $this->add_building_link( $building_id, $_POST["link_title"], $_POST["link_url"] );
		echo '<p class="result">' . $result . '</p>';

	} elseif ( $_POST['delete'] = 1 ) {
		$result = $this->delete_building_link( $_POST['link_id'] );
		echo '<p class="result">' . $result . '</p>';
		
	}

	echo "<p>Selected building:</p>";
	echo $building_id . " - " . $building_name;
	echo "<h3>Manage Links</h3>";

	echo '<div class="datagrid">';
	echo '<table><thead><tr><th></th><th>Link Title</th><th>URL</th></tr></thead>';

	$links = $this->get_building_links( $building_id );
	//echo "hello";
	//print_r( $links );

	if ( $links ){
		foreach( $links as $link){
			echo "<tr>";
			echo '<td>';
			echo '<form action="' . esc_url( $_SERVER['REQUEST_URI'] ) . '" method="post">';
			echo '<input type="hidden" name="link_id" value="'. $link[ "id" ] . '">';
			echo '<input type="hidden" name="building_id" value="'. $building_id . '">';
			echo '<input type="hidden" name="delete" value=1>';
			echo '<button name="action" value="delete">del</button>';
			echo '</form></td>';
			echo "<td>". $link["title"] . "</td>";
			echo '<td><a href=' . $link["url"] . '" target="_blank">' . $link["url"] . '</a></td>';
			echo "</tr>";
		}

	}
	echo '<form action="' . esc_url( $_SERVER['REQUEST_URI'] ) . '" method="post">';
	echo "<tr>";
	echo '<td>';

	echo '<input type="hidden" name="building_id" value="'. $building_id . '">';
	echo '<input type="hidden" name="add" value=1>';
	echo '<button name="action" value="add">add</button>';
	echo '</td>';
	echo '<td><input type="text" name="link_title" value="" placeholder="Link Name" size="20" /></td>';
	echo '<td><input type="text" name="link_url" value="" placeholder="http://" size="50" /></td>';
	echo '</tr></form>';
	echo "</table>";
	echo "</div>";

}
?>
