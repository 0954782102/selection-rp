</div>
<div id='content'>
    <div class='container'>
        <div class='row'>
            <h1 class='title wow fadeIn'>GOOGLE AUTHENTICATOR - Ваш захист облікового запису!</h1>
            <div class='col-xs-12'>

                <div class='col-xs-12'>
                    <center><h3 class='blockTitle wow fadeIn'>Загальна інформація</h3></center>
                    <div class='darkBlock wow fadeInRight'>
                        <div class='row'>
                            <center>Не вяхай бути, на ваш игровой обліковий запис спробують відкрити з чужого комп'ютера, ми запросимо підтвердження.
                                <br><br>
                                Для цього встановіть додаток на телефон
                                та сповідайтесь вимогам наведеним нижче.<br><br>
                                <a href='https://itunes.apple.com/ru/app/google-authenticator/id388497605'><img width='150' src='/assets/img/app.jpg'/></a>
                                <a href='https://www.windowsphone.com/s?appid=f758eb53-ff04-404b-9382-4d4e26f7bd46'><img width='150' src='/assets/img/wind.png'/></a>
                                <a href='https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2'><img width='150' src='/assets/img/goog.png'/></a>
                            </center>
                        </div>
                    </div>
                    <center><h3 class='blockTitle wow fadeIn'>ОТРИМАЙТЕ
                            QR-КОД</h3></center>
                    <div class='darkBlock wow fadeIn'>
                        <div class='row'>
                            <div class='col-md-3'>
                                <img src='<?php echo $ga->getQRCodeGoogleUrl($user->player[$tableconf['TABLE_NAME']],$_SESSION['ga_secret'],'grand-rp.ru');?>'>
                            </div>
                            <div class='col-md-9'>
                                При допомозі додатку Google Authenticator отримайте QR-код розташований зліва(використовуючи камеру вашого мобільного телефона).
                                <br><br>
                                Потім введіть одноразовий пароль(код) з додатку:
                                <form method='post'>
                                    <label for='inp-nickname'  class='labler yellowShadowText'>Ваш таїний код</label>
                                    <input id='inp-nickname' class='allInp' name='code' onkeypress='return check(event);'  type='text' required><i class='fa fa-warning' aria-hidden='true'></i>
                                    <center><button type='sumbit' class='doButton'><i class='fa fa-arrow-right' aria-hidden='true'></i> Продовжити</button></center>
                                </form>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function check(event)
    {
        if (event.keyCode==32)
        {
            alert('Не будь ставити пробіл');
            return false;
        }
    }
</script>
<script>
    $('.absoluter').addClass('dispblck');
    $('.absoluter .nice').addClass('animated fadeInUp');
    setTimeout(function() {
        $('.absoluter .nice').removeClass('fadeInUp');
        $('.absoluter .nice').addClass('fadeOutUp');
        setTimeout(function() {
            $('.absoluter').removeClass('dispblck');
            $('.absoluter .nice').removeClass('animated fadeOutUp');
        }, 1000);
    }, 3000);

    $('.absoluterone').addClass('dispblck');
    $('.absoluterone .bad').addClass('animated fadeInUp');
    setTimeout(function() {
        $('.absoluterone .bad').removeClass('fadeInUp');
        $('.absoluterone .bad').addClass('fadeOutUp');
        setTimeout(function() {
            $('.absoluterone').removeClass('dispblck');
            $('.absoluterone .bad').removeClass('animated fadeOutUp');
        }, 1000);
    }, 3000);
</script>