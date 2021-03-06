<!DOCTYPE html>
<html dir="ltr" lang="vi-vn">

<head>
    <title>
        <?php global $page, $paged;wp_title( '|', true, 'right' );bloginfo( 'name' );$site_description = get_bloginfo( 'description', 'display' );if ( $site_description && ( is_home() || is_front_page() ) )echo " | $site_description";if ( $paged >= 2 || $page >= 2 )echo ' | ' . sprintf( __( 'Page %s', 'twentyten' ), max( $paged, $page ) );?>
    </title>
    <?php wp_head();?>
        <?php if(is_category()) { ?>
            <meta name="keywords" content="Xem phim <?php echo get_cat_name($cat);?>" />
            <?php } ?>
                <?php if(is_tag() ) { ?>
                    <meta name="description" content="Xem phim <?php single_tag_title(); ?> ,Phim <?php single_tag_title(); ?> HD,tuyen tap phim <?php single_tag_title(); ?>" />
                    <meta name="keywords" content="Xem phim <?php single_tag_title(); ?>, phim <?php single_tag_title(); ?> HD,phim <?php single_tag_title(); ?> chat luong cao " />
                    <?php } ?>
                        <link rel="alternate" type="application/rss+xml" title="<?php echo SITE_NAME; ?> RSS Feed" href="<?php bloginfo('rss2_url'); ?>" />
                        <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
                        <meta name="robots" content="index, follow" />
                        <meta name='revisit-after' content='1 days' />
                        <meta property="fb:app_id" content="490871234321238" />
                        <meta property="fb:admins" content="100002662099704" />
                        <link rel="stylesheet" type="text/css" href="<?php bloginfo('template_directory');?>/css/print.css" media="print">
                        <link rel="profile" href="http://gmpg.org/xfn/11" />
                        <link href="<?php bloginfo('template_directory');?>/css/style.css?v=13012013013637" type="text/css" rel="stylesheet">
                        <script src="<?php bloginfo('template_directory');?>/js/phim6789.js.php" type="text/javascript"></script>
                        <script src="<?php bloginfo('template_directory');?>/js/jquery.min.js" type="text/javascript"></script>
                        <script src="<?php bloginfo('template_directory');?>/js/light.js?v=13012013013637" type="text/javascript"></script>
                        <script src="<?php bloginfo('template_directory');?>/js/phim6789-4.0.0.js?v=13012013013637" type="text/javascript"></script>
</head>

<body>
    <div id="fb-root"></div>
    <script type="text/javascript">
        (function(d, s, id) {
            var js, fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id)) return;
            js = d.createElement(s);
            js.id = id;
            js.src = "//connect.facebook.net/vi_VN/all.js#xfbml=1&amp;appId=490871234321238";
            fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));
    </script>
    <div id="wrapper">
        <div id="header">
            <div class="container">
					<div class="header-logo">
						<a class="logo" href="<?php bloginfo( 'siteurl');?>" title="Xem phim mới chất lượng HD">
							<span>PHIMMOI.NET</span>
						</a>
					</div>
					<div id="search" class=""">
						<form method="get" id="cse-search-box" name="search" action="<?php bloginfo('siteurl');?>/tim-kiem-phim">
							<input type="hidden" name="cx" value="017120531741807955792:WMX-1055315542" />
							<input type="hidden" value="FORID:9" name="cof" />
							<input type="hidden" value="UTF-8" name="ie" />
							<input type="hidden" name="search_type" id="search_type" value="all" />
							<input type="text" name="q" placeholder="Từ khóa cần tìm..." class="keyword">
							<button type="submit" class="submit"></button>
						</form>
					</div>
					<div id="sign" class="">
						<?php if (is_user_logged_in()) { ?>
							<?php $userid=get_current_user_id();$user_info = get_userdata($userid);$q = $wpdb->get_results("SELECT box_id,box_phim FROM wp_boxfilm WHERE box_user = $userid ");?>
								<div class="logged">
									<div class="info">Xin chào, <a data-user-id="<?php echo $user_info->ID;?>" class="username"><?php echo $user_info->user_login;?></a></div>
									<div class="bookmark"><span>Hộp phim</span>
										<ul class="bookmarklist"></ul>
									</div>
									<div class="logout"><a rel="nofollow" href="<?php bloginfo('siteurl');?>/wp-login.php?action=logout" title="logout">Thoát</a></div>
								</div>
								<?php } else { ?>
									<div class="links"> <a rel="nofollow" href="<?php bloginfo('siteurl');?>/wp-login.php" class="simplemodal-login" title="Đăng nhập">Đăng nhập</a></div>
									<div class="links"><a rel="nofollow" href="<?php bloginfo('siteurl');?>/wp-login.php?action=register" class="simplemodal-register" title="Đăng ký">Đăng ký thành viên</a></div>
									<?php } ?>
					</div>
            </div>
        </div>
        <div id="nav">
            <?php $defaults = array('theme_location'  => 'top','menu'            => '', 'container'       => '', 'container_class' => '', 'container_id'    => '','menu_class'      => '', 'menu_id'         => '','echo'            => true,'fallback_cb'     => 'wp_page_menu','items_wrap'      => '<ul id="%1$s" class="container menu">%3$s</ul>','depth'           => 0,'walker'          => ''); ?>
                <?php wp_nav_menu( $defaults ); ?>
        </div>