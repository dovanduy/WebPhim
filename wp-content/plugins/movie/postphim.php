<?php
/**
 * @package Hello_Dolly
 * @version 1.6
 */
/*
Plugin Name: POSST PHIM
Plugin URI: http://wordpress.org/extend/plugins/hello-dolly/
Description: This is not just a plugin, it symbolizes the hope and enthusiasm of an entire generation summed up in two words sung most famously by Louis Armstrong: Hello, Dolly. When activated you will randomly see a lyric from <cite>Hello, Dolly</cite> in the upper right of your admin screen on every page.
Author: Matt Mullenweg
Version: 1.6
Author URI: http://tap-$name.a.tt/
*/
ob_start();


add_action('admin_menu', 'add_custom_box');
add_action('save_post', 'save_custom_box');
remove_filter('template_redirect', 'redirect_canonical'); 
remove_action('wp_head', 'rel_canonical');
function add_custom_box() {
    add_meta_box( 'vn_news_id', 'Nhập link phim', 'show_custom_box', 'post', 'normal' );
    add_meta_box( 'vn_news_id', 'Link phim', 'show_custom_box', 'page', 'normal' );
	add_meta_box( 'vn_news_id', 'Link phim', 'show_custom_box', 'phim', 'normal' );
}
   
function show_custom_box() {
	global $post;
	echo '<input type="hidden" name="vnnews_noncename" id="vnnews_noncename" value="'.wp_create_nonce( plugin_basename(__FILE__) ).'" />'."\n";
	echo '<p style="font-size: 0.9em; color: #000; margin: 0;">(Mỗi tên tập, link, thứ tự ưu tiên viết cách nhau bởi dấu thăng (#), mỗi link ở một dòng.)<br>
							Ví dụ: <br>
							Tập 1#link1#1<br>
							Tập 2#link2#2</p>';
	echo '<div>
	Nhập tập bắt đầu:
	<input name="start" value="" id="ep_start"/><br/>
	Nhập tập kết thúc
	<input name="end" value="" id="ep_end"/><br/></div>
	<a class="nhap_ep" style="border: 1px solid rgb(204, 204, 204); border-radius: 5px 5px 5px 5px; padding: 5px; position: absolute; left: 315px; top: 85px;">Nhập</a>
	<br style="clear:both"/>
	<textarea name="vnn_thumbnail" style="width:500px; height:150px;" id="multi_ep"></textarea>
	<script type="text/javascript">
	var $j = jQuery.noConflict();
	$j(function(){
	
	    $j(".nhap_ep").click(function(){
			var i,cont;
			var ep_start = parseInt($j("#ep_start").attr("value"));
			var ep_end = parseInt($j("#ep_end").attr("value")) + 1;
			for (i=ep_start;i<ep_end;i++) {
				var areaValue = $j("#multi_ep").val();
				if(i==(ep_end -1)) {
					$j("#multi_ep").val(areaValue+i+"##"+i);
				} else {
					$j("#multi_ep").val(areaValue+i+"##"+i+"\n");
				}
				
			}
				
		});
	
	});
</script>';	

	

	
	
}

function save_custom_box( $post_id ) {
	if ( !wp_verify_nonce( $_POST['vnnews_noncename'], plugin_basename(__FILE__) )) {
		return $post_id;
	}

		if ( !current_user_can( 'edit_post', $post_id )){
			return $post_id;
			         }
	
	global $wpdb,$post;


if(check_fiml_meta($post->ID)==false) {add_film_meta($post->ID);}
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
			
			

		}

	
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
		//'www.dailymotion.com',
		//'www.metacafe.com',
		'www.livevideo.com',
	);
	
	for ($i=0;$i<count($extra_swf_arr);$i++){
		if (preg_match("#^http://".$extra_swf_arr[$i]."/(.*?)#s",$url)) {
			$type = 2;
			break;
		}
	}
	$is_youtube = (preg_match("#http://www.youtube.com/watch\?v=(.*?)#s",$url));
$is_youtube2 = (preg_match("#youtube.com/p/(.*?)#",$url));
    $is_gdata = (preg_match("#http://gdata.youtube.com/feeds/api/playlists/(.*?)#s",$url));
	$is_daily = (preg_match("#dailymotion.com#",$url));
	$is_xvideos = (preg_match("#flashservice.xvideos.com/embedframe/#",$url));
	$is_vntube = (preg_match("#http://www.vntube.com/mov/view_video.php\?viewkey=(.*?)#s",$url));
	$is_tamtay = (preg_match("#http://video.tamtay.vn/play/([^/]+)(.*?)#s",$url,$idvideo_tamtay));
	$is_vidbull = (preg_match("#http://vidbull.com/(.*?)#s",$url));
	$is_clipvn = (preg_match("#clip.vn/embed/([^/]+)/([^,]+),#",$url));
	$is_clipvn1 = (preg_match("#clip.vn/embed/(.*?)#s",$url));	
	$is_googleVideo = (preg_match("#http://video.google.com/videoplay\?docid=(.*?)#s",$url));
	$is_myspace = (preg_match("#http://vids.myspace.com/index.cfm\?fuseaction=vids.individual&VideoID=(.*?)#s",$url));
	$is_vidbull = (preg_match("#http://vidbull.com/([^/_]+)_([^/.]+).html#s",$url));
	$is_timnhanh = (preg_match("#http://video.yume.vn/(.*?)#s",$url));
	$is_zing = (preg_match("#http://video.zing.vn/video/clip/([^/.]+).([^/.]+).html#s",$url,$idvideo_zing));
        $is_zing1 = (preg_match("#http://mp3.zing.vn/tv/media/([^/.]+).([^/.]+).html#s",$url,$idvideo_zing));
        $is_zing2 = (preg_match("#http://tv.zing.vn/video/([^/.]+)/([^/.]+).html#s",$url,$idvideo_zing));
        $is_zing3 = (preg_match("#http://tv.zing.vn/embed/video/#",$url));
	$is_veoh = (preg_match("#http://www.veoh.com/watch/(.*?)#s",$url));
	
	$is_veoh1 = (preg_match("#http://www.veoh.com/browse/videos/category/([^/]+)/watch/(.*?)#s",$url));
	$is_vimeo = (preg_match("#http://www.vimeo.com/(.*?)#s",$url));
        $is_vimeo1 = (preg_match("#http://vimeo.com/(.*?)#s",$url));
	$is_baamboo = (preg_match("#http://video.baamboo.com/watch/([0-9]+)/video/([^/]+)/(.*?)#",$url,$idvideo_baamboo));
	$is_livevideo = (preg_match("#http://www.livevideo.com/video/([^/]+)/(.*?)#",$url,$idvideo_live));
	$is_sevenload = (preg_match("#sevenload.com/videos/([^/-]+)-([^/]+)#",$url,$id_sevenload));
	$is_vtv = preg_match("/^mms:\/\/+[a-zA-Z0-9\.]+(.*?)(VTV|VTC|HTV|dn1|dn2)+(.*?)/i",$url);
	$is_badongo = (preg_match("#badongo.com/vid/(.*?)#s",$url));
	$is_sendspace = (preg_match("#sendspace.com/file/(.*?)#s",$url));
	$is_olala = (preg_match("#http://timvui.vn/player/(.*?)#s",$url));
	$is_2shared = (preg_match("#http://www.2shared.com/video/(.*?)#s",$url));
	$is_4shared = (preg_match("#http://www.4shared.com/video/(.*?)#s",$url));
    $is_mediafire = (preg_match("#http://www.mediafire.com/?(.*?)#s",$url));
    $is_cyworld = (preg_match("#http://www.cyworld.vn/v2/myhome/video/detail/homeid/([^/.]+)/post/(.*?)#s",$url));
    $is_cyworld2 = (preg_match("#http://kine.cyworld.vn/detail/(.*?)#s",$url));
    $is_goonline = (preg_match("#http://clips.goonline.vn(.*?)#s",$url));
    $is_movshare = (preg_match("#http://www.movshare.net/video/(.*?)#s",$url));
    $is_novamov = (preg_match("#http://www.novamov.com/video/(.*?)#s",$url));
    $is_nct = (preg_match("#nhaccuatui.com(.*)#s",$url));
	$is_viddler = (preg_match("#viddler.com/explore(.*?)#s", $url));
	$is_hulkshare = (preg_match("#http://hulkshare.com/(.*?)#s", $url));
	$is_vidxden = (preg_match("#http://www.vidxden.com/(.*?)#s", $url));
	$is_images = (preg_match("#http://img(.*?).imageshack.us/img(.*?)/(.*?)/(.*?).mp4#s", $url));
    $is_zippy = (preg_match("#www([0-9]+).zippyshare.com/v/(.*?)/file.html#s",$url));
    $is_dankfile = (preg_match("#http://www.dankfile.com/(.*?)#s", $url));
    $is_tusfiles = (preg_match("#http://tusfiles.net/(.*?)#s", $url));
    $is_speedyshare = (preg_match("#http://www.speedyshare.com/files/(.*?)/(.*?)#s", $url));
    $is_ovfile = (preg_match("#http://ovfile.com/(.*?)#s", $url));    
    $is_videobb = (preg_match("#http://videobb.com/video/(.*?)#s", $url));
	$is_wootly = (preg_match("#http://www.wootly.com/(.*?)#s", $url));
    $is_vipbee = (preg_match("#http://megafun.vn/channel/(.*?)#s",$url));
	 $is_sharevn = (preg_match("#http://share.vnn.vn/dl.php/(.*?)#s",$url));
	 $is_xixam = (preg_match("#http://phim.xixam.com/(.*?).html#s",$url));
	$is_supershare = (preg_match("#http://www.supershare.net/en/file/(.*?).html#s",$url));
	 $is_soha = (preg_match("#http://phim.soha.vn/watch/([0-9]+)/video/([^/]+)/(.*?)#",$url,$idvideo_soha));
	$is_vidbux = (preg_match("#http://www.vidbux.com/(.*?)#s", $url));
	$is_videoweed = (preg_match("#http://www.videoweed.com/file/(.*?)#s", $url));
	$is_loombo = (preg_match("#http://loombo.com/(.*?)#s", $url));
