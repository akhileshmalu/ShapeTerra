$("#preview").click(function () {
    document.getElementById("actionlist1").style.display = "none";
    document.getElementById("actionlist2").style.display = "none";
    document.getElementById("actionlist3").style.display = "none";

    var x = document.getElementById('execsummary');
    document.getElementById('exesumtitle').innerHTML = x.value;

    var y = document.getElementById('missionstatement');
    document.getElementById('missiontext').innerHTML = y.value;

    var z = document.getElementById('goaloutcome');
    document.getElementById('goalout').innerHTML = z.value;

});


