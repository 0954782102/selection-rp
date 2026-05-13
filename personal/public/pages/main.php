<?php $number = rand(1,3)?>
<div class='say say-1 wow fadeIn wait-3' style='<?php echo ($number == 1) ? "display:block;" : ""?>'> <img src='assets\img\top1.png'>
    <p><span>Выбери</span>
        <br>любую сферу
        <br><small>заработка!</small></p>
</div>
<div class='say say-1 say-2 wow fadeIn wait-3' style='<?php echo ($number == 2) ? "display:block;" : ""?>'> <img src='assets\img\top2.png'>
    <p><span>Управляй</span>
        <br>будущим
        <br><small>своей роли!</small></p>
</div>
<div class='say say-1 say-3 wow fadeIn wait-3' style='<?php echo ($number == 3) ? "display:block;" : ""?>'> <img src='assets\img\top3.png'>
    <p><span>Индивидуальное</span>
        <br>будущее
        <br><small>каждого игрока!</small></p>
</div>
<div class='row' style='position:relative;' data-toggle="modal" data-target="#viewVideo"> <a href='#' class='play animated1 infinite tada'><i class='yellowShadow'></i> <p>Посмотреть</p><p><small>Промо-ролик</small></p></a></div>
</div>
<div id='online'>
    <div class='container'>
        <div class='row'>
            <p class='onlineTitle wow flipInX'>Сейчас нас
                <br><span class='fontnumb fsize40 spincrement'><?php echo $func->online; ?></span></p>
            <div class='col-xs-12'>
                <?php if($need): ?>
                    <div class="grid-4 info">
                        <?php
                        foreach($func->servers as $key=>$value) {
                            include PUBLIC_DIR . "/servers.php";
                        }
                        ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<section id="play">
    <div id='howgogame'>
        <div class='container'>
            <div class='row'>
                <div class='col-xs-12 wow flipInX wait-1'>
                    <h1>Как начать играть</h1>
                    <p><small>расскажем и покажем</small></p>
                    <center><span class='particletext bubbles'><a href='https://www.youtube.com/watch?v=DRU0tiqGKK4' data-toggle="modal" data-target="#viewVideo2" class='youtubeButton'><i class="fa fa-caret-right animated infinite fajump" aria-hidden="true"></i> ролик-инструкция <i class="fa fa-youtube-play" aria-hidden="true"></i></a></span></center>
                </div>
            </div>
            <div class='row'>
                <div class='step step-1 wow flipInX wait-2'> <a href='http://62.109.6.51/GTA%20San%20Andreas.zip' class='downloadButton yellowShadow'><i class="fa fa-arrow-down animated infinite fajump" aria-hidden="true"></i></a>
                    <h2>Установите</h2>
                    <p>GTA: San Andreas на свой компьютер</p>
                </div>
                <div class='step step-2 wow flipInX wait-2'> <a href='http://files.sa-mp.com/sa-mp-0.3.7-install.exe' class='downloadButton yellowShadow'><i class="fa fa-arrow-down animated infinite fajump" aria-hidden="true"></i></a>
                    <h2>Установите</h2>
                    <p>клиент SA-MP в папку с игрой</p>
                </div>
                <div class='step step-3 wow flipInX wait-2'>
                    <p>Запустите клиент SA-MP и в поле NAME введите свой ник,
                        <br>затем нажмите на <img style='box-shadow:0 2px 20px #ffa200' src='assets\img\mark.png'> и добавьте наш сервер в избранное.
                        <br>Рекомендуем вам ознакомиться с основами Role Play режима.</p>
                </div>
            </div>
        </div>
    </div>
</section>
<div class="modal fade" id="viewVideo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <iframe width="100%" height="315" src="https://www.youtube.com/embed/DsphLWlGP84" frameborder="0" allowfullscreen=""></iframe>
        </div>
    </div>
</div>
<div class="modal fade" id="viewVideo2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <iframe width="100%" height="315" src="https://www.youtube.com/embed/pHQa5b3xVAk" frameborder="0" allowfullscreen=""></iframe>
        </div>
    </div>
</div>