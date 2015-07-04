<?php

function add_copyright_text() {
if (is_single()) { ?>
 
<script type='text/javascript'>
function addLink() {
 if (
window.getSelection().containsNode(
document.getElementsByClassName('entry-content')[0], true)) {
 var body_element = document.getElementsByTagName('body')[0];
 var selection;
 selection = window.getSelection();
 var oldselection = selection
 var pagelink = "<br /><br /> Read more at WPBeginner: <?php the_title(); ?> <a href='<?php echo wp_get_shortlink(get_the_ID()); ?>'><?php echo wp_get_shortlink(get_the_ID()); ?></a>"; //Change this if you like
 var copy_text = selection + pagelink;
 var new_div = document.createElement('div');
 new_div.style.left='-99999px';
 new_div.style.position='absolute';
 
body_element.appendChild(new_div );
 new_div.innerHTML = copy_text ;
 selection.selectAllChildren(new_div );
 window.setTimeout(function() {
 body_element.removeChild(new_div );
 },0);
}
}
document.oncopy = addLink;
</script>
 
<?php
}
}
 
add_action( 'wp_head', 'add_copyright_text');

define('DATA_POST', $wpdb->posts);
define('DATA_POSTMETA', $wpdb->prefix.'postmeta');
define('DATA_COMMENTS', $wpdb->prefix.'comments');
define('DATA_COMMENTMETA', $wpdb->prefix.'commentmeta');
define('DATA_TERMS', $wpdb->prefix.'terms');
define('DATA_TERM_TAXONOMY', $wpdb->prefix.'term_taxonomy');
define('DATA_TERM_RELATIONSHIPS', $wpdb->prefix.'term_relationships');
define('DATA_TERM_META', $wpdb->prefix.'term_meta');
define('DATA_FILM_EPISODE', $wpdb->prefix.'film_episode');
define('DATA_FILM_META', $wpdb->prefix.'film_meta');
define('DATA_FILM_SERVER', $wpdb->prefix.'film_server');
define('DATA_FILM_EPISODE', $wpdb->prefix.'film_episode');
define('DOCUMENT_ROOT', $_SERVER['DOCUMENT_ROOT']."");
define('PATH', $_SERVER['DOCUMENT_ROOT']."phim/cached/");
define('DOCUMENT_TEMP', get_template_directory());
define('TEMP_DIR', get_bloginfo('template_directory')."");
define('SITE_NAME', get_bloginfo('name'));
define('SITE_URL', get_bloginfo('url'));
define('NOW', time());
register_sidebar(array(
 'name'=>'sidebar',
 'id' => 'sidebar',
 'description' => 'Sidebar',
 'before_widget' => '<div class="block"><div class="blocktitle">',
 'after_widget'  => '</div></div><div class="divider"></div>',
 'before_title' => '<div class="title-widget">',
 'after_title' => '</div>',
));

