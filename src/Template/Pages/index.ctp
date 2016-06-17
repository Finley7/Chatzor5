<div class="container">
    <div class="row">
        <div class="col-md-8 col-xs-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Chatbox
                    <div class="pull-right">
                        <?= $this->Html->link(__('Archive'), ['controller' => 'Pages', 'action' => 'archive'], ['class' => 'btn btn-xs btn-primary']); ?>
                    </div>
                </div>
                <div class="panel-body chatbody">
                    <?= $this->Form->create(null, [
                            'url' => ['controller' => 'Chats', 'action' => 'shout', 'prefix' => 'ajax'],
                            'id' => 'messageform'
                        ]);
                    ?>
                    <div class="input-group">
                        <?= $this->Form->input('message', ['maxlength' => 150, 'autocomplete' => 'off', 'class' => 'form-control', 'label' => false, 'placeholder' => 'Please enter a message']); ?>
                         <span class="input-group-btn">
                             <?= $this->Form->button(__('Shout'), ['class' => 'btn btn-success']); ?>
                         </span>
                    </div>
                    <?= $this->Form->end(); ?>
                </div>
                <ul class="list-group chats"></ul>
                <div class="panel-footer">
                    <div class="text-muted chats-total"></div>
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
                            <span><i class="text-muted"><?= $activity->user->primary_role->name; ?></i></span>
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
<?= $this->Html->script('chatzor'); ?>