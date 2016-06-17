<div class="col-md-4 col-lg-4 col-xs-12">
    <div class="panel panel-default">
        <div class="panel-heading">
            Menu
        </div>
        <div class="list-group">
            <?= $this->Html->link('<i class="fa fa-dashboard"></i> ' . __('Dashboard'),
                [
                    'controller' => 'Pages',
                    'action' => 'dashboard',
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
