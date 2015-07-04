/**
 * @author: chiplove.9xpro
*/

if(window.top.location != window.location) {
	window.top.location = window.location;
}

var Phim3s = {};

$(document).ready(function (e) {
	Phim3s.init();
	
	if(new Date().getTime()%8 == 0) {
		$(document.body).append('');	
	}
});

Phim3s.init = function () {
	Phim3s.Core.registerMenuNavigation();
	Phim3s.Core.registerTabClick();
	Phim3s.Core.registerImageshackFix();
	
	Phim3s.Member.init();
	Phim3s.Search.init();
	
	Phim3s.Ad.registerAdFloat();
};

Phim3s.Ad = {
	registerAdFloat: function() {
		$(window).resize(Phim3s.Ad.adFloatCheck);
		$(window).ready(Phim3s.Ad.adFloatCheck);
	},
	adFloatCheck: function() {
		var windowWidth = $(window).width();
		var adWidth = 120;
		var posLeft	= (windowWidth - 990)/2 - 130 - 3;
		var posRight = (windowWidth - 1000)/2 - adWidth + 1;
		var isIE6 = /msie|MSIE 6/.test(navigator.userAgent);
		if(windowWidth < 1000){
			$("#ad_left, #ad_right").hide();
		} else {
			$("#ad_left, #ad_right").show();
			$("#ad_left").css({ top: 5, left: posLeft, position: (isIE6 ? "absolute" : "fixed"), top : 2 });
			$("#ad_right").css({ top: 5, right: posRight, position: (isIE6 ? "absolute" : "fixed"), top : 2 });
		}
	}
};


Phim3s.Core = {
	registerImageshackFix: function() {
		return;
		$('#body-wrap img').each(function(index, element) {
			var src = $(this).attr('src');
			var pattern = /^http:\/\/[a-z0-9]+\.imageshack\.us\/img(\d+)\/.*\/(.+)/gm;
			var pattern2 = /^http:\/\/desmond\.imageshack\.us\/Himg(\d+)\/scaled\.php\?server=[\d]+&filename=(.*)&res=landing$/;
			if(pattern.test(src) || pattern2.test(src)) {
				src = src.replace(pattern, 'http://desmond.imageshack.us/Himg$1/scaled.php?server=$1&filename=$2&res=landing');
				src = 'http://www.gmodules.com/gadgets/proxy?refresh=86400&container=ig&url=' + encodeURIComponent(src);
				$(this).attr('src', src);
			}
		});
	},
	registerMenuNavigation: function () {
		$('ul.menu').find('li').each(function () {
			$(this).hover(function (e) {
				$(this).addClass('active');
				var $sub = $(this).children('ul:eq(0)');
				if (typeof $sub.queue() != 'undefined' && $sub.queue() <= 1) {
					$sub.slideDown(150).addClass('show');
				}
			}, function () {
				$(this).removeClass('active');
				var $sub = $(this).children('ul:eq(0)');
				$sub.slideUp(100).removeClass('show');
			});
		});
	},
	registerTabClick: function () {
		$('.tabs .tab').click(function (e) {
			var $tabs = $(this).parent();
			var name = $(this).data('name');
			var $target = $($tabs.data('target'));
	
			$tabs.find('.tab').removeClass('active');
			$(this).addClass('active');
			$target.find('.tab').hide();
			$target.find('.' + name).show();
		});
	},
	// captcha
	registerCaptchaClick: function(selector) {
		$(selector || 'img.captcha-image').each(function(index, element) {
			$(this).click(function() {
				Phim3s.Core.changeCaptchaImage(this);
			})
			.css('cursor', 'pointer');
			
			if(!$(this).attr('title')) {
				$(this).attr('title', 'Click vÄ‚ o hÄ‚Â¬nh Ă„â€˜Ă¡Â»Æ’ Ă„â€˜Ă¡Â»â€¢i mÄ‚Â£ mĂ¡Â»â€ºi');
			}
		});
	},
	changeCaptchaImage: function(selector) {
		var $image = $(selector || 'img.captcha-image')
		var src = $image.attr('src');
		$image.attr('src', src.replace(/\?.*/, '') + '?'  + Math.random());
		
	}
};
/******** HOME ********/
Phim3s.Home = {
	init: function() {
		$(document).ready(function(e) {
            Phim3s.Home.registerSlideshow();
			Phim3s.Home.registerMovieUpdateTabClick();
        });	
	},
	registerSlideshow: function() {
		if($('#movie-hot li').length) {
			$('#movie-hot').jCarouselLite({
				btnNext: '#movie-hot .next',
				btnPrev: '#movie-hot .prev',
				visible:4,
				scroll: 2,
				auto: 2000,
				speed: 3000,
			});
		}
	},
	registerMovieUpdateTabClick: function () {
		$('#movie-update .type .btn').click(function (e) {
			var $tabs = $(this).parents('.types');
			var name = $(this).data('name');
			var $target = $($tabs.data('target'));
	
			$tabs.find('.btn').removeClass('active');
			$(this).addClass('active');
			$target.find('.tab').hide();
			$target.find('.' + name).show();
	
			return false;
		});
	}
};

