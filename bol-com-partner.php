<?php
	/*
	Plugin Name: Bol.com Partner Programma
	Plugin URI: http://www.boonen.eu/wordpress/plugins/bol-com-partner
	Description: get tag or category data from a page and finds data for it in selected categories for bol.com
	Version: 0.9.4.1
	Author: Imego
	Author URI: http://www.boonen.eu
	License: Copyright (c) Imego
	*/

class BolPartnerProgramme {
	public static $instance     = null;
	private $pluginFolder		= null;
	private $bolServer			= 'api.bol.com';
	private $bolServerPort		= '443';
	private $bolServiceUrl		= '/catalog/v4/search';
	private $keyword			= null;
	private $options			= array();
	private $xml 				= null;
	private $xmlHeader			= null;
	private $products			= null;
	private $nrItems			= null;

	private $bolSettings		= 'bol_option_main';
	private $keywordSettings	= 'bol_option_keyword';
	private $layoutSettings		= 'bol_option_layout';
	private $tmpLayoutSettings		= array();

	public function setNrItems($nrItems) {
		$this->nrItems = $nrItems;
		update_option($this->getLayoutSettings(),array('nr_items' => $nrItems));
		return $this->nrItems;
	}
	public function getNrItems() {
		if (!$this->nrItems) {
			$this->nrItems = $this->getOption('nr_items',$this->getLayoutSettings());
		}
		return $this->nrItems;
	}
	public function setTmpLayoutSettings($settings) {
		foreach($settings as $key => $value) {
			$this->tmpLayoutSettings[$key] = $value;
		}
		return $this->tmpLayoutSettings;
	}
	public function setTmpSetting($key, $value) {
		$this->tmpLayoutSettings[$key] = $value;
		return $this->tmpLayoutSettings;
	}
	public function getTmpLayoutSettings() {
		if (isset($this->tmpLayoutSettings))
			return $this->tmpLayoutSettings;
		return '';
	}
	public function getTmpSetting($key) {
		if (isset($this->tmpLayoutSettings[$key]))
			return $this->tmpLayoutSettings[$key];
		return '';
	}
	public function getBolSettings() {
		return $this->bolSettings;
	}
	public function getKeywordSettings() {
		return $this->keywordSettings;
	}
	public function getLayoutSettings() {
		return $this->layoutSettings;
	}

	private $elementAlignment = array (
		'none' => 'none',
		'left' => 'left',
		'center' => 'center',
		'right' => 'right',
	);
	public function getElementAlignment() {
		return $this->elementAlignment;
	}
	private $searchMethod = array (
		'sync' => 'sync',
		'async' => 'async',
	);
	public function getSearchMethod() {
		return $this->searchMethod;
	}
	private $imageOptions = array (
		'None' => 'None',
		'ExtraSmall' => 'Extra Small',
		'Small' => 'Small',
		'Medium' => 'Medium',
		'Large' => 'Large',
		'ExtraLarge' => 'Extra Large',
	);
	public function getImageOptions() {
		return $this->imageOptions;
	}
	private $keywordOptions = array (
		'tags' => 'tags',
		'custom-field' => 'custom-field',
		'categories' => 'categories',
	);
	public function getKeywordOptions() {
		return $this->keywordOptions;
	}
	private $categories = array (
		 '0' => 'Alle artikelen',
		 '8299' => 'Boeken',
		 '3133' => 'Dvd',
		 '3135' => 'Games',
		 '3136' => 'Elektronica',
		 '3134' => 'Computer',
		 '7934' => 'Speelgoed',
		 '3132' => 'Muziek',
	);
	public function getCategories() {
		return $this->categories;
	}

	public function __construct() {
		self::$instance = $this;
		$this->initialise();
	}

	private function initialise() {
		$this->setPluginFolder(null);

	}

	public static function getInstance() {
		return self::$instance;
	}

