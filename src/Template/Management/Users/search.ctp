<div class="container">
    <div class="row">
        <?= $this->element('Menu/management-menu'); ?>
        <div class="col-md-9 col-xs-12 col-lg-9">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <?= __('User management'); ?>
                </div>
                <div class="panel-body">
                    <?= $this->Form->create(null, [
                        'type' => 'get',
                        'url' => ['controller' => 'Users', 'action' => 'search'],
                        'id' => 'messageform',
                    ]);
                    ?>
                    <div class="col-md-6">
                        <div class="input-group">
                            <?= $this->Form->input('search_string', ['maxlength' => 150, 'autocomplete' => 'off', 'class' => 'form-control', 'label' => false, 'placeholder' => __('Search for an user')]); ?>
                            <span class="input-group-btn">
                             <?= $this->Form->button(__('Search'), ['class' => 'btn btn-warning', 'required']); ?>
                         </span>
                        </div>
                    </div>
                    <?= $this->Form->end(); ?>
                </div>
                <div class="panel-body">
                    <div class="alert alert-info"><?= __('There are {0} results found!', $users->count()); ?></div>
                </div>
                <?php if ($users->count() > 0): ?>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th><?= __('Avatar'); ?></th>
                                <th><?= __('Username'); ?></th>
                                <th><?= __('Primary role'); ?></th>
                                <th><?= __('Created'); ?></th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($users as $member): ?>
                                <tr>
                                    <td><?= $member->id; ?></td>
                                    <td><?= $this->Html->image('uploads/avatars/' . $member->avatar, ['class' => 'avatar-image img-circle', 'style' => 'width:40px;height:40px;']); ?></td>
                                    <td><span
                                            class="role <?= $member->primary_role->name; ?>"><?= $member->username; ?></span>
                                    </td>
                                    <td><?= $member->primary_role->name; ?></td>
                                    <td><?= $member->created->nice(); ?></td>
                                    <td>
                                        <?= $this->Html->link('<i class="fa fa-edit"></i> ' . __('Edit'),
                                            ['action' => 'edit', $member->username],
                                            ['class' => 'btn btn-xs btn-warning', 'escape' => false]
                                        );
                                        ?>
                                        <?= ($member->primary_role->name == 'Banned') ? $this->Form->postButton('<i class="fa fa-user-times"></i> ' . __('Un-Block'),
                                            ['action' => 'block', $member->id],
                                            ['class' => 'btn btn-xs btn-info', 'escape' => false]
                                        ) : $this->Form->postButton('<i class="fa fa-user-times"></i> ' . __('Block'),
                                            ['action' => 'block', $member->id],
                                            ['class' => 'btn btn-xs btn-danger', 'escape' => false]
                                        ); ?>

                                        <?= ($member->primary_role->name == 'Niet-geactiveerd') ? $this->Form->postButton('<i class="fa fa-check"></i> ' . __('Activate'),
                                            ['action' => 'activate', $member->id],
                                            ['class' => 'btn btn-xs btn-success', 'escape' => false]
                                        ) : '<button class="btn btn-xs btn-success" disabled><i class="fa fa-check"></i>' . __('Already activated') . '</button>'; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="panel-body">
                        <ul class="pagination pagination-sm">
                            <li class="page-item">
                                <?= $this->Paginator->prev(__('Back'), ['class' => 'page-link']) ?>
                            </li>
                            <li class="page-item">
                                <?= $this->Paginator->numbers(['class' => 'page-link']); ?>
                            </li>
                            <li class="page-item">
                                <?= $this->Paginator->next(__('Next'), ['class' => 'page-link']) ?>
                            </li>
                        </ul>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>