function excerpt($num) {
	$link = get_permalink();
	$ending = get_option('wl_excerpt_ending');
	$limit = $num+1;
	$excerpt = explode(' ', get_the_excerpt(), $limit);
	array_pop($excerpt);
	$excerpt = implode(" ",$excerpt).$ending;
	echo $excerpt;
	$readmore = get_option('wl_readmore_link');
	if($readmore!="") {
		$readmore = '<p class="readmore"><a href="'.$link.'">'.$readmore.'</a></p>';
		echo $readmore;
	}
}
function img2($width,$height) {
	global $post;
	$custom_field_value_2 = get_post_meta($post->ID, 'Image', true);
	$attachments = get_children( array('post_parent' => $post->ID, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'numberposts' => 1) );
	if(has_post_thumbnail()){
		$domsxe = simplexml_load_string(get_the_post_thumbnail($post->ID,'full'));
		$thumbnailsrc = $domsxe->attributes()->src;
		$img_url = parse_url($thumbnailsrc,PHP_URL_PATH);
	print '<img src="'.get_bloginfo('template_url').'/thumb.php?src='.$img_url.'&amp;w='.$width.'&amp;h='.$height.'&amp;q=100" alt="'.$post->post_title.'" title="'.$post->post_title.'" />';
	}
	elseif ($custom_field_value_2 == true) {
	print '<img src="'.$custom_field_value_2.'" width="'.$width.'" height="'.$height.'" alt="'.$post->post_title.'" title="'.$post->post_title.'" />';
	} 
	elseif ($attachments == true) {
		foreach($attachments as $id => $attachment) {
		$img = wp_get_attachment_image_src($id, 'full');
		$image = $image[0];
		$img_url = parse_url($img[0], PHP_URL_PATH);
		print '<img src="'.get_bloginfo('template_url').'/thumb.php?src='.$img_url.'&amp;w='.$width.'&amp;h='.$height.'&amp;q=70" alt="'.$post->post_title.'" title="'.$post->post_title.'" />';
		}
	}
	else {
		global $post, $posts;
		$first_img = '';
		ob_start();
		ob_end_clean();
		$output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches);
		$first_img = $matches [1] [0];
		
		if($first_img){ 
			print '<img src="'.$first_img.'" width="'.$width.'" height="'.$height.'" alt="'.$post->post_title.'" title="'.$post->post_title.'" />';
		}
        else {
            print '<img src="https://lh3.googleusercontent.com/-aFbL2YlsigM/T_rUHAXccpI/AAAAAAAABDM/TsUOlCpZkgQ/s430/hien-thi-logo.jpg" title="'.$post->post_title.'" alt="'.$post->post_title.'" width="'.$width.'" height="'.$height.'" />';
        }
	}
}
function img($width,$height) {
	global $post;
	$custom_field_value_2 = get_post_meta($post->ID, 'Image', true);
	$attachments = get_children( array('post_parent' => $post->ID, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'numberposts' => 1) );
	if(has_post_thumbnail()){
		$domsxe = simplexml_load_string(get_the_post_thumbnail($post->ID,'full'));
		$thumbnailsrc = $domsxe->attributes()->src;
		$img_url = parse_url($thumbnailsrc,PHP_URL_PATH);
	print get_bloginfo('template_url')."/thumb.php?src=$img_url&amp;w=$width&amp;h=$height&amp;q=100";
	}
	elseif ($custom_field_value_2 == true) {
	print $custom_field_value_2;
	} 
	elseif ($attachments == true) {
		foreach($attachments as $id => $attachment) {
		$img = wp_get_attachment_image_src($id, 'full');
		$image = $image[0];
		$img_url = parse_url($img[0], PHP_URL_PATH);
		print get_bloginfo('template_url')."/thumb.php?src=$img_url&amp;w=$width&amp;h=$height&amp;q=70";
		}
	}
	else {
		global $post, $posts;
		$first_img = '';
		ob_start();
		ob_end_clean();
		$output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches);
		$first_img = $matches [1] [0];
		
		if($first_img){ 
			print "$first_img";
		}
        else {
            print "https://lh3.googleusercontent.com/-aFbL2YlsigM/T_rUHAXccpI/AAAAAAAABDM/TsUOlCpZkgQ/s430/hien-thi-logo.jpg";
        }
	}
}

