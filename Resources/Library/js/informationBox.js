$('.information h1').css('cursor', 'pointer');

$('.information h1').click(function(){
	if($('.information span.minus').hasClass('hidden')){
		$('.information span.minus').removeClass('hidden');
		$('.information span.plus').addClass('hidden');
		$('.information p').removeClass('hidden');
	}else{
		$('.information span.plus').removeClass('hidden');
		$('.information span.minus').addClass('hidden');
		$('.information p').addClass('hidden');
	}
});