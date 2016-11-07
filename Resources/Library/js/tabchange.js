    var $tabs = $('.nav-pills li');

    $('.changeTab').on('click', function() {
        $tabs.filter('.active').next('li').find('a[data-toggle="tab"]').tab('show');
    });

    function copyText1() {
    var a = document.getElementById('missiontitle');
    var b = document.getElementById('missionstatement');
    if (a != null) {
        b.value = a.value;
    }
}