<h1>Buildings Management</h1>
<hr/>
<form action="<?php esc_url( $_SERVER['REQUEST_URI'] ); ?>" method="post">
	<button name="action" value="add_new">Add New Building</button>
</form>
<hr/>
<?php

$action = "";
$id = "";
$name = "";
$website = "";

if ( isset( $_POST['action'] ) ) {
  if ( $_POST['action'] == "edit_architects" ) {

		// check if architect was added
		if (  $_POST[ "add_architect" ] == 1 ) {
			//add architect
			$result = $this->add_architect_building( $_POST[ "building_id" ], $_POST[ "architect_id" ] );
			echo '<p class="result">' . $result . '</p>';

		}
		// check if architect was added
		if (  $_POST[ "delete_architect" ] == 1 ) {
			//add architect
			$result = $this->delete_architect_building( $_POST[ "ab_id" ] );
			echo '<p class="result">' . $result . '</p>';

		}




		echo "<h2>Edit building architects:</h2>";
		//display building name
		echo "<p><strong>" . $_POST['building_name'] . "</strong></p>";
		//list architects
		$architects = $this->get_building_architects( $_POST['building_id'] );
		$num_architects = count( $architects );
		if ( $num_architects > 0 ) {
			//display architects
			echo "<p>Architects associated with this building:</p>";
			echo "<table>";
			foreach ( $architects as $architect ) {
				//edit delete buttons
				echo '<tr><td>';
				echo '<form action="' . esc_url( $_SERVER['REQUEST_URI'] ) . '" method="post">';
				echo '<input type="hidden" name="ab_id" value="'. $architect[ "ab_id" ] . '">';
				echo '<input type="hidden" name="building_id" value="'. $_POST[ "building_id" ] . '">';
				echo '<input type="hidden" name="building_name" value="'. $_POST[ "building_name" ] . '">';
				echo '<input type="hidden" name="delete_architect" value=1>';
				echo '<button name="action" value="edit_architects">del</button>';
				echo '</form>';
				echo '</td>';
				//architect name
				echo "<td>" . $architect[ "architect_name" ] . "</td><tr>";
			}
			echo "</table>";
		} else {
			echo "(no architects associated with this building)";
		}

		//display dropdown with architect names
		echo "<p>Select architect to associate with this building:</p>";
		echo '<form action=""' . esc_url( $_SERVER['REQUEST_URI'] ) . '" method="post">';
		$selector = $this->display_architect_selector ();
		echo $selector;
		echo '<input type="hidden" name="building_id" value="'. $_POST['building_id'] . '">';
		echo '<input type="hidden" name="add_architect" value=1>';
		echo '<input type="hidden" name="building_name" value="'. $_POST[ "building_name" ] . '">';
		echo '<button name="action" value="edit_architects">add</button>';
		echo '</form>';
		//cancel button
		echo '<form action="' . esc_url( $_SERVER['REQUEST_URI'] ) . '" method="post">';
		echo '<p><button name="action" value="cancel">cancel</button></p></form>';

    // = $this->add_architect( $_POST["architect_name"], $_POST["architect_website"] );
    //echo $result;
    $action = "Add";
    $id = "";
    $name = "";
    $website = "";
  } elseif ( $_POST['action'] == "add_new" ) {
		//display add building form
		?>
<h2>Add New Building:</h2>
<form action="<?php esc_url( $_SERVER['REQUEST_URI'] ); ?>" method="post">
	<fieldset>
		<table>
			<tr><td>
				<label class="col-md-4 control-label" for="name">Name</label>:
			</td><td>
				<input id="name" name="name" type="text" placeholder="" size="50">
			</td></tr>
			<tr><td>
			  <label for="architect">Architect</label>:
			</td><td>
				<?php
					$selector = $this->display_architect_selector ();
					echo $selector;
				 ?>
			</td></tr>
			<tr><td>
			  <label for="website">Website</label>:
			</td><td>
				<input id="website" name="website" type="text" placeholder="" size="50">
			</td></tr>
			<tr><td>
			  <label for="function">Function</label>:
			</td><td>
				<input id="function" name="function" type="text" placeholder="" size="50">
			</td></tr>
			<tr><td>
			  <label for="year">Year</label>:
			</td><td>
				<input id="year" name="year" type="text" placeholder="YYYY" size="5">
			</td></tr>
			<tr><td>
			  <label for="gfa">GFA (sqm)</label>:
			</td><td>
				<input id="gfa" name="gfa" type="text" placeholder="">
			</td></tr>
			<tr><td>
			  <label for="height">Height (m)</label>:
			</td><td>
				<input id="height" name="height" type="text" placeholder="">
			</td></tr>
			<tr><td>
			  <label for="city">City</label>:
			</td><td>
				<select id="city" name="city">
					<?php
						$cities = $this->get_cities();
						foreach ( $cities as $city ) {
							echo '<option value="' . $city[ "id" ] . '">' . $city[ "city" ] . '</option>';
						}
					 ?>
			   </select>
			</td></tr>
			<tr><td>
			  <label for="gmaps_link">Google Maps Link</label>:
			</td><td>
				<input id="gmaps_link" name="gmaps_link" type="text" placeholder="" size="50">
			</td></tr>
			<tr><td>
			  <label for="gmaps_embed">Google Maps Embed</label>:
			</td><td>
				<textarea id="gmaps_embed" name="gmaps_embed" rows="5" cols="50"></textarea>
			</td></tr>
			<tr><td>
			  <label for="visited">Visited?</label>:
			</td><td>
				<label for="visited-0">
			    <input type="radio" name="visited" id="visited-0" value="0" checked="checked">
			      No
			  </label>
			  <label for="visited-1">
			    <input type="radio" name="visited" id="visited-1" value="1">
			      Yes
			  </label>
			</td></tr>
			<tr><td>
			  <label for="date_visited">Date Visited</label>:
			</td><td>
				<input id="date_visited" name="date_visited" type="text" placeholder="YYYY-MM-DD" size="15">
			</td></tr>
			<tr><td>
			  <label for="bucket_list">Bucket List?</label>
			</td><td>
				<label for="bucket_list-0">
			    <input type="radio" name="bucket_list" id="bucket_list-0" value="0" checked="checked">
			      No
			    </label>
			    <label for="bucket_list-1">
			      <input type="radio" name="bucket_list" id="bucket_list-1" value="1">
			      Yes
			    </label>
			</td></tr>
		</table>
		<p>
			<button name="action" value="confirm_add">Add</button>&nbsp;
			<button name="action" value="cancel">Cancel</button>
		</p>
	</fieldset>
</form>

		<?php
	} elseif ( $_POST['action'] == "confirm_add" ) {
		//add building to database
		$result = $this->add_building( $_POST );
		echo '<p class="result">' . $result . '</p>';
	} elseif ( $_POST['action'] == "delete" ) {
		//delete building entry
		$result = $this->delete_building( $_POST[ "building_id" ] );
		echo '<p class="result">' . $result . '</p>';

	}

}
?>

