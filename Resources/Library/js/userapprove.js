function selectlist() {
    // get the index of the selected option
    var idx = selectObj.selectedIndex;
    // get the value of the selected option
    var user_role = selectObj.options[idx].roles;
    var user_right = selectObj.options[idx].rights;

    // get the country select element via its known id
    var cSelect = document.getElementById("user-rights");

    $("select[name='user-rights']").find("option[value = user_rights]").attr("selected", true);
    $("select[name='role']").find("option[value = user_role]").attr("selected", true);

}