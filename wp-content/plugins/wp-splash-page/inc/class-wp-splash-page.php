<?php
wpsp_import_class( 'Mobile_Detect', 'inc/mobile-detect/Mobile_Detect.php' );

class WP_Splash_Page {
	
	private	$settings;
	private	$state;
	private $cookie_name;
	private $cookie_expiration;
	private $detect;
	private $minor;
	private $opt_in_rejected;
	private $current_url;
	private $template_url;
	
	public function __construct() {
	
		$this->minor			= false;
		$this->opt_in_rejected	= false;
			
		if ( isset( $_GET['mode'] ) && $_GET['mode'] == 'wpsp_preview' ) {
			
			$this->settings	= get_option( 'wp_splash_page_options_preview' );
			$this->state	= ( ! current_user_can( 'manage_options' ) || ! file_exists( WP_SPLASH_PAGE_ROOT_PATH . 'templates/' . $this->settings['template'] . '/splash-page.php' ) ) ? false : 'preview';
			
		} else {
			
			$this->settings				= get_option( 'wp_splash_page_options' );
			$config_options				= get_option( 'wp_splash_page_config' );
			$this->cookie_name			= $config_options['cookie_name'];
			$this->cookie_expiration	= ( $this->settings['expiration_time'] != 0 ) ? time() + ( $this->settings['expiration_time'] * 24 * 3600 ) : 0;
			$this->detect				= new Mobile_Detect();
			$this->state				= ( $this->test() ) ? 'active' : false;

		}
		
	}
	
	public function splash_page() {
		
		$protocol			= ( is_ssl() ) ? 'https://' : 'http://';
		$this->current_url	= esc_url( $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] );
		$this->template_url	= esc_url( WP_SPLASH_PAGE_ROOT_URL . 'templates/' . $this->settings['template'] . '/' );
		
		if ( $this->state === 'active' && ( ! $this->settings['enable_age_confirmation'] ) && ( ! $this->settings['enable_opt_in'] ) ){
		
			setCookie( $this->cookie_name, time(), $this->cookie_expiration );
			$this->save_ip();
				
		}
		
