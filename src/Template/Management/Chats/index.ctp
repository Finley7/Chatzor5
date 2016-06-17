<div class="container">
    <div class="row">
        <?= $this->element('Menu/management-menu'); ?>
        <div class="col-md-8 col-xs-12 col-lg-8">
            <div class="panel panel-default table-responsive">
                <div class="panel-heading">
                    <?= __('Archive'); ?>
                </div>
                <table class="table">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th><?= __('User'); ?></th>
                        <th><?= __('Message'); ?></th>
                        <th><?= __('Time'); ?></th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>

                    <?php
                    foreach($chats as $chat):

                    $string = $this->Ubb->parse(h($chat->message));

                    $string = str_replace(['&#039'], ["'"], $string);

                    foreach(Cake\Core\Configure::read("Blocked.words") as $key => $value) {
                        $string = "<strong>[GEFILTERD]</strong> " .$this->Ubb->parse(h($chat->message));
                    }

                    if(!is_null($chat->whisper_to)) {
                        if($chat->whisper_to == $user->id) {
                            $string = "<b>". __("Whisper") .": </b>" . $string;
                        }
                        else
                        {
                            $string = "<b>" . __("Whisper to {0}", h($chat->whisper->username)) .": </b>" . $string;
                        }
                    }
                    ?>
                    <tr>
                        <td><?= $chat->id; ?></td>
                        <td><span class="role <?= $chat->user->primary_role->name; ?>"><?= $chat->user->username; ?></span></td>
                        <td><?= ($chat->deleted) ? '<strong>[DELETED]</strong> ' . $string : $string; ?></td>
                        <td><?= $chat->created->nice(); ?></td>
                        <td><?= $this->Form->postButton('<i class="fa fa-trash"></i>', [
                                'controller' => 'Chats',
                                'action' => 'delete',
                                $chat->id
                            ], ['class' => 'btn btn-xs btn-danger', 'escape' => false]); ?>
                        </td>
                    </tr>
                    </tbody>
                    <?php endforeach; ?>
                </table>
                <div class="panel-body">
                    <ul class="pagination pagination-sm">
                        <li class="page-item">
                            <?= $this->Paginator->prev(__('Back'), ['class' => 'page-link']) ?>
                        </li>
                        <li class="page-item">
                            <?= $this->Paginator->numbers(['class' => 'page-link']); ?>
                        </li>
                        <li class="page-item">
                            <?= $this->Paginator->next(__('Next'), ['class' => 'page-link']) ?>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
