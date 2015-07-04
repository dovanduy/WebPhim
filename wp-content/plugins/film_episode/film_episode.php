<?php
/*
Plugin Name: Phim - thêm tập phim
Plugin URI: http://alohot.net
Description: Liệt kê, chỉnh sửa các tập phim - theme alophim.
Author: nothingdng_88
Version: 1.0
Author URI: http://alohot.net
*/
$checkbox=plugins_url( 'admin.js' , __FILE__ );
add_action('admin_menu', 'FilmEpisodeAddOpc'); 
register_activation_hook( __FILE__, 'FilmEpisodeInstall' );
register_deactivation_hook (__FILE__, 'FilmEpisodeUnInstall');



function FilmEpisodeAddOpc(){   
      if (function_exists('add_options_page')) {
         add_menu_page('Tập phim', 'Tập phim', 2, basename(__FILE__), 'FilmEpisodeMenu');
      }
   }

function FilmEpisodeInstall(){
	global $wpdb;
	 
	require_once(ABSPATH . 'wp-admin/upgrade-functions.php');

	
	$sql = " CREATE TABLE ".DATA_FILM_EPISODE."(
				`episode_id` int(10) NOT NULL auto_increment,
				`episode_name` varchar(120) NOT NULL,
				`episode_film` int(5) NOT NULL,
				`episode_url` varchar(250) NOT NULL,
				`episode_server` int(11) NOT NULL,
				`episode_type` int(1) NOT NULL,
				`episode_broken` tinyint(1) NOT NULL,
				`episode_time` varchar(50) NOT NULL,
				`episode_order` int(11) NOT NULL,
				PRIMARY KEY ( `episode_id` ) ,
				INDEX ( `episode_id` )
			) ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_general_ci;";
	
	$wpdb->query($sql);

   }
   
function FilmEpisodeUnInstall(){
	global $wpdb;	
	 
	require_once(ABSPATH . 'wp-admin/upgrade-functions.php');

	$sql = "DROP TABLE ".DATA_FILM_EPISODE;
	
	$wpdb->query($sql);
   
}

