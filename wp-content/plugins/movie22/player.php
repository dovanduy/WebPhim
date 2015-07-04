<?php
function cut_str($str_cut,$str_c,$val)
{	
	$url=explode($str_cut,$str_c);
	$urlv=$url[$val];
	return $urlv;
}
function get_link_total($url) {
	global $wpdb;
	$link=$url;
	$mylink='http://top1vn.info/player/mediaplayer.swf?file=';
	$t="";
	if (preg_match("#thienduongviet.org/getlink/(.*?)#", $url)){
			$link=str_replace('http://top1vn.info/player/mediaplayer.swf?file=',$mylink,$url);
			$url=$link;
		}

   
    elseif (preg_match("#video.google.com/(.*?)#s",$url)) {
		$id = $url;
		$url=$id;
	}
	elseif (preg_match("#ooyala.com/online/(.*?)#s",$url)) {
		$id = cut_str('online/',$url,1);
		$link2=$id;
		$url=$link2;
	}
	elseif (preg_match("#go.vn/(.*?)#s",$url)) {
		$id = $url;
		$url=$id;
	}
	elseif (preg_match("#modovideo.com/(.*?)#s",$url)) {
		$id = $url;
		$url=$id;
	}
	elseif (preg_match("#movreel.com/(.*?)#s",$url)) {
		$id = $url;
		$url=$id;
	}
	elseif (preg_match("#openfile.ru/video/(.*?)#s",$url)) {
		$id = $url;
		$url=$id;
	}
	elseif (preg_match("#divxstage.eu/video/(.*?)#s",$url)) {
		$id = $url;
		$url=$id;
	}
	elseif (preg_match("#uploads.glumbo.com/(.*?)#s",$url)) {
		$id = $url;
		$url=$id;
	}
	elseif (preg_match("#sharefiles4u.com/(.*?)#s",$url)) {
		$id = $url;
		$url=$id;
	}
	elseif (preg_match("#banbe.net/(.*?)#s",$url)) {
		$id = $url;
		$url=$id;
	}
	elseif (preg_match("#xvidstage.com/(.*?)#s",$url)) {
		$id = $url;
		$url=$id;
	}
	elseif (preg_match("#hulkshare.com/(.*?)#s",$url)) {
		$id = $url;
		$url=$id;
	}
    elseif (preg_match('#veoh.com/(.*?)#s', $url, $id_sr)){
		$id = cut_str('watch/',$url,1);
		$link2=$id;
		$url=$link2;
    }
    else if (preg_match('#picasaweb.google.com/(.*?)#s', $url, $id_sr)){
		$id = $url;
		$url=$id;
	}
	elseif (preg_match("#http://v1vn.com/xem-phim-online/(.*?).html#s",$url, $id_sr)){
		$id = $url;
		$url=$id;
	}
	else if (preg_match('#2shared.com/file/(.*?)#s', $url, $id_sr)){
		$id = cut_str('file/',$url,1);
		$link2=$id;
		$url=$link2;
	}
	else if (preg_match('#2shared.com/video/(.*?)#s', $url, $id_sr)){
		$id = cut_str('video/',$url,1);
		$link2=$id;
		$url=$link2;
	}
	else if (preg_match('#4shared.com/file/(.*?)#s', $url, $id_sr)){
		$id = cut_str('file/',$url,1);
		$link2=$id;
		$url=$link2;
	}
		else if (preg_match('#4shared.com/video/(.*?)#s', $url, $id_sr)){
		$id = cut_str('video/',$url,1);
		$link2=$id;
		$url=$link2;
	}
		else if (preg_match('#4shared.com/embed/(.*?)#s', $url, $id_sr)){
		$id = cut_str('embed/',$url,1);
		$link2=$id;
		$url=$link2;
	}
	elseif (preg_match("#videoweed.es/file/([^/]+)#",$url,$id_sr)) {
		$id = cut_str('file/',$url,1);
		$link2='http://embed.videoweed.es/embed.php?v='.$id;
		$url=$link2;
	}
	elseif (preg_match("#videobb.com/video/([^/]+)#",$url,$id_sr)) {
		$id = cut_str('video/',$url,1);
		$link2='http://videobb.com/e/'.$id;
		$url=$link2;
	}
	elseif (preg_match("#videobb.com/e/([^/]+)#",$url,$id_sr)) {
		$id = cut_str('e/',$url,1);
		$link2='http://videobb.com/e/'.$id;
		$url=$link2;
	}
		else if (preg_match('#share.vnn.vn/dl.php/(.*?)#s', $url, $id_sr)){
		$id = $url;
		$url=$id;
	}
    else if (preg_match("#videozer.com/embed/(.*?)#s", $url, $id_sr)){
		$id = cut_str('embed/',$url,1);
		$link2='http://videozer.com/flash/'.$id;
		$url=$link2;
    }
    else if (preg_match('#videozer.com/video/(.*?)#s', $url, $id_sr)){
		$id = cut_str('video/',$url,1);
		$link2='http://videozer.com/flash/'.$id;
		$url=$link2;
    }
	elseif (preg_match('#clip.vn/watch/(.*?)#s',$url)) {
		$id = cut_str('/',$url,4);
		$link2 = 'http://clip.vn/watch/'.$id;
		$url=$link2;
	}
	elseif (preg_match('#clip.vn/w/(.*?)#s',$url)) {
		$id = cut_str('/',$url,4);
		$link2 = 'http://clip.vn/w/'.$id;
		$url=$link2;
	}
    elseif (preg_match("#dailymotion.com/video/(.*?)#s",$url,$id_sr)) {
		$id = cut_str('/',$url,4);		
		$link2='http://www.dailymotion.com/swf/'.$id;
		$url=$link2;
	}
   else if (preg_match("#youtube.com/watch\?v=(.*?)#s", $url,$id_sr)){
		$id=cut_str('=',$url,1);
		$link2='http://www.youtube.com/v/'.$id;
		$url=$link2;
	}
	else if (preg_match("#hayghe.com/xem-phim/(.*?)#s", $url,$id_sr)){
		$id = $url;
		$url=$id;
	}
	elseif (preg_match("#youtube.com/v/([^/]+)#",$url,$id_sr)) {

		$id = cut_str('v/',$url,1);
		$link2='http://www.youtube.com/watch?v='.$id;
		$url=$link2;

		

    }

	elseif (preg_match("#youtube.com/feeds/api/playlists/([^/]+)#",$url,$id_sr)) {
		$id = cut_str('playlists/',$url,1);
		$link2='http://gdata.youtube.com/feeds/api/playlists/'.$id;
		$url=$link2;
    }
	elseif (preg_match("#viddler.com/([^/]+)#",$url,$id_sr)) {
		$id = $url;
		$url=$id;
	}
	else if (preg_match("#video.zing.vn/([^/]+)#",$url,$id_sr)){
		$id = $url;
		$url=$id;
	}
	else if (preg_match("#video.zing.vn/video/clip/([^/]+)#",$url,$id_sr)){
		$id = $url;
		$url=$id;
	}
	else if (preg_match("#mp3.zing.vn/tv/media/([^/]+)#",$url,$id_sr)){
		$id = $url;
		$url=$id;
	}
	else if (preg_match("#nhaccuatui.com(.*?)#s", $url)){
			$kind=substr($url,31,1);
			$id = cut_str('=',$url,1);
			$link=$mylink.'/nct/'.$id.'.flv';
			if($kind=="M")
				$url='http://www.nhaccuatui.com/m/'.$id;
			else if($kind=="L")
				$url='http://www.nhaccuatui.com/L/'.$id;		
	}
	else if (preg_match("#video.timnhanh.com/xem-clip/([^/]+)#",$url,$id_sr)) {
		$id = $id_sr[1];
		$link='http://vn1.vtoday.net/vtimnhanh/'.$id.'.flv';
		$url=$web_link.'/'.'player.swf?autostart=true&file='.$link;
	}
	else if (preg_match("#http://video.yume.vn/xem-clip/([^/]+)#",$url,$id_sr)) {
		$id = $id_sr[1];
		$link='http://vn1.vtoday.net/vtimnhanh/'.$id.'.flv';
		$url=$web_link.'/'.'player.swf?autostart=true&file='.$link;
		
		}
		else if (preg_match("#http://video.yume.vn/video-clip/([^/]+)#",$url,$id_sr)) {
		$id = $id_sr[1];
		$link='http://vn1.vtoday.net/vtimnhanh/'.$id.'.flv';
		$url=$web_link.'/'.'player.swf?autostart=true&file='.$link;
		
		}
		
	else if (preg_match("#badongo.com/vid/(.*?)#s", $url,$id_sr)){
		$id=cut_str('/',$url,4);
		$link2='http://www.badongo.com/vid/'.$id.'';
		$url=$link2;
		
	}
	
	else if (preg_match("#sendspace.com/file/(.*?)#s", $url)){
		$id = cut_str('/',$link,4);
		$link2='http://www.sendspace.com/file/'.$id;
		$url=$link2;
	}
	else if (preg_match("#vidxden.com/(.*?)#s", $url)){
		$id = $url;
		$url=$id;
	}
	else if (preg_match("#movshare.net/video/(.*?)#s", $url)){
		$id = cut_str('/',$link,4);
		$link2='http://www.movshare.net/video/'.$id;
		$url=$link2;
	}
	elseif (preg_match("#twitvid.com/(.*?)#s",$url)) {
		$id = cut_str('/',$url,3);		
		$link2='http://www.twitvid.com/embed.php?guid='.$id;
		$url=$link2;
	}
	elseif (preg_match("#ovfile.com/(.*?)#s",$url)) {
		$id = cut_str('/',$url,3);		
		$link2='http://ovfile.com/'.$id;
		$url=$link2;
	}
	else if (preg_match("#novamov.com/video/(.*?)#s", $url)){
		$id = cut_str('/',$link,4);
		$link2='http://www.novamov.com/video/'.$id;
		$url=$link2;
	}
	elseif (preg_match("#zippyshare.com/v/([^/]+)#",$url,$id_sr)) {
		$id = $url;
		$url=$id;
	}
	elseif (preg_match("#cyworld.vn/([^/]+)#",$url,$id_sr)) {
		$id = $url;
		$url=$id;
	}
	elseif (preg_match("#megafun.vn/([^/]+)#",$url,$id_sr)) {
		$id = $url;
		$url=$id;
	}
	
	elseif (preg_match("#[0-9]/[0-9]+(.*?)#s",$url)) {
		$id = $url;
		$url=$id;
	}
	elseif (preg_match("#speedyshare.com/files/([^/]+)#",$url,$id_sr)) {
		$id = $url;
		$url=$id;
	}
	else if (preg_match("#mediafire.com/?(.*?)#s", $url)){
		$id = $url;
		$url=$id;
	}
	else if (preg_match("#video.tamtay.vn/(.*?)#s", $url)){
		$id = cut_str('/',$link,4);
		$link2='http://video.tamtay.vn/play/'.$id;
		$url=$link2;
	}
	elseif (preg_match("#video.rutube.ru/([^/]+)#",$url,$id_sr)) {
		$id = $url;
		$url=$id;
	}
	elseif (preg_match("#video.seeon.tv/([^/]+)#",$url,$id_sr)) {
		$id = $url;
		$url=$id;
	}
	elseif (preg_match("#zalaa.com/([^/]+)#",$url,$id_sr)) {
		$id = $url;
		$url=$id;
	}
	elseif (preg_match("#vidbux.com/([^/]+)#",$url,$id_sr)) {
		$id = $url;
		$url=$id;
	}
	elseif (preg_match("#eyvx.com/([^/]+)#",$url,$id_sr)) {
		$id = $url;
		$url=$id;
	}
	elseif (preg_match("#film.rolo.vn/([^/]+)#",$url,$id_sr)) {
		$id = $url;
		$url=$id;
	}
	

	if (($t=="") && (substr_count($url,$web_link.'/player.swf') != 0)) 
		//$url=$url.'&plugins=captions-1&captions.file='.$web_link.'/'.'captions.xml&captions.back=ffff&logo='.$web_link.'/logo.png';
		$url=$url.'&logo='.$web_link.'/logo.png';
	if ($type==0) $trave=$url;
	elseif ($type==1) $trave=$link;
return $trave;
}
function players($url){
global $wpdb;$type=acp_type($url);
$mahoa="aHR0cDovL3d3dy55b3V0dWJlLmNvbS92L1ZLc3F4b21SM0tv";
$url = get_link_total($url);	
	if ($type==1 || $type==2 || $type==3 || $type==9 || $type==10 || $type==12 ||$type==13 || $type==16 || $type==17 ||$type==18 || $type==19 || $type==20)  
	$player = "<embed width=\"100%\" height=\"500\" autostart=\"true\" allowscriptaccess=\"always\" allowfullscreen=\"true\" type=\"application/x-shockwave-flash\" flashvars=\"plugins=http://data.yophim.com/gk/plugins/proxy.swf&amp;proxy.link=$url&amp;autostart=true&amp;skin=http://s0.cohet.vn/gkp/plugin/stormtrooper.zip\" src=\"http://data.yophim.com/gk/player.swf\" name=\"flashplayer\"/>";

	elseif ($type==4) 
		$player = "<embed width=\"100%\" height=\"500\" allownetworking=\"internal\" autostart=\"true\" allowscriptaccess=\"always\" allowfullscreen=\"true\" type=\"application/x-shockwave-flash\" src=\"$url?modestbranding=1&amp;version=3&amp;hl=vi_VN&amp;rel=0&amp;autoplay=1&amp;showsearch=0&amp;iv_load_policy=3\"/>";
	elseif ($type==21) 
		$player = "<iframe src=\"http://phim.styledn.net/grab/grabv1vn.php?link=$url&amp;autostart=true&amp;allowfullscreen=true\"  width=\"100%\" height=\"500\" frameborder=\"0\"></iframe>";
	elseif ($type==22) 
		$player = "<embed src=\"http://phim.styledn.net/grab/grabhg.php?link=$url\" width=\"100%\" height=\"500\" allowfullscreen=\"true\" allowscriptaccess=\"always\" autostart=\"true\"/>";
	
	elseif($type==5)
		$player = "<embed width=\"100%\" height=\"100%\" wmode=\"transparent\" autostart=\"true\" allowscriptaccess=\"always\" allowfullscreen=\"true\" type=\"application/x-shockwave-flash\" flashvars=\"plugins=http://sv1.bay68.com/plugins/proxy.swf&amp;proxy.link=$url&autostart=true&skin=http://sv1.bay68.com/skin/skewd/skewd.xml\" src=\"http://sv1.bay68.com/player.swf\" name=\"flashplayer\">";
	elseif ($type==6) 
		$player = "<object type=\"application/x-shockwave-flash\" data=\"http://www.movshare.net/player/player.swf\" width=\"100%\" height=\"410\"><param name=\"allowfullscreen\" value=\"true\" /><param name=\"allowscriptaccess\" value=\"always\" /><param name=\"wmode\" value=\"transparent\" /><param name=\"flashvars\" value=\"plugins=http://anhtrang.org/images/grab.swf&anhtrang.org=$url&autostart=true&repeat=always\" /></object>";

	elseif ($type==7) 
		$player = "<center><b><font size=\"5\" color=\"red\"><a href=\"$url\" target=\"_blank\">Hãy nh?n vào dây d? xem phim</a></font></b></center>";
	
	elseif ($type==8) 
		$player = "<embed allowfullscreen=\"true\" allowscriptaccess=\"always\" height=\"410\" src=\"http://www.4shared.com/embed/$url\" type=\"application/x-shockwave-flash\" width=\"100%\"></embed>";					
	elseif ($type==11)
		$player = "<iframe width=\"100%\" height=\"410\" src=\"$url\" frameborder=\"0\" allowfullscreen></iframe>";
	elseif ($type==14)
		$player = "
		<script type='text/javascript' src='http://www.p1vn.com/player/jwplayer.js'></script>
			<div id='mediaplayer'></div>			
			<script type='text/javascript'>
			  jwplayer('mediaplayer').setup({
				'flashplayer': 'http://www.p1vn.com/player/player.swf?logo.file=http://www.p1vn.com/player/logo.png&logo.hide=false&logo.position=top-left&logo.margin=10&frontcolor=0x0000FF&lightcolor=0xFF0000',
				'id': 'playerID',
				'width': '565',
				'height': '400',
				'autostart': 'true',
				'repeat': 'always',
				'streamer': 'http://s21.ctl.vsolutions.vn/ctl/video/',
				'controlbar': 'bottom',
				'skin': 'http://www.p1vn.com/player/music.swf',
				'file': '$url',
				'plugins': {
				   'captions-2': {},
				},
				'captions.file':'http://www.p1vn.com/player/Intro.srt',
				'captions.color':'00CCFF',
				'captions.fontSize':'14',
				'captions.fontFamily':'Tahoma'
			  });
		</script>
		";
		
	elseif ($type==15)
		$player = "<object id='flashplayer' classid='clsid:D27CDB6E-AE6D-11cf-96B8-444553540000' width='100%' height='410'>
	<param name='movie' value='http://www.p1vn.com/player/player.swf' />
	<param name='allowFullScreen' value='true' />
	<param name='allowScriptAccess' value='always' />
	<param name='FlashVars' value='plugins=http://www.p1vn.com/player/plugins/proxy.swf&proxy.link=$url' />
	<embed name='flashplayer' src='http://www.p1vn.com/player/player.swf?logo.file=http://www.p1vn.com/player/logo.png&logo.hide=false&logo.position=top-left&logo.margin=10&skin=http://www.p1vn.com/player/skins/bekle.zip' FlashVars='plugins=captions,timeslidertooltipplugin-2,http://www.p1vn.com/player/plugins/proxy.swf&proxy.link=$url&captions.file=http://www.p1vn.com/player/Intro.srt&captions.color=#00CCFF&captions.fontFamily=Tahoma&amp;captions.fontSize=14&autostart=true&repeat=always' type='application/x-shockwave-flash' allowfullscreen='true' allowScriptAccess='always' width='100%' height='410' />
</object>";	
$player=base64_encode($player);
  return '<script type="text/javascript">document.write(Base64.decode("'.$player.'"));</script>'; 
  //return $player;
}
?>