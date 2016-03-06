



<?php if(is_category() || is_tag() || is_tax()){ ?>
		

		
			<div class="banner-wrapper">
				<div class="single-cat-title">
					<section class="layout">
						<h1><?php single_cat_title('',true); ?></h1>
					</section>
				</div>
			</div>
			
<?php } elseif(is_search()) { ?>
		<div class="banner-wrapper">
			<div class="single-cat-title">
				<section class="layout">
					<h1><?php printf( __( 'Search Results for: %s', 'patiab' ), get_search_query() ); ?></h1>
				</section>
			</div>
		</div>
<?php } elseif(is_singular()) {
		$banner_mod = get_post_meta(get_the_ID(),'_patiab_banner_mod');
		
		switch ($banner_mod[0]) {
			case 'slider':
				$slider_shortcode = get_post_meta(get_the_ID(),'_patiab_slider_shortcode');
				echo '<div class="banner-wrapper">'.do_shortcode($slider_shortcode[0] ).'</div>';
				break;
			case 'image':
				$image = get_post_meta( get_the_ID(), '_patiab_image' );
				echo '<div class="banner-wrapper"><div class="banner-inner"><img class="page-banner" src="'.$image[0].'"/></div></div>';
				break;
			case 'map':
				$map = get_post_meta( get_the_ID(), '_patiab_map',1 );
				echo '<div class="banner-wrapper"><div class="banner-inner">'.$map.'</div></div>';
				break;
			default: 
				echo '<div class="banner-space"></div>';
				break;
		} ?>

		
<?php } else{ ?>
		<div class="banner-space">
		</div>
<?php  } ?>