function img3($width,$height) {
	global $post;
	$custom_field_value_2 = get_post_meta($post->ID, 'Image', true);
	$attachments = get_children( array('post_parent' => $post->ID, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'numberposts' => 1) );
	if(has_post_thumbnail()){
		$domsxe = simplexml_load_string(get_the_post_thumbnail($post->ID,'full'));
		$thumbnailsrc = $domsxe->attributes()->src;
		$img_url = parse_url($thumbnailsrc,PHP_URL_PATH);
	$img=get_bloginfo('template_url')."/thumb.php?src=$img_url&amp;w=$width&amp;h=$height&amp;q=100";
	}
	elseif ($custom_field_value_2 == true) {
	$img= $custom_field_value_2;
	} 
	elseif ($attachments == true) {
		foreach($attachments as $id => $attachment) {
		$img = wp_get_attachment_image_src($id, 'full');
		$image = $image[0];
		$img_url = parse_url($img[0], PHP_URL_PATH);
		$img= get_bloginfo('template_url')."/thumb.php?src=$img_url&amp;w=$width&amp;h=$height&amp;q=70";
		}
	}
	else {
		global $post, $posts;
		$first_img = '';
		ob_start();
		ob_end_clean();
		$output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches);
		$first_img = $matches [1] [0];
		
		if($first_img){ 
			$img= "$first_img";
		}
        else {
            $img= "https://lh3.googleusercontent.com/-aFbL2YlsigM/T_rUHAXccpI/AAAAAAAABDM/TsUOlCpZkgQ/s430/hien-thi-logo.jpg";
        }
	}
	return $img;
}
function en_id($id) {
    $id = dechex($id + 241104185);
    $id = str_replace(1,'I',$id);
    $id = str_replace(2,'W',$id);
    $id = str_replace(3,'O',$id);
    $id = str_replace(4,'U',$id);
    $id = str_replace(5,'Z',$id); 
    return strtoupper($id);
}
function del_id($id) {
    $id = str_replace('Z',5,$id);
    $id = str_replace('U',4,$id);
    $id = str_replace('O',3,$id);
    $id = str_replace('W',2,$id);
	$id = str_replace('I',1,$id);
    $id = hexdec($id);
	$id = $id - 241104185;
    return strtoupper($id);
}
function catchuoi($chuoi,$gioihan){ 
    // nếu độ dài chuỗi nhỏ hơn hay bằng vị trí cắt 
    // thì không thay đổi chuỗi ban đầu 
    if(strlen($chuoi)<=$gioihan) 
    { 
        return $chuoi; 
    } 
    else{ 
        /*  
        so sánh vị trí cắt  
        với kí tự khoảng trắng đầu tiên trong chuỗi ban đầu tính từ vị trí cắt 
        nếu vị trí khoảng trắng lớn hơn 
        thì cắt chuỗi tại vị trí khoảng trắng đó 
        */ 
        if(strpos($chuoi," ",$gioihan) > $gioihan){ 
            $new_gioihan=strpos($chuoi," ",$gioihan); 
            $new_chuoi = substr($chuoi,0,$new_gioihan)."..."; 
            return $new_chuoi; 
        } 
        // trường hợp còn lại không ảnh hưởng tới kết quả 
        $new_chuoi = substr($chuoi,0,$gioihan)."..."; 
        return $new_chuoi; 
    } 
}



function new_tap($tap,$id) {
      $name = get_the_title( $id );
      $tap =get_name($tap);
	  $link = get_permalink( $id );
	  
	  if($tap==""){
	  return $name;
	  }
      else{
	  return $name." tập ".$tap;
	  }
  } 
function new_title() {
     if ( !is_singular() )
          return;

      global $wp_the_query;
      if ( !$id = $wp_the_query->get_queried_object_id() )
          return;
		$ep=get_query_var('ep');
      $name = get_the_title( $id );
      $tap =get_name($ep);
	  
	  if($ep==""){
	  $title ="";
	  }
      else{
	  $title =" tập $tap";
	  }
	  return $title;
  }  
function new_desc() {
     if ( !is_singular() )
          return;

      global $wp_the_query;
      if ( !$id = $wp_the_query->get_queried_object_id() )
          return;
		$ep=get_query_var('ep');
      $name = get_the_title( $id );
      $tap =get_name($ep);
	  
	  if($ep==""){
	  $desc ="";
	  }
      else{
	  $desc =" $name tập $tap";
	  }
	  return $desc;
  }
function new_brek() {
     if ( !is_singular() )
          return;

      global $wp_the_query,$post;
      if ( !$id = $wp_the_query->get_queried_object_id() )
          return;
		$ep=get_query_var('ep');
      $name = get_the_title( $id );
      $tap ="Tập ".get_name($ep);
	  $link = get_permalink( $id );
	  
	  if($ep==""){
	  return '<h2 class="item last-child" typeof="v:Breadcrumb">'.$name.'</h2>';
	  }
      else{
	  return '<span typeof="v:Breadcrumb" class="item"><a title="'.$name.'" href="'.$link.'" property="v:title" rel="v:url">'.$name.'</a></span><h2 class="item last-child">'.$tap.'</h2>';
	  }
  }
