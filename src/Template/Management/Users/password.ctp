<div class="container">
    <div class="row">
        <?= $this->element('Menu/management-menu'); ?>
        <div class="col-md-9 col-xs-12 col-lg-9">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <?= __('Edit user password'); ?>
                </div>
                <div class="panel-body">
                    <legend><?= __('Change {0}\'s password', $editUser->username); ?></legend>
                    <?= $this->Form->create($editUser); ?>
                    <fieldset class="form-group">
                        <?= $this->Form->input('password', ['class' => 'form-control', 'value' => '', 'label' => __('New password')]); ?>
                    </fieldset>
                    <fieldset class="form-group">
                        <?= $this->Form->input('password_verify', ['class' => 'form-control', 'label' => __('Repeat new password')]); ?>
                    </fieldset>
                    <?= $this->Form->submit('Update', ['class' => 'btn btn-warning']);?>
                    <?= $this->Form->end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
