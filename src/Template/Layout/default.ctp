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
    <meta name="author" content="Finley Siebert">

    <?= $this->Html->css('bootstrap.min.css') ?>
    <?= $this->Html->css('font-awesome.min.css') ?>
    <?= $this->Html->css('base.css') ?>

    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <?= $this->fetch('script') ?>

    <script src="http://code.jquery.com/jquery-2.1.1.min.js"></script>

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
                            <li><?= $this->Html->link(__('My profile'), ['controller' => 'Users', 'action' => 'view', $user->username]); ?></li>
                            <li><?= $this->Html->link(__('Settings'), ['controller' => 'Users', 'action' => 'settings']); ?></li>
                            <li role="separator" class="divider"></li>
                            <li><?= $this->Form->postLink(__('Logout'), ['controller' => 'Users', 'action' => 'logout']); ?></li>
                        </ul>
                    </li>
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