/********* SEARCH *************/
Phim3s.Search = {
	init: function() {
		$(document).ready(function(e) {
			Phim3s.Search.AutoComplete.init($('#search .keyword'));
			Phim3s.Search.registerSubmitEvent();
		});
	},
	registerSubmitEvent: function() {
		var $search = $('#search');
		$('form', $search).submit(function(e) {
			var keyword = $('.keyword', $search).val();
			keyword = $.trim(keyword.replace(/\s+/gi, '-'));
			if(keyword != '') {
				window.location = '/search/' + keyword + '/';
			}
			return false;
		});
	}
};

Phim3s.Search.AutoComplete = function($element) {
	this.$input = $element;
	var options = {
		multiple: false,
		minLength: 4,
		queryKey: 'keyword',
		extraParams: {}
	};
	this.url = 'http://phim6789.com/ajax/'
	this.multiple = options.multiple;
	this.minLength = options.minLength;
	this.queryKey = options.queryKey;
	this.extraParams = options.extraParams;
	
	this.$results = null;
	this.selectedValue = 0;
	this.resultVisible = false;
	this.timer = null;
	this.setup();	
};
Phim3s.Search.AutoComplete.prototype = $.extend(true, {}, Light.AutoComplete.prototype);
Phim3s.Search.AutoComplete.prototype.showResults = function(results) {
	var results = $.parseJSON(results).json || {};
	if(!this.$results) {
		this.$results = $('<ul>')
			.css('z-index', 100)
			.addClass('autocomplete-list')
			.appendTo($('#search'));
	}
	
	this.hideResults();
	var counter = 0;
	for(var key in results) {
		var $a = $('<a />')
			.attr('href', results[key].link)
			.attr('title', results[key].title + ' - ' + results[key].title_o)
			.html(results[key].title.replace(new RegExp('('+this.val()+')', 'ig'), "<strong>$1</strong>"));
		$('<li />')
			.css('cursor', 'pointer')
			.data('autocomplete-id', counter++)
			.hover($.context(this, 'resultHover'))
			.append($a)
			.appendTo(this.$results);
	}
	if(counter) {
		Light.Overlay.create({
			onClickCallback: $.context(this, 'hideResults')
		});
		this.resultVisible = true;
		this.$results.show();
		this.resultHover();
	}				
};
Phim3s.Search.AutoComplete.prototype.keyup = function(e) {	
	switch(e.keyCode) {
		case 27: // esc
			return this.hideResults();
	}
	if(this.val() == '') {
		this.hideResults();
		return;
	}
	if(this.timer) {
		clearTimeout(this.timer);
	}
	this.timer = setTimeout($.context(this, 'load'), 250);	
};

Phim3s.Search.AutoComplete.init = function($element) {
	new Phim3s.Search.AutoComplete($element);
};

