<?php	

function GetFilmToSelectBox ($filmId = '') {
	global $wpdb, $post;
	$q = "SELECT ID, post_title  
    FROM $wpdb->posts wposts 
    WHERE wposts.post_type = 'post' 
    AND wposts.post_status = 'publish' 
    ORDER BY ID DESC";
	$row = $wpdb->get_results($q);
	
	if ($row):
	$html .= "<select name='film_episode'><option value='0'>Vui lòng chọn phim</option>";
 	foreach ($row as $post):	
 		if ($post->ID == $filmId) $selected = "selected='selected'";
 		else $selected = '';
		$html .= "<option value='".$post->ID."' ".$selected.">".$post->post_title."</option>";
	endforeach;
	$html .= "</select>";
	else :
		$html .= "Không có phim nào - Vui lòng thêm phim mới trước khi thêm tập phim";
	endif;
	return $html;
}

function GetServerToSelectBox ($serverId = '') {
	global $wpdb, $post;
	$table_name= $wpdb->prefix . 'film_server';
	$q = "SELECT server_id, server_name  
    FROM $table_name  
    ORDER BY server_order ASC";
	$row = $wpdb->get_results($q);
	
	if ($row):
	$html .= "<select name='episode_server'>";
	$html .= "<option>Chọn sv</option>";
 	foreach ($row as $post):	
 		if ($post->server_id == $serverId) $selected = "selected='selected'";
 		else $selected = '';
		$html .=  "<option value='".$post->server_id."' ".$selected.">".$post->server_name."</option>";
	endforeach;
	$html .= "</select>";
	else :
		$html .=  "Không có server nào - Vui lòng thêm server mới trước khi thêm tập phim";
	endif;
	return $html;
}

function admin_pages($tt,$n,$link,$p){
	$pgt = $p-1;
	$html .= "<div class='tablenav'><div class='tablenav-pages'>Trang: ";
	if ($p<>1) $html.="<a class='prev page-numbers' href=$link title ='Xem trang đầu'><b>&laquo;&laquo;</b></a> <a class='prev page-numbers' href=$link&p=$pgt title='Xem trang trước'><b>&laquo;</b></a> ";
	for($l = 0; $l < $tt/$n; $l++) {
		$m = $l+1;
		if($m == $p) $html .= "<span class='page-numbers current'>$m</span> ";
		else $html .= "<a class='page-numbers' href=$link&p=$m title='Xem trang $m'>$m</a> ";
	}
	$pgs = $p+1;
	if ($p<>$m) $html.="<a class='next page-numbers' href=$link&p=$pgs title='Xem trang kế tiếp'><b>&raquo;</b></a> <a class='next page-numbers' href=$link&p=$m title='Xem trang cuối'><b>&raquo;&raquo;</b></a> ";
	$html .="</div></div>";
	return $html;
}
function un_htmlchars($str) {
	return str_replace ( array ('&lt;', '&gt;', '&quot;', '&amp;', '&#92;', '&#39;' ), array ('<', '>', '"', '&', chr ( 92 ), chr ( 39 ) ), $str );
}

function htmlchars($str) {
	return str_replace ( array ('\"','&', '<', '>', '"', chr ( 39 ) ), array ('"','&amp;', '&lt;', '&gt;', '&quot;', '&#39;' ), $str );
}
function mt_post($name) {
	if (isset ( $_POST ["$name"] )) {
		return addslashes($_POST ["$name"]);
	}
	return null;
}
function mt_get($name) {
	if (isset ( $_GET ["$name"] )) {
		return addslashes($_GET ["$name"]);
	}
	return null;
}
function show_success($str) {
	echo "<span class='success_box'>" . $str . "</span>";
}
function show_error($str) {
	echo "<span class='error_box'>" . $str . "</span>";
}
function getwords($str,$num)
{
	$limit = $num - 1 ;
    $str_tmp = '';
    //explode -- Split a string by string
    $arrstr = explode(" ", $str);
    if ( count($arrstr) <= $num ) { return $str; }
    if (!empty($arrstr))
    {
        for ( $j=0; $j< count($arrstr) ; $j++)    
        {
            $str_tmp .= " " . $arrstr[$j];
            if ($j == $limit) 
            {
                break;
            }
        }
    }
    return $str_tmp.'...';
}

