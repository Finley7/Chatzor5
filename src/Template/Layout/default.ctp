<!DOCTYPE html>
<html>
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-type" content="text/html;charset=UTF-8">
    <title>
        Chatzor |
        <?= isset($title) ? $title : $this->fetch('title') ?>
    </title>
    <?= $this->Html->meta('icon') ?>
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">

    <?= $this->Html->meta('description', 'Chatzor: gratis chatten met een groep mensen!'); ?>
    <?= $this->Html->meta('title', 'Chatzor ~ ' . isset($title) ? $title : $this->fetch('title')); ?>
    <?= $this->Html->meta('keywords', 'chatten, dutch, chat, nederlands, groupchat, im'); ?>

    <meta name="author" content="Finley Siebert">

    <?= $this->Html->css('bootstrap.min.css') ?>
    <?= $this->Html->css('font-awesome.min.css') ?>
    <?= $this->Html->css('base.css') ?>

    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <?= $this->fetch('script') ?>

    <?= $this->Html->script('jquery-2.1.1.min'); ?>
    <?= $this->Html->script('jquery.timeago'); ?>
    <?= $this->Html->script('bootstrap.min'); ?>

</head>
<body>
<?= $this->Flash->render() ?>
<?= $this->Flash->render('auth') ?>

<nav class="navbar navbar-default menu-header">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#menu-collapse"
                    aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <?= $this->Html->link(__('Chatzor'), '/', ['class' => 'navbar-brand', 'style' => 'color:#fff;']); ?>
        </div>
        <div class="collapse navbar-collapse" id="menu-collapse">
            <ul class="nav navbar-nav navbar-right">
                <?php if(isset($user)): ?>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?= $user->username; ?> <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><?= $this->Html->link(__('My profile'), ['controller' => 'Users', 'action' => 'view', 'prefix' => false, $user->username]); ?></li>
                            <li><?= $this->Html->link(__('Settings'), ['controller' => 'Users', 'action' => 'settings', 'prefix' => false]); ?></li>
                            <li role="separator" class="divider"></li>
                            <li><?= $this->Form->postLink(__('Logout'), ['controller' => 'Users', 'action' => 'logout', 'prefix' => false]); ?></li>
                        </ul>
                    </li>
                    <li><?= $this->Html->link(__('Archive'), ['controller' => 'Pages', 'action' => 'archive', 'prefix' => false]); ?></li>
                    <?php if($user->hasPermission('management_pages_index')): ?>
                        <li><?= $this->Html->link(__('Management'), ['controller' => 'Pages', 'action' => 'index', 'prefix' => 'management']); ?></li>
                    <?php endif; ?>
                <?php endif; ?>
            </ul>
            <?php if (!isset($user)): ?>
                <?= $this->Form->create(null, ['url' => ['controller' => 'Users', 'action' => 'login'], ['navbar-form navbar-right class']]); ?>
                <div class="form-group">
                    <?= $this->Form->input('username', ['label' => false, 'placeholder' => __('Gebruikersnaam'), 'class' => 'form-control']); ?>
                </div>
                <div class="form-group">
                    <?= $this->Form->input('password', ['label' => false, 'placeholder' => __('Wachtwoord'), 'class' => 'form-control']); ?>
                </div>
                <?= $this->Form->button(__('Aanmelden'), ['class' => 'btn btn-default']); ?>
                <?= $this->Form->end(); ?>
            <?php endif; ?>
        </div>
    </div>
</nav>

<?= $this->fetch('content') ?>

<?= $this->element('footer'); ?>

</body>
</html>