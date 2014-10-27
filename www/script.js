function getParameterByName(name) {
    name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
    var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
        results = regex.exec(location.search);
    return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
}


var token = getParameterByName("token");

$.getJSON('http://localhost:8888/garage/validate.php?token=' + token, function(res) {
    console.log(res);
    if (res.status != "success") {
        $('#registerMe, #inviteFriends').remove();
        return;
        // TODO: error handling
    }

    // TODO: what if the person has already registered? show "cancel registration"?

    if (!res.data.freeInvites || res.data.freeInvites == "0") {
        $('#inviteFriends').remove();
    } else {
        $('#nbrOfInvites').text(res.data.freeInvites);
    }

    $('#myEmail').val(res.data.email);

});

$('#registerMe form').submit(function(e) {
    // TODO: handle registration
    e.preventDefault();
});

$('#inviteFriends form').submit(function(e) {


    e.preventDefault();
});