$is_videozer = (preg_match("#http://videozer.com/(.*?)#s", $url));
$is_v1vn1 = (preg_match("#http://v1vn.com/player/(.*?)#s", $url));
$is_v1vn2 = (preg_match("#http://v1vn.com/player/(.*?)#s", $url));
$is_v1vn3 = (preg_match("#http://v1vn.com/player/(.*?)#s", $url));
$is_v1vn4 = (preg_match("#http://v1vn.com/player/(.*?)#s", $url));
$is_xixam1 = (preg_match("#http://phim.xixam.com/(.*?)#s", $url));
$is_xixam2 = (preg_match("#http://phim.xixam.com/(.*?)#s", $url));
$is_seeon = (preg_match("#video.seeon.tv/video/(.*?)#s", $url));
$is_twitvid = (preg_match("#www.twitvid.com/(.*?)#s", $url));
$is_rolo = (preg_match("#http://film.rolo.vn/films/xem/(.*?)#s", $url));
$is_eyvx = (preg_match("#http://eyvx.com/(.*?)#s", $url));
$is_vzaar = (preg_match("#http://vzaar.com/videos/(.*?)#s", $url));
$is_playwire = (preg_match("#http://www.playwire.com/videos/(.*?)#s", $url));
$is_bcove = (preg_match("#http://bcove.me/(.*?)#s", $url));
$is_sorensonmedia = (preg_match("#http://360.sorensonmedia.com/page/detail/(.*?)#s", $url));
$is_ooyala = (preg_match("#http://player.ooyala.com/player.swf\?embedCode=(.*?)#s", $url));
$is_go = (preg_match("#http://content.go.vn/maytinh/chi-tiet/(.*?).htm#s", $url));
$is_blip = (preg_match("#http://blip.tv/(.*?)/(.*?)#s", $url));
$is_putlocker = (preg_match("#http://www.putlocker.com/file/(.*?)#s", $url));
$is_sockshare = (preg_match("#http://www.sockshare.com/file/(.*?)#s", $url));
$is_zalaa = (preg_match("#http://www.zalaa.com/(.*?)/(.*?).htm#s", $url));
$is_phimsomot = (preg_match("#http://data.phimsomot.com/(.*?).xml#s", $url));
$is_vip= (preg_match("#picasaweb.google.com/(.*?)/(.*?)/(.*?)?feat=directlink#s", $url));
$is_openfile = (preg_match("#http://openfile.ru/video/(.*?)#s", $url));
$is_docgoogle = (preg_match("#https://docs.google.com/file/(.*?)#s", $url));
$is_picasaweb = (preg_match("#picasaweb.google.com/(.*?)/(.*?)/(.*?)#s", $url));
$is_banbe = (preg_match("#http://banbe.net/(.*?)/(.*?)/(.*?)/(.*?)/(.*?)/(.*?)#s", $url));
$is_tudou = (preg_match("#http://www.tudou.com/(.*?)/(.*?)/(.*?)#s", $url));
$is_phimvang = (preg_match("#http://phimvang.org/(.*?)/(.*?)/(.*?)/(.*?).xml#s", $url));
$is_divxcabin = (preg_match("#http://www.divxcabin.com/(.*?).xml#s", $url));
$is_divxstage = (preg_match("#http://www.divxstage.eu/video/(.*?).xml#s", $url));
$is_xvidstage = (preg_match("#http://xvidstage.com/(.*?).xml#s", $url));
$is_phim3s= (preg_match("#http://phim3s.net/(.*?)#s", $url));

	if ($ext == 'swf' || $is_googleVideo || $is_baamboo || $is_megavideo_url) $type = 2;
	elseif (in_array($ext,$movie_arr) || $is_vtv) $type = 3;
	elseif ($is_youtube) $type = 4;
