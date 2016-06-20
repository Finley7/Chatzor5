<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <?= __("Change your avatar!"); ?>
                </div>
                <div class="panel-body">
                    <?= $this->Html->image('uploads/avatars/' . $editUser->avatar, ['class' => 'img-circle', 'style' => 'width:150px']); ?>
                    <?= $this->Form->create($editUser, ['type' => 'file']); ?>
                    <fieldset class="form-group">
                        <?= $this->Form->file('avatar'); ?>
                    </fieldset>
                    <?= $this->Form->submit(__('Update avatar')); ?>
                    <?= debug($editUser); ?>
                </div>
            </div>
        </div>
    </div>
</div>