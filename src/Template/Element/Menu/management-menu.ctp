<div class="col-md-3 col-lg-3 col-xs-12">
    <div class="panel panel-default">
        <div class="panel-heading">
            Menu
        </div>
        <div class="list-group">
            <?= $this->Html->link('<i class="fa fa-dashboard"></i> ' . __('Dashboard'),
                [
                    'controller' => 'Pages',
                    'action' => 'index',
                    'prefix' => 'management',
                ],
                [
                    'class' => 'list-group-item',
                    'escape' => false
                ]
            ); ?>
            <?= $this->Html->link('<i class="fa fa-comment"></i> ' . __('Chat management'),
                [
                    'controller' => 'Chats',
                    'action' => 'index',
                    'prefix' => 'management',
                ],
                [
                    'class' => 'list-group-item',
                    'escape' => false
                ]
            ); ?>
            <?= $this->Html->link('<i class="fa fa-users"></i> ' . __('User management'),
                [
                    'controller' => 'Users',
                    'action' => 'index',
                    'prefix' => 'management',
                ],
                [
                    'class' => 'list-group-item',
                    'escape' => false
                ]
            ); ?>
            <?= $this->Html->link('<i class="fa fa-user-times"></i> ' . __('Banning'),
                [
                    'controller' => 'Bans',
                    'action' => 'index',
                    'prefix' => 'management',
                ],
                [
                    'class' => 'list-group-item',
                    'escape' => false
                ]
            ); ?>
        </div>
    </div>
</div>
