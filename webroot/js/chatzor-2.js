$(function () {
    load_chats();
    load_activities();
    setInterval(lastshout, 2000);
    setInterval(load_activities, 60000);

    $('#messageform').submit(function (e) {
        e.preventDefault();

        if ($('#message').val() == '') {
            alert('Deze shout is te kort!');
        }
        else {
            var request = $.ajax({
                'url': $('#messageform').attr('action'),
                'data': $('#messageform').serialize(),
                'type': 'POST',
                'dataType': 'JSON'
            });


            request.success(function (result) {
                if (result.response.status == 'ok') {
                    $('#message').val('');
                }
                else {
                    alert(result.response.message);
                }
            });
        }
    });

    $(".chats").on("click", "span.user", function () {
        var e = $(this).parents(".msg").data("name");
        $("#message").focus().val("/pvt " + e + " ")
    })

});


function load_chats() {
    var chats = $.getJSON('/ajax/chats/index');

    chats.success(function (results) {

        $.each(results, function (key, value) {
            $.each(value, function (key, chats) {
                $.each(chats, function (key, chat) {

                    if (chat.user.primary_role == 'Banned') {
                        $('.chats').html("<div class='alert alert-danger'>Je bent verbannen van Chatzor!</div>");
                        clearInterval(lastshout());
                    }
                    else {
                        var code = '<li data-name="' + chat.user.username + '" data-id="' + chat.message.id + '" class="msg list-group-item">\n';
                        code += '<img style="width:30px;height:30px;margin:-5px 10px;display:inline-block;" class="img-circle avatar-image pull-left hidden-xs" src="./img/uploads/avatars/' + chat.user.avatar + '">\n'
                        code += '<p class="message"><span class="text-muted pull-right">' + chat.message.created + '</span>\n';
                        code += '<span class="user role ' + chat.user.primary_role + '">' + chat.user.username + '</span> &raquo; ' + chat.message.content + '</p>\n';
                        code += '</li>';

                        $('.chats').append(code);
                    }
                });
            });
        });
    });
}


function lastshout() {
    var id = $.getJSON('/ajax/chats/index', {after: $('.chats').find('>:first-child').data('id')});
    id.success(function (result) {

        var last_shout = $('.chats').find('>:first-child');
        if (result.response.last_id == 0) {
            console.info("Shoutbox is empty");
        }
        else {
            if (last_shout.data('id') < result.response.last_id) {
                load_chat(result.response.last_id);
            }
        }

    });


}

function load_chat(id) {
    var chat_message = $.getJSON('/ajax/chats/view/' + id);

    chat_message.success(function (result) {

        if (result.response.chat.user.primary_role == 'Banned') {
            $('.chats').html("<div class='alert alert-danger'>Je bent verbannen van Chatzor!</div>");
            clearInterval(lastshout());
        }
        else {
            var code = '<li data-name="' + result.response.chat.user.username + '" data-id="' + result.response.chat.message.id + '" class="list-group-item">\n';
            code += '<img style="width:30px;height:30px;margin:-5px 10px;display:inline-block;" class="img-circle avatar-image pull-left hidden-xs" src="./img/uploads/avatars/' + result.response.chat.user.avatar + '">\n'
            code += '<p class="message"><span class="text-muted pull-right">' + result.response.chat.message.created + '</span>\n';
            code += '<span class="role ' + result.response.chat.user.primary_role + '">' + result.response.chat.user.username + '</span> &raquo; ' + result.response.chat.message.content + '</p>\n';
            code += '</li>';

            $('.chats').prepend(code);
        }
    });
}

function load_activities() {
    var activities = $.getJSON('/ajax/activities/index');

    activities.success(function (results) {

        if (results.response.activities == '') {
            $('.user-activites').html("<li class='list-group-item-danger'>Geen huidige activiteiten</li>");
        }
        else {
            $('.user-activities').html("");
            $.each(results, function (key, value) {
                $.each(value, function (key, activities) {
                    $.each(activities, function (key, activity) {

                        var code = '<li class="list-group-item msg">\n';
                        code += '<p class="message"><span class="text-muted pull-right">' + activity.date.nice + '</span>\n';
                        code += '<span class="user role ' + activity.user.primary_role + '">' + activity.user.username + '</span></p>\n';
                        code += '</li>';

                        $('.user-activities').append(code);
                        
                    });
                });
            });
        }
    });
}
