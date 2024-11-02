<?php
/**
 * Plugin Name
 *
 * @package           BolPartnerSearch
 * @author            IMEGO
 * @license           GPL-2.0+
 * @link              http://imego.nl
 * @copyright         2014 Imego
 */
class BolPartnerProgrammeSearch extends BolPartnerProgramme {

	public function createJS() {
		ob_start();
			include BolPartnerProgramme::getInstance()->getPluginFolder().'/templates/search.php';
		$html = ob_get_contents();
		ob_end_clean();
	}

	public function doRequest($method, $searchWord, $content, $sessionId) {
		if (!$this->getOption('always_include_default',$this->getKeywordSettings())) {
			if (!$searchWord || $searchWord == '') 
				$searchWord = $this->getOption('default_keyword',$this->getKeywordSettings());

			if (!$searchWord || $searchWord == '') 
				return;
		}

		$params = array(
			'q' => urlencode($searchWord),
			'apikey' => $this->getOption('api_access_key',$this->getBolSettings()),
			'offset' => 0,
			'dataoutput' => 'products',
			'limit' => $this->getNrItems(),
			'includeAttributes' => true,
			'format' => 'json',
			'offers' => 'bestoffer',
			'ids' => urlencode($this->getOption('category',$this->getKeywordSettings())),
		);
		$parameters = '?';
		$i = 0;
		foreach($params as $key => $value) {
			if ($i > 0)
				$parameters .= '&';

			$parameters .= $key.'='.$value;
			$i++;
		}

		$server = $this->getBolServer();
		$port = $this->getBolServerPort();
		$url = $this->getBolServiceUrl();

		$today = gmdate('D, d F Y H:i:s \G\M\T');

		if ($method == 'GET') {
			$contentType = 'application/json';
		} elseif ($method == 'POST') {
			$contentType = 'application/x-www-form-urlencoded';
		}

		$headers = $method . " " . $url . $parameters . " HTTP/1.0\r\nContent-type: " . $contentType . "\r\n";
		$headers .= "Host: " . $server . "\r\n";
		$headers .= "Content-length: " . strlen($content) . "\r\n";
		$headers .= "Connection: close\r\n";
		$headers .= "X-OpenAPI-Date: " . $today . "\r\n";
		if (!is_null($sessionId)) {
			$headers .= "X-OpenAPI-Session-ID: " . $sessionId . "\r\n";
		}
		$headers .= "\r\n";

		// Connect using fsockopen (you could also try CURL)
		$socket = fsockopen('ssl://' . $server, $port, $errno, $errstr, 30);
		if (!$socket) {
			echo "$errstr ($errno)<br />\n";
		}
		fputs($socket, $headers);
		fputs($socket, $content);
		$ret = "";

		if ($socket && !is_bool($socket)) {
			while (!feof($socket)) {
				$readLine = fgets($socket);
				$ret .= $readLine;
			}
		}
		fclose($socket);

		return $ret;
	}

	public function doSearch($postId = null) {
		$term = '';
		$post = get_post($postId);
		
		if (!$post) {
			global $post;
		}

		if ($this->getOption('read_keyword',$this->getKeywordSettings()) == 'tags') {
			$terms = wp_get_post_tags($post->ID);
			if ($terms) {
				$i = 0;
				foreach($terms as $tag) {
					if ($i > 0)
						$term .= ' || ';

					$term .= $tag->name; 
					$i++;
				}
			}
		} else if ($this->getOption('read_keyword',$this->getKeywordSettings()) == 'custom-field') {
			$term = get_post_meta($post->ID, 'bol-keyword', true);

		} else {
			$terms = wp_get_post_categories($post->ID);
			if ($terms) {
				$i = 0;
				foreach($terms as $categoryId) {
					if ($i > 0)
						$term .= ' || ';

					$term .= get_cat_name( $categoryId );
					$i++;
				}
			}
				
		}
		if ($this->getOption('always_include_default',$this->getKeywordSettings())) {
			$term = $term.' || '.$this->getOption('default_keyword',$this->getKeywordSettings());
		}
		if ($this->getOption('enable_promo_keyword',$this->getKeywordSettings()) && $this->getOption('promo_keyword',$this->getKeywordSettings()) != '') {
			$term = $this->getOption('promo_keyword',$this->getKeywordSettings());
		}
		$this->setProducts($this->doRequest('GET',$term, '', null));
	}
	public function search() {
		add_action( 'wp', array($this,'doSearch'), 5, 1);
	}
	public function printResult($rest = false) {
		if (!$rest) {
			wp_enqueue_style('bol-com-partner', BolPartnerProgramme::getInstance()->getPluginUrl().'css/bol.css?'.http_build_query($this->getTmpLayoutSettings()));
		}

		$bolPPSearch = $this;
			ob_start();
				include BolPartnerProgramme::getInstance()->getPluginFolder().'/templates/products.php';
			$html = ob_get_contents();
			ob_end_clean();
			return $html;
	}
	public function bolPartnerWidget() {
		register_widget('BolPartnerWidget');
		if ($this->getOption('searchMethod',$this->getBolSettings()) == 'sync') {
			if (!is_admin()) {
				$this->search();
			}
		}
	}

	public function bolPartnerShortcode( $atts ){
		$this->setTmpLayoutSettings($atts);
		return $this->printResult();
	}

	public function addBolJS() {
		wp_enqueue_script( 'bol-com-partner', BolPartnerProgramme::getInstance()->getPluginUrl().'js/bol.js',array( 'jquery' ) );
	}
	public function asyncRequest() {
		if (isset($_GET['post_id']) && is_numeric($_GET['post_id'])) {
		}

		$this->doSearch($_GET['post_id']);
		$bolPPSearch = $this;
		print $this->printResult('rest');
		die();
	}

}

$bolPPSearch = new BolPartnerProgrammeSearch();


if ($bolPPSearch->getOption('searchMethod',$bolPPSearch->getBolSettings()) == 'async') {
	add_action('wp_enqueue_scripts', array($bolPPSearch,'addBolJs'),10);
}
if ($bolPPSearch->getOption('searchMethod',$bolPPSearch->getBolSettings()) == 'sync') {
	$bolPPSearch->search();
} 
 

add_action( 'wp_ajax_bol_request',  array($bolPPSearch,'asyncRequest') ); // ajax for logged in users
add_action( 'wp_ajax_nopriv_bol_request',  array($bolPPSearch,'asyncRequest') ); // ajax for not logged in users

add_action( 'widgets_init', array($bolPPSearch,'bolPartnerWidget'),12 );
add_shortcode( 'bol_partner_programme', array($bolPPSearch,'bolPartnerShortcode') );
