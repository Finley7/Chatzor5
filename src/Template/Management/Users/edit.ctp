<div class="container">
    <div class="row">
        <?= $this->element('Menu/management-menu'); ?>
        <div class="col-md-9 col-xs-12 col-lg-9">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <?= __('Edit user'); ?>
                </div>
                <div class="panel-body">
                    <legend>
                        <?= __('Edit user'); ?>
                        <?= $this->Html->link(__('Change password'), ['action' => 'password', $editUser->username], ['class' => 'btn btn-xs btn-danger pull-right']); ?>&nbsp;
                    </legend>
                    <fieldset class="form-group">
                        <?= $this->Html->image('uploads/avatars/' . $editUser->avatar, ['class' => 'img-circle avatar-image', 'style' => 'width:60px;height:60px']); ?>
                        <?= $this->Form->postButton(__('Reset avatar'), ['action' => 'avatarReset', $editUser->id], ['class' => 'btn btn-xs btn-warning']); ?>

                    </fieldset>
                    <?= $this->Form->create($editUser); ?>
                    <fieldset class="form-group">
                        <?= $this->Form->input('username', ['class' => 'form-control']); ?>
                    </fieldset>
                    <fielset class="form-group">

                    </fielset>
                    <fieldset class="form-group">
                        <?= $this->Form->input('email', ['class' => 'form-control']); ?>
                    </fieldset>
                    <fielset class="form-group">
                        <label for="role"><?= __('Role'); ?></label>
                        <br>
                        <?= $this->Form->select(
                            'roles._ids',
                            $roles,
                            [
                                'class' => 'form-control',
                                'label' => false,
                                'multiple' => true,
                                'default' => $hasRoles
                            ]) ?>
                        <?= $this->Form->error('status'); ?>
                    </fielset>
                    <br>
                    <fieldset class="form-group">
                        <?= $this->Form->submit(__('Update user'), ['class' => 'btn btn-warning']); ?>
                    </fieldset>

                    <?= $this->Form->end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
