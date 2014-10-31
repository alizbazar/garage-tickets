var config = {
    baseUrl: "http://tools.alizweb.com/garage/"
};

function getParameterByName(name) {
    name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
    var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
        results = regex.exec(location.search);
    return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
}


var token = getParameterByName("t");
var invite;

$.getJSON(config.baseUrl + 'validate.php?token=' + token, function(res) {
    console.log(res);
    if (res.status != "success") {
        $('#registerMe, #inviteFriends').remove();
        return;
        // TODO: error handling
    }

    // TODO: what if the person has already registered? show "cancel registration"?
    if (res.data.freeInvites && res.data.freeInvites != "0") {
        $('#nbrOfInvites').text(res.data.freeInvites);
        $('#inviteFriends').removeClass('hidden');
    }

    if (res.data.registered) {
        $('#registerMe').addClass('hidden');
        $('#justRegistered').toggleClass('hidden', false);
    }

    $('#myEmail').val(res.data.email);
    if (!!res.data.name) {
        $('#myName').val(res.data.name);
    }
    if (!!res.data.phone) {
        $('#myPhone').val(res.data.phone);
    }
    invite = res.data;

});

$('#registerMe form').submit(function(e) {
    e.preventDefault();
    var fields = {
        token: token,
        name: $('#myName').val(),
        email: $('#myEmail').val(),
        phone: $('#myPhone').val()
    };

    // Validate the form fields so that no one is empty
    $('#validationErrors').empty().toggleClass('hidden', true);
    $('.has-error').removeClass('has-error');
    var validationErrors = [];
    if (!fields.name) {
        validationErrors.push({
            "field": "myName",
            "msg": "Please provide your name"
        });
    }
    if (!fields.email || fields.email.indexOf('.') == -1 || fields.email.indexOf('@') == -1) {
        validationErrors.push({
            "field": "myEmail",
            "msg": "Please provide your email"
        });
    }
    if (!fields.phone) {
        validationErrors.push({
            "field": "myPhone",
            "msg": "Please provide your phone number"
        });
    }
    if (validationErrors.length > 0) {
        $.each(validationErrors, function(i, v) {
            $('#' + v.field).closest('.form-group').toggleClass('has-error', true);
            $('#validationErrors').append('<p>' + v.msg + '</p>');
        });
        $('#validationErrors').toggleClass('hidden', false);
       return; 
    }

    $.getJSON(config.baseUrl + 'register.php', fields, function(res) {
        // TODO: fix success / error handling
        var dialogMsg;
        if (res.status == "success") {
            dialogMsg = 'Thanks for registering!';
            if (invite.freeInvites > 0) {
                dialogMsg += "\n\n" + 'Now go ahead and invite up to ' + invite.freeInvites + ' of your friends.';
            }
            invite = $.extend(invite, fields);
            $('#registerMe').addClass('hidden');
            $('#justRegistered').toggleClass('hidden', false);
        } else {
            dialogMsg = 'Unfortunately there was an error. Please contact XYZ and tell us your information personally.';
            // TODO: probably error logging on the backend would be pretty good idea here...
        }
        alert(dialogMsg);
    });
});

$('#inviteFriends form').submit(function(e) {
    e.preventDefault();

    if (!invite.name) {
        alert("You haven't registered yourself. Please register first, then invite others!");
        return;
    }

    var email = $('#friendsEmail').val();
    if (!email || email.indexOf('.') == -1 || email.indexOf('@') == -1) {
        $('#validationErrors').append('<p>Please enter your friend\'s email first</p>');
        $('#friendsEmail').toggleClass('error', true);
        return;
    } else {
        $('#validationErrors').empty();
        $('#friendsEmail').removeClass('error');
    }

    $.getJSON(config.baseUrl + 'invite.php', {token: token, email: email}, function(res) {
        if (res.status == "success") {
            alert('Your friend was invited! Make sure she checks her email ;)');
            invite.freeInvites--;
            if (invite.freeInvites) {
                $('#nbrOfInvites').text(invite.freeInvites);
            } else {
                $('#inviteFriends').addClass('hidden');
            }
        } else {
            // TODO: handle error in inviting the user
            alert('Unfortunately there was an error. Please contact XYZ and tell us more about what you did.');
        }
    });

});