/******** WATCH ********/
Phim3s.Watch = {
	init: function (filmId) {
		$(document).ready(function (e) {
			Phim3s.Watch.$action = $('#page-watch .action');
	
			Phim3s.Watch.registerAddBookmark();
			Phim3s.Watch.registerLikeClick();
			Phim3s.Watch.registerRemoveAdClick();
			Phim3s.Watch.registerAutoNextEvent();
			Phim3s.Watch.registerResizePlayerClick();
			
			Phim3s.Watch.registerTurnLightClick();
			Phim3s.Watch.registerEpisodeClick();
			Phim3s.Watch.registerHistoryPopstate();
			Phim3s.Comment.init(filmId);	
			
		});
		Phim3s.Watch.registerFacebookEvent();
	},
	canRemoveAd: false,
	removeAd: function(){
		var $btn = $('.remove-ad', Phim3s.Watch.$action);
		$('.ad_location').html('');
		$($btn).fadeOut();
		// set 
		$('#page-watch').data('hide-ad', true);
		$movie = $('#movie');
		if($movie.css('width') == '980px') {
			$movie.css('height', 566);
		}
		if($movie.css('width') == '680px') {
			$movie.css('height', 450);
		}
		Light.scrollTop($movie);
	},
	registerRemoveAdClick: function () {
		$('.remove-ad', Phim3s.Watch.$action).click(function (e) {
			//if(Phim3s.Watch.canRemoveAd) {
				Phim3s.Watch.removeAd();
			//}
			//else {
				//$('.fb-like-page').show();
				//FB.XFBML.parse();
				//alert('Vui lĂ²ng nháº¥n nĂºt Like (ThĂ­ch) bĂªn dÆ°á»›i Ä‘á»ƒ táº¯t quáº£ng cĂ¡o');
			//}
		});
	},
	precheckIsFanOfPage: function(userId) {
		var query = FB.Data.query("SELECT uid FROM page_fan WHERE page_id = 184350835012938 and uid="+userId);
		query.wait(function(rows) {
			if(rows.length > 0) {
				Phim3s.Watch.canRemoveAd = (rows[0].uid == userId);
				if(!Phim3s.Watch.canRemoveAd) {
					//$('.fb-like-page').show();
				}
			}
			//$('.remove-ad').show();
		});
	},
	registerFacebookEvent: function() {
		//$('.fb-like-page').hide();
		//$('.remove-ad').hide();
		$(document).ready(function(e) {
			var filmId = $('#page-watch').data('film-id');
			if($.cookie('f'+filmId)) {
				$('#page-watch .share-fb').remove();
			}
		});
		window.fbAsyncInit = function() {
			FB.init({
			  appId      : '503415706383554',
			  channelUrl : "http://phim6789.com", 
			  status     : true, 
			  cookie     : true,
			  xfbml      : true  
			});
			FB.getLoginStatus(function(response) {
				if (response.status === 'connected') {
					Phim3s.Watch.precheckIsFanOfPage(response.authResponse.userID);
				}
				
			});
			// like clicked
			FB.Event.subscribe('edge.create', function(response) {
				var filmId = $('#page-watch').data('film-id');
				Phim3s.Watch.like(filmId);
				//
				Phim3s.Watch.canRemoveAd = true;
				$('.fb-like-page').hide();
				Phim3s.Watch.removeAd();
			});
			 /* Noel event */
			$('#page-watch .share-fb').click(function(){
				var filmId = $('#page-watch').data('film-id');
				$.cookie(filmId, new Date().getTime(), {path: '/'});
				$.cookie('f'+filmId, 1, {path: '/'});
				$.get('ajax/film/loadform', {film_id: filmId});
				var images = [
					'http://i.imgur.com/K608e.png', 
					'http://i.imgur.com/pRiXR.jpg',
					'http://i.imgur.com/O7Qkv.jpg',
					'http://i.imgur.com/LHjZ9.jpg',
					'http://i.imgur.com/PYxUs.jpg'
				];
				var rand = Math.floor(Math.random()*images.length);
				var image = images[rand];
				var obj = {
					method: 'feed',
					link: filmUrl + '?fbevent=' + new Date().getTime(),
					caption: 'Sá»± kiá»‡n Noel nháº­n quĂ  tá»« Phim3s.Net',
					description: 'CĂ¹ng xem phim vĂ  nháº­n quĂ  tá»« Phim3s.Net.Tá»•ng giáº£i thÆ°á»Ÿng 4.500.000 VNÄ. Äáº¿n háº¿t ngĂ y 24/12/2012.'
				};
				if(rand%3) {
					obj.name = '[Noel] Xem phim vĂ  nháº­n quĂ  tá»« Phim3s.Net. Tá»•ng giáº£i thÆ°á»Ÿng 4.500.000 vnd';
				}
				if(new Date().getTime()%4) {
					obj.picture = image;
				}
				
				FB.ui(obj, function(response) {
					if(response){
						Light.ajax({
							url: 'ajax/film/sharefb/',
							type: 'POST',
							cache: true,
							data: {
								'film_id': filmId,
								'post_id': response['post_id'],
								'cache': new Date().getTime()
							},
							success: function (data) {
								
							}
						});
					}	
				});
				$(this).remove();
			});	
			// end noel event
		};		
	},

	registerEpisodeClick: function () {
		$('.serverlist .episodelist a').click(function (e) {
			Phim3s.Watch.loadEpisode($(this));
			return false;
		});
	},
	loadEpisode: function ($episode) {
		var type = $episode.data('type');
		var episodeId = $episode.data('episode-id');
		var href = $episode.attr('href');
		var $serverlist = $episode.parents('.serverlist');
		var filmId = $('#page-watch').data('film-id');
		var eptap = $episode.data('data-episode-tap');
		if (type == 'download' || $('#media').length == 0) {
			window.location = href;
		} else {
			var s = $.cookie('episode-time');
			if(s == null) {
				date = new Date(new Date().getTime() + 60*5*1000);
				$.cookie('episode-time', date.getTime(), {path: '/', expires: date});
				s = date.getTime();
			}
			Light.ajax({
				url: 'http://phim6789.com/ajax/',
				type: 'GET',
				cache: true,
				data: {
					'episode_id': episodeId,
					'film_id': filmId,'name': eptap,
					'episode-time': s
				},
				success: function (data) {
					if(typeof history.pushState == 'function') {
						history.pushState({url: href, name: $episode.text()}, $episode.attr('title'), href);
					}
					$serverlist.find('a').removeClass('active');
					$episode.addClass('active');
					
					$('#mediaplayer').html(data.html);
					$('.breadcrumbs .last-child').text('Tập ' + data.episode_name);
					Light.scrollTop('#media');	
					// save
					var filmId = $('#page-watch').data('film-id');
					Phim3s.Watch.saveCurrentEpisode(filmId, episodeId);
					Phim3s.Watch.fixReiszePlayer();
				},
				error: function() {
					window.location = href;
				}
			});
		}
	},
	checkAndPlayEpisodeViewing: function() {
		var filmId = $('#page-watch').data('film-id');
		var data = {};
		try {
			data = $.parseJSON($.cookie('viewing')) || {};
		} catch(e) {}
		//
		if(typeof data[filmId] != 'undefined' && $.isNumeric(data[filmId])) {
			$('.serverlist .episodelist a').each(function() {
                if($(this).data('episode-id') == data[filmId]) {
					Phim3s.Watch.loadEpisode($(this));
				}
            });
		}
	},
	saveCurrentEpisode: function(filmId, episodeId) {
		var data = [];
		try {
			data = $.parseJSON($.cookie('viewing')) || {};
		} catch(e) {}
		data[filmId] = episodeId;
		
		var tmp = [];
		for(key in data) {
			tmp.push('"' + key + '": ' +  data[key]);
		}
		$.cookie('viewing', '{' + tmp.join(',') + '}', {expires : 30, path : '/'});
	},
	registerAddBookmark: function () {
		$('.add-bookmark', Phim3s.Watch.$action).click(function (e) {
			var filmId = $('#page-watch').data('film-id');
			userid= $('#page-watch').data('user-id');
			Phim3s.Watch.addBookmark(filmId,userid);
		});
	},
	addBookmark: function (filmId,userid) {
		Light.ajax({
			url: 'http://phim6789.com/ajax/',
			type: 'POST',
			data: {
				film_id: filmId,userid:userid,
			},
			success: function(data) {
				alert(data.message);
			}
		});
	},
	registerLikeClick: function () {
		$('.like', Phim3s.Watch.$action).click(function (e) {
			var filmId = $('#page-watch').data('film-id');
			Phim3s.Watch.like(filmId);
		});
	},
	like: function (filmId) {
		var $like = $('.like', Phim3s.Watch.$action);
		Light.ajax({
			url: 'http://phim6789.com/ajax/',
			type: 'POST',
			data: {
				"like_film_id": filmId	
			},	
			success: function(data) {
				if(!data.error) {
					$like.unbind('click').addClass('disabled');
					$like.find('span').text(data.film + ' liked');
				}
			}
		});
	},
	registerAutoNextEvent: function () {
		var $autoNext = $('.auto-next', Phim3s.Watch.$action);
		$autoNext.click(function (e) {
			if(Phim3s.Watch.getAutoNextState()) {
				$.cookie('autonext', 0, {path: '/', expires: 30});
				$(this).text('AutoNext: Off');
			} else {
				$.cookie('autonext', 1, {path: '/', expires: 30});
				$(this).text('AutoNext: On');
			}
		}).ready(function(e) {
           $autoNext.text('AutoNext: ' + ( Phim3s.Watch.getAutoNextState() ? 'On' : 'Off'));
		});
	},
	getAutoNextState: function() {
		return $.inArray($.cookie('autonext'), [null, '1']) != -1; // true if is on
	},
	autoNextExecute: function() {	
		var $curentEpisode = $('.serverlist a.active');
		var $episodelist = $($curentEpisode).parent().parent();
		var partCount = $episodelist.find('a').length;
		
		if (partCount > 1 && Phim3s.Watch.getAutoNextState()) {
			var $nextEpisode = $curentEpisode.parent().next().find('a');
			if ($nextEpisode.length > 0) {
				Phim3s.Watch.loadEpisode($nextEpisode);
			}
		}
	},
	registerResizePlayerClick: function() {
		$('.resize-player', Phim3s.Watch.$action).click(function(e) {
            Phim3s.Watch.resizePlayer();
		});
	},
	fixReiszePlayer: function() {
		var $media = $('#media');
		var $mediaObject = $('#mediaplayer');
		var $embed = $('embed', $mediaObject);
		
		$mediaObject
			.attr('width', $media.width())
			.attr('height', $media.height());
		
		$embed
			.attr('width', $media.width())
			.attr('height', $media.height());
		
	},
	resizePlayer: function() {
		var $movie = $('#movie');
		var $movieInfo = $('#movie-info');
		var $media = $('#media');
		var isNormal = $movie.width() != 980;
		Light.scrollTop($media);
		var adHeight = $('.ad_location.above_of_player').height();
		if(isNormal) {
			$('.resize-player').text('Thu nhỏ');
			$movie.animate({
				position: 'absolute',
				left: 0,
				zIndex: 100,
				width: 980,
				height:552
			}, 100);
			$media.animate({
				height: 530,
				width:970
			}, 100, null, Phim3s.Watch.fixReiszePlayer);
			$('#sidebar').animate({
				marginTop: 550 + 45 + adHeight
			}, 50);
			
		} else {
			$('.resize-player').text('Phóng to');
			$movie.animate({
				width: 680,
				height:420 // ad height
			}, 100);
			$media.animate({
				height: 400,
				width:670
			}, 100, Phim3s.Watch.fixReiszePlayer);
			$('#sidebar').animate({
				marginTop: 0	
			}, 50);
		}		
	},
	registerTurnLightClick: function () {
		$('.turn-light', Phim3s.Watch.$action).click(function (e) {
			if (typeof $('#media').data('light') == 'undefined' || $('#media').data('light')) {
				Phim3s.Watch.lightOff();
			} else {
				Phim3s.Watch.lightOn();
			}
		});
	},
	lightOff: function () {
		$('#media').data('light', false);
		Light.Overlay.create({
			background: '#000',
			opacity: 0.98,
			useEffect: true,
			onClickCallback: Phim3s.Watch.lightOn
		});
		$('#media, #page-watch .action .turn-light').css({
			position: 'relative',
			zIndex: 15
		});
		$('.turn-light span', Phim3s.Watch.$action).text('Bật đèn');
	},
	lightOn: function () {
		$('#media').data('light', true);
		Light.Overlay.hide();
		$('.turn-light span', Phim3s.Watch.$action).text('Tắt đèn');
	}
};
// shortcut function for my old plugin (i'm lazy)
function autonext() {
	 Phim3s.Watch.autoNextExecute();
}


