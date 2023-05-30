<?php if ( ! defined( 'THEME_VERSION' ) ) {
  die( 'Forbidden' );
}
function merimag_share_networks( $keys = false, $get = 'name' ) {
  $social_networks = array(
      'facebook' => array( 'color' => '#3B5998', 'name' => __('Facebook', 'merimag') , 'icon' => 'icofont-facebook', 'action' => __('Share', 'merimag') ),
      'twitter' => array( 'color' => '#55ACEE', 'name' => __('Twitter', 'merimag') , 'icon' => 'icofont-twitter', 'action' => __('Tweet', 'merimag') ),
      'pinterest' => array( 'color' => '#cd1d1f', 'name' => __('Pinterest', 'merimag') , 'icon' => 'icofont-pinterest', 'action' => __('Pin', 'merimag') ),
      'linkedin' => array( 'color' => '#0976B4', 'name' => __('LinkedIn', 'merimag') , 'icon' => 'icofont-linkedin', 'action' => __('Share', 'merimag') ),
      'reddit'=> array( 'color' => '#F64720', 'name' => __('Reddit', 'merimag') , 'icon' => 'icofont-reddit', 'action' => __('Submit', 'merimag') ),
      'tumblr' => array( 'color' => '#35465d', 'name' => __('Tumblr', 'merimag') , 'icon' => 'icofont-tumblr', 'action' => __('Share', 'merimag') ),
      'whatsapp' => array( 'color' => '#01c501', 'name' => __('WhatsApp', 'merimag') , 'icon' => 'icofont-whatsapp', 'action' => __('Share', 'merimag') ),
       'vk' => array( 'color' => '#4e7db2', 'name' => __('VK', 'merimag') , 'icon' => 'icofont-vk', 'action' => __('Share', 'merimag') ),
      'email' => array( 'color' => '#242424', 'name' => __('Email', 'merimag') , 'icon' => 'icofont-envelope', 'action' => __('Email', 'merimag') ),
  );
  $valid_keys = array( 'color', 'name', 'icon', 'action','all');
  if( !in_array( $get, $valid_keys) && !isset( $social_networks[$get] ) ) {
      return false;
  }
  if( isset( $social_networks[$get] ) ) {
    return $social_networks[$get];
  }
  foreach( $social_networks as $id => $social_network ) {
    $return[$id] = $get !== 'all' && isset( $social_network[$get] ) ? $social_network[$get] : $social_network;
  }
  return $keys === false ? $return : array_keys($return);
}
function merimag_share_default_order() {
	return array('facebook', 'twitter', 'pinterest', 'linkedin', 'tumblr','reddit','vk', 'whatsapp', 'email');
}
function merimag_inline_share( $atts = array() ) {
	$class   = isset( $atts['layout'] ) && is_string( $atts['layout'] ) ? ' ' . $atts['layout'] . ' ' : ' justified prio ';
	$class  .= isset( $atts['size'] ) && is_string( $atts['size'] ) ? ' ' . $atts['size'] . ' ' : ' medium ';
	$class 	.= isset( $atts['rounded'] ) && $atts['rounded'] == true ? ' rounded ' : '';
	$color 	 = isset( $atts['color'] ) && merimag_validate_color( $atts['color'] ) ? $atts['color'] : false;
	if( function_exists('is_amp_endpoint') && is_amp_endpoint() ) {
		$atts['collapse'] = false;
	}
	$collapse = !isset( $atts['collapse'] ) || $atts['collapse'] === true ? true : false;
	$class  .= $collapse === true ? ' collapsed-sharing ' : '';
	$id 	 = merimag_uniqid('merimag-inline-sharing-');
	if( $color ) {
		merimag_add_share_networks_css( $color, $id );
	}
	$share_networks = merimag_share_networks(false, 'all');
	echo sprintf('<div id="%s" class="merimag-inline-sharing  with-background %s">', esc_attr( $id ), esc_attr( $class ) );
	$orderd_networks = merimag_share_default_order();
	foreach($orderd_networks as $ntk ) {
		$network_id = $ntk;
		$title = isset( $share_networks[$ntk]['name'] ) ? $share_networks[$ntk]['name'] : false;
		$link  	 = merimag_get_share_link( $ntk );
		$icon  	 = isset( $share_networks[$ntk]['icon'] ) ? $share_networks[$ntk]['icon'] : 'icofont-facebook';
		$share_title = isset( $network['action'] ) ? $share_networks[$ntk]['action'] : __('Share','merimag');
		$onclick = sprintf("window.open(this.href, '%s','left=50,top=50,width=600,height=320,toolbar=0'); return false;", esc_js( $share_title ) );
		$target = $network_id !== 'email' ? '_blank' : '_self';
		echo sprintf('<a href="%s" onclick="%s" target="%s" class="merimag-share-item %s"><span class="merimag-share-item-content"><i class="%s"></i><span>%s</span></span></a>', esc_url( $link ), $onclick, esc_attr( $target ), esc_attr( $network_id ), esc_attr( $icon ), esc_attr( $title ) );
	}
	if( $collapse === true ) {
		echo '<a href="#" class="merimag-share-item more"><span class="merimag-share-item-content"><i class="icofont-plus"></i></span></a>';
	}
	
	echo '</div>';
}
function merimag_get_share_networks_css( $color = false, $id = '' ) {
	$css = '';
	$id  = !is_string( $id ) ? '' : $id;
	$share_networks = merimag_share_networks(false, 'all');
	if( !$color ) {
		foreach( $share_networks as $network_id => $network ) {
			$color = isset( $network['color'] ) ? $network['color'] : false;
			$text_color  = merimag_get_text_color_from_background( $color );
			if( merimag_validate_color( $color ) ) {
				$text_color = merimag_get_text_color_from_background( $color );
				$link  = merimag_get_share_link( $network_id );
				$icon  = isset( $network['icon'] ) ? $network['icon'] : 'icofont-facebook';
				$css   .= sprintf('.merimag-inline-sharing.with-background .merimag-share-item.%s .merimag-share-item-content { background: %s; color: %s; }', $network_id, $color, $text_color );
			}
		}
	} else {
		$text_color  = merimag_get_text_color_from_background( $color );
		$css   		.= sprintf('#%s.merimag-inline-sharing.with-background .merimag-share-item .merimag-share-item-content { background: %s; color: %s; }', $id, $color, $text_color );
	}
	return $css;
}
function merimag_add_share_networks_css( $color = false, $id = false ) {
	$cache_id = 'merimag_cache_share_networks_css';
	$css_cache = get_transient($cache_id);
	$enable_cache = merimag_get_db_customizer_option('enable_cache', 'no');
	if( $css_cache && !$color && $enable_cache === 'yes' ) {
		$css = $css_cache;
		if( function_exists('is_amp_endpoint') && is_amp_endpoint() ) {
			echo wp_kses_post( $css );
			return;
		}
	} else {
		$css = function_exists('is_amp_endpoint') && is_amp_endpoint() ? merimag_get_share_networks_css() : merimag_get_share_networks_css( $color, $id );
		if( function_exists('is_amp_endpoint') && is_amp_endpoint() ) {
			echo wp_kses_post( $css );
			return;
		}
		set_transient($cache_id, $css );
	}
	// Dynamic bloc css
	$id = $id && is_string( $id ) ? $id . '-css': 'merimag-share-networks-css';
	if( function_exists('is_amp_endpoint') && is_amp_endpoint() ) {
		echo wp_kses_post( $css );
	} else {
		wp_register_style( $id, false );
	    wp_enqueue_style( $id );
	    wp_add_inline_style( $id, $css );
	}
    
}
add_action('wp_head', 'merimag_add_share_networks_css', 2 );
add_action('amp_post_template_css', 'merimag_add_share_networks_css', 99 );
function merimag_get_share_link($network_id) {

	$args['url']   = get_the_permalink();
	$args['title'] = get_the_title();
	$args['image'] = get_the_post_thumbnail_url();
	$args['desc']  = get_the_excerpt();

	$url 		   = isset( $args['url'] ) ? $args['url'] : '';
	$title  	   = isset( $args['title'] ) ? urlencode($args['title']) : '';
	$title_normal  = isset( $args['title'] ) ? $args['title'] : '';
	$image  	   = isset( $args['image'] ) ? urlencode($args['image']) : '';
	$desc 		   = isset( $args['desc'] ) ? urlencode($args['desc']) : '';

	$text   	   = $title;


	if( $desc ) {
		$text .= '%20%3A%20';	# This is just this, " : "
		$text .= $desc;
	}
	
	// conditional check before arg appending
	
	$data = [
	    'whatsapp' => 'https://wa.me/?text=' . $title_normal . '%20%3A%20' . $url,
		'add.this'=>'http://www.addthis.com/bookmark.php?url=' . $url,
		'blogger'=>'https://www.blogger.com/blog-this.g?u=' . $url . '&n=' . $title . '&t=' . $desc,
		'buffer'=>'https://buffer.com/add?text=' . $title . '&url=' . $url,
		'diaspora'=>'https://share.diasporafoundation.org/?title=' . $title . '&url=' . $url,
		'digg'=>'http://digg.com/submit?url=' . $url . '&title=' . $title,
		'douban'=>'http://www.douban.com/recommend/?url=' . $url . '&title=' . $title,
		'email'=>'mailto:?subject=' . $title_normal . '&body=' . $desc,
		'evernote'=>'http://www.evernote.com/clip.action?url=' . $url . '&title=' . $title,
		'getpocket'=>'https://getpocket.com/edit?url=' . $url,
		'facebook'=>'http://www.facebook.com/sharer.php?u=' . $url,
		'flipboard'=>'https://share.flipboard.com/bookmarklet/popout?v=2&title=' . $title . '&url=' . $url, 
		'gmail'=>'https://mail.google.com/mail/?view=cm&to=&su=' . $title . '&body=' . $url,
		'google.bookmarks'=>'https://www.google.com/bookmarks/mark?op=edit&bkmk=' . $url . '&title=' . $title . '&annotation=' . $text,
		'instapaper'=>'http://www.instapaper.com/edit?url=' . $url . '&title=' . $title . '&description=' . $desc,
		'line.me'=>'https://lineit.line.me/share/ui?url=' . $url . '&text=' . $title,
		'linkedin'=>'https://www.linkedin.com/shareArticle?mini=true&url=' . $url . '&title=' . $title . '&summary=' . $text,
		'livejournal'=>'http://www.livejournal.com/update.bml?subject=' . $title . '&event=' . $url,
		'hacker.news'=>'https://news.ycombinator.com/submitlink?u=' . $url . '&t=' . $title,
		'ok.ru'=>'https://connect.ok.ru/dk?st.cmd=WidgetSharePreview&st.shareUrl=' . $url,
		'pinterest'=>'http://pinterest.com/pin/create/button/?url=' . $url ,
		'qzone'=>'http://sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshare_onekey?url=' . $url,
		'reddit'=>'https://reddit.com/submit?url=' . $url . '&title=' . $title,
		'renren'=>'http://widget.renren.com/dialog/share?resourceUrl=' . $url . '&srcUrl=' . $url . '&title=' . $text . '&description=' . $desc,
		'skype'=>'https://web.skype.com/share?url=' . $url . '&text=' . $title,
		'sms'=>'sms:?body=' . $title,
		'surfingbird.ru'=>'http://surfingbird.ru/share?url=' . $url . '&description=' . $desc . '&screenshot=' . $image . '&title=' . $title,
		'telegram.me'=>'https://t.me/share/url?url=' . $url . '&text=' . $title . '&to',
		'tumblr'=>'https://www.tumblr.com/widgets/share/tool?canonicalUrl=' . $url . '&title=' . $title . '&caption=' . $desc,
		'twitter'=>'https://twitter.com/intent/tweet?url=' . $url . '&text=' . $title,
		'vk'=>'http://vk.com/share.php?url=' . $url . '&title=' . $title . '&comment=' . $title,
		'weibo'=>'http://service.weibo.com/share/share.php?url=' . $url . '&appkey=&title=' . $title . '&pic=&ralateUid=',
		'xing'=>'https://www.xing.com/spi/shares/new?url=' . $url,
		'yahoo'=>'http://compose.mail.yahoo.com/?to=&subject=' . $title . '&body=' . $text,
	];

	return isset( $data[$network_id] ) ? $data[$network_id] : '';
}