<?php

/*
Plugin Name: POSST PHIM
Plugin URI: http://alohot.net/
Description: Plugin Post phim by NoThingDNG_88
Author: NothingDN
Version: 1.5
Author URI: http://alohot.net/
*/
ob_start();


add_action('admin_menu', 'add_custom_box');
add_action('save_post', 'save_custom_box');
remove_filter('template_redirect', 'redirect_canonical'); 
remove_action('wp_head', 'rel_canonical');
function add_custom_box() {
    add_meta_box( 'vn_news_id', 'Link phim', 'show_custom_box', 'post', 'normal' );
    add_meta_box( 'vn_news_id', 'Link phim', 'show_custom_box', 'page', 'normal' );
	add_meta_box( 'vn_news_id', 'Link phim', 'show_custom_box', 'phim', 'normal' );
}
   
function show_custom_box() {
	global $post;
	echo '<input type="hidden" name="vnnews_noncename" id="vnnews_noncename" value="'.wp_create_nonce( plugin_basename(__FILE__) ).'" />'."\n";
	echo '<p><label for="vnn_thumbnail">Enter your url here:</label><br />';
	echo '<textarea id="film_episode" rows="10" style="width: 70%;" name="vnn_thumbnail"></textarea><br/><br/>';
echo GetServerToSelectBox();
	
	
}

