var $tabs = $('.nav-pills li');

$('.changeTab').on('click', function () {
    $tabs.filter('.active').next('li').find('a[data-toggle="tab"]').tab('show');
});