function the_breadcrumb() {
		echo '';
	if (!is_home()) {
		echo '<span class="item" typeof="v:Breadcrumb"><a href="';
		echo get_option('home');
		echo '" rel="v:url" property="v:title">';
		echo 'Phim6789';
		echo "</a></span>";
		if (is_category() || is_single()) {
			echo '';
			$categories = get_the_category();
$seperator = '';
$output = '';
if($categories){
	foreach($categories as $category) {
		$output .= '<span class="item" typeof="v:Breadcrumb"><a rel="v:url" property="v:title" href="'.get_category_link($category->term_id ).'" title="' . esc_attr( sprintf( __( "View all posts in %s" ), $category->name ) ) . '">'.$category->cat_name.'</a></span>'.$seperator;
	}
echo trim($output, $seperator);
}
echo '';
			if (is_single()) {
			
				
				echo new_brek();
				
			}
		} elseif (is_page()) {
			echo '<span class="item" itemtype="http://data-vocabulary.org/Breadcrumb" itemscope="">';
			echo the_title();
			echo '</span>';
		}
echo '';		
	}
	elseif (is_tag()) {single_tag_title();}
	elseif (is_day()) {echo"<li>Archive for "; the_time('F jS, Y'); echo'</li>';}
	elseif (is_month()) {echo"<li>Archive for "; the_time('F, Y'); echo'</li>';}
	elseif (is_year()) {echo"<li>Archive for "; the_time('Y'); echo'</li>';}
	elseif (is_author()) {echo"<li>Author Archive"; echo'</li>';}
	elseif (isset($_GET['paged']) && !empty($_GET['paged'])) {echo "<li>Blog Archives"; echo'</li>';}
	elseif (is_search()) {echo"<li>Search Results"; echo'</li>';}
	echo '';
}
function search_url_rewrite_rule() {
	if ( is_search() && !empty($_GET['s'])) {
		wp_redirect(home_url("/search/") . str_replace('+','-',urlencode(get_query_var('s'))));
		exit();
	}	
}
add_action('template_redirect', 'search_url_rewrite_rule');
function get_views_last($type,$num=10){
			global $wpdb;
if($type=="day") { $where="AND ".DATA_FILM_META.".film_viewed_d";$order="".DATA_FILM_META.".film_viewed_d";}
elseif($type=="week") { $where="AND ".DATA_FILM_META.".film_viewed_w";$order="".DATA_FILM_META.".film_viewed_w";}
else {$where="AND ".DATA_FILM_META.".film_viewed_m";$order="".DATA_FILM_META.".film_viewed_m";}
$sql=$wpdb->get_results("SELECT 
								".DATA_POST.".post_title
								, ".DATA_POST.".ID
								, ".DATA_POST.".post_name
								,".DATA_POST.".post_content 								
								, ".DATA_FILM_META.".film_viewed_d
								, ".DATA_FILM_META.".film_viewed_w
								, ".DATA_FILM_META.".film_viewed_m 
							FROM ".DATA_POST.", ".DATA_FILM_META." 
							WHERE ".DATA_POST.".post_status = 'publish'
							".$where."
							AND ".DATA_POST.".ID = ".DATA_FILM_META.".film_id  
							ORDER BY ".$order." DESC LIMIT 10");
$i=0;
	foreach ($sql as $viewday){
if($type=="day") $views=$viewday->film_viewed_d;
elseif($type=="week") $views=$viewday->film_viewed_w;
else $views=$viewday->film_viewed_m;
	$i++;
if($i==1 || $i==2 || $i==3) {					
					$class='class="st top"';}else $class='class="st"';
$html.='<li>
<span '.$class.'>'.$i.'</span>
<div class="detail">
<div class="name">
<h3><a href="'.get_permalink($viewday->ID).'" title="'.get_the_title($viewday->ID).'">'.get_the_title($viewday->ID).'</a></h3>
</div>
<div class="views">Lượt xem '.$views.'</div>
</div>
</li>
';
}
return $html;
}
function get_most_viewed_2($limit = 16,$begin=0,$excerpt_length=40, $show_thumb = true) {
		global $wpdb, $post;
	$type_phim6789	=	$type;
	$file = DOCUMENT_TEMP."/cached/phim6789_mostview.xxx"; // lấy tên file cache theo type để tránh trùng lập
	$expire = 86400; // 24h
	if (file_exists($file) &&
    filemtime($file) > (time() - $expire)) {
    include(DOCUMENT_TEMP."/cached/phim6789_mostview.xxx");
	} else { 		
		$where = "";
		$output = '';
		$where = "post_type = 'post'";
		$most_viewed = $wpdb->get_results("SELECT DISTINCT $wpdb->posts.*, (meta_value+0) AS views FROM $wpdb->posts LEFT JOIN $wpdb->postmeta ON $wpdb->postmeta.post_id = $wpdb->posts.ID WHERE post_date < '".current_time('mysql')."' AND $where AND post_status = 'publish' AND meta_key = 'views' AND post_password = '' ORDER  BY views DESC LIMIT $begin,$limit");
		if($most_viewed) { 
		$html.='';
			 foreach ($most_viewed as $post) { 
				$post_views = number_format_i18n(intval($post->views));
				$post_url = get_permalink($post->ID);
				$post_title = get_the_title();
					$html .= '<li><div class="inner"><a href="'.get_permalink().'" title="'.get_the_title().'"><img src="'.img3(146,195).'" alt="'.get_the_title().'"/></a><div class="info"><div class="name"><a href="'.get_permalink().'" title="'.get_the_title().'">'.get_the_title().'</a> </div><div class="name2">'.get_post_meta($post->ID, "phim_en", true).'</div><div class="stats"></div></div><div class="status">'.get_post_meta($post->ID, "phim_hd", true).'</div></div></li>';
			 } 
$html.='
';			
		 } 
		     $fp = fopen($file,"w");
    fputs($fp, $html);
    fclose($fp);
	include(DOCUMENT_TEMP."/cached/phim6789_mostview.xxx");
}		 
		
	}
function get_excerpt_content($max_char = 55,$more_text = '', $printout = 1,$content = '') {

	if ($content == '')

		$content = get_the_content('');

		

	$content = apply_filters('the_content', $content);

	$content = str_replace(']]>', ']]&gt;', $content);

	$content = strip_tags($content);

	

	$words = explode(' ', $content, $max_char + 1);

	

	if (count($words) > $max_char) {

		array_pop($words);

		$content = implode(' ', $words);

		$content = $content . '...';

	}

	

	if ($more_text != '') $content = $content.' <a class="continuebox" href="'.get_permalink().'" title="Permanent Link to '.get_the_title().'">'.$more_text.'</a>';

	

	if ($printout==1)

		echo $content;

	else

		return $content;

}
function get_film_home($type,$num=16,$excerpt_length=40) {
global $post;
	// tao cache					
query_posts('post_type=post&showposts='.$num.'&meta_key=phim_loai&meta_value='.$type.'&paged='.$q.'&order=desc');
while (have_posts()) : the_post(); 
$html.='<li><div class="inner"><a href="'.get_permalink().'" title="'.get_the_title().'"><img src="'.img3(146,195).'" alt="'.get_the_title().'"/></a><div class="info"><div class="name"><h3><a href="'.get_permalink().'" title="'.get_the_title().'">'.get_the_title().'</a></h3> </div><div class="name2"><h4>'.get_post_meta($post->ID, "phim_en", true).'</h4></div><div class="stats"><span class="liked">'.get_total_rating($post->ID,"like").' like</span></div><h5 style="text-indent:-9999px;">'.get_excerpt_content($excerpt_length,'',0,$post->post_content).'</h5></div><div class="status"><span class="status_r">'.get_post_meta($post->ID, "phim_tl", true).'</span></div></div></li>
';
endwhile;wp_reset_query();
return $html;
}
function get_meta_cat($meta_key,$meta_value,$num=16) {
global $post;
	// tao cache
	$type_phim6789	=	$meta_key;
	$file = DOCUMENT_TEMP."/cached/phim6789_".$type.".xxx"; // lấy tên file cache theo type để tránh trùng lập
	$expire = 86400; // 24h
	if (file_exists($file) &&
    filemtime($file) > (time() - $expire)) {
    include(DOCUMENT_TEMP."/cached/phim6789_".$type_phim6789.".xxx");
	} else { 	
$q = (get_query_var('paged')) ? get_query_var('paged') : 1;					
query_posts('post_type=post&showposts='.$num.'&meta_key='.$meta_key.'&meta_value='.$meta_value.'&paged='.$q.'&order=desc');
while (have_posts()) : the_post(); 
$html.='<li><div class="inner"><a href="'.get_permalink().'" title="'.get_the_title().'"><img src="'.img3(146,195).'" alt="'.get_the_title().'"/></a><div class="info"><div class="name"><a href="'.get_permalink().'" title="'.get_the_title().'">'.get_the_title().'</a> </div><div class="name2">'.get_post_meta($post->ID, "phim_en", true).'</div><div class="stats"></div></div><div class="status">'.get_post_meta($post->ID, "phim_hd", true).'</div></div></li>';
endwhile;wp_reset_query();
    $fp = fopen($file,"w");
    fputs($fp, $html);
    fclose($fp);
	include(DOCUMENT_TEMP."/cached/phim6789_".$type_phim6789.".xxx");
}
}
function get_ascii($st){
		$vietChar 	= 'á|à|ả|ã|ạ|ă|ắ|ằ|ẳ|ẵ|ặ|â|ấ|ầ|ẩ|ẫ|ậ|é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ|ó|ò|ỏ|õ|ọ|ơ|ớ|ờ|ở|ỡ|ợ|ô|ố|ồ|ổ|ỗ|ộ|ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự|í|ì|ỉ|ĩ|ị|ý|ỳ|ỷ|ỹ|ỵ|đ|Á|À|Ả|Ã|Ạ|Ă|Ắ|Ằ|Ẳ|Ẵ|Ặ|Â|Ấ|Ầ|Ẩ|Ẫ|Ậ|É|È|Ẻ|Ẽ|Ẹ|Ê|Ế|Ề|Ể|Ễ|Ệ|Ó|Ò|Ỏ|Õ|Ọ|Ơ|Ớ|Ờ|Ở|Ỡ|Ợ|Ô|Ố|Ồ|Ổ|Ỗ|Ộ|Ú|Ù|Ủ|Ũ|Ụ|Ư|Ứ|Ừ|Ử|Ữ|Ự|Í|Ì|Ỉ|Ĩ|Ị|Ý|Ỳ|Ỷ|Ỹ|Ỵ|Đ';
		$engChar	= 'a|a|a|a|a|a|a|a|a|a|a|a|a|a|a|a|a|e|e|e|e|e|e|e|e|e|e|e|o|o|o|o|o|o|o|o|o|o|o|o|o|o|o|o|o|u|u|u|u|u|u|u|u|u|u|u|i|i|i|i|i|y|y|y|y|y|d|A|A|A|A|A|A|A|A|A|A|A|A|A|A|A|A|A|E|E|E|E|E|E|E|E|E|E|E|O|O|O|O|O|O|O|O|O|O|O|O|O|O|O|O|O|U|U|U|U|U|U|U|U|U|U|U|I|I|I|I|I|Y|Y|Y|Y|Y|D';
		$arrVietChar 	= explode("|", $vietChar);
		$arrEngChar		= explode("|", $engChar);
		return str_replace($arrVietChar, $arrEngChar, $st);
	}
function get_total_rating($id,$type) {
		global $wpdb, $post;
							$q = "SELECT 
								".DATA_FILM_META.".film_id
								
								, ".DATA_FILM_META.".film_rating 
								, ".DATA_FILM_META.".film_like 
								, ".DATA_FILM_META.".film_rating_total 
							FROM ".DATA_FILM_META." 
							WHERE ".DATA_FILM_META.".film_id =".$id."
							";
$rating_total=$wpdb->get_row($q);
if($type=="rating"){
return $rating_total->film_rating_total;
}elseif($type=="like") {
return $rating_total->film_like;
}
							
}	
require_once('cache.php');
require_once('function_func.php');
require_once('function_custom_type.php');  

define( 'DISALLOW_FILE_EDIT', true );


?>