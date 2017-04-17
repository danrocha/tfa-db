<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    TFA_DB
 * @subpackage TFA_DB/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    TFA_DB
 * @subpackage TFA_DB/admin
 * @author     Your Name <email@example.com>
 */
class TFA_DB_Admin {

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
	 * @param      string    $tfa_db       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $tfa_db, $version ) {

		$this->tfa_db = $tfa_db;
		$this->version = $version;
		$this->db = $this->db_connect();

	}

	/**
	 * Register the stylesheets for the admin area.
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

		wp_enqueue_style( $this->tfa_db, plugin_dir_url( __FILE__ ) . 'css/tfa-db-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
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

		wp_enqueue_script( $this->tfa_db, plugin_dir_url( __FILE__ ) . 'js/tfa-db-admin.js', array( 'jquery' ), $this->version, false );

	}

	public function add_admin_menu() {

		add_menu_page("TFA DB", "TFA DB", 1, "TFA DB", array( $this, 'render_admin_home' ) );
        add_submenu_page(
			"TFA DB",
			"Architects",
			"Architects",
			1,
			"Architects",
			array( $this, 'render_architects_list' )
		);
		add_submenu_page(
			"TFA DB",
			"Buildings",
			"Buildings",
			1,
			"Buildings",
			array( $this, 'render_buildings_list' )
		);
		add_submenu_page(
			"TFA DB",
			"Links",
			"Links",
			1,
			"Links",
			array( $this, 'render_add_links' )
		);
        //add_submenu_page("TFA DB","Buildings","Buildings", 1,"Buildings","tfadb_buildings");
        //add_submenu_page("TFA DB","All","All", 1,"All","tfadb_all");


	}

	/**
	* Required file used to display the architects list page
	*/
	public function render_admin_home() {

		require_once plugin_dir_path( __FILE__ ) . 'partials/tfa-db-admin-home.php';
	}

	public function render_architects_list() {
		require_once plugin_dir_path( __FILE__ ) . 'partials/architects-admin.php';
	}

	public function render_buildings_list() {
		require_once plugin_dir_path( __FILE__ ) . 'partials/buildings-admin.php';
	}

	public function render_add_links() {
		//$this->building = null;
		require_once plugin_dir_path( __FILE__ ) . 'partials/links-admin.php';
	}

	/**
	* COUNT items
	*
	**/
	public function get_num_architects () {
		//connect to DB
		$db = $this->db_connect();
		$count = $db->query('SELECT COUNT(*) FROM tfa_architects')->fetchColumn();

		return $count;
	}

	public function get_num_buildings () {
		//connect to DB
		$db = $this->db_connect();
		$count = $db->query('SELECT COUNT(*) FROM tfa_buildings')->fetchColumn();

		return $count;
	}

	/**
	*
	* ADD
	* functions to add data
	*
	**/

	//add architects
	public function add_architect( $architect_name, $architect_website ) {
		$db = $this->db_connect();
		$stmt = $this->db->prepare( 	"INSERT INTO
																	tfa_architects
																	( name, website )
																	VALUES ( :name , :site )"
																);

		$retval = $stmt->execute( [ ':name' => $architect_name, ':site' => $architect_website ] );
		if(! $retval )
		{
  			die('Could not enter data: ' . mysql_error());
		}
		$inserted_id = $this->db->lastInsertId();
		return "Entered data successfully: ($inserted_id) - $architect_name / $architect_website";
	}

	//add building
	public function add_building( $data ) {

		//sort data
		$name = $data[ "name" ];
		$website = $data[ "website" ];
		$function = $data[ "function" ];
		$year = $data[ "year" ];
		$gfa = $data[ "gfa" ];
		$height = $data[ "height" ];
		$gmaps_link = $data[ "gmaps_link" ];
		$gmaps_embed = $data[ "gmaps_embed" ];
		$city = $data[ "city" ];
		$visited = $data[ "visited" ];
		$date_visited = $data[ "date_visited" ];
		$bucket_list = $data[ "bucket_list" ];

		$db = $this->db_connect();
		$stmt = $this->db->prepare( 	"INSERT INTO tfa_buildings
																	( name, website_official, function, year, gfa, height, gmaps_embed, gmaps_link, city_id, visited, date_visited, bucket_list )
																	VALUES
																	( :name, :website, :function, :year, :gfa, :height, :gmaps_embed, :gmaps_link, :city, :visited, :date_visited, :bucket_list )"
																);

		$retval = $stmt->execute( [ ':name' => $name,
																':website' => $website,
																':function' => $function,
																':year' => $year,
																':gfa' => $gfa,
																':height' => $height,
																':gmaps_link' => $gmaps_link,
																':gmaps_embed' => $gmaps_embed,
																':city' => $city,
																':visited' => $visited,
																':date_visited' => $date_visited,
																':bucket_list' => $bucket_list
															] );
		if(! $retval )
		{
  			die('Could not enter data: ' . mysql_error());
		}
		$inserted_id = $this->db->lastInsertId();
		return "Entered data successfully: ($inserted_id) - $name";
	}

	//add ARCHITECT to building
	public function add_architect_building ( $building_id, $architect_id ) {
		$db = $this->db_connect();
		$stmt = $this->db->prepare( 	"INSERT INTO
																	tfa_architects_buildings
																	( building_id, architect_id )
																	VALUES ( :bid , :aid )"
																);

		$retval = $stmt->execute( [ ':bid' => $building_id, ':aid' => $architect_id ] );
		if(! $retval )
		{
  			die('Could not enter data: ' . mysql_error());
		}
		$inserted_id = $this->db->lastInsertId();
		return "Entered data successfully!";
	}


	/*******************************************************
	*
	* LIST functions
	*
	******************************/

	//list architects
	public function list_architects () {
		$db = $this->db_connect();
		$stmt = $db->prepare( "SELECT * FROM tfa_architects ORDER BY name" );
		$stmt->execute();
		$rows = $stmt->fetchAll( PDO::FETCH_ASSOC );

		if( $rows ) {
			return $rows;
		} else {
			//something went wrong
			echo "error";
		}

	}

	//get single architect data
	public function get_architect( $id ) {
		$db = $this->db_connect();
		$stmt = $db->prepare( "SELECT * FROM tfa_architects WHERE id = :id" );
		$stmt->execute( [':id' => $id] );

		$rows = $stmt->fetchAll( PDO::FETCH_ASSOC );

		if( $rows ) {
			return $rows[0];
		} else {
			//something went wrong
			echo "error";
		}
	}

	//get architects from building
	public function get_building_architects( $building_id ) {
		//connect to DB
		$db = $this->db_connect();
		$stmt = $db->prepare( "SELECT architects.name AS architect_name,
													architects.website AS architect_website,
													ab.id AS ab_id
													FROM 	tfa_architects AS architects,
													tfa_architects_buildings AS ab
													WHERE	ab.building_id = :building_id AND
													ab.architect_id = architects.id
													ORDER BY architect_name"
												);

		$stmt->execute( [':building_id' => $building_id] );
		$rows = $stmt->fetchAll( PDO::FETCH_ASSOC ); //fetch as associative array

		if( $rows ) {
			return $rows;
		} else {
			//something went wrong
			return null;
		}

	}

	//list buildings
	public function list_buildings() {
		//connect to DB
		$db = $this->db_connect();
		$stmt = $db->prepare( "SELECT buildings.id AS id,
									buildings.name AS name,
									buildings.website_official AS website,
									buildings.gmaps_link AS gmaps_link,
									cities.city AS city,
									countries.country_code AS country_code
								FROM tfa_buildings AS buildings,
									tfa_cities AS cities,
									tfa_countries AS countries
								WHERE buildings.city_id = cities.id AND
									cities.country_id = countries.id
								ORDER BY id DESC");

		//be sure to sanitize $tablename! use a whitelist filter, not escapes!
		$stmt->execute();
		$rows = $stmt->fetchAll( PDO::FETCH_ASSOC ); //fetch as associative array

		if( $rows ) {
			return $rows;
		} else {
			//something went wrong
			echo "error";
		}
	}

	//list building names and ids only
	public function list_buildings_names_ids() {
		//connect to DB
		$db = $this->db_connect();
		$stmt = $db->prepare( "SELECT buildings.id AS id,
									buildings.name AS name
								FROM tfa_buildings AS buildings
								ORDER BY name");

		//be sure to sanitize $tablename! use a whitelist filter, not escapes!
		$stmt->execute();
		$rows = $stmt->fetchAll( PDO::FETCH_ASSOC ); //fetch as associative array
		//print_r( $rows );
		if( $rows ) {
			return $rows;
		} else {
			//something went wrong
			echo "no buildings!";
		}
	}

	//get building name
	public function get_building_name( $id ) {
		//connect to DB
		$db = $this->db_connect();
		$stmt = $db->prepare( "SELECT buildings.name AS name
													FROM tfa_buildings AS buildings
													WHERE buildings.id = :id" );

		//be sure to sanitize $tablename! use a whitelist filter, not escapes!
		$stmt->execute( [ ':id' => $id ] );
		$rows = $stmt->fetchAll( PDO::FETCH_ASSOC ); //fetch as associative array
		//print_r( $rows );
		if( $rows ) {
			return $rows[0][ "name" ];
		} else {
			//something went wrong
			echo "no buildings!";
		}
	}

	// get all cities
	public function get_cities() {
		$db = $this->db_connect();
		$stmt = $db->prepare( "SELECT * FROM tfa_cities ORDER BY city" );
		$stmt->execute();
		$rows = $stmt->fetchAll( PDO::FETCH_ASSOC );

		if( $rows ) {
			return $rows;
		} else {
			//something went wrong
			echo "error";
		}
	}


	/*************************************************
	*
	* UPDATE functions
	*
	**/

	//update architect data
	public function update_architect( $id, $name, $website ) {
		$db = $this->db_connect();
		$stmt = $db->prepare( "UPDATE tfa_architects
													SET name = :name, website = :website
													WHERE id = :id" );
		$retval = $stmt->execute( [':id' => $id, ':name' => $name, ':website' => $website ] );

		if(! $retval )
		{
  			die('Could not update data: ' . mysql_error());
		}
		return "Update data successfully: ($id) - $name / $website";


	}

	/******************************************************
	*
	* DELETE functions
	*
	**/

	//delete architect data
	public function delete_architect( $id ) {
		$db = $this->db_connect();
		$stmt = $db->prepare( "DELETE FROM tfa_architects
													WHERE id = :id" );
		$retval = $stmt->execute( [':id' => $id ] );

		if(! $retval )
		{
  			die('Could not update data: ' . mysql_error());
		}
		return "Delete data successfully: ($id) ";
	}


	//delete building data
	public function delete_building( $id ) {
		$db = $this->db_connect();
		$stmt = $db->prepare( "DELETE FROM tfa_buildings
													WHERE id = :id" );
		$retval = $stmt->execute( [':id' => $id ] );

		if(! $retval )
		{
				die('Could not update data: ' . mysql_error());
		}
		return "Delete data successfully: ($id) ";
	}

	//delete architect building
	public function delete_architect_building( $id ) {
		$db = $this->db_connect();
		$stmt = $db->prepare( "DELETE FROM tfa_architects_buildings
													WHERE id = :id" );
		$retval = $stmt->execute( [':id' => $id ] );

		if(! $retval )
		{
				die('Could not update data: ' . mysql_error());
		}
		return "Delete data successfully: ($id) ";
	}
















/*
	public function get_building_names( ) {

		$stmt = $this->db->prepare( "SELECT buildings.id AS building_id,
									buildings.name AS building_name
								FROM tfa_buildings AS buildings
								ORDER BY building_name
								");
		//debug
		//print_r( $stmt );

		//be sure to sanitize $tablename! use a whitelist filter, not escapes!
		$stmt->execute();
		$rows = $stmt->fetchAll( PDO::FETCH_ASSOC ); //fetch as associative array

		//debug
		//print_r( $rows );

		if( $rows ) {

			return $rows;

		} else {

			//something went wrong
			echo "error";

		}

	}
*/
	public function get_building_links( $building_id ) {

		$stmt = $this->db->prepare( "SELECT links.id AS id,
											links.link_url AS url,
											links.link_title AS title
									FROM 	tfa_building_links AS links
									WHERE	links.building_id = :building_id
									ORDER BY title"
							);


		//print_r( $stmt );
		$stmt->execute( [':building_id' => $building_id] );
		//print_r( $stmt );
		$rows = $stmt->fetchAll( PDO::FETCH_ASSOC ); //fetch as associative array

		return $rows;

	}

	public function add_building_link( $building_id, $link_title, $link_url ) {
		$stmt = $this->db->prepare( "INSERT INTO
											tfa_building_links
											( building_id, link_title, link_url )
											VALUES ( :building_id, :link_title, :link_url )"
									);

		$retval = $stmt->execute( [':building_id' => $building_id, ':link_title' => $link_title, ':link_url' => $link_url] );

		if(! $retval )
		{
  			die('Could not enter data: ' . mysql_error());
		}
		return "Entered data successfully\n";

	}

	public function delete_building_link( $id ) {
			//$id = mysql_real_escape_string($id);
			$stmt = $this->db->prepare("DELETE FROM tfa_building_links WHERE id = :id");
			$retval = $stmt->execute( [':id' => $id] );

			if(! $retval )
			{
  				die('Could not enter data: ' . mysql_error());
			}

			return "Deleted data successfully\n";
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
	//ONE FUNCTION TO GET DATA
	// TWO DIFFERENT FUNCTIONS TO PARSE THE DATA!
	//then shortcode!



}