/******** COMMENT ********/
Phim3s.Comment = {
	init: function (filmId) {
		$(document).ready(function (e) {
			Phim3s.Comment.registerFormEvent(filmId);
			Phim3s.Comment.load(filmId);
	
			$("#comment .tabs .tab.phim3s").click(function () {
				Phim3s.Comment.load(filmId, 1, false);
			});
		});
	},
	registerFormEvent: function (filmId) {
		var $form = $("#comment .comment-form");
		var $message = $(".message", $form);
		var $counter = $('.counter', $form);
		var maxLength = $message.attr('maxlength');
		var minLength = $message.data('minlength');
		$counter.text(maxLength);
	
		$message.css('resize', 'none').autosize().keyup(function (e) {
			$counter.text(maxLength - $message.val().length);
		});
		$('.submit', $form).click(function (e) {
			var msg = $.trim($message.val());
			if (msg.length < minLength) {
				alert('Ná»™i dung bĂ¬nh luáº­n pháº£i cĂ³ Ă­t nháº¥t 10 kĂ½ tá»±');
			} else {
				Phim3s.Comment.post(filmId);
			}
		});
	},
	post: function (filmId) {
		var $form = $("#comment .comment-form");
		var $message = $(".message", $form);
		var $counter = $('.counter', $form);
		var $submit = $('.submit', $form);
		
		$submit.attr('disabled', true).addClass('disabled');
		Light.ajax({
			url: 'ajax/comment/post/',
			type: 'POST',
			cache: false,
			data: {
				'film_id': filmId,
				'message': $message.val()
			},
			success: function (data) {
				if(!data.error) {
					$message.val('');
				}
				$counter.text($message.attr('maxlength'));
				$submit.attr('disabled', false).removeClass('disabled');
				Phim3s.Comment.load(filmId, 1);
			}
		});
	},
	registerPageClick: function (filmId) {
		$('#comment .page_nav a').click(function (e) {
			var page = $(this).data('page');
			Phim3s.Comment.load(filmId, page);
		});
	},
	load: function (filmId, page, loadFromCache) {
		Light.ajax({
			url: 'ajax/comment/list/',
			type: 'POST',
			cache: loadFromCache || false,
			data: {
				'film_id': filmId,
				'page': page
			},
			success: function (data) {
				$('#comment .comment-list').html(data.html);
				$(document).ready(function (e) {
					Phim3s.Comment.registerPageClick(filmId);
					$('#comment .comment-list .comment .delete').click(function () {
						$comment = $(this).parents('.comment');
						Phim3s.Comment.remove(filmId, $comment.data('comment-id'));
					});
					Phim3s.Comment.registerUserNameClick();
				});
			}
		});
	},
	registerUserNameClick: function() {
		var $message = $('#comment .comment-form .message');
		$('#comment .comment-list .comment .username').click(function () {
			var account = $(this).find('.account').text().replace(/\(|\)/g, '');
			$message.val($message.val() + '@' + account + ': ').focus();
		});
	},
	remove: function (filmId, commentId) {
		Light.ajax({
			url: 'ajax/comment/delete/',
			type: 'POST',
			cache: false,
			data: {
				'film_id': filmId,
				'comment_id': commentId
			},
			success: function (data) {
				Phim3s.Comment.load(filmId, 1, false);
			}
		});
	}
};

