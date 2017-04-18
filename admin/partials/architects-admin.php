<h1>Architects Management</h1>


<?php

$action = "Add";
$id = "";
$name = "";
$website = "";

  if ( isset( $_POST['action'] ) ) {
    if ( $_POST['action'] == "add" ) {
        $result = $this->add_architect( $_POST["architect_name"], $_POST["architect_website"] );
        echo $result;
        $action = "Add";
        $id = "";
        $name = "";
        $website = "";

    } elseif ( $_POST['action'] == "edit" ) {
      //get architect data
      $architect = $this->get_architect( $_POST[ "architect_id" ] );

      $name = $architect[ "name" ];
      $website = $architect[ "website" ];
      $id = $_POST[ "architect_id" ];
      $action = "Edit";

    } elseif ( $_POST['action'] == "update" ) {
      //update architect data
      $result = $this->update_architect( $_POST[ "architect_id" ], $_POST["architect_name"], $_POST["architect_website"] );
      echo $result;

    } elseif ( $_POST['action'] == "delete" ) {
      //delete architect
      $result = $this->delete_architect( $_POST[ "architect_id" ] );
      echo $result;
    }
  }

?>

<h2><?php echo $action; ?> architect</h2>

<?php
  if ( $action == "Edit" ){
    echo "<p>Architect ID: " . $id . "</p>";
  }
?>

<form action="<?php esc_url( $_SERVER['REQUEST_URI'] ); ?>" method="post">
<fieldset>
<!-- Text input-->
<table>
  <tr>
    <td>
      <label for="architect_name">Name</label>
    </td>
    <td>
      <input id="architect_name" name="architect_name" type="text" placeholder="Name" required="" value="<?php echo $name; ?>" size="50">
    </td>
  </tr>
  <tr>
    <td>
      <label for="architect_website">Website</label>
    </td>
    <td>
      <input id="architect_website" name="architect_website" placeholder="http://" type="text" value="<?php echo $website; ?>" size="50">
    </td>
  </tr>
</table>
<p>
  <?php
    if ( $action == "Edit" ) {
      echo '<input type="hidden" name="architect_id" value="'. $id . '">';
      echo '<button name="action" value="update">Confirm</button>&nbsp;';
      echo '<button name="action" value="Cancel">Cancel</button>';
    } else {
      echo '<button name="action" value="add">Add</button>';
    }
  ?>
</p>
</fieldset>
</form>

<h2>Existing architects:</h2>

<div class="datagrid">
<table>
	<thead>
  <tr>
    <th style="text-align:left"></th>
    <th style="text-align:left">ID</th>
    <th style="text-align:left">Name</th>
    <th style="text-align:left">Website</th>
  </tr>
  </thead>
  <tbody>

  <?php
    $architects = $this->list_architects();
    $i = 0;
    foreach ( $architects as $architect ) {
      $i += 1;
	     if ( $i % 2 == 0 ){
         echo '<tr class="alt">';
	     } else {
         echo '<tr>';
       }
      echo '<td>';
      echo '<form action=""' . esc_url( $_SERVER['REQUEST_URI'] ) . '" method="post">';
      echo '<input type="hidden" name="architect_id" value="'. $architect[ "id" ] . '">';
      echo '<button name="action" value="edit">edit</button>';
      echo '<button name="action" value="delete">del</button>';
      echo '</form>';
      echo '</td>';
      echo '<td>'. $architect[ "id" ]  .'</td>';
      echo '<td>' . $architect[ "name" ] . '</td>';
      echo '<td><a href="' . $architect[ "website" ] . '" target="_blank">' . $architect[ "website" ] . '</a></td>';
      echo '</tr>';
    }

   ?>

 </tbody>
 </table>