function save_custom_box( $post_id ) {
	if ( !wp_verify_nonce( $_POST['vnnews_noncename'], plugin_basename(__FILE__) )) {
		return $post_id;
	}

	if ( 'page' == $_POST['post_type'] ) {
		if ( !current_user_can( 'edit_page', $post_id ))
			return $post_id;
	} else {
		if ( !current_user_can( 'edit_post', $post_id ))
			return $post_id;
	}
	
	global $wpdb,$post;



	$meta_value=$_POST['vnn_thumbnail'];
	$data=explode('|',$meta_value);	
	
	
	if ($meta_value!="") {
			$episode_post = $_POST['vnn_thumbnail'];
			$episode_film=$post->ID;			
			$list_episode = explode ( "\n", $episode_post );
			$count_ep = count ( $list_episode );
			
			for($i = 0; $i < $count_ep; $i ++) {
				$tap [$i] = explode ( '#', trim ( $list_episode [$i] ) );
				$ten_tap [$i] = trim ( $tap [$i] [0] );
				$link_tap [$i] = trim ( $tap [$i] [1] );
				$thu_tu [$i] = trim ( $tap [$i] [2] );
				if(FilmEpisodeNewEpisode($ten_tap [$i],$post->ID,$_POST['episode_server'],$link_tap [$i],$thu_tu [$i],time())) {
					$tb .= $ten_tap [$i] . ' - ';
				}
				else 
					echo '<div id="message" class="error fade" style="background-color: rgb(218, 79, 33);"><br/><b>L?i ! t?p '.$ten_tap [$i].' server '.$_POST['episode_server'].' d� t?n t?i</b><br/><br/></div>';
			}
			echo '<div id="message" class="updated fade" style="background-color: rgb(255, 251, 204);"><b><br/>C�c T?p '.$tb.' du?c th�m th�nh c�ng !</b><br/><br/></div>';
			
			$_acc="addEpisode";
		}



	wp_cache_delete($post_id, 'post_meta');
	
	return $meta_value;
}
function acp_type($url) {
	$t_url = strtolower($url);
	$ext = explode('.',$t_url);
	$ext = $ext[count($ext)-1];
	$ext = explode('?',$ext);
	$ext = $ext[0];
	$movie_arr = array(
		'wmv',
		'avi',
		'asf',
		'mpg',
		'mpe',
		'mpeg',
		'asx',
		'm1v',
		'mp2',
		'mpa',
		'ifo',
		'vob',
		'smi',
	);
	
	$extra_swf_arr = array(
		'www.metacafe.com',
		'www.livevideo.com',
	);
	
	for ($i=0;$i<count($extra_swf_arr);$i++){
		if (preg_match("#^http://".$extra_swf_arr[$i]."/(.*?)#s",$url)) {
			$type = 2;
			break;
		}
	}
	$is_youtube = (preg_match("#http://www.youtube.com/watch\?v=(.*?)#s",$url));
	$is_youtube1 = (preg_match("#http://www.youtube.com/watch%(.*?)#s",$url));
	$is_youtube2 = (preg_match("#http://www.youtube.com/watch/v/(.*?)#s",$url));
	$is_youtube3 = (preg_match("#http://www.youtube.com/v/(.*?)#s",$url));
	$is_youtube4 = (preg_match("#http://youtube.com/watch\?v=(.*?)#s",$url));
    $is_gdata = (preg_match("#http://gdata.youtube.com/feeds/api/playlists/(.*?)#s",$url));
	$is_daily = (preg_match("#dailymotion.com#",$url));
	$is_vntube = (preg_match("#http://www.vntube.com/mov/view_video.php\?viewkey=(.*?)#s",$url));
	$is_tamtay = (preg_match("#http://video.tamtay.vn/play/([^/]+)(.*?)#s",$url,$idvideo_tamtay));
	$is_chacha = (preg_match("#http://chacha.vn/song/(.*?)#s",$url));
	$is_clipvn = (preg_match("#phim.clip.vn/watch/([^/]+)/([^,]+),#",$url));
	$is_clipvn1 = (preg_match("#clip.vn/watch/(.*?)#s",$url));	
	$is_clipvn2 = (preg_match('#clip.vn/w/(.*?)#s',$url));
	$is_clipvn3 = (preg_match('#clip.vn/embed/(.*?)#s',$url));
	$is_googleVideo = (preg_match("#http://video.google.com/videoplay\?docid=(.*?)#s",$url));
	$is_myspace = (preg_match("#http://vids.myspace.com/index.cfm\?fuseaction=vids.individual&VideoID=(.*?)#s",$url));
	$is_timnhanh = (preg_match("#http://video.yume.vn/(.*?)#s",$url));
	$is_veoh = (preg_match("#http://www.veoh.com/videos/(.*?)#s",$url));
	$is_veoh1 = (preg_match("#http://www.veoh.com/browse/videos/category/([^/]+)/watch/(.*?)#s",$url));
	$is_baamboo = (preg_match("#http://video.baamboo.com/watch/([0-9]+)/video/([^/]+)/(.*?)#",$url,$idvideo_baamboo));
	$is_livevideo = (preg_match("#http://www.livevideo.com/video/([^/]+)/(.*?)#",$url,$idvideo_live));
	$is_sevenload = (preg_match("#sevenload.com/videos/([^/-]+)-([^/]+)#",$url,$id_sevenload));
	$is_picasa = (preg_match('#picasaweb.google.com/(.*?)#s', $url));
	$is_badongo = (preg_match("#badongo.com/vid/(.*?)#s",$url));
	$id_sevenload = (preg_match("#sevenload.com/videos/([^/-]+)-([^/]+)#",$url,$id_sevenload));
	$is_olala = (preg_match("#http://timvui.vn/player/(.*?)#s",$url));
    $is_zing = (preg_match("#video.zing.vn/([^/]+)#",$url));
	$is_zing1 = (preg_match("#video.zing.vn/video/clip/([^/]+)#",$url));
	$is_zing2 = (preg_match("#mp3.zing.vn/tv/media/([^/]+)#",$url));
    $is_mediafire = (preg_match("#http://www.mediafire.com/?(.*?)#s",$url));
    $is_cyworld = (preg_match("#cyworld.vn/([^/]+)#",$url));
    $is_goonline = (preg_match("#http://clips.goonline.vn/xem/(.*?)#s",$url));
    $is_movshare = (preg_match("#http://www.movshare.net/video/(.*?)#s",$url));
    $is_novamov = (preg_match("#http://www.novamov.com/video/(.*?)#s",$url));
	$is_zippyshare = (preg_match("#http://www([0-9]+).zippyshare.com/v/(.*?)/file.html#s",$url));
	$is_sendspace = (preg_match("#http://www.sendspace.com/file/(.*?)#s",$url,$idvideo_sendspace));
	$is_vidxden = (preg_match("#http://www.vidxden.com/(.*?)#s",$url));
	$is_hayghe = (preg_match("#http://www.hayghe.com/xem-phim/(.*?).html#s",$url));
	
	$is_BB = (preg_match("#http://www.videobb.com/video/(.*?)#s",$url));
	$is_Sshare = (preg_match("#http://www.speedyshare.com/files/(.*?)#s",$url));
	$is_4share1 = (preg_match("#4shared.com/file/(.*?)#s",$url));
	$is_4share2 = (preg_match("#4shared.com/video/(.*?)#s",$url));
	$is_4share3 = (preg_match("#4shared.com/embed/(.*?)#s",$url));
	$is_2share1 = (preg_match("#2shared.com/file/(.*?)#s",$url));
	$is_2share2 = (preg_match("#2shared.com/video/(.*?)#s",$url));
	$is_2share3 = (preg_match("#2sharedid=(.*?)#s",$url));
	$is_Wootly = (preg_match("#http://www.wootly.com/(.*?)#s",$url));
	$is_tusfiles = (preg_match("#http://www.tusfiles.net/(.*?)#s",$url));
	$is_sharevnn = (preg_match("#http://share.vnn.vn/dl.php/(.*?)#s",$url));
	$is_BBS = (preg_match("#http://videobb.com/video/(.*?)#s",$url));
	$is_ovfile = (preg_match("#http://ovfile.com/(.*?)#s",$url));
	$is_SSh = (preg_match("#http://phim.soha.vn/watch/3/video/(.*?)#s",$url));
	$is_em4share = (preg_match("#http://www.4shared.com/embed/(.*?)#s",$url));
	$is_viddler = (preg_match("#http://www.viddler.com/player/(.*?)#s",$url));
	$is_vivo = (preg_match("#http://vivo.vn/episode/(.*?)#s",$url));
	$is_SeeOn = (preg_match("#http://video.seeon.tv/video/(.*?)#s",$url));
	$is_vidus = (preg_match("#http://s([0-9]+).vidbux.com:([0-9]+)/d/(.*?)#s",$url));
	$is_Twitclips = (preg_match("#http://www.twitvid.com/(.*?)#s",$url));
	$is_videozer = (preg_match("#http://videozer.com/embed/(.*?)#s",$url));
	$is_eyvx = (preg_match("#http://eyvx.com/(.*?)#s",$url));
	$is_banbe = (preg_match("#banbe.net/(.*?)#s",$url));
	$is_nhaccuatui = (preg_match("#nhaccuatui.com(.*?)#s", $url));
	$is_v1vn = (preg_match("#v1vn.com/(.*?)#s", $url));
	
	if ($ext == 'flv' || $ext == 'mp4') $type = 1;
	elseif ($ext == 'swf' || $is_googleVideo || $is_baamboo) $type = 2;
	elseif ($is_zing || $is_zing1 || $is_zing2) $type = 3;
	elseif ($is_youtube || $is_youtube1 || $is_youtube2 || $is_youtube3 || $is_youtube4) $type = 4;
	elseif ($is_picasa) $type = 5;
    elseif ($is_movshare) $type = 6;
	elseif ($is_tamtay || $is_tamtay1 || $idvideo_tamtay || $idvideo_tamtay2) $type = 7;
	elseif ($is_4share1 || $is_4share2 || $is_4share3) $type = 8;
	elseif ($ext == 'divx' || $is_sendspace || $is_olala || $is_megavideo || $is_mediafire || $is_goonline || $is_sevenload || $is_vidxden || $is_novamov || $is_BB || $is_Sshare || $is_Wootly || $is_tusfiles || $is_sharevnn || $is_BBS || $is_ovfile || $is_SSh || $is_em4share || $is_viddler || $is_vivo || $is_SeeOn || $is_vidus || $is_Twitclips || $is_videozer || $is_eyvx || $is_myspace || $is_timnhanh || $is_chacha) $type = 9;
	elseif ($is_2share1 || $is_2share2 || $is_2share3) $type = 10;
	elseif ($is_clipvn || $is_clipvn1 || $is_clipvn2 || $is_clipvn3) $type = 11;
	elseif ($is_banbe) $type = 12;
	elseif ($is_veoh || $is_veoh1) $type = 13;
	elseif ($is_megafun) $type = 14;
	elseif ($is_nhaccuatui) $type = 15;
	elseif ($is_daily) $type = 16; 
	elseif ($is_zippyshare) $type = 17;
	elseif ($is_gdata) $type = 18;
	elseif ($is_cyworld) $type = 19;
	elseif ($is_badongo) $type = 20;
	elseif ($is_v1vn) $type=21;
	elseif (is_hayghe) $type=22;
	elseif (!$type) $type = 1;
    return $type;
}
function episode_show($film_id){
	global $wpdb,$permalink;
	$permalink = get_permalink( $film_id );
$list=$wpdb->get_results("SELECT episode_id, episode_name, episode_type, episode_url,episode_film,episode_server FROM wp_film_episode WHERE episode_film = '".$film_id."' ");
foreach ( $list as $value ) 
{
$episode_type=$value->episode_type;
$name=$value->episode_name;
$tap=en_id($value->episode_id);
$phim=$value->episode_film;
$broken=$value->episode_server;
switch($episode_type){
	      case '1':
		        $sv1 .= "<li><a title=\"tập ".$value->episode_id."\" data-episode-id=\"$value->episode_id\" data-type=\"watch\" class=\"\" href=\"".$permalink."M".$tap."\">".$name."</a></li> ";
		  break;
	      case '2':
		        $sv2 .= "<li><a title=\"tập ".$value->episode_id."\" data-episode-id=\"$value->episode_id\" data-type=\"watch\" class=\"\" href=\"".$permalink."M".$tap."\">".$name."</a></li> ";
		  break;
	      case '3':
		        $sv3 .= "<li><a title=\"tập ".$value->episode_id."\" data-episode-id=\"$value->episode_id\" data-type=\"watch\" class=\"\" href=\"".$permalink."M".$tap."\">".$name."</a></li> ";
		  break;
		  case '4':
		        $sv4 .= "<li><a title=\"tập ".$value->episode_id."\" data-episode-id=\"$value->episode_id\" data-type=\"watch\" class=\"\" href=\"".$permalink."M".$tap."\">".$name."</a></li> ";
		  break;
	      case '5':
		        $sv5 .= "<li><a title=\"tập ".$value->episode_id."\" data-episode-id=\"$value->episode_id\" data-type=\"watch\" class=\"\" href=\"".$permalink."M".$tap."\">".$name."</a></li> ";
		  break;
		  case '6':
		        $sv6 .= "<li><a title=\"tập ".$value->episode_id."\" data-episode-id=\"$value->episode_id\" data-type=\"watch\" class=\"\" href=\"".$permalink."M".$tap."\">".$name."</a></li> ";
		  break;
	      case '7':
		        $sv7 .= "<li><a title=\"tập ".$value->episode_id."\" data-episode-id=\"$value->episode_id\" data-type=\"watch\" class=\"\" href=\"".$permalink."M".$tap."\">".$name."</a></li> ";
		  break;
		  case '8':
		        $sv8 .= "<li><a title=\"tập ".$value->episode_id."\" data-episode-id=\"$value->episode_id\" data-type=\"watch\" class=\"\" href=\"".$permalink."M".$tap."\">".$name."</a></li> ";
		  break;
	      case '9':
		        $sv9 .= "<li><a title=\"tập ".$value->episode_id."\" data-episode-id=\"$value->episode_id\" data-type=\"watch\" class=\"\" href=\"".$permalink."M".$tap."\">".$name."</a></li> ";
		  break;
		  case '10':
		        $sv10 .= "<li><a title=\"tập ".$value->episode_id."\" data-episode-id=\"$value->episode_id\" data-type=\"watch\" class=\"\" href=\"".$permalink."M".$tap."\">".$name."</a></li> ";
		  break;
		  case '11':
		        $sv11 .= "<li><a title=\"tập ".$value->episode_id."\" data-episode-id=\"$value->episode_id\" data-type=\"watch\" class=\"\" href=\"".$permalink."M".$tap."\">".$name."</a></li> ";
		  break;
	      case '12':
		        $sv12 .= "<li><a title=\"tập ".$value->episode_id."\" data-episode-id=\"$value->episode_id\" data-type=\"watch\" class=\"\" href=\"".$permalink."M".$tap."\">".$name."</a></li> ";
		  break;
		  case '13':
		        $sv13 .= "<li><a title=\"tập ".$value->episode_id."\" data-episode-id=\"$value->episode_id\" data-type=\"watch\" class=\"\" href=\"".$permalink."M".$tap."\">".$name."</a></li> ";
		  break;	
	      case '14':
		        $sv14 .= "<li><a title=\"tập ".$value->episode_id."\" data-episode-id=\"$value->episode_id\" data-type=\"watch\" class=\"\" href=\"".$permalink."M".$tap."\">".$name."</a></li> ";
		  break;
		  case '15':
		        $sv15 .= "<li><a title=\"tập ".$value->episode_id."\" data-episode-id=\"$value->episode_id\" data-type=\"watch\" class=\"\" href=\"".$permalink."M".$tap."\">".$name."</a></li> ";
		  break;	
		  case '16':
		        $sv16 .= "<li><a title=\"tập ".$value->episode_id."\" data-episode-id=\"$value->episode_id\" data-type=\"watch\" class=\"\" href=\"".$permalink."M".$tap."\">".$name."</a></li> ";
		  break;	
	      case '17':
		        $sv17 .= "<li><a title=\"tập ".$value->episode_id."\" data-episode-id=\"$value->episode_id\" data-type=\"watch\" class=\"\" href=\"".$permalink."M".$tap."\">".$name."</a></li> ";
		  break;
		  case '18':
		        $sv18 .= "<li><a title=\"tập ".$value->episode_id."\" data-episode-id=\"$value->episode_id\" data-type=\"watch\" class=\"\" href=\"".$permalink."M".$tap."\">".$name."</a></li> ";
		  break;
		  case '19':
		        $sv19 .= "<li><a title=\"tập ".$value->episode_id."\" data-episode-id=\"$value->episode_id\" data-type=\"watch\" class=\"\" href=\"".$permalink."M".$tap."\">".$name."</a></li> ";
		  break;
		  case '20':
		        $sv20 .= "<li><a title=\"tập ".$value->episode_id."\" data-episode-id=\"$value->episode_id\" data-type=\"watch\" class=\"\" href=\"".$permalink."M".$tap."\">".$name."</a></li> ";
		  break;
		  case '21':
		        if($broken==1 || $broken==0){
				$sv211 .= "<li><a title=\"tập ".$value->episode_id."\" data-episode-id=\"$value->episode_id\" data-type=\"watch\" class=\"\" href=\"".$permalink."M".$tap."\">".$name."</a></li> ";
				} elseif($broken==2){
				$sv212 .= "<li><a title=\"tập ".$value->episode_id."\" data-episode-id=\"$value->episode_id\" data-type=\"watch\" class=\"\" href=\"".$permalink."M".$tap."\">".$name."</a></li> ";
				}		  break;
		  case '22':
		        if($broken==1 || $broken==0){
				$sv221 .= "<li><a title=\"tập ".$value->episode_id."\" data-episode-id=\"$value->episode_id\" data-type=\"watch\" class=\"\" href=\"".$permalink."M".$tap."\">".$name."</a></li> ";
				} elseif($broken==2){
				$sv222 .= "<li><a title=\"tập ".$value->episode_id."\" data-episode-id=\"$value->episode_id\" data-type=\"watch\" class=\"\" href=\"".$permalink."M".$tap."\">".$name."</a></li> ";
				}
		  break;
	  }

}
$total_server .= '<div id="servers" class="serverlist">';
if($sv1) $total_server .= '<div class="server"><div class="label"><img src="/flags/vn.png">Video:</div> <ul class="episodelist">'.$sv1.'</ul></div>';
if($sv2) $total_server .= '<div class="server"><div class="label"><img src="/flags/vn.png">Flash:</div> <ul class="episodelist">'.$sv2.'</ul></div>';
if($sv3) $total_server .= '<div class="server"><div class="label"><img src="/flags/vn.png">Zing:</div> <ul class="episodelist">'.$sv3.'</ul></div>';
if($sv4) $total_server .= '<div class="server"><div class="label"><img src="/flags/vn.png">V.I.P</div> <ul class="episodelist">'.$sv4.'</ul></div>';
if($sv5) $total_server .= '<div class="server"><div class="label"><img src="/flags/vn.png">Picasa:</div> <ul class="episodelist">'.$sv5.'</ul></div>';
if($sv6) $total_server .= '<div class="server"><div class="label"><img src="/flags/vn.png">Movshare:</div> <ul class="episodelist">'.$sv6.'</ul></div>';
if($sv7) $total_server .= '<div class="server"><div class="label"><img src="/flags/vn.png">T?m Tay:</div> <ul class="episodelist">'.$sv7.'</ul></div>';
if($sv8) $total_server .= '<div class="server"><div class="label"><img src="/flags/vn.png">4Share:</div> <ul class="episodelist">'.$sv8.'</ul></div>';
if($sv9) $total_server .= '<div class="server"><div class="label"><img src="/flags/vn.png">Unknow:</div> <ul class="episodelist">'.$sv9.'</ul></div>';
if($sv10) $total_server .= '<div class="server"><div class="label"><img src="/flags/vn.png">2Share:</div> <ul class="episodelist">'.$sv10.'</ul></div>';
if($sv11) $total_server .= '<div class="server"><div class="label"><img src="/flags/vn.png">ClipVN:</div> <ul class="episodelist">'.$sv11.'</ul></div>';
if($sv12) $total_server .= '<div class="server"><div class="label"><img src="/flags/vn.png">Ban Be:</div> <ul class="episodelist">'.$sv12.'</ul></div>';
if($sv13) $total_server .= '<div class="server"><div class="label"><img src="/flags/vn.png">Veoh:</div> <ul class="episodelist">'.$sv13.'</ul></div>';
if($sv14) $total_server .= '<div class="server"><div class="label"><img src="/flags/vn.png">MegaFun:</div> <ul class="episodelist">'.$sv14.'</ul></div>';
if($sv15) $total_server .= '<div class="server"><div class="label"><img src="/flags/vn.png">NhacCuaTui:</div> <ul class="episodelist">'.$sv15.'</ul></div>';
if($sv16) $total_server .= '<div class="server"><div class="label"><img src="/flags/vn.png">Dailymotion:</div> <ul class="episodelist">'.$sv16.'</ul></div>';
if($sv17) $total_server .= '<div class="server"><div class="label"><img src="/flags/vn.png">Zippy Share:</div> <ul class="episodelist">'.$sv17.'</ul></div>';
if($sv18) $total_server .= '<div class="server"><div class="label"><img src="/flags/vn.png">Playlists YouTube:</div> <ul class="episodelist">'.$sv18.'</ul></div>';
if($sv19) $total_server .= '<div class="server"><div class="label"><img src="/flags/vn.png">Cyworld:</div> <ul class="episodelist">'.$sv19.'</ul></div>';
if($sv20) $total_server .= '<div class="server"><div class="label"><img src="/flags/vn.png">Badongo:</div> <ul class="episodelist">'.$sv20.'</ul></div>';
if($sv211) $total_server .= '<div class="server"><div class="label"><img src="/flags/vn.png">V1Vn1:</div> <ul class="episodelist">'.$sv211.'</ul></div>';
if($sv212) $total_server .= '<div class="server"><div class="label"><img src="/flags/vn.png">V1Vn2:</div> <ul class="episodelist">'.$sv212.'</ul></div>';
if($sv221) $total_server .= '<div class="server"><div class="label"><img src="/flags/vn.png">Server hg1:</div> <ul class="episodelist">'.$sv221.'</ul></div>';
if($sv222) $total_server .= '<div class="server"><div class="label"><img src="/flags/vn.png">Server hg2:</div> <ul class="episodelist">'.$sv222.'</ul></div>';
$total_server .= "</div>";

	return $total_server;
}
function replace($str) {
	$str = str_replace('%20', '-', $str);
	$str = str_replace(':', '-', $str);
	$str = str_replace('--', '', $str);
	$str = str_replace('(', '', $str);
	$str = str_replace(')', '', $str);
	$str = str_replace('[', '', $str);
	$str = str_replace(']', '', $str);
	$str = str_replace('-', '', $str);
	$str = str_replace('|', '', $str);
	$str = str_replace(',', '', $str);
	$str = str_replace('-/', '/', $str);
	$str = str_replace(' ', '-', $str);
	return $str."";
}
function episode_showadmin($film_id){
	global $wpdb;
$list=$wpdb->get_results("SELECT episode_id, episode_name, episode_type, episode_url FROM wp_film_episode WHERE episode_film = '".$film_id."' ");
echo '<form action="" method="post">';
foreach ( $list as $value ) 
{
echo "<input type=\"text\" name=\"tap".$value->episode_id."\" value=\"".$value->episode_name."\"><input name=\"".$value->episode_id."\" type=\"text\" value=\"".$value->episode_url."\" size=\"80%\" id=\"tap".$value->episode_id."\" /></br>";
echo '</form>';
}

}

