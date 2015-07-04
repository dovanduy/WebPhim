<?php

function cut_str($str_cut,$str_c,$val)
{	
	$url=explode($str_cut,$str_c);
	$urlv=$url[$val];
	return $urlv;
}


function players($url,$sub=""){
global $wpdb;$type=acp_type($url);


//$url = get_link_total($url);
if($type==11 or $type==101) $player='<iframe width="100%" height="100%" src="'.$url.'" frameborder="0" allowfullscreen></iframe>';
elseif($type==96) $player='<iframe width="100%" height="100%" src="'.$url.'" frameborder="0" allowfullscreen></iframe>';
else
$player = '<object id="flashplayer" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="100%" height="100%">
<param name="movie" value="http://phim6789.com/player.swf?logo.file=http://phim6789.com/player/logo.png&logo.hide=false&logo.position=top-left&logo.margin=10" />
<param name="allowFullScreen" value="true" />
<param name="allowScriptAccess" value="always" />
<param name="FlashVars" value="plugins=http://phim6789.com/wp-content/plugins/movie/captions.swf,http://www.kenh88.com/plugins4/proxy.swf&proxy.link='.$url.'&skin=http://phim6789.com/player/skin/slim/slim/slim.xml&autostart=true&captions.color=#ffff80&captions.fontFamily=Tahoma, Geneva, sans-serif&captions.fontSize=16&captions.fontWeight=normal0" />
<embed name="flashplayer" src="http://phim6789.com/player.swf?logo.file=http://phim6789.com/player/logo.png&logo.hide=false&logo.position=top-left&logo.margin=10" FlashVars="plugins=http://phim6789.com/wp-content/plugins/movie/captions.swf,http://phim6789.com/plugins4/proxy.swf&proxy.link='.$url.'&skin=http://phim6789.com/player/skin/slim/slim/slim.xml&autostart=true&captions.color=#ffff80&captions.fontFamily=Tahoma, Geneva, sans-serif&captions.fontSize=16&captions.fontWeight=normal0" type="application/x-shockwave-flash" allowfullscreen="true" allowScriptAccess="always" width="100%" height="100%" />
</object>';	
//$player=base64_encode($player);
  //return '<script type="text/javascript">document.write(player.decode("'.$player.'"));</script>'; 
  return $player;
}
?>