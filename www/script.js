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
    if (res.status != "success" || !token) {
        $('#registerMe, #inviteFriends').remove();
        var $letUsKnow = $('#letUsKnow').addClass('noInvite');
        $('#header').after($letUsKnow);

        if (window.location.search.indexOf('event_full') != -1) {
            $('#letUsKnow .notification').html('Unfortunately the event is full.');
        }

        return;
        // TODO: error handling
    }

    // TODO: what if the person has already registered? show "cancel registration"?
    if (res.data.freeInvites && res.data.freeInvites != "0") {
        $('#nbrOfInvites').text(res.data.freeInvites);
        $('#inviteFriends').removeClass('hidden');

        if (res.data.dev) {
            $('#slushOnly').addClass('hidden');
        }
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
            invite.registered = true;

            $('#registerMe').addClass('hidden');
            $('#justRegistered').toggleClass('hidden', false);
        } else {
            dialogMsg = 'Unfortunately there was an error. Please contact info@elisaxslush.com and tell us your information personally.';
            // TODO: probably error logging on the backend would be pretty good idea here...
        }
        alert(dialogMsg);
    });
});

$('#inviteFriends form').submit(function(e) {
    e.preventDefault();

    if (!invite.registered) {
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
            alert('Your friend was invited! ' + "\n" + 'Make sure they check their emails ;)');
            $('#friendsEmail').val('');
            invite.freeInvites--;
            if (invite.freeInvites) {
                $('#nbrOfInvites').text(invite.freeInvites);
            } else {
                $('#inviteFriends').addClass('hidden');
            }
        } else {
            // TODO: handle error in inviting the user
            alert('Unfortunately there was an error. Please contact info@elisaxslush.com and tell us more about what you did.');
        }
    });

});

$('address').click(function(e) {
    e.preventDefault();
    window.open('https://www.google.com/maps/place/Ratavartijankatu+5,+00520+Helsinki/@60.1987319,24.9336123,16z/data=!4m2!3m1!1s0x4692098fbac8c489:0x324a8cd1bf38a741?hl=en');
});


/*! http://mths.be/placeholder v2.0.8 by @mathias */
(function(i,k,f){var b=Object.prototype.toString.call(i.operamini)=="[object OperaMini]";var a="placeholder" in k.createElement("input")&&!b;var g="placeholder" in k.createElement("textarea")&&!b;var l=f.fn;var e=f.valHooks;var c=f.propHooks;var n;var m;if(a&&g){m=l.placeholder=function(){return this};m.input=m.textarea=true}else{m=l.placeholder=function(){var p=this;p.filter((a?"textarea":":input")+"[placeholder]").not(".placeholder").bind({"focus.placeholder":d,"blur.placeholder":h}).data("placeholder-enabled",true).trigger("blur.placeholder");return p};m.input=a;m.textarea=g;n={get:function(q){var p=f(q);var r=p.data("placeholder-password");if(r){return r[0].value}return p.data("placeholder-enabled")&&p.hasClass("placeholder")?"":q.value},set:function(q,s){var p=f(q);var r=p.data("placeholder-password");if(r){return r[0].value=s}if(!p.data("placeholder-enabled")){return q.value=s}if(s==""){q.value=s;if(q!=o()){h.call(q)}}else{if(p.hasClass("placeholder")){d.call(q,true,s)||(q.value=s)}else{q.value=s}}return p}};if(!a){e.input=n;c.value=n}if(!g){e.textarea=n;c.value=n}f(function(){f(k).delegate("form","submit.placeholder",function(){var p=f(".placeholder",this).each(d);setTimeout(function(){p.each(h)},10)})});f(i).bind("beforeunload.placeholder",function(){f(".placeholder").each(function(){this.value=""})})}function j(q){var p={};var r=/^jQuery\d+$/;f.each(q.attributes,function(t,s){if(s.specified&&!r.test(s.name)){p[s.name]=s.value}});return p}function d(q,r){var p=this;var s=f(p);if(p.value==s.attr("placeholder")&&s.hasClass("placeholder")){if(s.data("placeholder-password")){s=s.hide().next().show().attr("id",s.removeAttr("id").data("placeholder-id"));if(q===true){return s[0].value=r}s.focus()}else{p.value="";s.removeClass("placeholder");p==o()&&p.select()}}}function h(){var t;var p=this;var s=f(p);var r=this.id;if(p.value==""){if(p.type=="password"){if(!s.data("placeholder-textinput")){try{t=s.clone().attr({type:"text"})}catch(q){t=f("<input>").attr(f.extend(j(this),{type:"text"}))}t.removeAttr("name").data({"placeholder-password":s,"placeholder-id":r}).bind("focus.placeholder",d);s.data({"placeholder-textinput":t,"placeholder-id":r}).before(t)}s=s.removeAttr("id").hide().prev().attr("id",r).show()}s.addClass("placeholder");s[0].value=s.attr("placeholder")}else{s.removeClass("placeholder")}}function o(){try{return k.activeElement}catch(p){}}}(this,document,jQuery));


$('input').placeholder();