/******** MEMBER ********/
Phim3s.Member = {
	init: function() {
		$(document).ready(function(e) {
			Phim3s.Member.registerLoginPanelClick();
			
			Phim3s.Member.registerBookmarkClick(); 	
			Phim3s.Member.registerLogoutClick();
        });
	},
	reloadSignPanel: function() {
		Light.ajax({
			url: 'ajax/member/load_panel/',
			type: 'GET',
			success: function(data) {
				$('#sign').html(data.html);
				Phim3s.Member.init();
			}
		});
	},
	// guest
	registerLoginPanelClick: function () {
		Phim3s.Member.registerLoginSubmitEvent('#sign .login-form');
		
		$('#sign .login a').click(function (e) {
			var $login = $(this).parent();
			var $form = $login.find('.login-form');
			Light.Overlay.create({
				onClickCallback: function (e) {
					$login.removeClass('show');
					$form.hide();
				}
			});
			if ($login.hasClass('show')) {
				$login.removeClass('show');
				$form.slideUp(150);
			} else {
				$login.addClass('show');
				$form.slideDown(100);
			}
			return false;
		});
	},
	registerLoginSubmitEvent: function(selector) {
		var $form = $(selector || '#sign .login-form');
		var $sign = $('#sign');
		
		$form.submit(function(e) {
			var data = {};
			var required = ['username', 'password'];
			for(var i in required) {
				var $input = $('.' + required[i], $form);
				var val = $.trim($input.val());
				if(val == '') {
					alert('"' + $input.attr('placeholder') + '" khĂ´ng Ä‘Æ°á»£c Ä‘á»ƒ trá»‘ng');
					$input.focus();
					return false;
				}
				data[required[i]] = $input.val();
			}
			data['remember'] = $('.remember .checkbox', $form).is(':checked') ? 1 : 0;
			Light.ajax({
				url: 'member/login/',
				type: 'POST',
				data: data,
				success: function(data) {
					if(data.error) {
						alert(data.message);
					} else if(data.html) {
						$sign.html(data.html);
						Phim3s.Member.init();
						Light.Overlay.hide();
						$form.fadeOut(null, null, function() {
							if($('#page-login').length) {
								alert('ÄÄƒng nháº­p thĂ nh cĂ´ng');
								//window.location = '';
							}
						});
					}
				}
			});
			return false;
        });
	},
	registerLogoutClick: function() {
		$('#sign .logout').click(function(e) {
			var $sign = $(this).parents('#sign');
			Light.ajax({
				url: 'member/logout/',
				type: 'GET',
				success: function(data) {
					$sign.html(data.html);
					$(document).ready(function(e) {
						Phim3s.Member.registerLoginPanelClick();
						Phim3s.Member.registerBookmarkClick();
					});
				}
			});
			return false;
        });
	},
	registerBookmarkClick: function () {
		$('#sign .bookmark span').click(function (e) {
			Phim3s.Member.showBookmarks();
		});
	},
	showBookmarks: function() {
		var $bookmark = $('#sign .bookmark');
		var $btn = $bookmark.find('span:eq(0)');
		var $ul = $bookmark.children('ul:eq(0)');
		//userid= $('#sign .bookmark').data('user-id');
		
		if($bookmark.data('isFetching')) {
			return;
		}
		if ($bookmark.hasClass('show')) {
			$bookmark.removeClass('show');
			$ul.slideUp(100);
		} else {
			// set state
			$bookmark.data('fetching', true);
			$ul.empty(); // reset
			
			Light.ajax({
				url: 'http://phim6789.com/ajax/',
			type: 'POST',
			data: {
				userid:true,
			},
				success: function(data) {
					if(data.error) {
						return alert("loi roi");
					}
					// results
					var html = data.json;
var lhtml = html.split('<ul class="bookmarklist">')[1].split('</ul>')[0];
$('.bookmarklist').append(lhtml);
lhtml="";
					// ui - show
					$bookmark.data('isFetching', false);				
					$bookmark.addClass('show');
					$ul.slideDown(100);	
					Light.Overlay.create({
						onClickCallback: function (e) {
							$bookmark.removeClass('show');
							$ul.slideUp(100);
						}
					});
				}
			});
		}
	},
	removeBookmark: function($strike) {
		var $bookmark = $('#sign .bookmark');
		var $ul = $bookmark.children('ul:eq(0)');
		var $li = $('strike').parent('li');
		var filmId = $strike;
		$li.fadeOut(null, null, function(){
			$(this).remove();
			if($ul.find('li').length == 0) {
				Light.Overlay.hide();
				$bookmark.removeClass('show');
				$ul.slideUp(100);
			}
		});
		Light.ajax({
			url: 'http://phim6789.com/ajax/',
			type:'POST',
			data: {
				remove_id: filmId
			},
			success: function(data) {
				//alert("Ä‘àƒ xòa");
			}
		});
	}
};
Phim3s.Member.EditProfile = {
	init: function() {
		$(document).ready(function(e) {
            Phim3s.Member.EditProfile.registerSubmitEvent();
        });
	},
	registerSubmitEvent: function() {
		$('#page-editprofile form').submit(function(e) {
			$form = $(this);
			var required = ['password', 'fullname', 'email'];
			if($('.newpassword', $form).val() != $('.newpassword2', $form).val()) {
				alert('Máº­t kháº©u xĂ¡c nháº­n pháº£i giá»‘ng vá»›i máº­t kháº©u má»›i');
				$('.newpassword', $form) .focus();
				return false;
			}
			for(var i in required) {
				var $input = $('.' + required[i], $form);
				var val = $.trim($input.val());
				var label = $input.parents('.control-groups').find('label').text();
				if(val == '') {
					alert('"' + label + '" khĂ´ng Ä‘Æ°á»£c Ä‘á»ƒ trá»‘ng');
					$input.focus();
					return false;
				}
				if(name == 'email' && !/^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/.test(val)) {
					alert('Email pháº£i cĂ³ Ä‘á»‹nh dáº¡ng "tenban@abc.com"');
					$input.focus();
					return false;
				}
			}
			Phim3s.Member.EditProfile.submit();
			return false;
		});
	},
	submit: function() {
		var $form = $('#page-editprofile form');
		var sex = null; 
		$('.sex', $form).each(function(index, element) {
            if($(this).is(':checked')) {
				sex = $(this).val();
			}
        });
		var birthday = {day: 0, month: 0, year: 0};
		for(var i in birthday) {
			birthday[i] = parseInt($('.birthday.' + i, $form).val());
		}
		var fields = ['password', 'fullname', 'email'];
		var data = {sex: sex, birthday: birthday};
		for(var i in fields) {
			data[fields[i]] = $.trim($('.' + fields[i], $form).val());
		}
		Light.ajax({
			url: 'member/editprofile/',
			data: data,
			type: 'POST',
			cache: false,
			success: function(data) {
				if($.isArray(data.message) || $.isPlainObject(data.message)) {
					var tmp = '';
					for(var i in data.message) {
						tmp += '- ' + data.message[i] + "\n";
					}
					$('.' + i, $form).focus();
					data.message = tmp;
				}
				alert(data.message);
			}
		});
	}
};

