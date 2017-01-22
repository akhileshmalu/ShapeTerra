$('.mission_submit').click(function(){
    $('.mission-status-alert').removeClass('hidden');
    $('form.mission').addClass('hidden')
})

$('.tab1-next-tab').click(function(){
    $('.nav a[href="#tab2"]').tab('show');
})


$('.vision_submit').click(function(){
    $('.vision-status-alert').removeClass('hidden');
    $('form.vision').addClass('hidden')
})

$('.tab2-next-tab').click(function(){
    $('.nav a[href="#tab3"]').tab('show');
})

$('.value_submit').click(function(){
    $('.value-status-alert').removeClass('hidden');
    $('form.value').addClass('hidden')
})