elseif ($is_youtube2) $type = 96;
	elseif ($ext == 'flv' || $ext == 'wma') $type = 5;
	elseif ($is_googleVideo) $type = 6;
	elseif ($is_tamtay || $is_tamtay1 || $idvideo_tamtay || $idvideo_tamtay2) $type = 7;
	elseif ($is_timnhanh) $type = 8;
	elseif ($ext == 'divx') $type = 9;
	elseif ($is_vidbull) $type = 10;
	elseif ($is_clipvn || $is_clipvn1) $type = 11;
	elseif ($is_vidbull) $type = 12;
	elseif ($is_veoh || $is_veoh1) $type = 13;
	elseif ($$is_zing || $idvideo_zing || $is_zing1) $type = 14;
	elseif ($is_myspace) $type = 15;
	elseif ($is_daily) $type = 16;
	elseif ($is_sevenload || $id_sevenload) $type = 17;
	elseif ($is_badongo) $type = 19;
	elseif ($is_sendspace) $type = 23;
	elseif ($is_olala) $type = 26;
	elseif ($is_vimeo || $is_vimeo1) $type = 27;
	elseif ($is_4shared) $type = 29;
	elseif ($is_gdata) $type = 31;
    elseif ($is_mediafire) $type = 32;
    elseif ($is_cyworld) $type = 35;
	elseif ($is_2shared) $type = 36;
    elseif ($is_cyworld2) $type = 37;
	elseif ($is_goonline) $type = 38;
    elseif ($is_movshare) $type = 39;
    elseif ($is_novamov) $type = 41;
    elseif ($is_mediafire2) $type = 42;
	elseif ($is_nct) $type = 43;
	elseif ($is_viddler) $type = 44;
	elseif ($is_zippy) $type = 45;
	elseif ($is_hulkshare) $type = 46;
	elseif ($is_megafun) $type = 47;
	elseif ($is_vidxden) $type = 48;
	elseif ($is_images) $type = 49;
	elseif ($is_dankfile) $type = 50;
	elseif ($is_tusfiles) $type = 51;
	elseif ($is_speedyshare) $type = 52;
	elseif ($is_ovfile) $type = 53;
	elseif ($is_videobb) $type = 54;
	elseif ($is_wootly) $type = 55;
	elseif ($is_vipbee) $type = 56;
	elseif ($is_sharevn) $type = 57;
	elseif ($is_xixam) $type = 58;
	elseif ($is_soha) $type = 59;
	elseif ($is_vidbux) $type = 60;
	elseif ($is_videoweed) $type = 61;
	elseif ($is_loombo) $type = 62;
	elseif ($is_videozer) $type = 63;
	elseif ($is_v1vn1) $type = 64;
	elseif ($is_v1vn2) $type = 65;
	elseif ($is_v1vn3) $type = 66;
    elseif ($is_v1vn4) $type = 67;
    elseif ($is_xixam1) $type = 68;
	elseif ($is_xixam2) $type = 69;
    elseif ($is_seeon) $type = 70;
    elseif ($is_twitvid) $type = 71;
    elseif ($is_rolo) $type = 72;
	elseif ($is_eyvx) $type = 73;
    elseif ($is_vzaar) $type = 74;
	elseif ($is_playwire) $type = 75;
	elseif ($is_bcove) $type = 76;
	elseif ($is_sorensonmedia) $type = 77;
	elseif ($is_ooyala) $type = 78;
	elseif ($is_go) $type = 79;
	elseif ($is_supershare) $type = 80;
	elseif ($is_blip) $type = 81;
	elseif ($is_putlocker) $type = 82;
	elseif ($is_sockshare) $type = 83;
	elseif ($is_zalaa) $type = 84;
    elseif ($is_phimsomot) $type = 85;
    elseif ($is_vip) $type = 86;
    elseif ($is_openfile) $type = 87;
    elseif ($is_docgoogle) $type = 88;
    elseif ($is_picasaweb) $type = 89;
    elseif ($is_banbe) $type = 90;
	elseif ($is_tudou) $type = 91;
	elseif ($is_phimvang) $type = 92;
	elseif ($is_divxcabin) $type = 93;
    elseif ($is_divxstage) $type = 94;
	elseif ($is_xvidstage) $type = 95;
	elseif ($is_xvideos) $type = 96;
	elseif ($is_zing2) $type = 97;
	elseif (!$type) $type = 1;
    elseif ($is_phim3s) $type = 100;
    elseif ($is_zing3) $type = 101;
    return $type;
}
function set_type($file_type='') {
	$html = "<select name=episode_server>".
		"<option value=0".(($file_type==0)?' selected':'').">DEFAULT</option>".
		"<option value=1".(($file_type==1)?' selected':'').">1 - Other</option>".
		"<option value=2".(($file_type==2)?' selected':'').">2 - Flash</option>".
		"<option value=3".(($file_type==3)?' selected':'').">3 - WMP VIDEO</option>".
		"<option value=4".(($file_type==4)?' selected':'').">4 - Youtube</option>".
		"<option value=5".(($file_type==5)?' selected':'').">5 - FLV</option>".
		"<option value=6".(($file_type==6)?' selected':'').">6 - GoogleVideo</option>".
		"<option value=7".(($file_type==7)?' selected':'').">7 - Tamtay</option>".
		"<option value=8".(($file_type==8)?' selected':'').">8 - Yume</option>".
		"<option value=9".(($file_type==9)?' selected':'').">9 - DIVX</option>".
		"<option value=10".(($file_type==10)?' selected':'').">10 - Vidbull</option>".
		"<option value=11".(($file_type==11)?' selected':'').">11 - Clipvn</option>".
		"<option value=12".(($file_type==12)?' selected':'').">12 - Vidbull</option>".
		"<option value=13".(($file_type==13)?' selected':'').">13 - Veoh</option>".
		"<option value=14".(($file_type==14)?' selected':'').">14 - ZingVideo</option>".
		"<option value=15".(($file_type==15)?' selected':'').">15 - Myspace</option>".
		"<option value=16".(($file_type==16)?' selected':'').">16 - Dailymotion</option>".
		"<option value=17".(($file_type==17)?' selected':'').">17 - Sevenload</option>".
		"<option value=18".(($file_type==18)?' selected':'').">18 - Metcafe</option>".
		"<option value=19".(($file_type==19)?' selected':'').">19 - BADONGO</option>".
		"<option value=23".(($file_type==23)?' selected':'').">23 - Sendspace</option>".
		"<option value=24".(($file_type==24)?' selected':'').">24 - FileFactory</option>".
	        "<option value=26".(($file_type==26)?' selected':'').">26 - Timvui</option>".
		"<option value=27".(($file_type==27)?' selected':'').">27 - Vimeo</option>".
		"<option value=29".(($file_type==29)?' selected':'').">29 - 4Shared</option>".
		"<option value=31".(($file_type==31)?' selected':'').">31 - Gdata</option>".
		"<option value=32".(($file_type==32)?' selected':'').">32 - Download</option>".
		
		"<option value=35".(($file_type==35)?' selected':'').">35 - Cyworld</option>".
		"<option value=36".(($file_type==36)?' selected':'').">36 - 2Shared</option>".
		"<option value=37".(($file_type==37)?' selected':'').">37 - Cine Cyworld</option>".
		"<option value=38".(($file_type==38)?' selected':'').">38 - GoClip</option>".
		"<option value=39".(($file_type==39)?' selected':'').">39 - Movshared</option>".
		"<option value=41".(($file_type==41)?' selected':'').">41 - Novamov</option>".
		"<option value=42".(($file_type==42)?' selected':'').">42 - Mediafire Player</option>".
		"<option value=43".(($file_type==43)?' selected':'').">43 - NCT (OTS)</option>".
		"<option value=44".(($file_type==44)?' selected':'').">44 - Viddler</option>".
		"<option value=45".(($file_type==45)?' selected':'').">45 - Zippy</option>".
		"<option value=46".(($file_type==46)?' selected':'').">46 - HulkShare</option>".
		"<option value=47".(($file_type==47)?' selected':'').">47 - Megafun</option>".
		"<option value=48".(($file_type==48)?' selected':'').">48 - Vidxden</option>".
		"<option value=49".(($file_type==49)?' selected':'').">49 - Images</option>".
		"<option value=50".(($file_type==50)?' selected':'').">50 - DankFile</option>".
		"<option value=51".(($file_type==51)?' selected':'').">51 - TusFiles</option>".
        "<option value=52".(($file_type==52)?' selected':'').">52 - SpeedyShare</option>".
		"<option value=53".(($file_type==53)?' selected':'').">53 - OvFile.com</option>".
		"<option value=54".(($file_type==54)?' selected':'').">54 - Videobb</option>".
		"<option value=55".(($file_type==55)?' selected':'').">55 - Wootly</option>".
		"<option value=56".(($file_type==56)?' selected':'').">56 - Megafun</option>".
		"<option value=57".(($file_type==57)?' selected':'').">57 - Share.vnn.vn</option>".
		"<option value=58".(($file_type==58)?' selected':'').">58 - HotShare</option>".
		"<option value=59".(($file_type==59)?' selected':'').">59 - Soha</option>".
		"<option value=60".(($file_type==60)?' selected':'').">60 - VidBux</option>".
		"<option value=61".(($file_type==61)?' selected':'').">61 - VideoWeed</option>".
		"<option value=62".(($file_type==62)?' selected':'').">62 - LoomBo</option>".
		"<option value=63".(($file_type==63)?' selected':'').">63 - Videozer</option>".
		"<option value=64".(($file_type==64)?' selected':'').">64 - v1vn youtube</option>".
		"<option value=65".(($file_type==65)?' selected':'').">65 - v1vn movshare</option>".
		"<option value=66".(($file_type==66)?' selected':'').">66 - v1vn Vidxden</option>".
		"<option value=67".(($file_type==67)?' selected':'').">67 - v1vn Sharevn</option>".
		"<option value=68".(($file_type==68)?' selected':'').">68 - xixam youtube</option>".
		"<option value=69".(($file_type==69)?' selected':'').">69 - xixam cyword</option>".
		"<option value=70".(($file_type==70)?' selected':'').">70 - SeeOn</option>".
		"<option value=71".(($file_type==71)?' selected':'').">71 - Twitvid</option>".
		"<option value=72".(($file_type==72)?' selected':'').">72 - Rolo</option>".
		"<option value=73".(($file_type==73)?' selected':'').">73 - Eyvx</option>".
		"<option value=74".(($file_type==74)?' selected':'').">74 - Vzaar</option>".
		"<option value=75".(($file_type==75)?' selected':'').">75 - Playwire</option>".	
		"<option value=76".(($file_type==76)?' selected':'').">76 - Bcove</option>".
		"<option value=77".(($file_type==77)?' selected':'').">77 - Sorenson</option>".
		"<option value=78".(($file_type==78)?' selected':'').">78 - Ooyala</option>".
		"<option value=79".(($file_type==79)?' selected':'').">79 - Govn</option>".
		"<option value=80".(($file_type==80)?' selected':'').">80 - SuperShare</option>".
		"<option value=81".(($file_type==81)?' selected':'').">81 - Blip</option>".
		"<option value=82".(($file_type==82)?' selected':'').">82 - PutLoker</option>".
		"<option value=83".(($file_type==83)?' selected':'').">83 - SockShare</option>".
		"<option value=84".(($file_type==84)?' selected':'').">84 - Zalaa</option>".
		"<option value=85".(($file_type==85)?' selected':'').">85 - Phimsomot</option>".
		"<option value=86".(($file_type==86)?' selected':'').">86 - V.I.P</option>".
		"<option value=87".(($file_type==87)?' selected':'').">87 - Openfile</option>".
		"<option value=88".(($file_type==88)?' selected':'').">88 - DocGoogle</option>".
		"<option value=89".(($file_type==89)?' selected':'').">89 - Picasaweb</option>".
		"<option value=90".(($file_type==90)?' selected':'').">90 - BanBe</option>".
		"<option value=91".(($file_type==91)?' selected':'').">91 - Tudou</option>".
		"<option value=92".(($file_type==92)?' selected':'').">92 - Xem Full PHimvang</option>".
		"<option value=93".(($file_type==93)?' selected':'').">93 - DivXcabin</option>".
		"<option value=94".(($file_type==94)?' selected':'').">94 - DivxStage</option>".
		"<option value=95".(($file_type==95)?' selected':'').">95 - XvidStage</option>".
		"<option value=101".(($file_type==101)?' selected':'').">101 - ZingTV</option>".

		"</select>";
	return $html;
}
function episode_show($film_id){
	global $wpdb,$post;
	$permalink = get_bloginfo('url')."/xem-phim-".$post->post_name."-";
	$permalink_sv =get_bloginfo('url')."/xem-phim-".$post->post_name."/";
$list=$wpdb->get_results("SELECT episode_id, episode_name, episode_type, episode_url,episode_film,episode_server FROM wp_film_episode WHERE episode_film = '".$film_id."' order by episode_order asc");

foreach ( $list as $value ) 
{
$episode_type=$value->episode_type;
$name=$value->episode_name;
$tap=$value->episode_id;
$phim=$value->episode_film;
$broken=$value->episode_server;
switch($episode_type){
	      case '0':
		        $sv0 .= "<li><a title=\"tập ".$value->episode_name."\" data-episode-tap=\"".$value->episode_name."\" data-episode-id=\"".$value->episode_id."\" data-type=\"watch\" class=\"\" href=\"".$permalink."".$tap."\">".$name."</a></li> ";
		  break;
	      case '1':
		        $sv1 .= "<li><a title=\"tập ".$value->episode_name."\" data-episode-tap=\"".$value->episode_name."\" data-episode-tap=\"".$value->episode_name."\" data-episode-id=\"".$value->episode_id."\" data-type=\"watch\" class=\"\" href=\"".$permalink."".$tap."\">".$name."</a></li> ";
		  break;
	      case '2':
		        $sv2 .= "<li><a title=\"tập ".$value->episode_name."\" data-episode-tap=\"".$value->episode_name."\" data-episode-id=\"".$value->episode_id."\" data-type=\"watch\" class=\"\" href=\"".$permalink."".$tap."\">".$name."</a></li> ";
		  break;
	      case '3':
		        $sv3 .= "<li><a title=\"tập ".$value->episode_name."\" data-episode-tap=\"".$value->episode_name."\" data-episode-id=\"".$value->episode_id."\" data-type=\"watch\" class=\"\" href=\"".$permalink."".$tap."\">".$name."</a></li> ";
		  break;
		  case '4':
		        $sv4 .= "<li><a title=\"tập ".$value->episode_name."\" data-episode-tap=\"".$value->episode_name."\" data-episode-id=\"".$value->episode_id."\" data-type=\"watch\" class=\"\" href=\"".$permalink."".$tap."\">".$name."</a></li> ";
		  break;
	      case '5':
		        $sv5 .= "<li><a title=\"tập ".$value->episode_name."\" data-episode-tap=\"".$value->episode_name."\" data-episode-id=\"".$value->episode_id."\" data-type=\"watch\" class=\"\" href=\"".$permalink."".$tap."\">".$name."</a></li> ";
		  break;
		  case '6':
		        $sv6 .= "<li><a title=\"tập ".$value->episode_name."\" data-episode-tap=\"".$value->episode_name."\" data-episode-id=\"".$value->episode_id."\" data-type=\"watch\" class=\"\" href=\"".$permalink."".$tap."\">".$name."</a></li> ";
		  break;
	      case '7':
		        $sv7 .= "<li><a title=\"tập ".$value->episode_name."\" data-episode-tap=\"".$value->episode_name."\" data-episode-id=\"".$value->episode_id."\" data-type=\"watch\" class=\"\" href=\"".$permalink."".$tap."\">".$name."</a></li> ";
		  break;
		  case '8':
		        $sv8 .= "<li><a title=\"tập ".$value->episode_name."\" data-episode-tap=\"".$value->episode_name."\" data-episode-id=\"".$value->episode_id."\" data-type=\"watch\" class=\"\" href=\"".$permalink."".$tap."\">".$name."</a></li> ";
		  break;
	      case '9':
		        $sv9 .= "<li><a title=\"tập ".$value->episode_name."\" data-episode-tap=\"".$value->episode_name."\" data-episode-id=\"".$value->episode_id."\" data-type=\"watch\" class=\"\" href=\"".$permalink."".$tap."\">".$name."</a></li> ";
		  break;
		  case '10':
		        $sv10 .= "<li><a title=\"tập ".$value->episode_name."\" data-episode-tap=\"".$value->episode_name."\" data-episode-id=\"".$value->episode_id."\" data-type=\"watch\" class=\"\" href=\"".$permalink."".$tap."\">".$name."</a></li> ";
		  break;
	      case '11':
		        $sv11 .= "<li><a title=\"tập ".$value->episode_name."\" data-episode-tap=\"".$value->episode_name."\" data-episode-tap=\"".$value->episode_name."\" data-episode-id=\"".$value->episode_id."\" data-type=\"watch\" class=\"\" href=\"".$permalink."".$tap."\">".$name."</a></li> ";
		  break;
	      case '12':
		        $sv12 .= "<li><a title=\"tập ".$value->episode_name."\" data-episode-tap=\"".$value->episode_name."\" data-episode-id=\"".$value->episode_id."\" data-type=\"watch\" class=\"\" href=\"".$permalink."".$tap."\">".$name."</a></li> ";
		  break;
	      case '13':
		        $sv13 .= "<li><a title=\"tập ".$value->episode_name."\" data-episode-tap=\"".$value->episode_name."\" data-episode-id=\"".$value->episode_id."\" data-type=\"watch\" class=\"\" href=\"".$permalink."".$tap."\">".$name."</a></li> ";
		  break;
		  case '14':
		        $sv14 .= "<li><a title=\"tập ".$value->episode_name."\" data-episode-tap=\"".$value->episode_name."\" data-episode-id=\"".$value->episode_id."\" data-type=\"watch\" class=\"\" href=\"".$permalink."".$tap."\">".$name."</a></li> ";
		  break;
	      case '15':
		        $sv15 .= "<li><a title=\"tập ".$value->episode_name."\" data-episode-tap=\"".$value->episode_name."\" data-episode-id=\"".$value->episode_id."\" data-type=\"watch\" class=\"\" href=\"".$permalink."".$tap."\">".$name."</a></li> ";
		  break;
		  case '16':
		        $sv16 .= "<li><a title=\"tập ".$value->episode_name."\" data-episode-tap=\"".$value->episode_name."\" data-episode-id=\"".$value->episode_id."\" data-type=\"watch\" class=\"\" href=\"".$permalink."".$tap."\">".$name."</a></li> ";
		  break;
	      case '17':
		        $sv17 .= "<li><a title=\"tập ".$value->episode_name."\" data-episode-tap=\"".$value->episode_name."\" data-episode-id=\"".$value->episode_id."\" data-type=\"watch\" class=\"\" href=\"".$permalink."".$tap."\">".$name."</a></li> ";
		  break;
		  case '18':
		        $sv18 .= "<li><a title=\"tập ".$value->episode_name."\" data-episode-tap=\"".$value->episode_name."\" data-episode-id=\"".$value->episode_id."\" data-type=\"watch\" class=\"\" href=\"".$permalink."".$tap."\">".$name."</a></li> ";
		  break;
	      case '19':
		        $sv19 .= "<li><a title=\"tập ".$value->episode_name."\" data-episode-tap=\"".$value->episode_name."\" data-episode-id=\"".$value->episode_id."\" data-type=\"watch\" class=\"\" href=\"".$permalink."".$tap."\">".$name."</a></li> ";
		  break;
		  case '20':
		        $sv20 .= "<li><a title=\"tập ".$value->episode_name."\" data-episode-tap=\"".$value->episode_name."\" data-episode-id=\"".$value->episode_id."\" data-type=\"watch\" class=\"\" href=\"".$permalink."".$tap."\">".$name."</a></li> ";
		  break;
	      case '21':
		        $sv21 .= "<li><a title=\"tập ".$value->episode_name."\" data-episode-tap=\"".$value->episode_name."\" data-episode-tap=\"".$value->episode_name."\" data-episode-id=\"".$value->episode_id."\" data-type=\"watch\" class=\"\" href=\"".$permalink."".$tap."\">".$name."</a></li> ";
		  break;
	      case '22':
		        $sv22 .= "<li><a title=\"tập ".$value->episode_name."\" data-episode-tap=\"".$value->episode_name."\" data-episode-id=\"".$value->episode_id."\" data-type=\"watch\" class=\"\" href=\"".$permalink."".$tap."\">".$name."</a></li> ";
		  break;
	      case '23':
		        $sv23 .= "<li><a title=\"tập ".$value->episode_name."\" data-episode-tap=\"".$value->episode_name."\" data-episode-id=\"".$value->episode_id."\" data-type=\"watch\" class=\"\" href=\"".$permalink."".$tap."\">".$name."</a></li> ";
		  break;
		  case '24':
		        $sv24 .= "<li><a title=\"tập ".$value->episode_name."\" data-episode-tap=\"".$value->episode_name."\" data-episode-id=\"".$value->episode_id."\" data-type=\"watch\" class=\"\" href=\"".$permalink."".$tap."\">".$name."</a></li> ";
		  break;
	      case '25':
		        $sv25 .= "<li><a title=\"tập ".$value->episode_name."\" data-episode-tap=\"".$value->episode_name."\" data-episode-id=\"".$value->episode_id."\" data-type=\"watch\" class=\"\" href=\"".$permalink."".$tap."\">".$name."</a></li> ";
		  break;
		  case '26':
		        $sv26 .= "<li><a title=\"tập ".$value->episode_name."\" data-episode-tap=\"".$value->episode_name."\" data-episode-id=\"".$value->episode_id."\" data-type=\"watch\" class=\"\" href=\"".$permalink."".$tap."\">".$name."</a></li> ";
		  break;
	      case '27':
		        $sv27 .= "<li><a title=\"tập ".$value->episode_name."\" data-episode-tap=\"".$value->episode_name."\" data-episode-id=\"".$value->episode_id."\" data-type=\"watch\" class=\"\" href=\"".$permalink."".$tap."\">".$name."</a></li> ";
		  break;
		  case '28':
		        $sv28 .= "<li><a title=\"tập ".$value->episode_name."\" data-episode-tap=\"".$value->episode_name."\" data-episode-id=\"".$value->episode_id."\" data-type=\"watch\" class=\"\" href=\"".$permalink."".$tap."\">".$name."</a></li> ";
		  break;
	      case '29':
		        $sv29 .= "<li><a title=\"tập ".$value->episode_name."\" data-episode-tap=\"".$value->episode_name."\" data-episode-id=\"".$value->episode_id."\" data-type=\"watch\" class=\"\" href=\"".$permalink."".$tap."\">".$name."</a></li> ";
		  break;
		  case '30':
		        $sv30 .= "<li><a title=\"tập ".$value->episode_name."\" data-episode-tap=\"".$value->episode_name."\" data-episode-id=\"".$value->episode_id."\" data-type=\"watch\" class=\"\" href=\"".$permalink."".$tap."\">".$name."</a></li> ";
		  break;
	      case '31':
		        $sv31 .= "<li><a title=\"tập ".$value->episode_name."\" data-episode-tap=\"".$value->episode_name."\" data-episode-tap=\"".$value->episode_name."\" data-episode-id=\"".$value->episode_id."\" data-type=\"watch\" class=\"\" href=\"".$permalink."".$tap."\">".$name."</a></li> ";
		  break;
	      case '32':
		        $sv32 .= "<li><a title=\"tập ".$value->episode_name."\" data-episode-tap=\"".$value->episode_name."\" data-episode-id=\"".$value->episode_id."\" data-type=\"watch\" class=\"\" href=\"".$permalink."".$tap."\">".$name."</a></li> ";
		  break;
	      case '33':
		        $sv33 .= "<li><a title=\"tập ".$value->episode_name."\" data-episode-tap=\"".$value->episode_name."\" data-episode-id=\"".$value->episode_id."\" data-type=\"watch\" class=\"\" href=\"".$permalink."".$tap."\">".$name."</a></li> ";
		  break;
		  case '34':
		        $sv34 .= "<li><a title=\"tập ".$value->episode_name."\" data-episode-tap=\"".$value->episode_name."\" data-episode-id=\"".$value->episode_id."\" data-type=\"watch\" class=\"\" href=\"".$permalink."".$tap."\">".$name."</a></li> ";
		  break;
	      case '35':
		        $sv35 .= "<li><a title=\"tập ".$value->episode_name."\" data-episode-tap=\"".$value->episode_name."\" data-episode-id=\"".$value->episode_id."\" data-type=\"watch\" class=\"\" href=\"".$permalink."".$tap."\">".$name."</a></li> ";
		  break;
		  case '36':
		        $sv36 .= "<li><a title=\"tập ".$value->episode_name."\" data-episode-tap=\"".$value->episode_name."\" data-episode-id=\"".$value->episode_id."\" data-type=\"watch\" class=\"\" href=\"".$permalink."".$tap."\">".$name."</a></li> ";
		  break;
	      case '37':
		        $sv37 .= "<li><a title=\"tập ".$value->episode_name."\" data-episode-tap=\"".$value->episode_name."\" data-episode-id=\"".$value->episode_id."\" data-type=\"watch\" class=\"\" href=\"".$permalink."".$tap."\">".$name."</a></li> ";
		  break;
		  case '38':
		        $sv38 .= "<li><a title=\"tập ".$value->episode_name."\" data-episode-tap=\"".$value->episode_name."\" data-episode-id=\"".$value->episode_id."\" data-type=\"watch\" class=\"\" href=\"".$permalink."".$tap."\">".$name."</a></li> ";
		  break;
	      case '39':
		        $sv39 .= "<li><a title=\"tập ".$value->episode_name."\" data-episode-tap=\"".$value->episode_name."\" data-episode-id=\"".$value->episode_id."\" data-type=\"watch\" class=\"\" href=\"".$permalink."".$tap."\">".$name."</a></li> ";
		  break;
		  case '40':
		        $sv40 .= "<li><a title=\"tập ".$value->episode_name."\" data-episode-tap=\"".$value->episode_name."\" data-episode-id=\"".$value->episode_id."\" data-type=\"watch\" class=\"\" href=\"".$permalink."".$tap."\">".$name."</a></li> ";
		  break;
	      case '41':
		        $sv41 .= "<li><a title=\"tập ".$value->episode_name."\" data-episode-tap=\"".$value->episode_name."\" data-episode-tap=\"".$value->episode_name."\" data-episode-id=\"".$value->episode_id."\" data-type=\"watch\" class=\"\" href=\"".$permalink."".$tap."\">".$name."</a></li> ";
		  break;
	      case '42':
		        $sv42 .= "<li><a title=\"tập ".$value->episode_name."\" data-episode-tap=\"".$value->episode_name."\" data-episode-id=\"".$value->episode_id."\" data-type=\"watch\" class=\"\" href=\"".$permalink."".$tap."\">".$name."</a></li> ";
		  break;
	      case '43':
		        $sv43 .= "<li><a title=\"tập ".$value->episode_name."\" data-episode-tap=\"".$value->episode_name."\" data-episode-id=\"".$value->episode_id."\" data-type=\"watch\" class=\"\" href=\"".$permalink."".$tap."\">".$name."</a></li> ";
		  break;
		  case '44':
		        $sv44 .= "<li><a title=\"tập ".$value->episode_name."\" data-episode-tap=\"".$value->episode_name."\" data-episode-id=\"".$value->episode_id."\" data-type=\"watch\" class=\"\" href=\"".$permalink."".$tap."\">".$name."</a></li> ";
		  break;
	      case '45':
		        $sv45 .= "<li><a title=\"tập ".$value->episode_name."\" data-episode-tap=\"".$value->episode_name."\" data-episode-id=\"".$value->episode_id."\" data-type=\"watch\" class=\"\" href=\"".$permalink."".$tap."\">".$name."</a></li> ";
		  break;
		  case '46':
		        $sv46 .= "<li><a title=\"tập ".$value->episode_name."\" data-episode-tap=\"".$value->episode_name."\" data-episode-id=\"".$value->episode_id."\" data-type=\"watch\" class=\"\" href=\"".$permalink."".$tap."\">".$name."</a></li> ";
		  break;
	      case '47':
		        $sv47 .= "<li><a title=\"tập ".$value->episode_name."\" data-episode-tap=\"".$value->episode_name."\" data-episode-id=\"".$value->episode_id."\" data-type=\"watch\" class=\"\" href=\"".$permalink."".$tap."\">".$name."</a></li> ";
		  break;
		  case '48':
		        $sv48 .= "<li><a title=\"tập ".$value->episode_name."\" data-episode-tap=\"".$value->episode_name."\" data-episode-id=\"".$value->episode_id."\" data-type=\"watch\" class=\"\" href=\"".$permalink."".$tap."\">".$name."</a></li> ";
		  break;
	      case '49':
		        $sv49 .= "<li><a title=\"tập ".$value->episode_name."\" data-episode-tap=\"".$value->episode_name."\" data-episode-id=\"".$value->episode_id."\" data-type=\"watch\" class=\"\" href=\"".$permalink."".$tap."\">".$name."</a></li> ";
		  break;
		  case '50':
		        $sv50 .= "<li><a title=\"tập ".$value->episode_name."\" data-episode-tap=\"".$value->episode_name."\" data-episode-id=\"".$value->episode_id."\" data-type=\"watch\" class=\"\" href=\"".$permalink."".$tap."\">".$name."</a></li> ";
		  break;
	      case '51':
		        $sv5 .= "<li><a title=\"tập ".$value->episode_name."\" data-episode-tap=\"".$value->episode_name."\" data-episode-tap=\"".$value->episode_name."\" data-episode-id=\"".$value->episode_id."\" data-type=\"watch\" class=\"\" href=\"".$permalink."".$tap."\">".$name."</a></li> ";
		  break;
	      case '52':
		        $sv52 .= "<li><a title=\"tập ".$value->episode_name."\" data-episode-tap=\"".$value->episode_name."\" data-episode-id=\"".$value->episode_id."\" data-type=\"watch\" class=\"\" href=\"".$permalink."".$tap."\">".$name."</a></li> ";
		  break;
	      case '53':
		        $sv53 .= "<li><a title=\"tập ".$value->episode_name."\" data-episode-tap=\"".$value->episode_name."\" data-episode-id=\"".$value->episode_id."\" data-type=\"watch\" class=\"\" href=\"".$permalink."".$tap."\">".$name."</a></li> ";
		  break;
		  case '54':
		        $sv54 .= "<li><a title=\"tập ".$value->episode_name."\" data-episode-tap=\"".$value->episode_name."\" data-episode-id=\"".$value->episode_id."\" data-type=\"watch\" class=\"\" href=\"".$permalink."".$tap."\">".$name."</a></li> ";
		  break;
	      case '55':
		        $sv55 .= "<li><a title=\"tập ".$value->episode_name."\" data-episode-tap=\"".$value->episode_name."\" data-episode-id=\"".$value->episode_id."\" data-type=\"watch\" class=\"\" href=\"".$permalink."".$tap."\">".$name."</a></li> ";
		  break;
		  case '56':
		        $sv56 .= "<li><a title=\"tập ".$value->episode_name."\" data-episode-tap=\"".$value->episode_name."\" data-episode-id=\"".$value->episode_id."\" data-type=\"watch\" class=\"\" href=\"".$permalink."".$tap."\">".$name."</a></li> ";
		  break;
	      case '57':
		        $sv57 .= "<li><a title=\"tập ".$value->episode_name."\" data-episode-tap=\"".$value->episode_name."\" data-episode-id=\"".$value->episode_id."\" data-type=\"watch\" class=\"\" href=\"".$permalink."".$tap."\">".$name."</a></li> ";
		  break;
		  case '58':
		        $sv58 .= "<li><a title=\"tập ".$value->episode_name."\" data-episode-tap=\"".$value->episode_name."\" data-episode-id=\"".$value->episode_id."\" data-type=\"watch\" class=\"\" href=\"".$permalink."".$tap."\">".$name."</a></li> ";
		  break;
	      case '59':
		        $sv59 .= "<li><a title=\"tập ".$value->episode_name."\" data-episode-tap=\"".$value->episode_name."\" data-episode-id=\"".$value->episode_id."\" data-type=\"watch\" class=\"\" href=\"".$permalink."".$tap."\">".$name."</a></li> ";
		  break;
		  case '60':
		        $sv60 .= "<li><a title=\"tập ".$value->episode_name."\" data-episode-tap=\"".$value->episode_name."\" data-episode-id=\"".$value->episode_id."\" data-type=\"watch\" class=\"\" href=\"".$permalink."".$tap."\">".$name."</a></li> ";
		  break;
	      case '61':
		        $sv61 .= "<li><a title=\"tập ".$value->episode_name."\" data-episode-tap=\"".$value->episode_name."\" data-episode-tap=\"".$value->episode_name."\" data-episode-id=\"".$value->episode_id."\" data-type=\"watch\" class=\"\" href=\"".$permalink."".$tap."\">".$name."</a></li> ";
		  break;
	      case '62':
		        $sv62 .= "<li><a title=\"tập ".$value->episode_name."\" data-episode-tap=\"".$value->episode_name."\" data-episode-id=\"".$value->episode_id."\" data-type=\"watch\" class=\"\" href=\"".$permalink."".$tap."\">".$name."</a></li> ";
		  break;
	      case '63':
		        $sv63 .= "<li><a title=\"tập ".$value->episode_name."\" data-episode-tap=\"".$value->episode_name."\" data-episode-id=\"".$value->episode_id."\" data-type=\"watch\" class=\"\" href=\"".$permalink."".$tap."\">".$name."</a></li> ";
		  break;
		  case '64':
		        $sv64 .= "<li><a title=\"tập ".$value->episode_name."\" data-episode-tap=\"".$value->episode_name."\" data-episode-id=\"".$value->episode_id."\" data-type=\"watch\" class=\"\" href=\"".$permalink."".$tap."\">".$name."</a></li> ";
		  break;
	      case '65':
		        $sv65 .= "<li><a title=\"tập ".$value->episode_name."\" data-episode-tap=\"".$value->episode_name."\" data-episode-id=\"".$value->episode_id."\" data-type=\"watch\" class=\"\" href=\"".$permalink."".$tap."\">".$name."</a></li> ";
		  break;
		  case '66':
		        $sv66 .= "<li><a title=\"tập ".$value->episode_name."\" data-episode-tap=\"".$value->episode_name."\" data-episode-id=\"".$value->episode_id."\" data-type=\"watch\" class=\"\" href=\"".$permalink."".$tap."\">".$name."</a></li> ";
		  break;
	      case '67':
		        $sv67 .= "<li><a title=\"tập ".$value->episode_name."\" data-episode-tap=\"".$value->episode_name."\" data-episode-id=\"".$value->episode_id."\" data-type=\"watch\" class=\"\" href=\"".$permalink."".$tap."\">".$name."</a></li> ";
		  break;
		  case '68':
		        $sv68 .= "<li><a title=\"tập ".$value->episode_name."\" data-episode-tap=\"".$value->episode_name."\" data-episode-id=\"".$value->episode_id."\" data-type=\"watch\" class=\"\" href=\"".$permalink."".$tap."\">".$name."</a></li> ";
		  break;
	      case '69':
		        $sv69 .= "<li><a title=\"tập ".$value->episode_name."\" data-episode-tap=\"".$value->episode_name."\" data-episode-id=\"".$value->episode_id."\" data-type=\"watch\" class=\"\" href=\"".$permalink."".$tap."\">".$name."</a></li> ";
		  break;
		  case '70':
		        $sv70 .= "<li><a title=\"tập ".$value->episode_name."\" data-episode-tap=\"".$value->episode_name."\" data-episode-id=\"".$value->episode_id."\" data-type=\"watch\" class=\"\" href=\"".$permalink."".$tap."\">".$name."</a></li> ";
		  break;
	      case '71':
		        $sv71 .= "<li><a title=\"tập ".$value->episode_name."\" data-episode-tap=\"".$value->episode_name."\" data-episode-tap=\"".$value->episode_name."\" data-episode-id=\"".$value->episode_id."\" data-type=\"watch\" class=\"\" href=\"".$permalink."".$tap."\">".$name."</a></li> ";
		  break;
	      case '72':
		        $sv72 .= "<li><a title=\"tập ".$value->episode_name."\" data-episode-tap=\"".$value->episode_name."\" data-episode-id=\"".$value->episode_id."\" data-type=\"watch\" class=\"\" href=\"".$permalink."".$tap."\">".$name."</a></li> ";
		  break;
	      case '73':
		        $sv73 .= "<li><a title=\"tập ".$value->episode_name."\" data-episode-tap=\"".$value->episode_name."\" data-episode-id=\"".$value->episode_id."\" data-type=\"watch\" class=\"\" href=\"".$permalink."".$tap."\">".$name."</a></li> ";
		  break;
		  case '74':
		        $sv74 .= "<li><a title=\"tập ".$value->episode_name."\" data-episode-tap=\"".$value->episode_name."\" data-episode-id=\"".$value->episode_id."\" data-type=\"watch\" class=\"\" href=\"".$permalink."".$tap."\">".$name."</a></li> ";
		  break;
	      case '75':
		        $sv75 .= "<li><a title=\"tập ".$value->episode_name."\" data-episode-tap=\"".$value->episode_name."\" data-episode-id=\"".$value->episode_id."\" data-type=\"watch\" class=\"\" href=\"".$permalink."".$tap."\">".$name."</a></li> ";
		  break;
		  case '76':
		        $sv76 .= "<li><a title=\"tập ".$value->episode_name."\" data-episode-tap=\"".$value->episode_name."\" data-episode-id=\"".$value->episode_id."\" data-type=\"watch\" class=\"\" href=\"".$permalink."".$tap."\">".$name."</a></li> ";
		  break;
	      case '77':
		        $sv77 .= "<li><a title=\"tập ".$value->episode_name."\" data-episode-tap=\"".$value->episode_name."\" data-episode-id=\"".$value->episode_id."\" data-type=\"watch\" class=\"\" href=\"".$permalink."".$tap."\">".$name."</a></li> ";
		  break;
		  case '78':
		        $sv78 .= "<li><a title=\"tập ".$value->episode_name."\" data-episode-tap=\"".$value->episode_name."\" data-episode-id=\"".$value->episode_id."\" data-type=\"watch\" class=\"\" href=\"".$permalink."".$tap."\">".$name."</a></li> ";
		  break;
	      case '79':
		        $sv79 .= "<li><a title=\"tập ".$value->episode_name."\" data-episode-tap=\"".$value->episode_name."\" data-episode-id=\"".$value->episode_id."\" data-type=\"watch\" class=\"\" href=\"".$permalink."".$tap."\">".$name."</a></li> ";
		  break;
		  case '80':
		        $sv80 .= "<li><a title=\"tập ".$value->episode_name."\" data-episode-tap=\"".$value->episode_name."\" data-episode-id=\"".$value->episode_id."\" data-type=\"watch\" class=\"\" href=\"".$permalink."".$tap."\">".$name."</a></li> ";
		  break;
	      case '81':
		        $sv81 .= "<li><a title=\"tập ".$value->episode_name."\" data-episode-tap=\"".$value->episode_name."\" data-episode-tap=\"".$value->episode_name."\" data-episode-id=\"".$value->episode_id."\" data-type=\"watch\" class=\"\" href=\"".$permalink."".$tap."\">".$name."</a></li> ";
		  break;
	      case '82':
		        $sv82 .= "<li><a title=\"tập ".$value->episode_name."\" data-episode-tap=\"".$value->episode_name."\" data-episode-id=\"".$value->episode_id."\" data-type=\"watch\" class=\"\" href=\"".$permalink."".$tap."\">".$name."</a></li> ";
		  break;
	      case '83':
		        $sv83 .= "<li><a title=\"tập ".$value->episode_name."\" data-episode-tap=\"".$value->episode_name."\" data-episode-id=\"".$value->episode_id."\" data-type=\"watch\" class=\"\" href=\"".$permalink."".$tap."\">".$name."</a></li> ";
		  break;
		  case '84':
		        $sv84 .= "<li><a title=\"tập ".$value->episode_name."\" data-episode-tap=\"".$value->episode_name."\" data-episode-id=\"".$value->episode_id."\" data-type=\"watch\" class=\"\" href=\"".$permalink."".$tap."\">".$name."</a></li> ";
		  break;
	      case '85':
		        $sv85 .= "<li><a title=\"tập ".$value->episode_name."\" data-episode-tap=\"".$value->episode_name."\" data-episode-id=\"".$value->episode_id."\" data-type=\"watch\" class=\"\" href=\"".$permalink."".$tap."\">".$name."</a></li> ";
		  break;
		  case '86':
		        $sv86 .= "<li><a title=\"tập ".$value->episode_name."\" data-episode-tap=\"".$value->episode_name."\" data-episode-id=\"".$value->episode_id."\" data-type=\"watch\" class=\"\" href=\"".$permalink."".$tap."\">".$name."</a></li> ";
		  break;
	      case '87':
		        $sv87 .= "<li><a title=\"tập ".$value->episode_name."\" data-episode-tap=\"".$value->episode_name."\" data-episode-id=\"".$value->episode_id."\" data-type=\"watch\" class=\"\" href=\"".$permalink."".$tap."\">".$name."</a></li> ";
		  break;
		  case '88':
		        $sv88 .= "<li><a title=\"tập ".$value->episode_name."\" data-episode-tap=\"".$value->episode_name."\" data-episode-id=\"".$value->episode_id."\" data-type=\"watch\" class=\"\" href=\"".$permalink."".$tap."\">".$name."</a></li> ";
		  break;
	      case '89':
		        $sv89 .= "<li><a title=\"tập ".$value->episode_name."\" data-episode-tap=\"".$value->episode_name."\" data-episode-id=\"".$value->episode_id."\" data-type=\"watch\" class=\"\" href=\"".$permalink."".$tap."\">".$name."</a></li> ";
		  break;
		  case '90':
		        $sv90 .= "<li><a title=\"tập ".$value->episode_name."\" data-episode-tap=\"".$value->episode_name."\" data-episode-id=\"".$value->episode_id."\" data-type=\"watch\" class=\"\" href=\"".$permalink."".$tap."\">".$name."</a></li> ";
		  break;
	      case '91':
		        $sv91 .= "<li><a title=\"tập ".$value->episode_name."\" data-episode-tap=\"".$value->episode_name."\" data-episode-tap=\"".$value->episode_name."\" data-episode-id=\"".$value->episode_id."\" data-type=\"watch\" class=\"\" href=\"".$permalink."".$tap."\">".$name."</a></li> ";
		  break;
	      case '92':
		        $sv92 .= "<li><a title=\"tập ".$value->episode_name."\" data-episode-tap=\"".$value->episode_name."\" data-episode-id=\"".$value->episode_id."\" data-type=\"watch\" class=\"\" href=\"".$permalink."".$tap."\">".$name."</a></li> ";
		  break;
	      case '93':
		        $sv93 .= "<li><a title=\"tập ".$value->episode_name."\" data-episode-tap=\"".$value->episode_name."\" data-episode-id=\"".$value->episode_id."\" data-type=\"watch\" class=\"\" href=\"".$permalink."".$tap."\">".$name."</a></li> ";
		  break;
		  case '94':
		        $sv94 .= "<li><a title=\"tập ".$value->episode_name."\" data-episode-tap=\"".$value->episode_name."\" data-episode-id=\"".$value->episode_id."\" data-type=\"watch\" class=\"\" href=\"".$permalink."".$tap."\">".$name."</a></li> ";
		  break;
	      case '95':
		        $sv95 .= "<li><a title=\"tập ".$value->episode_name."\" data-episode-tap=\"".$value->episode_name."\" data-episode-id=\"".$value->episode_id."\" data-type=\"watch\" class=\"\" href=\"".$permalink."".$tap."\">".$name."</a></li> ";
		  break;
		  case '96':
		        $sv96 .= "<li><a title=\"tập ".$value->episode_name."\" data-episode-tap=\"".$value->episode_name."\" data-episode-id=\"".$value->episode_id."\" data-type=\"watch\" class=\"\" href=\"".$permalink."".$tap."\">".$name."</a></li> ";
		  break;
	      case '97':
		        $sv97 .= "<li><a title=\"tập ".$value->episode_name."\" data-episode-tap=\"".$value->episode_name."\" data-episode-id=\"".$value->episode_id."\" data-type=\"watch\" class=\"\" href=\"".$permalink."".$tap."\">".$name."</a></li> ";
		  break;
		  case '98':
		        $sv98 .= "<li><a title=\"tập ".$value->episode_name."\" data-episode-tap=\"".$value->episode_name."\" data-episode-id=\"".$value->episode_id."\" data-type=\"watch\" class=\"\" href=\"".$permalink."".$tap."\">".$name."</a></li> ";
		  break;
	      case '99':
		        $sv99 .= "<li><a title=\"tập ".$value->episode_name."\" data-episode-tap=\"".$value->episode_name."\" data-episode-id=\"".$value->episode_id."\" data-type=\"watch\" class=\"\" href=\"".$permalink."".$tap."\">".$name."</a></li> ";
		  break;
case '100':
		        $sv100 .= "<li><a title=\"tập ".$value->episode_name."\" data-episode-tap=\"".$value->episode_name."\" data-episode-id=\"".$value->episode_id."\" data-type=\"watch\" class=\"\" href=\"".$permalink."".$tap."\">".$name."</a></li> ";
		  break;
	      case '101':
		        $sv101 .= "<li><a title=\"tập ".$value->episode_name."\" data-episode-tap=\"".$value->episode_name."\" data-episode-id=\"".$value->episode_id."\" data-type=\"watch\" class=\"\" href=\"".$permalink."".$tap."\">".$name."</a></li> ";
		  break;
	  }

}
$total_server .= '<div id="servers" class="serverlist">';
if($sv1) $total_server .= '<div class="server"><div class="label">Video:</div> <ul class="episodelist">'.$sv1.'</ul></div>';
if($sv2) $total_server .= '<div class="server"><div class="label">Flash:</div> <ul class="episodelist">'.$sv2.'</ul></div>';
if($sv3) $total_server .= '<div class="server"><div class="label">WMP VIDEO:</div> <ul class="episodelist">'.$sv3.'</ul></div>';
if($sv4) $total_server .= '<div class="server"><div class="label">V.I.P</div> <ul class="episodelist">'.$sv4.'</ul></div>';
if($sv5) $total_server .= '<div class="server"><div class="label">FLV:</div> <ul class="episodelist">'.$sv5.'</ul></div>';
if($sv6) $total_server .= '<div class="server"><div class="label">GoogleVideo:</div> <ul class="episodelist">'.$sv6.'</ul></div>';
if($sv7) $total_server .= '<div class="server"><div class="label">Tamtay:</div> <ul class="episodelist">'.$sv7.'</ul></div>';
if($sv8) $total_server .= '<div class="server"><div class="label">Yume:</div> <ul class="episodelist">'.$sv8.'</ul></div>';
if($sv9) $total_server .= '<div class="server"><div class="label">DIVX:</div> <ul class="episodelist">'.$sv9.'</ul></div>';
if($sv10) $total_server .= '<div class="server"><div class="label">Vidbull:</div> <ul class="episodelist">'.$sv10.'</ul></div>';
if($sv11) $total_server .= '<div class="server"><div class="label">SERVER VIP:</div> <ul class="episodelist">'.$sv11.'</ul></div>';
if($sv12) $total_server .= '<div class="server"><div class="label">Vidbull:</div> <ul class="episodelist">'.$sv12.'</ul></div>';
if($sv13) $total_server .= '<div class="server"><div class="label">Veoh:</div> <ul class="episodelist">'.$sv13.'</ul></div>';
if($sv14) $total_server .= '<div class="server"><div class="label">SERVER VIP 1:</div> <ul class="episodelist">'.$sv14.'</ul></div>';
if($sv15) $total_server .= '<div class="server"><div class="label">Myspace:</div> <ul class="episodelist">'.$sv15.'</ul></div>';
if($sv16) $total_server .= '<div class="server"><div class="label">Dailymotion:</div> <ul class="episodelist">'.$sv16.'</ul></div>';
if($sv17) $total_server .= '<div class="server"><div class="label">Sevenload:</div> <ul class="episodelist">'.$sv17.'</ul></div>';
if($sv18) $total_server .= '<div class="server"><div class="label">Metcafe:</div> <ul class="episodelist">'.$sv18.'</ul></div>';
if($sv19) $total_server .= '<div class="server"><div class="label">BADONGO:</div> <ul class="episodelist">'.$sv19.'</ul></div>';
if($sv23) $total_server .= '<div class="server"><div class="label">Sendspace:</div> <ul class="episodelist">'.$sv23.'</ul></div>';
if($sv24) $total_server .= '<div class="server"><div class="label">FileFactory:</div> <ul class="episodelist">'.$sv24.'</ul></div>';
if($sv26) $total_server .= '<div class="server"><div class="label">Tìm vui:</div> <ul class="episodelist">'.$sv26.'</ul></div>';
if($sv27) $total_server .= '<div class="server"><div class="label">Vimeo:</div> <ul class="episodelist">'.$sv27.'</ul></div>';
if($sv29) $total_server .= '<div class="server"><div class="label">4share:</div> <ul class="episodelist">'.$sv29.'</ul></div>';
if($sv31) $total_server .= '<div class="server"><div class="label">Gdata:</div> <ul class="episodelist">'.$sv31.'</ul></div>';
if($sv32) $total_server .= '<div class="server"><div class="label">Download:</div> <ul class="episodelist">'.$sv32.'</ul></div>';
if($sv35) $total_server .= '<div class="server"><div class="label">Cyworld:</div> <ul class="episodelist">'.$sv35.'</ul></div>';
if($sv36) $total_server .= '<div class="server"><div class="label">2shared:</div> <ul class="episodelist">'.$sv36.'</ul></div>';
if($sv37) $total_server .= '<div class="server"><div class="label">Cyworld2:</div> <ul class="episodelist">'.$sv37.'</ul></div>';
if($sv38) $total_server .= '<div class="server"><div class="label">Go clip:</div> <ul class="episodelist">'.$sv38.'</ul></div>';
if($sv39) $total_server .= '<div class="server"><div class="label">Movshared:</div> <ul class="episodelist">'.$sv39.'</ul></div>';
if($sv41) $total_server .= '<div class="server"><div class="label">Novamov:</div> <ul class="episodelist">'.$sv41.'</ul></div>';
if($sv42) $total_server .= '<div class="server"><div class="label">Mediafire Player:</div> <ul class="episodelist">'.$sv42.'</ul></div>';
if($sv43) $total_server .= '<div class="server"><div class="label">NCT (OTS):</div> <ul class="episodelist">'.$sv43.'</ul></div>';
if($sv44) $total_server .= '<div class="server"><div class="label">Viddler</div> <ul class="episodelist">'.$sv44.'</ul></div>';
if($sv45) $total_server .= '<div class="server"><div class="label">Zippy:</div> <ul class="episodelist">'.$sv45.'</ul></div>';
if($sv46) $total_server .= '<div class="server"><div class="label">HulkShare:</div> <ul class="episodelist">'.$sv46.'</ul></div>';
if($sv47) $total_server .= '<div class="server"><div class="label">Megafun:</div> <ul class="episodelist">'.$sv47.'</ul></div>';
if($sv48) $total_server .= '<div class="server"><div class="label">Vidxden:</div> <ul class="episodelist">'.$sv48.'</ul></div>';
if($sv49) $total_server .= '<div class="server"><div class="label">Images:</div> <ul class="episodelist">'.$sv49.'</ul></div>';
if($sv50) $total_server .= '<div class="server"><div class="label">DankFile:</div> <ul class="episodelist">'.$sv50.'</ul></div>';
if($sv51) $total_server .= '<div class="server"><div class="label">TusFiles:</div> <ul class="episodelist">'.$sv51.'</ul></div>';
if($sv52) $total_server .= '<div class="server"><div class="label">SpeedyShare:</div> <ul class="episodelist">'.$sv52.'</ul></div>';
if($sv53) $total_server .= '<div class="server"><div class="label">OvFile.com:</div> <ul class="episodelist">'.$sv53.'</ul></div>';
if($sv54) $total_server .= '<div class="server"><div class="label">Videobb</div> <ul class="episodelist">'.$sv54.'</ul></div>';
if($sv55) $total_server .= '<div class="server"><div class="label">Wootly:</div> <ul class="episodelist">'.$sv55.'</ul></div>';
if($sv56) $total_server .= '<div class="server"><div class="label">Megafun:</div> <ul class="episodelist">'.$sv56.'</ul></div>';
if($sv57) $total_server .= '<div class="server"><div class="label">Share.vnn.vn:</div> <ul class="episodelist">'.$sv57.'</ul></div>';
if($sv58) $total_server .= '<div class="server"><div class="label">HotShare:</div> <ul class="episodelist">'.$sv58.'</ul></div>';
if($sv59) $total_server .= '<div class="server"><div class="label">Soha:</div> <ul class="episodelist">'.$sv59.'</ul></div>';
if($sv60) $total_server .= '<div class="server"><div class="label">VidBux:</div> <ul class="episodelist">'.$sv60.'</ul></div>';
if($sv61) $total_server .= '<div class="server"><div class="label">VideoWeed:</div> <ul class="episodelist">'.$sv61.'</ul></div>';
if($sv62) $total_server .= '<div class="server"><div class="label">LoomBo:</div> <ul class="episodelist">'.$sv62.'</ul></div>';
if($sv63) $total_server .= '<div class="server"><div class="label">Videozer:</div> <ul class="episodelist">'.$sv63.'</ul></div>';
if($sv64) $total_server .= '<div class="server"><div class="label">V1vn1</div> <ul class="episodelist">'.$sv64.'</ul></div>';
if($sv65) $total_server .= '<div class="server"><div class="label">V1vn2:</div> <ul class="episodelist">'.$sv65.'</ul></div>';
if($sv66) $total_server .= '<div class="server"><div class="label">V1vn3:</div> <ul class="episodelist">'.$sv66.'</ul></div>';
if($sv67) $total_server .= '<div class="server"><div class="label">V1vn4:</div> <ul class="episodelist">'.$sv67.'</ul></div>';
if($sv68) $total_server .= '<div class="server"><div class="label">Xixam:</div> <ul class="episodelist">'.$sv68.'</ul></div>';
if($sv69) $total_server .= '<div class="server"><div class="label">Xixam2:</div> <ul class="episodelist">'.$sv69.'</ul></div>';
if($sv70) $total_server .= '<div class="server"><div class="label">SeeOn:</div> <ul class="episodelist">'.$sv70.'</ul></div>';
if($sv71) $total_server .= '<div class="server"><div class="label">Twitvid:</div> <ul class="episodelist">'.$sv71.'</ul></div>';
if($sv72) $total_server .= '<div class="server"><div class="label">Rolo:</div> <ul class="episodelist">'.$sv72.'</ul></div>';
if($sv73) $total_server .= '<div class="server"><div class="label">Eyvx:</div> <ul class="episodelist">'.$sv73.'</ul></div>';
if($sv74) $total_server .= '<div class="server"><div class="label">Vzaar</div> <ul class="episodelist">'.$sv74.'</ul></div>';
if($sv75) $total_server .= '<div class="server"><div class="label">Playwire:</div> <ul class="episodelist">'.$sv75.'</ul></div>';
if($sv76) $total_server .= '<div class="server"><div class="label">Bcove:</div> <ul class="episodelist">'.$sv76.'</ul></div>';
if($sv77) $total_server .= '<div class="server"><div class="label">Sorenson:</div> <ul class="episodelist">'.$sv77.'</ul></div>';
if($sv78) $total_server .= '<div class="server"><div class="label">Ooyala:</div> <ul class="episodelist">'.$sv78.'</ul></div>';
if($sv79) $total_server .= '<div class="server"><div class="label">Govn:</div> <ul class="episodelist">'.$sv79.'</ul></div>';
if($sv80) $total_server .= '<div class="server"><div class="label">SuperShare:</div> <ul class="episodelist">'.$sv80.'</ul></div>';
if($sv81) $total_server .= '<div class="server"><div class="label">Blip:</div> <ul class="episodelist">'.$sv81.'</ul></div>';
if($sv82) $total_server .= '<div class="server"><div class="label">PutLoker:</div> <ul class="episodelist">'.$sv82.'</ul></div>';
if($sv83) $total_server .= '<div class="server"><div class="label">SockShare:</div> <ul class="episodelist">'.$sv83.'</ul></div>';
if($sv84) $total_server .= '<div class="server"><div class="label">Zalaa</div> <ul class="episodelist">'.$sv84.'</ul></div>';
if($sv85) $total_server .= '<div class="server"><div class="label">Phimsomot:</div> <ul class="episodelist">'.$sv85.'</ul></div>';
if($sv86) $total_server .= '<div class="server"><div class="label">V.I.P:</div> <ul class="episodelist">'.$sv86.'</ul></div>';
if($sv87) $total_server .= '<div class="server"><div class="label">Openfile:</div> <ul class="episodelist">'.$sv87.'</ul></div>';
if($sv88) $total_server .= '<div class="server"><div class="label">Docs:</div> <ul class="episodelist">'.$sv88.'</ul></div>';
if($sv89) $total_server .= '<div class="server"><div class="label">Picasa:</div> <ul class="episodelist">'.$sv89.'</ul></div>';
if($sv90) $total_server .= '<div class="server"><div class="label">BanBe:</div> <ul class="episodelist">'.$sv90.'</ul></div>';
if($sv91) $total_server .= '<div class="server"><div class="label">Tudou:</div> <ul class="episodelist">'.$sv91.'</ul></div>';
if($sv92) $total_server .= '<div class="server"><div class="label">PHimvang:</div> <ul class="episodelist">'.$sv92.'</ul></div>';
if($sv93) $total_server .= '<div class="server"><div class="label">DivXcabin:</div> <ul class="episodelist">'.$sv93.'</ul></div>';
if($sv94) $total_server .= '<div class="server"><div class="label">DivxStage</div> <ul class="episodelist">'.$sv94.'</ul></div>';
if($sv95) $total_server .= '<div class="server"><div class="label">XvidStage:</div> <ul class="episodelist">'.$sv95.'</ul></div>';
if($sv96) $total_server .= '<div class="server"><div class="label">Xvideos:</div> <ul class="episodelist">'.$sv96.'</ul></div>';
if($sv97) $total_server .= '<div class="server"><div class="label">ZingTV:</div> <ul class="episodelist">'.$sv97.'</ul></div>';
if($sv98) $total_server .= '<div class="server"><div class="label">Picasa:</div> <ul class="episodelist">'.$sv95.'</ul></div>';
if($sv99) $total_server .= '<div class="server"><div class="label">Picasa:</div> <ul class="episodelist">'.$sv95.'</ul></div>';
if($sv100) $total_server .= '<div class="server"><div class="label">Phim3S:</div> <ul class="episodelist">'.$sv100.'</ul></div>';
if($sv101) $total_server .= '<div class="server"><div class="label">ZingTV:</div> <ul class="episodelist">'.$sv101.'</ul></div>';
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
	global $wpdb,$post;
$list=$wpdb->get_results("SELECT episode_id, episode_name, episode_type, episode_url FROM wp_film_episode WHERE episode_film = '".$film_id."' order by episode_order limit 1 ");
foreach ( $list as $value ) 
{
$name=$value->episode_name;
$tap=$value->episode_id;
$permalink = get_bloginfo('url')."/xem-phim-".$post->post_name."-".$tap;

return $permalink;
}

}
function getlinkphim($idphim,$idtap,$sv=''){
global $wpdb,$idtap;
$episode_url = get_bloginfo('url')."/player-".$idtap."/";
$sub=get_post_meta($idphim, "phim_sub", true);
if($sub=="") $sub='http://phim6789.com/sub/sub.srt';
if(!$sv){
$fivesdrafts = $wpdb->get_row("SELECT episode_id, episode_name, episode_type, episode_url
	FROM wp_film_episode
	WHERE episode_film = '".$idphim."' AND episode_id='".$idtap."'");
if($fivesdrafts){
	$player=players($fivesdrafts->episode_url,$sub);
	}
}
else {
$ep_url="http://phim6789.com/".$idphim."-sv".$sv.".xml";
$player=players($ep_url,$sub);
}
return $player;

}

function get_name($idtap){
global $wpdb,$idphim;
$idtap=$idtap;

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
$idtap=$idtap;
$fivesdrafts = $wpdb->get_row("SELECT episode_id, episode_name, episode_type, episode_url
	FROM wp_film_episode
	WHERE episode_id='".$idtap."'");
$tap=$fivesdrafts->episode_name;
return $tap;
}
add_action('init', 'creat_film_taxonomies'); 
function creat_film_taxonomies() {
	register_taxonomy('danh-muc', array('post','trailer'), 
		array(
			'hierarchical'  =>  true,
			'labels' => array(
				'name' => 'Danh mục phim',
				'singual_name' => 'Danh mục phim',
				'add_new' => 'Thêm danh mục',
				'add_new_item' => 'Thêm danh mục',
				'new_item' => 'Danh mục mới',
				'search_item' => 'Tìm kiếm danh mục'
				),			
		)
	);
	register_taxonomy('quoc-gia', array('post','trailer'), 
		array(
			'hierarchical'  =>  true,
			'labels' => array(
				'name' => 'Quốc gia sản xuất',
				'singual_name' => 'Quốc gia sản xuất',
				'add_new' => 'Thêm quốc gia',
				'add_new_item' => 'Thêm quốc gia',
				'new_item' => 'Quốc gia mới',
				'search_item' => 'Tìm kiếm quốc gia',
'rewrite'             => array( 'slug' => 'quoc-gia' )
				),			
		)
	);
	register_taxonomy('nha-san-xuat', array('post','trailer'), 
		array(
			'hierarchical'  =>  false,
			'labels' => array(
				'name' => 'Nhà sản xuất',
				'singual_name' => 'Nhà sản xuất',
				'add_new' => 'Thêm Nhà sản xuất',
				'add_new_item' => 'Thêm Nhà sản xuất',
				'new_item' => 'Nhà sản xuất mới',
				'search_item' => 'Tìm kiếm Nhà sản xuất'
				),			
		)
	);
	register_taxonomy('dao-dien', 'post', 
		array(
			'hierarchical'  =>  false,
			'labels' => array(
				'name' => 'Đạo diễn',
				'singual_name' => 'Đạo diễn',
				'add_new' => 'Thêm đạo diễn',
				'add_new_item' => 'Thêm đạo diễn',
				'new_item' => 'Đạo diễn mới',
				'search_item' => 'Tìm kiếm đạo diễn',
'rewrite'             => array( 'slug' => 'dao-dien' )
				),			
		)
	);
	register_taxonomy('dien-vien', array('post','trailer'), 
		array(
			'hierarchical'  =>  false,
			'labels' => array(
				'name' => 'Diễn viên',
				'singual_name' => 'Diễn viên',
				'add_new' => 'Thêm Diễn viên',
				'add_new_item' => 'Thêm Diễn viên',
				'new_item' => 'Diễn viên mới',
				'search_item' => 'Tìm kiếm Diễn viên',
'rewrite'             => array( 'slug' => 'dien-vien' )
				),			
		)
	);
	register_taxonomy('nam-san-xuat', array('post','trailer'), 
		array(
			'hierarchical'  =>  false,
			'labels' => array(
				'name' => 'Năm sản xuất',
				'singual_name' => 'Năm sản xuất',
				'add_new' => 'Thêm Năm sản xuất',
				'add_new_item' => 'Thêm Năm sản xuất',
				'new_item' => 'Năm mới',
				'search_item' => 'Tìm kiếm Năm sản xuất'
				),			
		)
	);	
}

add_action('init', 'creat_tin_tuc_taxonomies'); 
function creat_tin_tuc_taxonomies() {
	register_taxonomy('danh-muc', array('tin-tuc','trailer'), 
		array(
			'hierarchical'  =>  true,
			'labels' => array(
				'name' => 'Danh mục Tin tức',
				'singual_name' => 'Danh mục Tin tức',
				'add_new' => 'Thêm danh mục',
				'add_new_item' => 'Thêm danh mục',
				'new_item' => 'Danh mục mới',
				'search_item' => 'Tìm kiếm danh mục'
				),			
		)
	);
}		
add_filter( 'query_vars', 'get_tap_phim' );
add_filter( 'query_vars', 'get_sv_phim' );
add_filter( 'query_vars', 'get_ten_phim' );
add_filter( 'query_vars', 'get_pagea' );
remove_action( 'wp_head', 'rel_canonical' );
add_action( 'wp_head', 'new_rel_canonical' );
function rewrite_permalink_post( $wp_rewrite ) {

		
    $wp_rewrite->rules = array(		
        'xem-phim-([^/]+)-([^/]+)?$' => 'index.php?name=$matches[1]&ep=$matches[2]',		
		'xem-phim-([^/]+)/sv([^/]+)?$' => 'index.php?name=$matches[1]&sv=$matches[2]',
		'xem-phim/([^/]+)/tap-([^/]+)\.([^/]+)?$' => 'index.php?name=$matches[1]&tap=$matches[2]&ep=$matches[3]',				
				
    ) + $wp_rewrite->rules;
}
add_action( 'generate_rewrite_rules', 'rewrite_permalink_post' );
function get_tap_phim( $public_query_vars ) {
		$public_query_vars[] = 'ep';
		return $public_query_vars;
	}
function get_sv_phim( $public_query_vars ) {
		$public_query_vars[] = 'sv';
		return $public_query_vars;
	}	
function get_pagea( $public_query_vars ) {
		$public_query_vars[] = 'paged';
		return $public_query_vars;
	}	
function get_ten_phim( $public_query_vars ) {
		$public_query_vars[] = 'tap';
		return $public_query_vars;
	}
	
function new_rel_canonical() {
echo '<link rel="canonical" href="http://'.$_SERVER["HTTP_HOST"].''.parse_url($_SERVER['REQUEST_URI'],PHP_URL_PATH).'" />';

  }
include('player.php');
include('meta.php');
?>