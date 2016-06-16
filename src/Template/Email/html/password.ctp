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
        background: #f04b3b;
        color:#fff;
    }
    button:hover {
        background: #af372c
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
        Reset je wachtwoord!
    </h1>
    <p>
        Hey <?= $username; ?>, Je hebt een aanvraag gedaan om je wachtwoord te veranderen! Klik op de knop beneden om dit te voltooien!
        Als jij dit niet was kun je deze mail negeren!
    </p>
    <p style="text-align:center;">
        <?= $this->Html->link(__('Request a new password'), ['controller' => 'Mailkeys', 'action' => 'password', $token], ['class' => 'button']); ?>
    </p>
    <small>* Deze code werkt maar 24 uur!</small>
</div>
<p style="text-align:center">
    Copyright &copy; 2016 Finley Siebert
</p>
<p style="text-align:center">
    <small>** Mail bij problemen hulp@finleyhd.nl en stuur deze code mee: <?= $token; ?></small>
</p>