<hr/>

<h2>Building List</h2>

<div class="datagrid">
<table>
	<thead>
  	<tr>
    	<th style="text-align:left"></th>
    	<th style="text-align:left">ID</th>
    	<th style="text-align:left">Name</th>
			<th style="text-align:left">Architect(s)</th>
			<th style="text-align:left">Location</th>
  	</tr>
	</thead>
	<tbody>

<?php
//get buildings
$buildings = $this->list_buildings();
//print buildings
$i = 0;
foreach ( $buildings as $building ){
	$i += 1;
	if ( $i % 2 == 0 ){
		echo '<tr class="alt">';
	} else {
		echo '<tr>';
	}
	//edit delete buttons
	echo '<td>';
	echo '<form action=""' . esc_url( $_SERVER['REQUEST_URI'] ) . '" method="post">';
	echo '<input type="hidden" name="building_id" value="'. $building[ "id" ] . '">';
	echo '<button name="action" value="edit">edit</button>';
	echo '<button name="action" value="delete">del</button>';
	echo '</form>';
	echo '</td>';
	//building id
	echo '<td>'. $building[ "id" ]  .'</td>';
	//building name
	echo '<td>';
	if ( $building[ "website" ] ){
		echo '<a href="' . $building[ "website" ] . '" target="_blank">' . $building[ "name" ] . '</a>';
	} else {
		echo $building[ "name" ];
	}
	echo '</td>';
	echo '<td>';
	//edit architects button
	echo '<form action=""' . esc_url( $_SERVER['REQUEST_URI'] ) . '" method="post">';
	echo '<input type="hidden" name="building_id" value="'. $building[ "id" ] . '">';
	echo '<input type="hidden" name="building_name" value="'. $building[ "name" ] . '">';
	echo '<button name="action" value="edit_architects">edit</button> ';
	//list architects
	$rows = $this->get_building_architects( $building[ "id" ] );
	if ( $rows ) {
		$architects = "";
		$num_architects = count( $rows );
		foreach ( $rows as $row ) {
			$architects .= $row[ "architect_name" ];
			if ( $num_architects > 1 ){
				$architects .= ", ";
			}
			$num_architects -= 1;
		}
		echo $architects;
	} else {
		echo "(no architects)";
	}

	echo '</form></td>';
	//location
	echo '<td>' . $building[ "city" ] . ' (' . $building[ "country_code" ] . ') <a href="' . $building[ "gmaps_link" ] . '" target="_blank">map</a></td>';
	echo '</tr>';
}
?>

</tbody>
</table>
</div>
