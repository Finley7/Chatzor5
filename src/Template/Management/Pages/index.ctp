<div class="container">
    <div class="row">
        <?= $this->element('Menu/management-menu'); ?>
        <div class="col-md-9 col-xs-12 col-lg-9">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Welkom op het dashboard
                </div>
                <div class="panel-body">
                    <p>Hallo <?= $user->username;?>, hier zijn wat statistieken</p>
                    <div class="panel-panel-default panel-body">
                        Er is een totaal aantal van <strong><?= $this->Number->format($chats->count()); ?></strong> chatberichten
                        geschreven door <strong><?= $this->Number->format($users->count()); ?></strong> leden.
                        <br>
                        Hiervan zijn er <strong><?= $this->Number->format($chats->where(['whisper_to is not' => null])->count()); ?></strong> prive.
                        Van deze chatberichten zijn er <strong><?= $this->Number->format($chats->where(['deleted' => 1])->count()); ?></strong> verwijderd
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
