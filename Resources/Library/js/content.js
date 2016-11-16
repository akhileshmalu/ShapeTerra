$("#preview").click(function () {
    var x = document.getElementById('execsummary');
    document.getElementById('exesumtitle').innerHTML = x.value;

    var y = document.getElementById('missionstatement');
    document.getElementById('missiontext').innerHTML = y.value;

    var z = document.getElementById('goaloutcome');
    document.getElementById('goalout').innerHTML = z.value;

});

$("#missionbtn").click(function(){
    var y =  document.getElementById('missiontitle');
    var x = document.getElementById('missionstate');
    y.innerHTML =x.value;
    y.value =x.value;
    y.setAttribute("readonly","readonly");
    document.getElementById("missionbtn").setAttribute("data-dismiss","modal");
    document.getElementById("missionbtn").setAttribute("aria-label","Close");
});

$("#visionbtn").click(function(){
    var y =  document.getElementById('visiontitle');
    var x = document.getElementById('visionstate');
    y.innerHTML =x.value;
    y.value =x.value;
    y.setAttribute("readonly","readonly");
    document.getElementById("visionbtn").setAttribute("data-dismiss","modal");
    document.getElementById("visionbtn").setAttribute("aria-label","Close");
});

$("#valuebtn").click(function(){
    var y =  document.getElementById('valuetitle');
    var x = document.getElementById('valuestate');
    y.innerHTML =x.value;
    y.value =x.value;
    y.setAttribute("readonly","readonly");
    document.getElementById("valuebtn").setAttribute("data-dismiss","modal");
    document.getElementById("valuebtn").setAttribute("aria-label","Close");
});

$("#unitgoalbtn").click(function(){
    var y =  document.getElementById('curgoaltext');
    var x = document.getElementById("goaltitle");
    var z= x.value;
    y.value = z;
    $("#curgoal").removeClass("hidden");

});
