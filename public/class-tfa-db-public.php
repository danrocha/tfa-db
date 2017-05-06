<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    TFA_DB
 * @subpackage TFA_DB/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    TFA_DB
 * @subpackage TFA_DB/public
 * @author     Your Name <email@example.com>
 */
class TFA_DB_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $tfa_db    The ID of this plugin.
	 */
	private $tfa_db;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $tfa_db       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $tfa_db, $version ) {

		$this->tfa_db = $tfa_db;
		$this->version = $version;
		$this->db = $this->db_connect();

	}

	public function db_connect() {
		global $wpdb;
		//print_r($wpdb);
		try {

			$dsn = "mysql:host=" . $wpdb->dbhost . ";port=8889;dbname=" . $wpdb->dbname;
			$username = $wpdb->dbuser;
			$password = $wpdb->dbpassword;

			$options = array( PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8', );

			$db = new PDO( $dsn, $username, $password, $options );//$dsn is the database connection strings. Depends on your DB.
			$db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
			//echo "connection succeeded";

			return $db;

		}catch( PDOException $e ) {

        	echo $e;//Always got exception

        }
	}


	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in TFA_DB_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The TFA_DB_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->tfa_db, plugin_dir_url( __FILE__ ) . 'css/tfa-db-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in TFA_DB_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The TFA_DB_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->tfa_db, plugin_dir_url( __FILE__ ) . 'js/tfa-db-public.js', array( 'jquery' ), $this->version, false );
		//add Google Maps API
		//wp_enqueue_script('google', 'https://maps.googleapis.com/maps/api/js?key=AIzaSyCNTmIdi0NQjK7JGsyARbel9RUCYBCYFc8&sensor=false');

	}

	public function display_building_info( $id ){
		//get building data
		$building_id = $id["id"];
		$stmt = $this->db->prepare( "SELECT buildings.name AS building_name,
										buildings.website_official AS building_website_official,
										buildings.gmaps_link AS gmaps_link,
										buildings.gmaps_embed AS gmaps_embed,
										buildings.year AS year,
										buildings.gfa AS gfa,
										buildings.function AS function,
										buildings.height AS height,
										cities.city AS city,
										countries.country_code AS country_code
								FROM	tfa_buildings AS buildings,
										tfa_cities AS cities,
										tfa_countries AS countries

								WHERE 	buildings.id = :building_id AND
										buildings.city_id = cities.id AND
										cities.country_id = countries.id
								ORDER BY building_name
								");
		//debug
		//print_r( $stmt );

		//be sure to sanitize $tablename! use a whitelist filter, not escapes!
		$stmt->execute( [":building_id" => $building_id] );
		//print_r( $stmt );
		$building_data = $stmt->fetchAll( PDO::FETCH_ASSOC ); //fetch as associative array

		//get architects
		$stmt = $this->db->prepare( "SELECT 	architects.name AS architect_name,
										architects.website AS architect_website
								FROM 	tfa_architects AS architects,
										tfa_architects_buildings AS ab
								WHERE	ab.building_id = :building_id AND
										ab.architect_id = architects.id
								ORDER BY architect_name"
							);

		//print_r( $stmt );
		$stmt->execute( [':building_id' => $building_id] );
		$architect_data = $stmt->fetchAll( PDO::FETCH_ASSOC ); //fetch as associative array

		// get links
		$stmt = $this->db->prepare( "SELECT links.link_url AS link_url,
											links.link_title AS link_title
									FROM 	tfa_building_links AS links
									WHERE	links.building_id = :building_id
									ORDER BY link_title"
							);


		//print_r( $stmt );
		$stmt->execute( [':building_id' => $building_id] );
		//print_r( $stmt );
		$link_data = $stmt->fetchAll( PDO::FETCH_ASSOC ); //fetch as associative array


		ob_start();
		include( plugin_dir_path( __FILE__ ) . 'partials/tfa-db-public-display.php' );
		$output = ob_get_clean();

		return $output;


	}

	public function output_architect_name( $architect_data ) {
		//var_dump( $architect_data );
		$num_architects = count($architect_data);
		if ( $num_architects < 1 ){
			return "(unknown architect)";
		}
		$output = '';
		foreach ( $architect_data as $architect ) {
			$name = $architect["architect_name"];
			$website = $architect["architect_website"];

			if ( is_null( $website) ) {
				$output .= $name;
			} else {
				$output .= '<a href="' . $website . '" target="_blank">' . $name . '</a>';
			}

			if ( $num_architects > 1 ) {
				$output .= ', ';
				$num_architects--;
			}
		}

		return $output;
	}

	public function output_building_name ( $building_data ) {

		$data = $building_data[0];
		$output = "";

		if ( !is_null( $data["building_website_official"] ) ){
			$output .= '<a href="' . $data["building_website_official"] . '" target="_blank">' . $data["building_name"] . '</a>';
		}else{
			$output .= $data["building_name"];
		}

		return $output;
	}


	public function output_building_details ( $building_data ) {

		$data = $building_data[0];
		$output = "";

		$output .= $data["function"] . "<br/>";
		$output .= $data["year"];
		if ( !is_null( $data["gfa"] ) AND $data["gfa"]!=0 ){
			$output .= "<br/>" . number_format( $data["gfa"] , 0, ".", $thousands_sep = "," ) . "sqm";
		}
		if ( !is_null( $data["height"] ) AND $data["height"]!=0 ){
			$output .= "<br/>" . number_format( $data["height"] , 0, ".", $thousands_sep = "," ) . "m";
		}

		return $output;
	}

}
