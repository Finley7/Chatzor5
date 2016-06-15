<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <?= __('Archive'); ?>
                </div>
                <ul class="list-group">
                    <?php
                    foreach($chats as $chat):

                        $string = $this->Ubb->parse(h($chat->message));

                        $string = str_replace(['&#039'], ["'"], $string);

                        foreach(Cake\Core\Configure::read("Blocked.words") as $key => $value) {
                            $string = str_ireplace($key, "[{$value}]", $string);
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
                        <li class="list-group-item">
                            <p class="message">
                                <span class="text-muted pull-right"><?= $chat->created->nice(); ?></span>
                                <span class="role <?= $chat->user->primary_role->name; ?>"><?= $chat->user->username; ?></span> &raquo;
                                <?= $string; ?>
                            </p>
                        </li>
                    <?php endforeach; ?>
                </ul>
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