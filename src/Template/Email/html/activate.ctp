<style>
    .box {
        width:250px;
        margin:auto;
        background:#fff;
        border:1px solid #eee;
        font-family:sans-serif;
    }
    .box p {
        padding:10px;
        font-size:11px
    }
    .box h1 {
        padding:0px 10px;
        font-size:22px;
    }
    button {
        padding:10px;
        border:0;
        background:#3DA1F0;
        color:#fff;
    }
    button:hover {
        background:#3EB1F0
    }
    small {
        margin:10px;
        text-align:center;
        font-size:10px;
        color:#777;
    }
</style>
<div class="box">
    <h1>
        Welkom op Chatzor
    </h1>
    <p>
        Hey <?= $username; ?>, wat leuk dat je ook op Chatzor bent! We hopen je snel in de shoutbox te zien!
        Druk op de knop beneden om je e-mail te activeren!
    </p>
    <p style="text-align:center;">
        <a href="<?= $this->Url->build(['controller' => 'Mailkeys', 'action' => 'activate', $token]); ?>"><button><?= __('Activate my account'); ?></button></a>
    </p>
    <small>* Deze code werkt maar 24 uur!</small>
</div>
<p style="text-align:center">
    Copyright &copy; 2016 Finley Siebert
</p>
<p style="text-align:center">
    <small>** Mail bij problemen hulp@finleyhd.nl en stuur deze code mee: <?= $token; ?></small>
</p>