function FilmEpisodeDeleteBD(){
	
	global $wpdb;	
	 
	require_once(ABSPATH . 'wp-admin/upgrade-functions.php');
	$sql = "DROP TABLE ".DATA_FILM_EPISODE;
	
	$wpdb->query($sql);
}

   
function FilmEpisodeMenu(){  
	global $wpdb;	
	$film_list = GetFilmToSelectBox();
	$server_list = GetServerToSelectBox();
	isset($_GET['acc']) ? $_acc=$_GET['acc']:$_acc="showEpisode";
	
	if($_POST['episode_name']!="" || $_POST['episode_info']!="" || $_POST['edit_multi_ep'] != ''){
		if($_POST['episode_id']!=""){
			if(FilmEpisodeUpdateEpisode($_POST['episode_id'],$_POST['episode_name'],$_POST['film_episode'],$_POST['episode_server'],$_POST['episode_url'],$_POST['episode_order'],time()))
				echo '<div id="message" class="updated fade" style="background-color: rgb(255, 251, 204);"><b><br/>Cập nhật thành công!</b><br/><br/></div>';

				$_acc="showEpisode";
		}elseif($_POST['edit_multi_ep'] == '1'){
			$arr = $_POST['chkName'];
			$in_sql = implode(',',$arr);
			$query = "UPDATE ".DATA_FILM_EPISODE." 
				set `episode_film` = '".mysql_real_escape_string($_POST['film_episode'])."' ,
					`episode_server` = '".mysql_real_escape_string($_POST['episode_server'])."' 
				where episode_id IN(".$in_sql.")";
		$wpdb->query($query);
		
		echo '<div id="message" class="updated fade" style="background-color: rgb(255, 251, 204);"><b><br/>Cập nhật thành công!</b><br/><br/></div>';
			
		} elseif ($_POST['multipostep']!="") {
			$episode_post = $_POST ['episode_info'];
			$list_episode = explode ( "\n", $episode_post );
			$count_ep = count ( $list_episode );
			
			for($i = 0; $i < $count_ep; $i ++) {
				$tap [$i] = explode ( '#', trim ( $list_episode [$i] ) );
				$ten_tap [$i] = trim ( $tap [$i] [0] );
				$link_tap [$i] = trim ( $tap [$i] [1] );
				$thu_tu [$i] = trim ( $tap [$i] [2] );
				if(FilmEpisodeNewEpisode($ten_tap [$i],$_POST['film_episode'],$_POST['episode_server'],$link_tap [$i],$thu_tu [$i],time())) {
					$tb .= $ten_tap [$i] . ' - ';
				}
				else 
					echo '<div id="message" class="error fade" style="background-color: rgb(218, 79, 33);"><br/><b>Lỗi ! tập '.$ten_tap [$i].' server '.$_POST['episode_server'].' đã tồn tại</b><br/><br/></div>';
			}
			echo '<div id="message" class="updated fade" style="background-color: rgb(255, 251, 204);"><b><br/>Các Tập '.$tb.' được thêm thành công !</b><br/><br/></div>';
			
			$_acc="addEpisode";
		}elseif($_POST['multipostep2']!=""){
		$meta_value=$_POST['episode_info'];	
		$data=explode('|',$meta_value);	
foreach($data as $value){	
		$value=explode(';',$value);
		$url=stripslashes($value[1]);
		$type=acp_type($value[1]);
		if(FilmEpisodeNewEpisode($value[0],$_POST['film_episode'],$_POST['episode_server'],$url,'',time())) {					
				}

	
}
		}
		else{
			if(FilmEpisodeNewEpisode($_POST['episode_name'],$_POST['film_episode'],$_POST['episode_server'],$_POST['episode_url'],$_POST['episode_order'],time()))
				echo '<div id="message" class="updated fade" style="background-color: rgb(255, 251, 204);"><b><br/>Thêm tập phim mới thành công!</b><br/><br/></div>';
			else 
				echo '<div id="message" class="error fade" style="background-color: rgb(218, 79, 33);"><br/><b>Lỗi ! Tập phim đã tồn tại</b><br/><br/></div>';
			
				$_acc="addEpisode";
		}
	}else{
		if($_GET['acc']=="del") {
			FilmEpisodeDeleteEpisode($_GET['episode_id']); 
			echo '<br><div id="message" class="updated fade" style="background-color: rgb(255, 251, 204);"> <br/>Xóa tập phim thành công!<br/><br/></div>';
			$_acc="showEpisode";
		}
		else if($_GET['acc']=="delBD"){
			//FilmEpisodeDeleteBD();
			echo '<br><div id="message" class="updated fade" style="background-color: rgb(255, 251, 204);"> <br/>Hiện tại không cho phép xóa DB Episode<br/><br/></div>';
			$_acc="dataBase";
		}
		else if($_GET['acc']=="fixed"){
			FilmEpisodeFixError($_GET['episode_id']);
			echo '<br><div id="message" class="updated fade" style="background-color: rgb(255, 251, 204);"> <br/>Sửa lỗi thành công<br/><br/></div>';
			echo '<meta http-equiv="Refresh" Content="1; url='.$PHP_SELF.'?page=film_episode.php&acc=showEpisode&orderBy=episode_broken&order=asc">';
			$_acc="showEpisode";
		}
	}  

echo '<div class="wrap">
		<h2>Danh sách tất cả các tập phim</h2>';		
echo'<ul class="subsubsub">	
		<li><a class="button" href="'.$PHP_SELF.'?page=film_episode.php&acc=showEpisode">Danh sách Episode</a></li>
		<li><a class="button" href="'.$PHP_SELF.'?page=film_episode.php&acc=addEpisode">Thêm Episode</a></li>
		<li><a class="button" href="'.$PHP_SELF.'?page=film_episode.php&acc=addMultiEpisode">Thêm nhiều Episode</a></li>
		<li><a class="button" href="'.$PHP_SELF.'?page=film_episode.php&acc=addMultiEpisode2">Thêm Episode</a></li>
		<li><a class="button" href="'.$PHP_SELF.'?page=film_episode.php&acc=dataBase">Database</a></li>
	</ul>	
	<br/>
	<br/>
	<hr/>
	<div>
	<div style="float:left; width:40%;"> <b>Tìm kiếm:</b><br/>
	<div>
	Nhập Ep ID:
		<form action="'.$PHP_SELF.'?page=film_episode.php&acc=search" method="post" name="sepid">
			<input name="epid" value=""/> <input name="epsubmit" value="Tìm" type="submit" class="button"/>
		</form><br/>
	Hoặc xem danh sách tập phim của: <br/>
		<form action="'.$PHP_SELF.'?page=film_episode.php&acc=search" method="post" name="svandfilm">
			Chọn phim: '.$film_list.' <br/>
			
			<input name="svsubmit" value="Tìm" type="submit" class="button"/>
		</form><br/>
	</div>
	<script>
	function deleteLink(episode_id){
		var opc = confirm("Bạn muốn xóa tập phim này ?");
		if (opc==true) window.location.href="'.$PHP_SELF.'?page=film_episode.php&acc=del&episode_id="+episode_id;
	}
	</script>
	<br/></div>
	<div style="float:left; width:55%">
	<b>Hướng dẫn post tập phim:</b><br/>
	Với mỗi server phim thì ID post (Episode URL) có dạng khác nhau (Phần chữ đỏ):<br/>
	
	<b>- Youtube:</b> Link dạng http://www.youtube.com/watch?v=<span style="color:#ff0000;">CbGxVcC3EZE</span><br/>
	<b>- Googlevideo:</b> http://video.google.com/googleplayer.swf?docId=<span style="color:#ff0000;">-5807528166426334307</span><br/>
	<b>- Go.vn:</b> http://content.go.vn/may-tinh/chi-tiet/<span style="color:#ff0000;">thu-gian-cuoi-tuan-hoi-xoay-dap-xoay-23072011-24229</span>.htm<br/>
	<b>- cyworld.vn:</b> http://kine.cyworld.vn/detail/12000984119/1725  -> <span style="color:#ff0000;">12000984119/post/1725</span><br/>
	<b>- 4shared.com</b> http://www.4shared.com/get/<span style="color:#ff0000;">Fjy78IUO</span>/Very_Funny_Cats_31-__.html hoặc lấy trong embed <span style="color:#ff0000;">436592759/9266a2e8</span> <br/>
	<b>- Dailymotion:</b> http://www.dailymotion.com/video/<span style="color:#ff0000;">x1bgid</span>_tom-jerry-jerry-and-the-lion_people<br/>
	<b>- Veoh:</b> http://www.veoh.com/watch/<span style="color:#ff0000;">v6434084aKzQBMKs</span><br/>
	<b>- Megavideo:</b> http://www.megavideo.com/v/<span style="color:#ff0000;">YAQ86O7K3a778f6f8355a1760698d05219ac9e78</span><br/>
	</div>
	<div style="clear:both"></div>
	</div>
	<hr/>
';  
if($_acc=="search"){
	if(isset($_POST['epid'])) {
		echo "Vui lòng chờ trong giây lát";
		echo '<meta http-equiv="Refresh" Content="1; url='.$PHP_SELF.'?page=film_episode.php&acc=edit&episode_id='.$_POST['epid'].'">';
	} elseif(isset($_POST['film_episode'])) {
		$film_list = GetFilmToSelectBox($_POST['film_episode']);
		$server_list = GetServerToSelectBox($_POST['episode_server']);
		echo' 
	 <h3>Các tập phim search</h3>
	 <script>
var fieldName="chkName[]";

function selectall(){
  var i=document.frm.elements.length;
  var e=document.frm.elements;
  var name=new Array();
  var value=new Array();
  var j=0;
  for(var k=0;k<i;k++)
  {
    if(document.frm.elements[k].name==fieldName)
    {
      if(document.frm.elements[k].checked==true){
        value[j]=document.frm.elements[k].value;
        j++;
      }
    }
  }
  checkSelect();
}
function selectCheck(obj)
{
 var i=document.frm.elements.length;
  for(var k=0;k<i;k++)
  {
    if(document.frm.elements[k].name==fieldName)
    {
      document.frm.elements[k].checked=obj;
    }
  }
  selectall();
}

function selectallMe()
{
  if(document.frm.allCheck.checked==true)
  {
   selectCheck(true);
  }
  else
  {
    selectCheck(false);
  }
}
function checkSelect()
{
 var i=document.frm.elements.length;
 var berror=true;
  for(var k=0;k<i;k++)
  {
    if(document.frm.elements[k].name==fieldName)
    {
      if(document.frm.elements[k].checked==false)
      {
        berror=false;
        break;
      }
    }
  }
  if(berror==false)
  {
    document.frm.allCheck.checked=false;
  }
  else
  {
    document.frm.allCheck.checked=true;
  }
}
</script>
	 <form name="frm" action="" method="post">
	 <input name="edit_multi_ep" value="1" type="hidden"/>
	 <table class="widefat">
		<thead>
			<tr>
				<th><input type="checkbox" name="allCheck" onClick="selectallMe();"> Chọn tất cả</th>
				<th scope="col">Tập phim (<a href="'.$PHP_SELF.'?page=film_episode.php&acc=showEpisode&film='.$_GET['film'].'&orderBy=episode_id&order=asc">Cũ</a>|<a href="'.$PHP_SELF.'?page=film_episode.php&acc=showEpisode&film='.$_GET['film'].'&orderBy=episode_id&order=desc">Mới</a>)</th>
				<th scope="col">Phim</th>
				<th scope="col">Server(<a href="'.$PHP_SELF.'?page=film_episode.php&acc=showEpisode&film='.$_GET['film'].'&orderBy=episode_server&order=asc">+</a>|<a href="'.$PHP_SELF.'?page=film_episode.php&acc=showEpisode&film='.$_GET['film'].'&orderBy=episode_server&order=desc">-</a>)</th>
				<th scope="col">URL</th>
				<th scope="col">Ngày đăng</th>
				<th scope="col">Thứ tự tập(<a href="'.$PHP_SELF.'?page=film_episode.php&acc=showEpisode&film='.$_GET['film'].'&orderBy=episode_order&order=asc">+</a>|<a href="'.$PHP_SELF.'?page=film_episode.php&acc=showEpisode&film='.$_GET['film'].'&orderBy=episode_order&order=desc">-</a>)</th>
				<th scope="col">Báo lỗi (<a href="'.$PHP_SELF.'?page=film_episode.php&acc=showEpisode&film='.$_GET['film'].'&orderBy=episode_broken&order=asc">Ko</a>|<a href="'.$PHP_SELF.'?page=film_episode.php&acc=showEpisode&film='.$_GET['film'].'&orderBy=episode_broken&order=desc">Lỗi</a>)</th>
				<th scope="col">Edit</th>
				<th scope="col">Delete</th>
			</tr>
		</thead>
				';
				if($_GET['orderBy']=="") $_GET['orderBy'] = "episode_id";
				if($_GET['order']=="") $_GET['order'] = "desc";
				$p = $_GET['p'];
				FilmEpisodeGetEpisode($_POST['film_episode'],$_POST['episode_server'],$_GET['orderBy'],$_GET['order'],$p,500);
				echo'
		</table>
		Chọn phim: '.$film_list.' <br/>
		
		<input name="editmultiep" value="Cập nhật" type="submit" class="button"/>
		</form>

</div>';
	}
}
elseif($_acc=="addEpisode"){
	
 if (FilmEpisodeInfoDB()==false) {
		echo "<br/><b>Dữ liệu và database tập phim đã bị xóa</b>! Bạn cần active lại plugin trước khi thêm tập phim mới.";
	  }
	  else{

echo '<h3>Thêm Episode</h3>
	<form method="post" action ="">
		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="row">
						<label for="default_post_edit_rows"> Episode name</label>
					</th>
					<td>
						<input type="text" name="episode_name" />
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						<label for="default_post_edit_rows"> Phim</label>
					</th>
					<td>
						'.$film_list.'
					</td>
				</tr>
				
				<tr valign="top">
					<th scope="row">
						<label for="default_post_edit_rows"> Episode Url</label>
					</th>
					<td>
						<input type="text" name="episode_url"/>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						<label for="default_post_edit_rows"> Episode order</label>
					</th>
					<td>
						<input type="text" name="episode_order"/>
					</td>
				</tr>
			</tbody>
		</table>
		<p class="submit"><input type="submit" name="FilmEpisode" value="Thêm tập phim" /></p>
	</form>';
	}
}	
elseif($_acc=="addMultiEpisode"){
	$film_list = GetFilmToSelectBox();
	$server_list = GetServerToSelectBox();
 if (FilmEpisodeInfoDB()==false) {
		echo "<br/><b>Dữ liệu và database tập phim đã bị xóa</b>! Bạn cần active lại plugin trước khi thêm tập phim mới.";
	  }
	  else{

echo '<h3>Thêm nhiều Episode</h3>
	Nhập tập bắt đầu:
	<input name="start" value="" id="ep_start"/><br/>
	Nhập tập kết thúc
	<input name="end" value="" id="ep_end"/><br/>
	<input type="submit" class="nhap_ep" value="nhập"/>
	<form method="post" action ="">
		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="row">
						<label for="default_post_edit_rows"> Episode info</label>
					</th>
					<td>
						<input type="hidden" name="multipostep" value="1"/>
						<textarea name="episode_info" style="width:500px; height:150px;" id="multi_ep"></textarea>
						<p>
							(Mỗi tên tập, link, thứ tự ưu tiên viết cách nhau bởi dấu thăng (#), mỗi link ở một dòng.)<br/>
							Ví dụ: <br/>
							Tập 1#link1#1<br/>
							Tập 2#link2#2
						</p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						<label for="default_post_edit_rows"> Phim</label>
					</th>
					<td>
						'.$film_list.'
					</td>
				</tr>
				
			</tbody>
		</table>
		<p class="submit"><input type="submit" name="FilmEpisode" value="Thêm tập phim" /></p>
	</form>
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
</script>
	';
	}
}
elseif($_acc=="addMultiEpisode2"){
	$film_list = GetFilmToSelectBox();
	$server_list = GetServerToSelectBox();
 if (FilmEpisodeInfoDB()==false) {
		echo "<br/><b>Dữ liệu và database tập phim đã bị xóa</b>! Bạn cần active lại plugin trước khi thêm tập phim mới.";
	  }
	  else{

echo '<h3>Thêm nhiều Episode kiểu blogspot</h3>
	<form method="post" action ="">
		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="row">
						<label for="default_post_edit_rows"> Episode info</label>
					</th>
					<td>
					<input type="hidden" name="multipostep2" value="1"/>
						<input type="text" name="episode_info" value="" style="width:400px"/>
						
						<p>
							(viết theo mẫu dưới)<br/>
							teentap1;linktap|tentap2;link2|....|tentapn;linkn
							Ví dụ: <br/>
							Tập 1#link1#1<br/>
							1;http://www.youtube.com/watch?v=kAd78piJ_WU|2;http://www.youtube.com/watch?v=tT_AvKl73qY|3;http://www.youtube.com/watch?v=HsqMhiSBDes
						</p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						<label for="default_post_edit_rows"> Phim</label>
					</th>
					<td>
						'.$film_list.'
					</td>
				</tr>
				
			</tbody>
		</table>
		<p class="submit"><input type="submit" name="FilmEpisode" value="Thêm tập phim" /></p>
	</form>
	';
	}
}
else if($_acc=="edit"){

 if (FilmEpisodeInfoDB()==false) {
		echo "<br/><b>Dữ liệu và database tập phim đã bị xóa</b>! Bạn cần active lại plugin trước khi thêm tập phim mới.";
	  }
	  else{	  
	  	  
	  $episode_id = $_GET['episode_id'];	
		$q = "select * from ".DATA_FILM_EPISODE." WHERE episode_id = '".$episode_id."'";
		$row = $wpdb->get_results($q);
		foreach($row as $r){
		  $episode_name = $r->episode_name;
		  $episode_film = $r->episode_film;
		  $episode_url  = $r->episode_url;
		  $episode_server = $r->episode_server;
		  $episode_time = $r->episode_time;
		  $episode_order = $r->episode_order;
		  $episode_broken = $r->episode_broken;
		  $film_list = GetFilmToSelectBox($episode_film);
	  	  $server_list = GetServerToSelectBox($episode_server);

echo '<h3>Sửa tập phim</h3>

	<fieldset>

	<form method="post" action ="">
		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="row">
						<label for="default_post_edit_rows"> Episode name</label>
					</th>
					<td>
						<input type="hidden" name="episode_id" value="'.$episode_id.'" />	
						<input type="text" value="'.$episode_name.'" name="episode_name" />
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						<label for="default_post_edit_rows"> Phim</label>
					</th>
					<td>
						'.$film_list.'
					</td>
				</tr>
				
				<tr valign="top">
					<th scope="row">
						<label for="default_post_edit_rows"> Episode Url</label>
					</th>
					<td>
						<input type="text" name="episode_url" value="'.$episode_url.'"/>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						<label for="default_post_edit_rows"> Episode order</label>
					</th>
					<td>
						<input type="text" name="episode_order" value="'.$episode_order.'"/>
					</td>
				</tr>
			</tbody>
		</table>

		<p class="submit"><input type="submit" name="FilmEpisode" value="Cập nhật" /></p>
	</form>
	</fieldset>';
	}
	}
	}
	
	else if($_acc=="showEpisode"){
	  
	   if (FilmEpisodeInfoDB()==false) {
		echo "<br/><b>Dữ liệu và database tập phim đã bị xóa</b>! Bạn cần active lại plugin trước khi thêm tập phim mới.";
	  }
	  else{
	  
	 echo' 
	 <h3>Các tập phim search</h3>
	 <script>
var fieldName="chkName[]";

function selectall(){
  var i=document.frm.elements.length;
  var e=document.frm.elements;
  var name=new Array();
  var value=new Array();
  var j=0;
  for(var k=0;k<i;k++)
  {
    if(document.frm.elements[k].name==fieldName)
    {
      if(document.frm.elements[k].checked==true){
        value[j]=document.frm.elements[k].value;
        j++;
      }
    }
  }
  checkSelect();
}
function selectCheck(obj)
{
 var i=document.frm.elements.length;
  for(var k=0;k<i;k++)
  {
    if(document.frm.elements[k].name==fieldName)
    {
      document.frm.elements[k].checked=obj;
    }
  }
  selectall();
}

function selectallMe()
{
  if(document.frm.allCheck.checked==true)
  {
   selectCheck(true);
  }
  else
  {
    selectCheck(false);
  }
}
function checkSelect()
{
 var i=document.frm.elements.length;
 var berror=true;
  for(var k=0;k<i;k++)
  {
    if(document.frm.elements[k].name==fieldName)
    {
      if(document.frm.elements[k].checked==false)
      {
        berror=false;
        break;
      }
    }
  }
  if(berror==false)
  {
    document.frm.allCheck.checked=false;
  }
  else
  {
    document.frm.allCheck.checked=true;
  }
}
</script>
	 <form name="frm" action="" method="post">
	 <input name="edit_multi_ep" value="1" type="hidden"/>
	 <table class="widefat">
		<thead>
			<tr>
				<th><input type="checkbox" name="allCheck" onClick="selectallMe();"> Chọn tất cả</th>
				<th scope="col">Tập phim (<a href="'.$PHP_SELF.'?page=film_episode.php&acc=showEpisode&film='.$_GET['film'].'&orderBy=episode_id&order=asc">Cũ</a>|<a href="'.$PHP_SELF.'?page=film_episode.php&acc=showEpisode&film='.$_GET['film'].'&orderBy=episode_id&order=desc">Mới</a>)</th>
				<th scope="col">Phim</th>
				<th scope="col">Server(<a href="'.$PHP_SELF.'?page=film_episode.php&acc=showEpisode&film='.$_GET['film'].'&orderBy=episode_server&order=asc">+</a>|<a href="'.$PHP_SELF.'?page=film_episode.php&acc=showEpisode&film='.$_GET['film'].'&orderBy=episode_server&order=desc">-</a>)</th>
				<th scope="col">URL</th>
				<th scope="col">Ngày đăng</th>
				<th scope="col">Thứ tự tập(<a href="'.$PHP_SELF.'?page=film_episode.php&acc=showEpisode&film='.$_GET['film'].'&orderBy=episode_order&order=asc">+</a>|<a href="'.$PHP_SELF.'?page=film_episode.php&acc=showEpisode&film='.$_GET['film'].'&orderBy=episode_order&order=desc">-</a>)</th>
				<th scope="col">Báo lỗi (<a href="'.$PHP_SELF.'?page=film_episode.php&acc=showEpisode&film='.$_GET['film'].'&orderBy=episode_broken&order=asc">Ko</a>|<a href="'.$PHP_SELF.'?page=film_episode.php&acc=showEpisode&film='.$_GET['film'].'&orderBy=episode_broken&order=desc">Lỗi</a>)</th>
				<th scope="col">Edit</th>
				<th scope="col">Delete</th>
			</tr>
		</thead>
				';
				if($_GET['orderBy']=="") $_GET['orderBy'] = "episode_id";
				if($_GET['order']=="") $_GET['order'] = "desc";
				$p = $_GET['p'];
				FilmEpisodeGetEpisode($_GET['film'],$_GET['server'],$_GET['orderBy'],$_GET['order'],$p,50);
				echo'
		</table>

</div>';
}
}
else if($_acc=="dataBase"){
	  
	  if (FilmEpisodeInfoDB()==false) {
		echo "<br/><b>Dữ liệu và database episode phim đã bị xóa</b>! Bạn cần active lại plugin trước khi thêm hoặc sửa tập phim mới.";
	  }
	  else{
		  echo '<h3>Dữ liệu</h3>
		  Bạn không được phép xóa toàn bộ dữ  liệu các tập phim. 
		  Nếu thấy thật sự cần thiết khi gỡ plugin này và muốn xóa bạn có thể <b><a href="'.$PHP_SELF.'?page=film_episode.php&acc=delBD">click here</a></b> để xóa toàn bộ và deactive plugin<br>Manhtienpt<br>';
	  }
   }
   
   }
   function FilmEpisodeGetEpisode($film_id, $svid='', $orderBy="episode_name",$order="asc",$p,$m_per_page){
   
		global $wpdb;
		$link = $PHP_SELF.'?page=film_episode.php&acc=showEpisode';
		if (!$p) $p = 1;
		echo '<tbody id="the-comment-list" class="list:comment">
			';

		if($film_id>0) $q_film = " AND episode_film = ".$film_id;
		else $q_film = "";
		if($svid>0) $q_sv = " AND episode_server = ".$svid;
		else $q_sv = "";
				$q = "select * from ".DATA_FILM_EPISODE." where episode_id > 0 ".$q_film.$q_sv." order by ".$orderBy." ".$order;
				$num = count($wpdb->get_results($q));
				$q .= " LIMIT ".(($p-1)*$m_per_page).",".$m_per_page;
				$row = $wpdb->get_results($q);
				foreach($row as $r){
					if($_GET['acc'] == 'search') {
						$check = "<input type='checkbox' class='checkbox' name='chkName[]' value='".$r->episode_id."' onClick='selectall()'/>";
					}
					else {
						$check = $r->episode_id;
					}
					if($r->episode_broken == 1) $sualoi = ' - <a href="'.$PHP_SELF.'?page=film_episode.php&acc=fixed&episode_id='.$r->episode_id.'">Đã fix lỗi ?</a>';
					else $sualoi = '';
					echo '<tr id="comment-1" class="">';
					echo "<td><input type='checkbox' class='checkbox' name='chkName[]' value='".$r->episode_id."' onClick='selectall()'/></td>";
					echo '<td>'; echo $check; echo'</td>';
					echo '<td>'; echo $r->episode_name; echo'</td>';
					echo '<td><a href="'.$PHP_SELF.'?page=film_episode.php&acc=showEpisode&film='.$r->episode_film.'">'; echo get_the_title($r->episode_film); echo'</a></td>';
					echo '<td>'; echo $r->episode_server; echo'</td>';
					echo '<td>'; echo $r->episode_url; echo'</td>';
					echo '<td>'; echo date('d-m-Y',$r->episode_time); echo'</td>';
					echo '<td>'; echo $r->episode_order; echo'</td>';
					echo '<td>'; echo $r->episode_broken; echo $sualoi.'</td>';
					echo '<td><a href="'.$PHP_SELF.'?page=film_episode.php&acc=edit&episode_id='.$r->episode_id.'">Edit</a></td>';
					echo '<td><a href="javascript:deleteLink('.$r->episode_id.');">Delete</a></td>';	
					echo '</tr>';
				}
				echo '
				<tr>
							<td colspan="9">
								<select name="selected_option">
								<option value="del">Xóa</option>
								</select>
							</td>
							
				</tr>
				<tr><td colspan="20"><input type="submit" name="do" class=submit value="Thực hiện"></td></tr></td>
				</tr>
				<tr>
							<td colspan="9">
								Có tất cả <b>'.$num.'</b> tập phim
								'.admin_pages($num,$m_per_page,$link,$p).'
							</td>
						</tr>';
				
		echo '</tbody>';
		if ($_POST['do']) {
	$arr = $_POST['chkName'];
	if (!count($arr)) die('Lỗi');
	if ($_POST['selected_option'] == 'del') {
		$in_sql = implode(',',$arr);
		mysql_query("DELETE FROM wp_film_episode WHERE episode_id IN (".$in_sql.")");
		header("Location: $PHP_SELF?page=film_episode.php");
	}
   }
		
   }

	
function FilmEpisodeNewEpisode($episode_name,$episode_film,$episode_type,$episode_url,$episode_order,$episode_time)
	{
		global $wpdb;
	if($episode_type=="")$type=acp_type($episode_url);else $type=$episode_type;

		$queryprev = "select `episode_name` from ".DATA_FILM_EPISODE." where `episode_name` = '$episode_name' AND episode_url='$episode_url' AND episode_film = '$episode_film' AND episode_server = '$episode_server'";
		$result = $wpdb->get_results($queryprev);

		if(count($result)>0) return false;
		
		$query = "INSERT INTO ".DATA_FILM_EPISODE." ( 
					`episode_name`
					, `episode_film`
					, `episode_server`
					, `episode_type`
					,`episode_url`
					,`episode_time`
					,`episode_order` ) 
				VALUES 
					('".mysql_real_escape_string($episode_name)."',
					'".mysql_real_escape_string($episode_film)."',
					'".mysql_real_escape_string($episode_type)."',
					'".mysql_real_escape_string($type)."',
					'".mysql_real_escape_string($episode_url)."',
					'".mysql_real_escape_string($episode_time)."',
					'".mysql_real_escape_string($episode_order)."'
				)";
		
		$wpdb->query($query);
		return true;
	}

		
	function FilmEpisodeUpdateEpisode($episode_id,$episode_name,$episode_film,$episode_server,$episode_url,$episode_order,$episode_time)
	{
		global $wpdb;
			$type=acp_type($episode_url);
		$query = "UPDATE ".DATA_FILM_EPISODE." 
				set `episode_name` = '".mysql_real_escape_string($episode_name)."',
	         		`episode_film` = '".mysql_real_escape_string($episode_film)."' ,
					`episode_server` = '".mysql_real_escape_string($episode_server)."',
					`episode_type` = '".mysql_real_escape_string($type)."',
					`episode_url` = '".mysql_real_escape_string($episode_url)."',
					`episode_time` = '".mysql_real_escape_string($episode_time)."',
					`episode_order` = '".mysql_real_escape_string($episode_order)."'  
				where episode_id ='".mysql_real_escape_string($episode_id)."' ";
		$wpdb->query($query);
		
		return true;
	}
	
function FilmEpisodeInfoDB(){
	global $wpdb;

				
		($wpdb->get_var("SHOW TABLES LIKE '".DATA_FILM_EPISODE."'") != "") ? $back= true : $back= false;
		return $back;

}

function FilmEpisodeDeleteEpisode($id){
	 global $wpdb;	 
	require_once(ABSPATH . 'wp-admin/upgrade-functions.php');

	$sql = "DELETE FROM ".DATA_FILM_EPISODE." where episode_id = $id;";
	
	$wpdb->query($sql);
	
}
function FilmEpisodeFixError($id) {
	global $wpdb;	 
	require_once(ABSPATH . 'wp-admin/upgrade-functions.php');

	$sql = "UPDATE " . DATA_FILM_EPISODE . " 
									SET  episode_broken = '0' 
									WHERE episode_id = '".$id."'";
	
	$wpdb->query($sql);
}
?>