function xemphim($film_id){
	global $wpdb;
$list=$wpdb->get_results("SELECT episode_id, episode_name, episode_type, episode_url FROM wp_film_episode WHERE episode_film = '".$film_id."' limit 1 ");
foreach ( $list as $value ) 
{
$name=$value->episode_name;
$tap=en_id($value->episode_id);
$permalink = get_permalink( $id );

return $permalink."M".$tap;
}

}
function getlinkphim($idphim,$idtap){
global $wpdb,$idtap;
$idtap=del_id($idtap);

$fivesdrafts = $wpdb->get_row("SELECT episode_id, episode_name, episode_type, episode_url
	FROM wp_film_episode
	WHERE episode_film = '".$idphim."' AND episode_id='".$idtap."'");
if(!$fivesdrafts->episode_url){echo "Tap phim n�y ko c� that";}
else $player=players($fivesdrafts->episode_url);
return $player;

}
function rewrite_permalink_post( $wp_rewrite ) {

		
    $wp_rewrite->rules = array(

        '([^/]+)/M([^/]+)?$' => 'index.php?name=$matches[1]&ep=$matches[2]',

    ) + $wp_rewrite->rules;
}
add_action( 'generate_rewrite_rules', 'rewrite_permalink_post' );
function add_custom_page_variables( $public_query_vars ) {
		$public_query_vars[] = 'ep';
		return $public_query_vars;
	}
function get_name($idtap){
global $wpdb,$idphim;
$idtap=del_id($idtap);

$fivesdrafts = $wpdb->get_row("SELECT episode_id, episode_name, episode_type, episode_url
	FROM wp_film_episode
	WHERE episode_id='".$idtap."'");
$sv=$fivesdrafts->episode_type;
if($sv==1) $sv = 'Video';
elseif ($sv==2) $sv = 'Flash';
elseif ($sv==3) $sv = 'Zing';
elseif ($sv==4) $sv = 'Youtube';
elseif ($sv==5) $sv = 'Picasa';
elseif ($sv==6) $sv = 'Movshare';
elseif ($sv==7) $sv = 'Tam tay';
elseif ($sv==8) $sv = '4Share';
elseif ($sv==9) $sv = 'Unknow';
elseif ($sv==10) $sv = '2Share';
elseif ($sv==11) $sv = 'ClipVN';
elseif ($sv==12) $sv = 'Ban be';
elseif ($sv==13) $sv = 'Veoh';
elseif ($sv==14) $sv = 'Megafun';
elseif ($sv==15) $sv = 'Nhac cua tui';
elseif ($sv==16) $sv = 'Dailymotion';
elseif ($sv==17) $sv = 'Zippy share';
elseif ($sv==18) $sv = 'Playlist Youtube';
elseif ($sv==19) $sv = 'Cyworld';
elseif ($sv==20) $sv = 'Badongo';
elseif ($sv==21) $sv = 'v1vn';
elseif ($sv==22) $sv = 'Server2';
$tap=$fivesdrafts->episode_name." | server " .$sv;
return $tap;

}
function get_tit($idtap){
global $wpdb,$idphim;
$idtap=del_id($idtap);
$fivesdrafts = $wpdb->get_row("SELECT episode_id, episode_name, episode_type, episode_url
	FROM wp_film_episode
	WHERE episode_id='".$idtap."'");
$tap=$fivesdrafts->episode_name;
return $tap;
}

		
add_filter( 'query_vars', 'add_custom_page_variables' );
include('player.php');
?>
