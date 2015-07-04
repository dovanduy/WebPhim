<script type="text/javascript">
function setActive(){aObj=document.getElementById("servers").getElementsByTagName("a");for(i=0;i<aObj.length;i++)0<=document.location.href.indexOf(aObj[i].href)&&(aObj[i].className="active")}window.onload=setActive;</script>	
<div id="content"><div id="page-watch" data-user-id="<?php echo get_current_user_id();?>" data-film-id="<?php echo $post->ID;?>"><div class="blocktitle breadcrumbs block">
<?php the_breadcrumb();?>
</div>
<div class="ad_location above_of_player">
</div>
<div class="blockbody">
<div id="movie"  class="block">
<div class="blockbody">
<h2>Xem phim: <?php the_title();?></h2>
<div id="media" >
<script type="text/javascript" src="<?php bloginfo('template_directory');?>/js/jwplayer.js"></script>	
<div id="mediaplayer"></div><?php echo getlinkphim($idphim,$idtap,$sv);?>
</div>
</div>
</div>
<div id="movie-info" class="block"><div class="blockbody"><div class="action"><div class="add-bookmark"><i></i>Thêm vào hộp </div><div class="like"><i></i><span><?php echo get_total_rating($post->ID,"like");?> Like</span></div><div class="remove-ad">Tắt quảng cáo</div><div title="Chức năng tự động chuyển tập tiếp theo khi xem hết 1 tập" class="auto-next">AutoNext: On</div><div class="resize-player">Phóng to</div><div class="turn-light"><i></i><span>Tắt đèn</span></div></div>
<!-- AddThis Button BEGIN -->
<div class="addthis_toolbox addthis_default_style" style="margin-top:10px;margin-bottom:10px;margin-left:150px;">
<a class="addthis_button_facebook_like">FB</a>
<a class="addthis_button_tweet"></a>
<a class="addthis_button_google_plusone"></a>
<a class="addthis_counter addthis_pill_style"></a>
</div>
<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-5112753846d6d791"></script>
<!-- AddThis Button END --><div class="fb-like-page" style="margin-left:234px;color:green;margin-bottom:15px;">Nhấn <a class="fb-like" data-href="https://www.facebook.com/pages/Phim6789Com/543956162308758" data-send="false" data-layout="button_count"></a> để tắt quảng cáo</div>
<a href="http://ltpvn.eazy.vn/viewtopic.php?f=10&amp;p=11#p11" target="_blank"><b><font size="4px" color="#9ACD32">Hướng dẫn xem phim các server yêu cầu cài đặt Java</font></b></a>
<a href="ymsgr:sendIM?netcafe_pro_kjk0_qp&m=Bạn Vui Lòng Coppy Link Lỗi Dán Vào Đây:">
<img border=0 src="http://opi.yahoo.com/online?u==baomataz1990&m=g&t=1">
</a>
<p>Tạm Dừng 30s Nếu Bị Giật Hoặc Có Thể Đổi Sang Server Khác Để Xem Tốt Hơn...!
-Báo Link Phim Lỗi Vào Yahoo Để Admin Cập Nhật Link Mới ( Chúc Các Bạn Xem Phim Vui Vẻ ) ...! </p>
<div class="note">
<?php the_excerpt(); ?> 
</div>
<?php echo episode_show($idphim);?>
<div class="box-download"><h3 style="border: 1px solid #CCCCCC;border-radius: 0 5px 5px 0;box-shadow: 0 0 5px #CCCCCC;font-size: 1.3em;font-weight: 100;padding: 10px;text-shadow: 0 0 4px #CCCCCC;">Download <?php the_title();?></h3><div class="down">

<?php echo get_post_meta($post->ID, "phim_download", true);?>
			</div></div>
</div></div>
<div id="comment" class="block"><div class="blocktitle"><div class="title">Bình luận</div><div data-target="#comment .tabs-content" class="tabs"><div data-name="fb-comment" class="tab active">Facebook</div><div data-name="phim6789-comment" class="tab phim6789">hayhay24.com</div></div></div><div class="blockbody tabs-content"><div class="tab fb-comment"><div class="fb-comments" data-href="<?php the_permalink();?>" data-num-posts="20" data-width="660" data-colorscheme="dark"></div></div><div class="tab phim6789-comment hide"><div class="comment-form"><div class="avatar"></div><form action="#" method="post"><div><textarea rows="1" cols="10" class="message" maxlength="500" data-minlength="10" style="resize: none; overflow: hidden; word-wrap: break-word; height: 45px;"></textarea></div><div><button class="submit" type="button">Bình luận</button><p class="warn">Bạn còn lại <span class="counter">500</span> ký tự</p></div></form></div><div class="comment-list"></div></div></div></div>
<div class="block" id="page-info" data-film-id="<?php echo $post->ID;?>">
<div class="blocktitle">
<div class="title">Phim liên quan</div>
<div class="blockbody">
    <?php
		$categories = get_the_category($post->ID);
		if ($categories) {
			$category_ids = array();
			foreach($categories as $individual_category) $category_ids[] = $individual_category->term_id;
		
			$args=array(
				'category__in' => $category_ids,
				'post__not_in' => array($post->ID),
'orderby' =>'rand',
				'showposts'=>8, // Number of related posts that will be shown.
				'caller_get_posts'=>1
			);
		$my_query = new wp_query($args);
		if( $my_query->have_posts() ) {
			echo '<ul id="list-movie-update" class="list-film">';
			while ($my_query->have_posts()) {
				$my_query->the_post();
			?>

	<li><div class="inner"><a title="<?php the_title();?>" href="<?php the_permalink();?>"><img alt="<?php the_title();?>" src="<?php img(146,195);?>"></a><div class="info"><div class="name"><h3><a title="<?php the_title();?>" href="<?php the_permalink();?>"><?php the_title();?></a></h3></div><div class="name2"><?php echo get_post_meta($post->ID, "phim_en", true);?></div><div class="stats"><span class="liked"><?php echo get_total_rating($post->ID,"like");?> like</span></div></div><div class="status"><?php echo get_post_meta($post->ID, "phim_tl", true);?></div></div>
	</li>
			<?php
			}
			echo '</ul>';
			wp_reset_query();
		}
	}
	?>
<div>
</div></div>
</div>
<div class="blockbody">
<div class="detail">
<div class="tags">
<?php the_tags('<strong>Tag :</strong>',', ',''); ?>
</div>
<div itemscope itemtype="http://schema.org/Movie">

<div itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating">
  <span itemprop="ratingValue">8</span>/<span itemprop="bestRating">10</span> stars from
  <span itemprop="ratingCount">200</span> users.
  Reviews: <span itemprop="reviewCount">50</span>.
</div>
</div>
</div>
<?php if(get_post_meta($post->ID, "phim_loai", true)=="phimbo"){
echo '<script type="text/javascript">				
phim6789.Watch.checkAndPlayEpisodeViewing();
</script>';
}?>
<script type="text/javascript">
phim6789.Watch.init('<?php echo $post->ID;?>');
</script>
</div></div>

</div></div></div>