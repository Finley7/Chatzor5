<div class="container">
    <div class="row">
        <?= $this->element('Menu/management-menu'); ?>
        <div class="col-md-9 col-xs-12 col-lg-9">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <?= __('Add an user (automaticly activated)'); ?>
                </div>
                <div class="panel-body">
                    <legend><?= __('Add an user'); ?></legend>
                    <?= $this->Form->create($newUser); ?>
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
                    <fielset class="form-group">
                        <label for="role"><?= __('Role'); ?></label>
                        <br>
                        <select id="role" name="role[]" multiple class="form-control">
                            <?php foreach($roles as $role): ?>
                                <option value="<?= $role->id; ?>" <?= ($role->name == 'Gebruiker') ? 'selected' : ''; ?>>
                                    <?= $role->name; ?>
                                </option>

                            <?php endforeach; ?>
                        </select>
                    </fielset>
                    <br>
                    <?= $this->Form->button(__("Add user"), ['class' => 'btn btn-success']); ?>
                    <?= $this->Form->end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>