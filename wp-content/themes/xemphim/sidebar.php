<div id="sidebar">
<div class="ad_location above_of_content">
<div class="blockbody"><div style="display:block;">
  <?php include('ads/sidebar.php');?>
</div></div>
</div>
<div class="block" id="chart">
<div class="blocktitle">
<i class="icon top"></i><div class="title">Phim xem nhiều</div>
<div class="tabs" data-target="#topview">
<div class="tab active" data-name="topviewday">Ngày</div>
<div class="tab" data-name="topviewweek">Tuần</div>
<div class="tab" data-name="topviewmonth">Tháng</div>
</div></div>
<div class="blockbody" id="topview">
<ul class="tab topviewday">
<?php echo get_views_last("day",10);?>
</ul>
<ul class="tab topviewweek hide">
<?php echo get_views_last("week",10);?>
</ul>
<ul class="tab topviewmonth hide">
<?php echo get_views_last("month",10);?>
</ul></div></div><div class="divider"></div>
<?php if ( ! dynamic_sidebar( 'sidebar' ) ) : ?><?php endif;?>

</div>
</div>
</div>