function html2txt($document){
$search = array('@<script[^>]*?>.*?</script>@si',  // Strip out javascript
               '@<[\/\!]*?[^<>]*?>@si',            // Strip out HTML tags
               '@<style[^>]*?>.*?</style>@siU',    // Strip style tags properly
               '@<![\s\S]*?--[ \t\n\r]*>@'         // Strip multi-line comments including CDATA
);
$text = preg_replace($search, ' ', $document);
return $text;
}
function text(&$string) {	
    $string = trim($string);
	$string = str_replace("\\'","'",$string);
	$string = str_replace("'","''",$string);
	$string = str_replace('\"',"&quot;",$string);
	$string = str_replace("<", "&lt;", $string);
	$string = str_replace(">", "&gt;", $string);
	return $string;
}
function low_to_hight($string){
  $convert_from = array(
    "a", "c", "d", "g", "h", "j", "k", "l", "n", "p", "q", "r", "s", "t",
    "v", "x", "y");
  $convert_to = array(
    "A", "C", "D", "G", "H", "J", "K", "L", "N", "P", "Q", "R", "S", "T",
    "V", "X", "Y");
  return str_replace($convert_from, $convert_to, $string);
} 
function them_gach($str) {
	$c = str_split($str);
	for($i=0;$i<count($c);$i++) {
		$d .= $c[$i]."|";
	}
	return $d;
}
function get_config_alophim() {
	$config = file_get_contents (  DOCUMENT_TEMP.'/config.alophim' );
	$arr = explode ( "\n", $config );
	if(md5($arr [0]) != '787863bc12093d695e2376eab997718c') {
		echo 'Code by NothingDNG_88 - Mail:jody.design88@gmail.com';
		exit();
	}
	$month = explode ( ':', $arr [1] );
	$week = explode ( ':', $arr [2] );
	$day = explode ( ':', $arr [3] );
	return array ('current_month' => $month [1], 'current_week' => $week [1], 'current_day' => $day [1] );
}
function Update_date_time() {
	global $wpdb;
	$arr = "Code by NothingDNG_88 - Mail:jody.design88@gmail.com\ncurrent_month:" . date ( 'M', NOW ) . "\ncurrent_week:" . date ( 'W', NOW ) . "\ncurrent_day:" . date ( 'd', NOW );
	$config = file_get_contents ( DOCUMENT_TEMP.'/config.alophim' );
	if ($config == "") {
		$file = fopen ( DOCUMENT_TEMP.'/config.alophim', "w" );
		fwrite ( $file, $arr );
	}
	
	$get_config = get_config_alophim ();
	
if (date ( 'M', NOW ) != $get_config ['current_month']) {
		$file = fopen ( DOCUMENT_TEMP.'/config.alophim', "w" );
		fwrite ( $file, $arr );
		$update = $wpdb->query("UPDATE ".DATA_FILM_META." SET film_viewed_m = '0'");
	}
if (date ( 'W', NOW ) != $get_config ['current_week']) {
		$file = fopen ( DOCUMENT_TEMP.'/config.alophim', "w" );
		fwrite ( $file, $arr );
		$update = $wpdb->query("UPDATE ".DATA_FILM_META." SET film_viewed_w = '0'");
	}
if (date ( 'd', NOW ) != $get_config ['current_day']) {
		$file = fopen ( DOCUMENT_TEMP.'/config.alophim', "w" );
		fwrite ( $file, $arr );
		$update = $wpdb->query("UPDATE ".DATA_FILM_META." SET film_viewed_d = '0'");
	}

}
add_action('wp_head', 'Update_date_time');
?>
