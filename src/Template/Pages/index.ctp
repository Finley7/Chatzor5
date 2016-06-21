<div class="container">
    <div class="row">
        <div class="col-md-9">
            <div class="panel panel-default">
                <div class="user-info clearfix">
                    <div class="chatbox">
                        <?= $this->Form->create(null, [
                            'url' => ['controller' => 'Chats', 'action' => 'shout', 'prefix' => 'ajax'],
                            'id' => 'messageform',
                            'style' => 'max-width:850px;'
                        ]);
                        ?>
                        <div class="input-group">
                            <?= $this->Form->input('message', ['maxlength' => 150, 'autocomplete' => 'off', 'class' => 'form-control', 'label' => false, 'placeholder' => 'Please enter a message']); ?>
                            <span class="input-group-btn">
                             <?= $this->Form->button(__('Send message'), ['class' => 'btn btn-info']); ?>
                         </span>
                        </div>
                        <?= $this->Form->end(); ?>
                    </div>
                </div>
                <ul class="list-group chats"></ul>
                <div class="panel-footer">
                    Er zijn in totaal <strong><?= $this->Number->format($users); ?></strong> aantal leden die <strong><?= $this->Number->format($chats); ?></strong> berichten geplaatst hebben.
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <?= __('Last seen active'); ?>
                </div>
                <ul class="list-group user-activities">
                </ul>
                <div class="panel-footer">
                    <span class="role Administrator">Administrator</span>,
                    <span class="role Gebruiker">Gebruiker</span>,
                    <span class="role Tester">Tester</span>,
                    <span class="role Moderator">Moderator</span>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->Html->script('chatzor-2'); ?>