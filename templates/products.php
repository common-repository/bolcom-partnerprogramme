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
	$gridClassName = '';
	if ($bolPPSearch->getTmpSetting('grid') == 'true') {
		$gridClassName = '-grid ';
	}
?>
<?php if (!$rest) { ?>
<div class="js--bol-pp-results bol-products <?php print $gridClassName?>" data-url="<?php print admin_url( 'admin-ajax.php' )?>">
<?php } ?>
<?php
if (count($bolPPSearch->getProducts()) > 0) { ?>
	<ul class="bol-products-list"><?php 
		$category = null;
		$resultSize = 0;
		foreach ($bolPPSearch->getProducts() as $item) {
			if (isset($item->{'category'}) && is_object($item->{'category'})) {
				$category = $item->{'category'};
			} else if (is_numeric($item)) {
				$resultSize = $item;
			} else if (isset($item) && count($item)) {
				foreach($item as $product) {
		
					$offer = $bolPPSearch->getProductOfferDetails($product);
					$url = $bolPPSearch->getProductUrlDetails($product);
					$images = $bolPPSearch->getProductImages($product);
					?><li class="_product"><?php
						if ($images[$bolPPSearch->getOption('image_size',$bolPPSearch->getLayoutSettings())] && strtolower($bolPPSearch->getOption('image_size',$bolPPSearch->getLayoutSettings())) != 'none') {
							?><a class="-product-image _size-<?=$bolPPSearch->getOption('image_size',$bolPPSearch->getLayoutSettings())?> _align-<?php print $this->getOption('image_align',$bolPPSearch->getLayoutSettings())?>" href="<?php print $url?>"><img class="-image" src="<?php print $images[$bolPPSearch->getOption('image_size',$bolPPSearch->getLayoutSettings())]?>" alt="<?php print $product->{'title'}?>"></a><?php
						} 
						?><h4 class="_title _align-<?php print $this->getOption('title_align',$bolPPSearch->getLayoutSettings())?>"><a href="<?php print $url?>"><?php print $product->{'title'}?></a></h4><?php

						if ($this->getOption('show_price',$bolPPSearch->getLayoutSettings())) {
							?><a href="<?php print $url?>" class="-offer _align-<?php print $this->getOption('price_align',$bolPPSearch->getLayoutSettings())?>"><?php  print $bolPPSearch->formatPrice($offer['best']->{'price'}); ?> </a><?php
						} 

						if ($this->getOption('show_delivery_specs',$bolPPSearch->getLayoutSettings())) { 
							?><div class="-delivery-time">Levertijd: <?php  print $offer['best']->{'availabilityDescription'}; ?> </div><?php
						}

						if ($this->getOption('show_author',$bolPPSearch->getLayoutSettings()) && isset($product->{'specsTag'})) { 
							?><div class="-author"><?php print $product->{'specsTag'}?></div><?php
						}
					?></li><?php
				}
			}
		}  
	?></ul><?php
} 
if (!$rest) { ?>
	</div><?
}
