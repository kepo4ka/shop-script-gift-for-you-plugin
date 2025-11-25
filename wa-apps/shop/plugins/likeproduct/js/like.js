function product_like(id, shop) {
	var like = $.cookie('like');
	if (!$(".cll"+id).hasClass('active')) {
		$(".cll"+id).addClass('active');
		if (like != null) {
			var likes = like.split(';');
			likes.push(id);
			var cookie_likes = [];
			for (var i = 0; i < likes.length; i++) {
				if (likes[i] != '') {
					cookie_likes.push(likes[i]);
				}
			}
			var likes_string = cookie_likes.join(';');
			$.cookie('like',likes_string,{
				expires: 1000,
				path: '/',
			});
		}
		else {
			$.cookie('like', id,{
				expires: 1000,
				path: '/',
			});
		}
	}
	else {
		$(".cll"+id).removeClass('active');
		if (like != null) {
			var likes = like.split(';');
			if (likes.indexOf(id) != -1) {
				for (var i = 0; i < likes.length; i++) {
					if (likes[i] == id) {
						delete likes[i];
					}
				}
				var cookie_likes = [];
				for (var i = 0; i < likes.length; i++) {
					if (likes[i] != '') {
						cookie_likes.push(likes[i]);
					}
				}
				var likes_string = cookie_likes.join(';');
				$.cookie('like',likes_string,{
					expires: 1000,
					path: '/',
				});
			}
		}
	}
	$.post(shop+"change_like/",{id:id,type:(($(".cll"+id).hasClass('active'))? 1 : 0)},function(res){
		if (res.status == 'ok') {
			$(".cll"+id).find('.product_like_count').html(res.data.count);
		}
	},"json");
}

$(function(){
	var like = $.cookie('like');
	if (like != null) {
		var likes = like.split(';');
		$.each(likes, function(){
			$(".cll"+this).addClass('active');
		});
	}
});