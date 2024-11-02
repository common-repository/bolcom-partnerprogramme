<?php
class BolPartnerProgrammeAdmin extends BolPartnerProgramme {
	public function addActions() {
		add_action('admin_menu',array($this,'adminActions'));
		add_action('admin_init',array($this,'settings') );

	}

	public function adminActions() {
		add_options_page( 'Bol.com partner programme settings', 'Bol.com', 'manage_options', 'bol-com-partner', array($this,'printPluginOptions') );
	}

	public function printPluginOptions() {
		$tab = isset( $_GET['tab'] ) ? $_GET['tab'] : $this->getBolSettings();
		ob_start();
			include BolPartnerProgramme::getInstance()->getPluginFolder().'/templates/plugin-options.php';
		$html = ob_get_contents();
		ob_end_clean();
		print $html;
	}
	public function printSectionInfo() {}

	public function settings() {
		register_setting(
			$this->getBolSettings(), // Option group
			$this->getBolSettings(), // Option name
			array( $this, 'sanitize' ) // Sanitize
		);
		register_setting(
			$this->getKeywordSettings(), // Option group
			$this->getKeywordSettings(), // Option name
			array( $this, 'sanitize' ) // Sanitize
		);
		register_setting(
			$this->getLayoutSettings(), // Option group
			$this->getLayoutSettings(), // Option name
			array( $this, 'sanitize' ) // Sanitize
		);

		add_settings_section(
			'bol_section_main', // ID
			'Main Settings', // Title
			array( $this, 'printSectionInfo' ), // Callback
			$this->getBolSettings()
		);  
		add_settings_field(
			'site_id', // ID
			'Site Id', // Title 
			array( $this, 'printInput' ), // Callback
			$this->getBolSettings(), // Page
			'bol_section_main', // Section           
			array('name' => 'site_id', 'type' => 'text', 'optionName' => $this->getBolSettings())
		);    
		add_settings_field(
			'api_access_key', // ID
			'API Access Key', // Title 
			array( $this, 'printInput' ), // Callback
			$this->getBolSettings(), // Page
			'bol_section_main', // Section           
			array('name' => 'api_access_key', 'type' => 'text', 'optionName' => $this->getBolSettings())
		);    
		add_settings_field(
			'searchMethod', 
			'How should I search?', 
			array( $this, 'printSelect'),
			$this->getBolSettings(), 
			'bol_section_main',
			array('name' => 'searchMethod','options' => $this->getSearchMethod(), 'optionName' => $this->getBolSettings())
		);

		add_settings_section(
			'bol_section_keyword', // ID
			'Keyword Settings', // Title
			array( $this, 'printSectionInfo' ), // Callback
			$this->getKeywordSettings()
		);  
		add_settings_field(
			'promo_keyword', // ID
			'Promo keyword', // Title 
			array( $this, 'printInput' ), // Callback
			$this->getKeywordSettings(), // Page
			'bol_section_keyword', // Section           
			array('name' => 'promo_keyword', 'type' => 'text', 'optionName' => $this->getKeywordSettings())
		);    
		add_settings_field(
			'enable_promo_keyword', 
			'Override all keywords with Promo keyword?', 
			array( $this, 'printCheckbox'),
			$this->getKeywordSettings(), 
			'bol_section_keyword', // Section           
			array('name' => 'enable_promo_keyword', 'optionName' => $this->getKeywordSettings())
		);
		add_settings_field(
			'default_keyword', // ID
			'Default Keyword', // Title 
			array( $this, 'printInput' ), // Callback
			$this->getKeywordSettings(), // Page
			'bol_section_keyword', // Section           
			array('name' => 'default_keyword', 'type' => 'text', 'optionName' => $this->getKeywordSettings())
		);    
		add_settings_field(
			'always_include_default', 
			'Always include default keyword?', 
			array( $this, 'printCheckbox'),
			$this->getKeywordSettings(), 
			'bol_section_keyword', // Section           
			array('name' => 'always_include_default', 'optionName' => $this->getKeywordSettings())
		);
		add_settings_field(
			'read_keyword', 
			'Read what keyword', 
			array( $this, 'printSelect'),
			$this->getKeywordSettings(),
			'bol_section_keyword', 
			array('name' => 'read_keyword', 'options' => $this->getKeywordOptions(), 'optionName' => $this->getKeywordSettings())
		);

		add_settings_field(
			'category', 
			'Category', 
			array( $this, 'printSelect'),
			$this->getKeywordSettings(),
			'bol_section_keyword',
			array('name' => 'category','options' => $this->getCategories(), 'optionName' => $this->getKeywordSettings())
		);
		add_settings_section(
			'bol_section_result', // ID
			'Layout Settings', // Title
			array( $this, 'printSectionInfo' ), // Callback
			$this->getLayoutSettings()
		);  
		add_settings_field(
			'nr_items', 
			'Number of results', 
			array( $this, 'printInput'),
			$this->getLayoutSettings(), 
			'bol_section_result',
			array('name' => 'nr_items', 'type' => 'number', 'optionName' => $this->getLayoutSettings())
		);
		add_settings_field(
			'show_price', 
			'Show Price', 
			array( $this, 'printCheckbox'),
			$this->getLayoutSettings(), 
			'bol_section_result', // Section           
			array('name' => 'show_price', 'optionName' => $this->getLayoutSettings())
		);
		add_settings_field(
			'show_author', 
			'Show Author', 
			array( $this, 'printCheckbox'),
			$this->getLayoutSettings(), 
			'bol_section_result', // Section           
			array('name' => 'show_author', 'optionName' => $this->getLayoutSettings())
		);
		add_settings_field(
			'show_delivery_specs', 
			'Show Delivery specs', 
			array( $this, 'printCheckbox'),
			$this->getLayoutSettings(), 
			'bol_section_result', // Section           
			array('name' => 'show_delivery_specs', 'optionName' => $this->getLayoutSettings())
		);
		add_settings_field(
			'title', 
			'Title', 
			array( $this, 'printInput' ), 
			$this->getLayoutSettings(), 
			'bol_section_result',
			array('name' => 'title', 'type' => 'text', 'optionName' => $this->getLayoutSettings())
		);
		add_settings_field(
			'text_color', 
			'Text Colour', 
			array( $this, 'printInput'),
			$this->getLayoutSettings(), 
			'bol_section_result',
			array('name' => 'text_color', 'type' => 'text', 'optionName' => $this->getLayoutSettings())
		);
		add_settings_field(
			'text_align', 
			'Text Align', 
			array( $this, 'printSelect'),
			$this->getLayoutSettings(), 
			'bol_section_result',
			array('name' => 'text_align','options' => $this->getElementAlignment(), 'optionName' => $this->getLayoutSettings())
		);
		add_settings_field(
			'title_color', 
			'Title Colour', 
			array( $this, 'printInput'),
			$this->getLayoutSettings(), 
			'bol_section_result',
			array('name' => 'title_color', 'type' => 'text', 'optionName' => $this->getLayoutSettings())
		);
		add_settings_field(
			'title_align', 
			'Title Align', 
			array( $this, 'printSelect'),
			$this->getLayoutSettings(), 
			'bol_section_result',
			array('name' => 'title_align','options' => $this->getElementAlignment(), 'optionName' => $this->getLayoutSettings())
		);
		add_settings_field(
			'title_size', 
			'Title Size', 
			array( $this, 'printInput'),
			$this->getLayoutSettings(), 
			'bol_section_result',
			array('name' => 'title_size', 'type' => 'text', 'optionName' => $this->getLayoutSettings())
		);
		add_settings_field(
			'image_size', // ID
			'Image Size', // Title 
			array( $this, 'printSelect' ), // Callback
			$this->getLayoutSettings(), // Page
			'bol_section_result', // Section           
			array('name' => 'image_size', 'options' => $this->getImageOptions(), 'optionName' => $this->getLayoutSettings())
		);    
		add_settings_field(
			'item_width', 
			'Item Width', 
			array( $this, 'printInput'),
			$this->getLayoutSettings(), 
			'bol_section_result',
			array('name' => 'item_width', 'type' => 'text', 'optionName' => $this->getLayoutSettings())
		);
		add_settings_field(
			'image_align', 
			'Image Align', 
			array( $this, 'printSelect'),
			$this->getLayoutSettings(), 
			'bol_section_result',
			array('name' => 'image_align','options' => $this->getElementAlignment(), 'optionName' => $this->getLayoutSettings())
		);
		add_settings_field(
			'price_color', 
			'Price Colour', 
			array( $this, 'printInput'),
			$this->getLayoutSettings(), 
			'bol_section_result',
			array('name' => 'price_color', 'type' => 'text', 'optionName' => $this->getLayoutSettings())
		);
		add_settings_field(
			'price_size', 
			'Price Size', 
			array( $this, 'printInput'),
			$this->getLayoutSettings(), 
			'bol_section_result',
			array('name' => 'price_size', 'type' => 'text', 'optionName' => $this->getLayoutSettings())
		);
		add_settings_field(
			'price_align', 
			'Price Align', 
			array( $this, 'printSelect'),
			$this->getLayoutSettings(), 
			'bol_section_result',
			array('name' => 'price_align','options' => $this->getElementAlignment(), 'optionName' => $this->getLayoutSettings())
		);
	}

