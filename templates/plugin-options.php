<div class="wrap">
	<h2><?php _e('Bol.com Partner programme Settings','bol-com-partner')?></h2>
	<h2 class="nav-tab-wrapper">
		<a class="nav-tab <?php print $tab == $this->getBolSettings() ? 'nav-tab-active' : ''?>" href="?page=bol-com-partner&tab=<?php print $this->getBolSettings()?>">Main settings</a>
		<a class="nav-tab <?php print $tab == $this->getKeywordSettings() ? 'nav-tab-active' : ''?>" href="?page=bol-com-partner&tab=<?php print $this->getKeywordSettings()?>">Keyword settings</a>
		<a class="nav-tab <?php print $tab == $this->getLayoutSettings() ? 'nav-tab-active' : ''?>" href="?page=bol-com-partner&tab=<?php print $this->getLayoutSettings()?>">Layout settings</a>
	</h2>
	<form method="post" action="options.php"><?php
		wp_nonce_field( 'update-options' );
		settings_fields($tab);
		do_settings_sections($tab);
		submit_button();
	?></form>
</div>
