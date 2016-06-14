function load_chats() {
    var chats = $.getJSON('/ajax/chats/index');

    chats.success(function(results) {

        $.each(results, function(key, value) {
            $.each(value, function(key, chat) {

                var created = new Date(chat.created);

                var code = '<li data-id="' + chat.id +'" class="list-group-item">\n';
                code += '<span class="text-muted pull-right">' + created.toLocaleString() + '</span>\n';
                code += '<span class="role ' + chat.user.primary_role.name + '">' + chat.user.username + '</span> &raquo; ' + chat.message + '\n';
                code += '</li>';

                $('.chats').append(code);
            });
        });
    });
}

$(function() {
    load_chats();
    setInterval(lastshout, 2000);

    $('#messageform').submit(function(e) {
        e.preventDefault();

        if($('#message').val() == '') {
            alert('Deze shout is te kort!');
        }
        else
        {
            var request = $.ajax({
                'url': $('#messageform').attr('action'),
                'data': $('#messageform').serialize(),
                'type': 'POST',
                'dataType': 'JSON'
            });

            $('#message').val('');

            request.success(function(result) {
                if(result.response.status == 'ok') {
                    load_chat(result.response.id);
                }
                else {
                    alert(result.response.message);
                }
            });
        }
    });

});

function lastshout() {
    var id = $.getJSON('/ajax/chats/lastid');
    id.success(function (result) {

        var last_shout = $('.chats').find('>:first-child');

        if(last_shout.data('id') < result.chat.id) {
            load_chat(result.chat.id);
        }

    });

    $('time').timeago();
}

function load_chat(id) {
    var chat_message = $.getJSON('/ajax/chats/view/' + id);

    chat_message.success(function(result) {

        var created = new Date(result.chat.created);

        var code = '<li data-id="' + result.chat.id +'" class="list-group-item">\n';
        code += '<span class="text-muted pull-right">' + created.toLocaleString() + '</span>\n';
        code += '<span class="role ' + result.chat.user.primary_role.name + '">' + result.chat.user.username + '</span> &raquo; ' + result.chat.message + '\n';
        code += '</li>';

        $('.chats').prepend(code);

    });
}