	public function setPluginFolder($folder) {
		if ($folder) {
			$this->pluginFolder = $folder;

		} else {
			$this->pluginFolder = plugin_dir_path(__FILE__);
		}
		return $this->pluginFolder;
	}

	public function setXML($xml) {
		$this->setXMLHeader(preg_replace("/^[^<?]+/",'',$xml));
		return $this->xml = new SimpleXMLElement(preg_replace("/^[^<?]+/",'',$xml));
	}
	public function setXMLHeader($header) {
		return $this->xmlHeader = $header;
	}
	public function setProducts($products) {
		$json = preg_replace("/^[^{?]+/",'',$products);
		$json = json_decode($json);
		return $this->products = $json;
	}

	public function getPluginFolder() {
		return $this->pluginFolder;
	}
	public function getPluginUrl() {
		return plugin_dir_url( __FILE__ );
	}
	public function getBolServer() {
		return $this->bolServer;
	}
	public function getBolServerPort() {
		return $this->bolServerPort;
	}
	public function getBolServiceUrl() {
		return $this->bolServiceUrl;
	}
	public function getOptions($settingsType) {
		return get_option($settingsType);
	}
	public function getOption($key,$settingsType) {
		$currentOption = get_option($settingsType);
		if (isset($currentOption[$key])) {
			return $currentOption[$key];
		}
		return null;
	}
	public function getXML() {
		return $this->xml;
	}
	public function getXMLHeader() {
		return $this->xmlHeader;
	}
	public function getProducts() {
		return $this->products;
	}
	public function getProductUrlDetails($product) {
		$return = 'http://partnerprogramma.bol.com/click/click?';
		$defaultUrl = '';

		foreach ($product->{'urls'} as $url) {
			if ($url->{'key'} == 'DESKTOP') {
				$defaultUrl = $url->{'value'};
			}
		}
		$urlVars = array(
			'p' => '1',
			't' => 'url',
			's' => $this->getOption('site_id',$this->getBolSettings()),
			'url' => $defaultUrl,
			'f' => 'txl',
		);
		return $return.http_build_query($urlVars);
	}
	public function getProductImages($product) {
		$images = array(
			'ExtraSmall' => '',
			'Small' => '',
			'Medium' => '',
			'Large' => '',
			'ExtraLarge' => '',
		);
		if (isset($product->{'images'}) && count($product->{'images'}) > 0) {
			foreach ($product->{'images'} as $image) {
				if ($image->{'key'} == 'XS') {
					$images['ExtraSmall'] = $image->{'url'};

				} else if ($image->{'key'} == 'S') {
					$images['Small'] = $image->{'url'};

				} else if ($image->{'key'} == 'M') {
					$images['Medium'] = $image->{'url'};

				} else if ($image->{'key'} == 'L') {
					$images['Large'] = $image->{'url'};

				} else if ($image->{'key'} == 'XL') {
					$images['ExtraLarge'] = $image->{'url'};
				}
			}
		}
		return $images;
	}
	public function getProductOfferDetails($product) {
		$prices = Array(
			'best' => array(),
			'secondhand' => array(),
		);
		foreach ($product->{'offerData'}->{'offers'} as $offer) {
			if (count($offer) > 1) {
				foreach($offer as $singleOffer) {
					
					if ($singleOffer->{'bestOffer'} && !$singleOffer->{'SecondHand'}) {
						$prices['best'] = $singleOffer;

					} else if ($singleOffer->{'SecondHand'}) {
						$prices['secondhand'] = $singleOffer;
					}
				}
			} else {
				$prices['best'] = $offer;
			}
		}
		return $prices;
	}
	public function formatPrice($price) {
		return '&euro;'.number_format((double)$price,2,',','.');
	}

}
$post;

$bolPP = new BolPartnerProgramme();
include $bolPP->getPluginFolder().'bol-com-partner-admin.php';
include $bolPP->getPluginFolder().'bol-com-partner-search.php';
include $bolPP->getPluginFolder().'bol-com-partner-widget.php';
