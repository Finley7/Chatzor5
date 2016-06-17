<?php
use Cake\Core\Configure;
use Cake\Error\Debugger;
if (Configure::read('debug')):
    $this->layout = 'dev_error';
    $this->assign('title', $message);
    $this->assign('templateName', 'error500.ctp');
    $this->start('file');
    ?>
    <?php if (!empty($error->queryString)) : ?>
    <p class="notice">
        <strong>SQL Query: </strong>
        <?= h($error->queryString) ?>
    </p>
<?php endif; ?>
    <?php if (!empty($error->params)) : ?>
    <strong>SQL Query Params: </strong>
    <?= Debugger::dump($error->params) ?>
<?php endif; ?>
    <?php
    echo $this->element('auto_table_warning');
    if (extension_loaded('xdebug')):
        xdebug_print_function_stack();
    endif;
    $this->end();
endif;
?>

<div class="container">
    <div class="row">
        <div style="margin:10px;" class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <?= __d('cake', 'An Internal Error Has Occurred') ?>
                </div>
                <div class="panel-body">
                <p class="error">
                    <strong><?= __d('cake', 'Error') ?>: </strong>
                    <?= h($message) ?>
                </p>
                </div>
            </div>
        </div>
    </div>
</div>