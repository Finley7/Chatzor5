<div class="container">
    <div class="row">
        <div class="col-md-7">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <?= __("Log in on Chatzor"); ?>
                </div>
                <div class="panel-body">
                    <legend><?= __('Log in on Chatzor'); ?></legend>
                    <?= $this->Form->create(null, ['url' => ['controller' => 'Users', 'action' => 'login']]); ?>
                    <fieldset class="form-group">
                        <?= $this->Form->input('username', ['class' => 'form-control']); ?>
                    </fieldset>
                    <fieldset class="form-group">
                        <?= $this->Form->input('password', ['class' => 'form-control']); ?>
                    </fieldset>
                    <?= $this->Form->button('Sign in', ['class' => 'btn btn-primary']); ?>
                    <?= $this->Form->end(); ?>
                </div>
            </div>
        </div>
        <div class="col-md-5">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <?= __("Create an Chatzor account"); ?>
                </div>
                <div class="panel-body">
                    <legend><?= __('Create an Chatzor account'); ?></legend>
                    <?= $this->Form->create(null, ['url' => ['controller' => 'Users', 'action' => 'add']]); ?>
                    <fieldset class="form-group">
                        <?= $this->Form->input('username', ['class' => 'form-control']); ?>
                    </fieldset>
                    <fieldset class="form-group">
                        <?= $this->Form->input('password', ['class' => 'form-control']); ?>
                    </fieldset>
                    <fieldset class="form-group">
                        <?= $this->Form->input('password_verify', ['class' => 'form-control', 'type' => 'password']); ?>
                    </fieldset>
                    <fieldset class="form-group">
                        <?= $this->Form->input('email', ['class' => 'form-control']); ?>
                    </fieldset>
                    <?= $this->Form->button(__("Create an account!"), ['class' => 'btn btn-success']); ?>
                    <?= $this->Form->end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>