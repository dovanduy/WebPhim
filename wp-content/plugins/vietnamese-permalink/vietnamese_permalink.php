<?php
/************************************************************************************
#	Plugin Name: Vietnamese Permarlink										
#	Plugin URI: http://nhanweb.com/2010/06/plugin-wordpress-vietnamese-permalink/
#	Description: Make you Permalink friendly: no space, no accents, no dot, no @... and reset your Permalink if you want...	
#	Author: Nguyen Duy Nhan															
#	Version: 1.2.0																	
#	Author URI: http://nhanweb.com													
************************************************************************************/
add_action('admin_menu', 'wp_add_vietnamese_permalink_settings');

function wp_add_vietnamese_permalink_settings() {
	if (function_exists('add_options_page')) {
		add_options_page( __('Reset Permalinks',''), __('Reset Permalinks',''), 8, basename(__FILE__), 'vietnamese_permalink_panel');
	}
}
/*function vn_sample_permalink_html( $id, $new_title = null, $new_slug = null ) {
	global $wpdb;
	$post = &get_post($id);
	list($permalink, $post_name) = get_sample_permalink($post->ID, $new_title, $new_slug);
	$link = str_replace(array('%pagename%','%postname%'), $post_name, $permalink);
	return $link;
}*/

function vietnamese_permalink_reset(){
	global $wpdb;
	$posts = $wpdb->get_results("SELECT * FROM " . $wpdb->posts . " ORDER BY id ASC");
	$i=1;
	foreach ($posts as $post) {
		$newpermalink =  vietnamese_permalink($post->post_title );
		//wp_update_post($my_post);
		$sql = "UPDATE " . $wpdb->posts . " SET `guid` = '{$newpermalink}', `post_name` = '{$newpermalink}' WHERE id = '$post->ID'";
		$update = $wpdb->query($sql);
		$i++;
	}
	echo " <div class=\"updated\"><p>All Permalinks were changed ! (Effected post: $i)</p></div>";
}
function vietnamese_permalink_panel(){
	if($_POST['wp_vn_permalink']){
		vietnamese_permalink_reset();
	}
	
	
	?>
	<h2>Reset Permalink</h2>
	<p>To reset your permalinks please confirm your action by click on <strong>Reset button</strong> below.</p>
	<p>If you have any problem or need help, please contact <a href="http://vnwebmaster.com">Webmaster Viet Nam forum</a></p>
	 <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>?page=<?php echo basename(__FILE__); ?>">
	<h3>Reset Permalinks</h3>
	<div class="updated"><p><strong>Warning</strong>: Please remember ! All of the Permalinks will be formatted according your <a href="options-permalink.php">Common settings</a></p></div>
	<p class="submit"><input type="submit" value="Reset Permalinks" name="wp_vn_permalink" class="button-primary" /></p>
  </form>
  <h3>Link for you:</h3>
<p><a href="http://nhanweb.com">Support Blog</a> - <a href="http://vnwebmaster.com">Support Forum</a> - <a href="http://nguyenduynhan.com">Author Website</a></p>
	<?php

}
function vietnamese_permalink($title) {
		/* 	Replace with "-"
			Change it if you want
		*/
		
		$replacement = '-';
		$map = array();
		$quotedReplacement = preg_quote($replacement, '/');

		$default = array(
			'/à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ|À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ|å/' => 'a',
			'/è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ|È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ|ë/' => 'e',
			'/ì|í|ị|ỉ|ĩ|Ì|Í|Ị|Ỉ|Ĩ|î/' => 'i',
			'/ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ|Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ|ø/' => 'o',
			'/ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ|Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ|ů|û/' => 'u',
			'/ỳ|ý|ỵ|ỷ|ỹ|Ỳ|Ý|Ỵ|Ỷ|Ỹ/'	=> 'y',
			'/đ|Đ/' => 'd',
			'/ç/' => 'c',
			'/ñ/' => 'n',
			'/ä|æ/' => 'ae',
			'/ö/' => 'oe',
			'/ü/' => 'ue',
			'/Ä/' => 'Ae',
			'/Ü/' => 'Ue',
			'/Ö/' => 'Oe',
			'/ß/' => 'ss',
			'/[^\s\p{Ll}\p{Lm}\p{Lo}\p{Lt}\p{Lu}\p{Nd}]/mu' => ' ',
			'/\\s+/' => $replacement,
			sprintf('/^[%s]+|[%s]+$/', $quotedReplacement, $quotedReplacement) => '',
		);
		//Some URL was encode, decode first
		$title = urldecode($title);
		
		$map = array_merge($map, $default);
		return strtolower( preg_replace(array_keys($map), array_values($map), $title) );
    #---------------------------------o
}

add_filter('sanitize_title', 'vietnamese_permalink', 1);

?>