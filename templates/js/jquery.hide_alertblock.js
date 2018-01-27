$(document).ready(function(){
	$('.close').on('click', function(){
		var deleted_block = $(this).parent(),
		bl_h = deleted_block.outerHeight(),
		bk_index = deleted_block.index(),
		next_bl = deleted_block.siblings(':eq('+bk_index+')'),
		marg = parseInt(deleted_block.css('margin-bottom'));
 
		deleted_block.fadeOut(500);
 
		setTimeout(function(){
			$(next_bl).css('margin-top', bl_h+marg);
			$(next_bl).animate({
				marginTop: 0
			},400);
		}, 505);
 
		setTimeout(function(){
			deleted_block.remove();
		}, 700);
		return false;
	});

	setTimeout(function(){
		setTimeout(function(){$('.alert-success').fadeOut('700')},5000);
	});
});