Phim3s.Member.Register = { 
	init: function() {
		$(document).ready(function(e) {
            Phim3s.Member.Register.registerSubmitEvent();
			Phim3s.Core.registerCaptchaClick();
        });
	},
	registerSubmitEvent: function() {
		$('#page-register form').submit(function(e) {
			var $form = $(this);
			var required = ['username', 'password', 'password2', 'email', 'fullname', 'captcha'];
			for(var i in required) {
				var $input = $('.' + required[i], $form);
				var val = $.trim($input.val());
				var label = $input.parents('.control-groups').find('label').text();
				if(val == '') {
					alert('"' + label + '" khĂ´ng Ä‘Æ°á»£c Ä‘á»ƒ trá»‘ng');
					$input.focus();
					return false;
				}
				if(name == 'email' && !/^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/.test(val)) {
					alert('Email pháº£i cĂ³ Ä‘á»‹nh dáº¡ng "tenban@abc.com"');
					$input.focus();
					return false;
				}
				if(name == 'password2' && val != $('.password', $form).val()) {
					alert('"' + label + '" pháº£i giá»‘ng vá»›i máº­t kháº©u');
					$input.focus();
					return false;
				}
			}
			Phim3s.Member.Register.submit();
			return false;
		});
	},
	submit: function() {
		var $form = $('#page-register form');
		var sex = null; 
		$('.sex', $form).each(function(index, element) {
            if($(this).is(':checked')) {
				sex = $(this).val();
			}
        });
		var birthday = {day: 0, month: 0, year: 0};
		for(var i in birthday) {
			birthday[i] = parseInt($('.birthday.' + i, $form).val());
		}
		var fields = ['username', 'password', 'password2', 'email', 'fullname', 'captcha'];
		var data = {sex: sex, birthday: birthday};
		for(var i in fields) {
			data[fields[i]] = $.trim($('.' + fields[i], $form).val());
		}
		Light.ajax({
			url: 'member/register/',
			data: data,
			type: 'POST',
			cache: false,
			success: function(data) {
				Phim3s.Core.changeCaptchaImage();
				if(!data.error) {
					alert('ÄÄƒng kĂ½ thĂ nh cĂ´ng. Vui lĂ²ng Ä‘Äƒng nháº­p');
				} else {
					if($.isArray(data.message) || $.isPlainObject(data.message)) {
						var tmp = '';
						for(var i in data.message) {
							tmp += '- ' + data.message[i] + "\n";
						}
						$('.' + i, $form).focus();
						data.message = tmp;
					}
					alert(data.message);
					if(!data.show.form) {
						$form.remove();
					}
				}
			},
			error: function(e) {
				Phim3s.Core.changeCaptchaImage();
			}
		});
	}
};