<?php

/*
Template Name: AJAX
*/

?>

<?php
 
if($_POST['like_film_id']) {
if(!$_SESSION['like']){
$insert = $wpdb->query ( "UPDATE " . DATA_FILM_META . " 
									SET  
										film_like = film_like + 1
										, film_rating_total = film_rating_total + 1 
									WHERE film_id = '" . $_POST['like_film_id'] . "'" );
	if ($insert) {
	$_SESSION['like']=$insert->film_like;
	$like="Thích phim này thành công";
}
}
else $like="bạn đã đánh giá phim này";
$liked=get_total_rating($_POST['like_film_id'],'like');
	$member = array(
					'data'=>true,
					'film'=>$liked
                   );
                   
   //dung ham json_encode de chuyen mang $member thanh chuoi JSON
   echo json_encode($member);
   
   //ket thuc tra ve du lieu va stop khong cho chay tiep
   die;
}elseif($_POST['film_id']) {
if (is_user_logged_in()) {
$filmid=$_POST['film_id'];
$user_id = get_current_user_id();
	$check = $wpdb->get_row("SELECT box_phim FROM wp_boxfilm WHERE box_phim=$filmid AND box_user=$user_id");
	if(count($check)>0) { $message="Bạn đã add phim này rồi" ;
	}else {
	$wpdb->query("INSERT INTO wp_boxfilm VALUES (0,$filmid,$user_id)");
	$message="Add vào hộp phim thành công";
	}

}else{
$message="Bạn chưa đăng nhập";
}
	$member = array(
					'message' => $message
                   );
   echo json_encode($member);  
   die;
}elseif($_GET['episode_id']) {
$idtap=$_GET['episode_id'];
$idphim=$_GET['film_id'];
$tap=get_name($idtap);
if($idtap!=0){
	$member = array(
					'html' => getlinkphim($idphim,$idtap),
					'server_name'=>'vip',
					'episode_name'=>$tap
                   );
   echo json_encode($member);  
   die;
}
}elseif($_POST['userid']){
$userid=get_current_user_id();
$q = $wpdb->get_results("SELECT box_id,box_phim FROM wp_boxfilm WHERE box_user = $userid ");
if($q){
foreach ($q as $box) {
$html.='<li><strike data-film-id="'.$box->box_phim.'" onclick="phim6789.Member.removeBookmark('.$box->box_phim.'); return false;" >Xóa</strike><a href="'.get_permalink($box->box_phim).'" title="'.get_the_title($box->box_phim).'">'.get_the_title($box->box_phim).'</a></li>';
}
}
	$member = array(
					
					'json' => '<ul class="bookmarklist">'.$html.'</ul>'
                   );
    echo json_encode($member);

   
}elseif($_POST['remove_id']){
$userid=get_current_user_id();
$idfilm=$_POST['remove_id'];
$q=$wpdb->query( "DELETE FROM wp_boxfilm WHERE box_phim = $idfilm AND box_user=$userid");
echo 1;
}


	?>	