		require_once( WP_SPLASH_PAGE_ROOT_PATH . 'templates/' . $this->settings['template'] . '/splash-page.php' );
		exit();
		
	}
	
	public function is_active() {

		return ( $this->state !== false ) ? true : false;
		
	}
	
	private function test() {
		
		if ( ! $this->settings['enable_splash_page'] )
			return false;
		
		if ( ! is_front_page() && ! $this->settings['show_in_all'] )
			return false;

		if ( isset( $_COOKIE[ $this->cookie_name ] ) ) {
			$this->delete_ip( $this->get_ip() );
			return false;
		}
		
		if ( $this->check_ip() ){
			setCookie( $this->cookie_name, time(), $this->cookie_expiration );
			return false;			
		}
		
		if ( ! $this->form_validation() )
			return false;
			
		if ( ! $this->settings['show_on_mobile'] && ( $this->detect->isMobile() || $this->detect->isTablet() ) )
			return false;
			
		if ( $this->is_bot() )
			return false;
		
		if ( ! file_exists( WP_SPLASH_PAGE_ROOT_PATH . 'templates/' . $this->settings['template'] . '/splash-page.php' ) )
			return false;		
			
		return true;
		
	}
	
	private function form_validation() {
	
		$must_save	= false;
		$error		= false;
		
		if ( $this->settings['enable_age_confirmation']  && isset( $_POST['wpsp-nonce'] ) && wp_verify_nonce( $_POST['wpsp-nonce'], WP_SPLASH_PAGE_FORM_NONCE ) ) {		
			
			if (  isset( $_POST['wpsp-year'] ) && ctype_digit( $_POST['wpsp-year'] ) && isset( $_POST['wpsp-month'] ) && ctype_digit( $_POST['wpsp-month'] ) && isset( $_POST['wpsp-day'] ) && ctype_digit( $_POST['wpsp-day'] ) && $this->check_age( $_POST['wpsp-day'], $_POST['wpsp-month'], $_POST['wpsp-year'] ) ) {
				
				$must_save	= true;
				
			} else {
			
				$this->minor	= true;
				$error			= true;
			
			}
				
		}

		if ( $this->settings['enable_opt_in'] && isset( $_POST['wpsp-nonce'] ) && wp_verify_nonce( $_POST['wpsp-nonce'], WP_SPLASH_PAGE_FORM_NONCE ) ) {

			if ( isset( $_POST['opt-in-checkbox'] ) ) {

				$must_save	= true;			
				
			} else {
			
				$this->opt_in_rejected	= true;
				$error					= true;
				
			}
			
		}
			
		if ( ! $error && $must_save ) {
		
			setCookie( $this->cookie_name, time(), $this->cookie_expiration );
			$this->save_ip();
			
			return false;
			
		}
		
		return true;
		
	}
	
	/* Source Code from here: http://www.beautifulcoding.com/snippets/178/a-simple-php-bot-checker-are-you-human/  */
	private function is_bot() {
	
		$spiders = array( "alexa", "froogle", "abot", "dbot", "ebot", "hbot", "kbot", "lbot", "mbot", "nbot", "obot", "pbot", "rbot", "sbot", "tbot", "vbot", "ybot", "zbot", "bot.", "bot/", "_bot", ".bot", "/bot", "-bot", ":bot", "(bot", "crawl", "slurp", "spider", "seek", "accoona", "acoon", "adressendeutschland", "ah-ha.com", "ahoy", "altavista", "ananzi", "anthill", "appie", "arachnophilia", "arale", "araneo", "aranha", "architext", "aretha", "arks", "asterias", "atlocal", "atn", "atomz", "augurfind", "backrub", "bannana_bot", "baypup", "bdfetch", "big brother", "biglotron", "bjaaland", "blackwidow", "blaiz", "blog", "blo.", "bloodhound", "boitho", "booch", "bradley", "butterfly", "calif", "cassandra", "ccubee", "cfetch", "charlotte", "churl", "cienciaficcion", "cmc", "collective", "comagent", "combine", "computingsite", "csci", "curl", "cusco", "daumoa", "deepindex", "delorie", "depspid", "deweb", "die blinde kuh", "digger", "ditto", "dmoz", "docomo", "download express", "dtaagent", "dwcp", "ebiness", "bingbot", "ebingbong", "e-collector", "ejupiter", "emacs-w3 search engine", "esther", "evliya celebi", "ezresult", "falcon", "felix ide", "ferret", "fetchrover", "fido", "findlinks", "fireball", "fish search", "fouineur", "funnelweb", "gazz", "gcreep", "genieknows", "getterroboplus", "geturl", "glx", "goforit", "golem", "grabber", "grapnel", "gralon", "griffon", "gromit", "grub", "gulliver", "hamahakki", "harvest", "havindex", "helix", "heritrix", "hku www octopus", "homerweb", "htdig", "html index", "html_analyzer", "htmlgobble", "hubater", "hyper-decontextualizer", "ia_archiver", "ibm_planetwide", "ichiro", "iconsurf", "iltrovatore", "image.kapsi.net", "imagelock", "incywincy", "indexer", "infobee", "informant", "ingrid", "inktomisearch.com", "inspector web", "intelliagent", "internet shinchakubin", "ip3000", "iron33", "israeli-search", "ivia", "jack", "jakarta", "javabee", "jetbot", "jumpstation", "katipo", "kdd-explorer", "kilroy", "knowledge", "kototoi", "kretrieve", "labelgrabber", "lachesis", "larbin", "legs", "libwww", "linkalarm", "link validator", "linkscan", "lockon", "lwp", "lycos", "magpie", "mantraagent", "mapoftheinternet", "marvin/", "mattie", "mediafox", "mediapartners", "mercator", "merzscope", "microsoft url control", "minirank", "miva", "mj12", "mnogosearch", "moget", "monster", "moose", "motor", "multitext", "muncher", "muscatferret", "mwd.search", "myweb", "najdi", "nameprotect", "nationaldirectory", "nazilla", "ncsa beta", "nec-meshexplorer", "nederland.zoek", "netcarta webmap engine", "netmechanic", "netresearchserver", "netscoop", "newscan-online", "nhse", "nokia6682/", "nomad", "noyona", "nutch", "nzexplorer", "objectssearch", "occam", "omni", "open text", "openfind", "openintelligencedata", "orb search", "osis-project", "pack rat", "pageboy", "pagebull", "page_verifier", "panscient", "parasite", "partnersite", "patric", "pear.", "pegasus", "peregrinator", "pgp key agent", "phantom", "phpdig", "picosearch", "piltdownman", "pimptrain", "pinpoint", "pioneer", "piranha", "plumtreewebaccessor", "pogodak", "poirot", "pompos", "poppelsdorf", "poppi", "popular iconoclast", "psycheclone", "publisher", "python", "rambler", "raven search", "roach", "road runner", "roadhouse", "robbie", "robofox", "robozilla", "rules", "salty", "sbider", "scooter", "scoutjet", "scrubby", "search.", "searchprocess", "semanticdiscovery", "senrigan", "sg-scout", "shai'hulud", "shark", "shopwiki", "sidewinder", "sift", "silk", "simmany", "site searcher", "site valet", "sitetech-rover", "skymob.com", "sleek", "smartwit", "sna-", "snappy", "snooper", "sohu", "speedfind", "sphere", "sphider", "spinner", "spyder", "steeler/", "suke", "suntek", "supersnooper", "surfnomore", "sven", "sygol", "szukacz", "tach black widow", "tarantula", "templeton", "/teoma", "t-h-u-n-d-e-r-s-t-o-n-e", "theophrastus", "titan", "titin", "tkwww", "toutatis", "t-rex", "tutorgig", "twiceler", "twisted", "ucsd", "udmsearch", "url check", "updated", "vagabondo", "valkyrie", "verticrawl", "victoria", "vision-search", "volcano", "voyager/", "voyager-hc", "w3c_validator", "w3m2", "w3mir", "walker", "wallpaper", "wanderer", "wauuu", "wavefire", "web core", "web hopper", "web wombat", "webbandit", "webcatcher", "webcopy", "webfoot", "weblayers", "weblinker", "weblog monitor", "webmirror", "webmonkey", "webquest", "webreaper", "websitepulse", "websnarf", "webstolperer", "webvac", "webwalk", "webwatch", "webwombat", "webzinger", "wget", "whizbang", "whowhere", "wild ferret", "worldlight", "wwwc", "wwwster", "xenu", "xget", "xift", "xirq", "yandex", "yanga", "yeti", "yodao", "zao/", "zippp", "zyborg", "Googlebot", "Google", 'Yammybot', 'Yahoo', 'msnbot', 'Teoma', 'Gigabot', 'Googlebot-Mobile' );
		
		foreach( $spiders as $spider ) { 
		
			if ( stripos( $_SERVER['HTTP_USER_AGENT'], $spider ) !== false )
				return true;
				
		}
		
		return false;
		
	}
	
	private function check_age( $day, $month, $year ){
	
		$year_dif 	= date('Y') - $year;
		$month_dif 	= date('m') - $month;
		$day_dif 	= date('d') - $day;
		
		if ( $day_dif < 0 || $month_dif < 0 )
			$year_dif--;

		if ( $year_dif >= $this->settings['min_age'] )
			return true;
		
		return false;
		
	}
	
	private function get_ip(){
	
		if ( !empty( $_SERVER['HTTP_CLIENT_IP'] ) )
		  $ip	= $_SERVER['HTTP_CLIENT_IP'];
		elseif ( !empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) )
		  $ip	= $_SERVER['HTTP_X_FORWARDED_FOR'];
		else
		  $ip	= $_SERVER['REMOTE_ADDR'];
		
		return $ip;
		
	}
	
	private function save_ip() {
	
		global $wpdb;
			
		$wpdb->insert( 
			WP_SPLASH_PAGE_TABLE_IPS, 
			array( 
				'ip'			=> $this->get_ip(), 
				'cookie_name'	=> $this->cookie_name, 
				'splash_date'	=> current_time('mysql')
			) 
		);
	
	}
	
	private function check_ip(){
	
		global $wpdb;
			
		$this->delete_ip();
			
		// Check Ip's
		$result	= $wpdb->get_var( $wpdb->prepare( 'SELECT ip FROM ' . WP_SPLASH_PAGE_TABLE_IPS . ' WHERE ip = %s AND cookie_name = %s', array( $this->get_ip(), $this->cookie_name ) ) );

		$wpdb->flush();
			
		if ( ! empty( $result ) )
			return true;
			
		return false;
			
	}
	
	private function delete_ip( $ip = false ){
		
		global $wpdb;
		$query	= "";
		$args	= "";
		
		if( ! empty( $ip ) ) {
			$query	= 'DELETE FROM ' . WP_SPLASH_PAGE_TABLE_IPS . ' WHERE ip = %s';
			$args	= $ip;
		
		}else{
			$query	= 'DELETE FROM ' . WP_SPLASH_PAGE_TABLE_IPS . ' WHERE splash_date < %s';
			$args	=  date( 'Y-m-d H:i:s', ( current_time('timestamp') - ( 24 * 3600 ) ) );
		}
		
		// Delete Ip's
		$wpdb->query( $wpdb->prepare( $query, $args ) );
	
	}
				
}
?>