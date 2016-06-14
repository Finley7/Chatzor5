<div class="container">
    <div class="row">
        <div class="col-md-8 col-xs-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Chatbox
                </div>
                <div class="panel-body">
                    <?= $this->Form->create(null, [
                            'url' => ['controller' => 'Chats', 'action' => 'shout', 'prefix' => 'ajax'],
                            'id' => 'messageform'
                        ]);
                    ?>
                    <div class="input-group">
                        <?= $this->Form->input('message', ['class' => 'form-control', 'label' => false, 'placeholder' => 'Please enter a message']); ?>
                         <span class="input-group-btn">
                             <?= $this->Form->button(__('Shout'), ['class' => 'btn btn-success']); ?>
                         </span>
                    </div>
                    <?= $this->Form->end(); ?>
                </div>
                <ul class="list-group chats"></ul>
                <div class="panel-footer">

                </div>
            </div>
        </div>
        <div class="col-md-4 col-xs-12">
            <div class="panel panel-default">
                <div class="panel-heading"><?= __('Last seen active'); ?></div>
                <ul class="active-users list-group">
                    <?php foreach($activities as $activity): ?>
                        <li class="list-group-item">
                            <span class="role <?= $activity->user->primary_role->name; ?>">
                                <?= $activity->user->username; ?>
                            </span>
                            <div class="pull-right text-muted">
                                <?= $activity->date->timeAgoInWords(); ?>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
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
                })
            })
        })
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
                })
            }
        })

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

        })
    }
</script>