<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <?= __('Reset your password'); ?>
                </div>
                <div class="panel-body">
                    <legend><?= __("Forgot your password?"); ?></legend>
                    <p>Maak je geen zorgen, we resetten hem voor je ;)</p>
                    <?= $this->Form->create(); ?>
                    <fieldset class="form-group">
                        <?= $this->Form->input('email', ['class' => 'form-control', 'required', 'email']); ?>
                    </fieldset>
                       <?= $this->Form->button(__('Submit'), ['class' => 'btn btn-warning']); ?>
                    <?= $this->Form->end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>