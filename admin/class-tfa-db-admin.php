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
		
		add_menu_page("TFA DB", "TFA DB", 1, "TFA DB");
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
	public function render_architects_list() {
		$this->area = "architects";
		require_once plugin_dir_path( __FILE__ ) . 'partials/list.php';
	}
	
	public function render_buildings_list() {
		require_once plugin_dir_path( __FILE__ ) . 'partials/list_buildings.php';
	}
	
	public function render_add_links() {
		//$this->building = null;
		require_once plugin_dir_path( __FILE__ ) . 'partials/add_links.php';
	}
	
	
	
	
	/**
	 * Get a list of all architects
	 *
	 * (extended descritprion)
	 */
	public function list_data( $tablename ) {

		$db = $this->db_connect();
		$stmt = $db->prepare( "SELECT * FROM $tablename" ); 
		//var_dump( $stmt );
	
		//be sure to sanitize $tablename! use a whitelist filter, not escapes!
		$stmt->execute();
		
		$rows = $stmt->fetchAll( PDO::FETCH_ASSOC ); //fetch as associative array
		//var_dump( $rows );
		
		
		if( $rows ) {
			return $rows;
		} else {
			//something went wrong
			echo "error";
		}
		
	}
	
	public function list_buildings( ) {
		
		//connect to DB
		$db = $this->db_connect();

		$stmt = $db->prepare( "SELECT buildings.id AS building_id, 
									buildings.name AS building_name, 
									buildings.website_official AS building_website_official,
									buildings.gmaps_link AS gmaps_link, 
									cities.city AS city,
									countries.country_code AS country_code 
								FROM tfa_buildings AS buildings, 
									tfa_cities AS cities, 
									tfa_countries AS countries
								WHERE buildings.city_id = cities.id AND
									cities.country_id = countries.id
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
		echo "Entered data successfully\n";
		
	}
	
	public function delete_building_link( $delete ) {
		foreach($delete as $id)
		{
			//$id = mysql_real_escape_string($id);
			$stmt = $this->db->prepare("DELETE FROM tfa_building_links WHERE id = :id");
			$retval = $stmt->execute( [':id' => $id] );
			
			if(! $retval )
			{
  				die('Could not enter data: ' . mysql_error());
			}
		}
	}
	
		
	
	public function get_architects( $building_id ) {
		//connect to DB
		//$db = $this->db_connect();
		
		$stmt = $db->prepare( "SELECT 	architects.name AS architect_name,
										architects.website AS architect_website 
								FROM 	tfa_architects AS architects, 
										tfa_architects_buildings AS ab 
								WHERE	ab.building_id = :building_id AND 
										ab.architect_id = architects.id 
								ORDER BY architect_name"
							);
		
		
		//print_r( $stmt );
		
		$stmt->execute( [':building_id' => $building_id] );
		
		$rows = $stmt->fetchAll( PDO::FETCH_ASSOC ); //fetch as associative array
		
		
		if( $rows ) {
			return $rows;
		} else {
			//something went wrong
			echo "error";
		}
		
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
