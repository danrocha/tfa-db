<?php

/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    TFA_DB
 * @subpackage TFA_DB/public/partials
 */

//print_r( $architect_data );
//print_r( $building_data );

//This file should primarily consist of HTML with a little bit of PHP.
?>

<div class="building-info-box">
	<div class="building-info-map">
	<iframe src="https://www.google.com/maps/embed?pb=<?php echo $building_data[0]["gmaps_embed"];?>" width="300" height="300" frameborder="0" style="border:0" allowfullscreen=""></iframe>
		<?php echo '<br class=""/><a href="' . $building_data[0][ "gmaps_link" ] . '" target="_blank" style="font-size:small">Google Maps Link</a>' ?>
	</div>
	<div class="building-info-infobox">
		<p>
			<strong><?php echo $this->output_building_name ( $building_data ); ?></strong><br class=""/>
			<?php echo $this->output_architect_name( $architect_data ); ?>
			<br class=""/>
			<?php echo $this->output_building_details( $building_data ); ?>
		</p>
		<p>
			<?php
				foreach ( $link_data as $link ) {
					$title = $link["link_title"];
					$url = $link["link_url"];
					echo '<a href="' . $url . '" target="_blank">' . $title . '</a><br class=""/>';
				}
			?>
		</p>
	</div>
</div>
