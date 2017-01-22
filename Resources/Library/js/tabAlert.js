$('.mission_submit').click(function(){
    $('.mission-status-alert').removeClass('hidden');
    $('form.mission').addClass('hidden')
})

$('.mission-next-tab').click(function(){
    $('.nav a[href="#vision"]').tab('show');
})


$('.vision_submit').click(function(){
    $('.vision-status-alert').removeClass('hidden');
    $('form.vision').addClass('hidden')
})

$('.vision-next-tab').click(function(){
    $('.nav a[href="#values"]').tab('show');
})

$('.value_submit').click(function(){
    $('.value-status-alert').removeClass('hidden');
    $('form.value').addClass('hidden')
})
