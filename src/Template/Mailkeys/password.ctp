<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <?= __('Reset your password'); ?>
                </div>
                <div class="panel-body">
                    <legend><?= __("Reset your password!"); ?></legend>
                    <p>Stel je nieuwe wachtwoord in!</p>
                    <?= $this->Form->create($new_user); ?>
                    <fieldset class="form-group">
                        <?= $this->Form->input('password', ['class' => 'form-control', 'value' => '']); ?>
                    </fieldset>
                    <fieldset class="form-group">
                        <?= $this->Form->input('password_verify', ['class' => 'form-control', 'type' => 'password']); ?>
                    </fieldset>
                    <?= $this->Form->button(__('Submit'), ['class' => 'btn btn-warning']); ?>
                    <?= $this->Form->end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>