	public function sanitize( $inputs ) {
		$new_input = array();

		foreach($inputs as $key => $value) {
			$new_input[$key] = sanitize_text_field( $inputs[$key] );
		}

		return $new_input;
	}

	public function printInput($params) {
		printf('<input type="%s" id="'.$params['name'].'" name="'.$params['optionName'].'['.$params['name'].']" value="%s" /><p class="description">%s</p>', isset($params['type']) ? $params['type'] : 'text', esc_attr( $this->getOption($params['name'],$params['optionName'])),isset($params['optionDescription']) ? $params['optionDescription'] : '');
	}
	public function printTextarea($params) {
		printf('<textarea id="'.$params['name'].'" name="'.$params['optionName'].'['.$params['name'].']">%s</textarea><p class="description">%s</p>', esc_attr( $this->getOption($params['name'],$params['optionName'])),isset($params['optionDescription']) ? $params['optionDescription'] : '');
	}
	public function printCheckbox($params) {
		printf('<input type="checkbox" id="'.$params['name'].'" name="'.$params['optionName'].'['.$params['name'].']"  %s/><p class="description">%s</p>', esc_attr( $this->getOption($params['name'],$params['optionName'])) ? 'checked' : '',isset($params['optionDescription']) ? $params['optionDescription'] : '');
	}
	public function printSelect($params) {
		$options = $params['options'];
		printf('<select id="'.$params['name'].'" name="'.$params['optionName'].'['.$params['name'].']"/>', $this->getOption($params['name'],$params['optionName']) ? esc_attr( $this->getOption($params['name'],$params['optionName'])) : '');
		foreach($options as $value => $label) {
			printf('<option value="%s" %s>%s</option>', esc_attr($value),esc_attr($value) == esc_attr($this->getOption($params['name'],$params['optionName'])) ? 'selected' : '',esc_attr($label));
		}
		printf('</select><p class="description">%s</p>',isset($params['optionDescription']) ? $params['optionDescription'] : '' );
	}
}

if (is_admin()) {
	$bolPPAdmin = new BolPartnerProgrammeAdmin();
	$bolPPAdmin->addActions();
}


