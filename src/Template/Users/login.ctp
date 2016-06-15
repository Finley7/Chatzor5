<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <?= __('Log in with your account'); ?>
                </div>
                <div class="panel-body">
                    <legend><?= __('Log in on Chatzor'); ?></legend>
                    <?= $this->Form->create(); ?>
                    <fieldset class="form-group">
                        <?= $this->Form->input('username', ['class' => 'form-control']); ?>
                    </fieldset>
                    <fieldset class="form-group">
                        <?= $this->Form->input('password', ['class' => 'form-control']); ?>
                    </fieldset>
                    <?= $this->Form->button('Sign in', ['class' => 'btn btn-primary']); ?>
                    <?= $this->Html->link(__('Wachtwoord vergeten?'), ['controller' => 'Users', 'action' => 'forgot']); ?>
                    <?= $this->Form->end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>