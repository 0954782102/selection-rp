</div>
</div>
</div>
<div id='content'>
    <div class='container'>
        <div class='row'>
            <h1 class='title wow fadeIn'>Особистий кабінет персонажа</h1>
            <div class='col-xs-12'>
                <div class='col-xs-4 textcenter leftinfo wow fadeInLeft'>
                    <div class='avatar'> <img src='/assets/img/ava.png'> </div>
                    <div class='level'><?php echo $user->player[$tableconf['TABLE_LEVEL']];?></div>
                    <p><small>рівень</small></p>
                    <p class='username'><?php echo $user->player[$tableconf['TABLE_NAME']];?></p>
                    <label>Очки досвіду</label>
                    <div id='progress' class='exp'><span><?php echo $user->player[$tableconf['TABLE_EXP']];?> / <?php echo $user->player[$tableconf['TABLE_LEVEL']]*4; ?></span><div style='width:<?php echo ($user->player[$tableconf['TABLE_EXP']]*100) / (4*$user->player[$tableconf['TABLE_LEVEL']]); ?>%'></div></div>
                    <label>Ситість</label>
                    <div id='progress' class='satiety'><span><?php echo $user->player[$tableconf['TABLE_EAT']]; ?> / 100</span><div style='width:<?php echo $user->player[$tableconf['TABLE_EAT']]; ?>%'></div></div>
                    <ul class='userMenu'>
                        <li><a href='/account/house/'><i class="fa fa-home" aria-hidden="true"></i> Мій Дім </a></li>
                        <li><a href='/account/business/'><i class="fa fa-university" aria-hidden="true"></i> Мій бізнес </a></li>
                        <li class='sep'><i class="fa fa-dot-circle-o" aria-hidden="true"></i> <i class="fa fa-dot-circle-o" aria-hidden="true"></i> <i class="fa fa-dot-circle-o" aria-hidden="true"></i></li>
                        <li><a href='/map/<?php echo strtolower($_SESSION['server']); ?>/'><i class="fa fa-map-marker" aria-hidden="true"></i> Мій штат</a></li>
                        <li><a href='/account/settings/'><i class="fa fa-cog" aria-hidden="true"></i> Моя Безпека </a></li>
                        <li><a href='/account/board/leader/'><i class="fa fa-star-half-o" aria-hidden="true"></i> Панель лідера </a></li>
                        <li><a href='/account/logout/'><i class="fa fa-toggle-off" aria-hidden="true"></i> Вийти </a></li>
                        <li class='sep'><i class="fa fa-dot-circle-o" aria-hidden="true"></i> <i class="fa fa-dot-circle-o" aria-hidden="true"></i> <i class="fa fa-dot-circle-o" aria-hidden="true"></i></li>

                    </ul>
                </div>
                <div class='col-xs-8'>
                    <?php if(empty($user->player[$tableconf['TABLE_GASECRET']])): ?>
                    <div class="isa_warning">
                        <center><i class="fa fa-warning"></i></center><br>
                        Шановний <?php echo $user->player[$tableconf['TABLE_NAME']]; ?>, у вас не встановлений Google Authenticator, наполегливо рекомендуємо  <a href="/security/google/">пройти процес установки</a> захисту.
                    </div>
                    <?php endif; ?>
                    <h3 class='blockTitle wow fadeIn'>Загальна інформація</h3>
                    <div class='darkBlock wow fadeInRight'>
                        <div class='row'>
                            <div class='col-xs-12'>
                                <div class='col-xs-4'>
                                    <p><small>Номер облікового запису</small></p>
                                    <p><span class='fontnumb'><span class='spincrement'><?php echo $user->player[$tableconf['TABLE_ID']];?></span></span></p>
                                </div>
                                <div class='col-xs-4'>
                                    <p><small>Адреса E-Mail</small></p>
                                    <p>
                                        <?php
                                        echo "***" . substr($user->player[$tableconf['TABLE_MAIL']], min(3, max(0, strcspn($user->player[$tableconf['TABLE_MAIL']], "@") - 3)));
                                        ?>

                                    </p>
                                </div>
                                <div class='col-xs-4'>
                                    <p><small>Стать</small></p>
                                    <p><i class="fa <?php echo $user->player[$tableconf['TABLE_SEX']] == 0 ? "fa-male" : "fa-female" ?>" aria-hidden="true"></i></p>
                                </div>
                                <div class='col-xs-4'>
                                    <p><small>ПІН-код</small></p>
                                    <p class='empty'><?php

                                        for($i=0; $i<strlen($user->player[$tableconf['TABLE_PIN']]); $i++){
                                            echo "*";
                                        }

                                        ?></p>
                                </div>
                                <div class='col-xs-4'>
                                    <p><small>Номер телефону</small></p>
                                    <p><span class='fontnumb'>#<?php echo $user->player[$tableconf['TABLE_PHONE']]; ?></span></p>
                                </div>
                                <div class='col-xs-4'>
                                    <p><small>На рахунку телефону</small></p>
                                    <p><i class='dollar'>$</i><span class='fontnumb'><?php echo $user->player[$tableconf['TABLE_PHONEMONEY']]; ?></span></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class='darkBlock wow fadeInRight wait-1'>
                        <div class='row'>
                            <div class='col-xs-12'>
                                <div class='col-xs-4'>
                                    <p><small>Кримінальний авторитет</small></p>
                                    <p><span class='fontnumb'><?php echo $user->player[$tableconf['TABLE_AUTHORITY']]; ?></span> Очок</p>
                                </div>
                                <div class='col-xs-4'>
                                    <p><small>Рівень розшуку</small></p>
                                    <p>
                                        <?php
                                        $zvezd = $user->player[$tableconf['TABLE_ZVEZD']];
                                        for($i=0; $i<6; $i++){
                                            if($zvezd != 0){
                                                echo '<i class="fa fa-star" aria-hidden="true"></i>';
                                                $zvezd--;
                                            }else echo '<i class="fa fa-star-o" aria-hidden="true"></i>';

                                        } ?>
                                    </p>
                                </div>
                                <div class='col-xs-4'>
                                    <p><small>Наркотики</small></p>
                                    <p class='empty'><span class='fontnumb'><?php echo $user->player[$tableconf['TABLE_DRUGS']]; ?></span> Грамм</p>
                                </div>
                                <div class='col-xs-4'>
                                    <p><small>Патрони</small></p>
                                    <p class='empty'><span class='fontnumb'><?php echo $user->player[$tableconf['TABLE_PATRONS']]; ?></span> Патронів</p>
                                </div>
                                <div class='col-xs-4'>
                                    <p><small>Репутація лідерства</small></p>
                                    <p><span class='fontnumb'><?php echo $user->player[$tableconf['TABLE_REPUTLEAD']]; ?></span> Очков</p>
                                </div>
                                <div class='col-xs-4'>
                                    <p><small>Статус адміністратора</small></p>
                                    <p><span class='fontnumb'><?php echo $user->player[$tableconf['TABLE_ADMIN']]; ?> Рівень</span></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <h3 class='blockTitle wow fadeIn'>Фінанси</h3>
                    <div class='darkBlock wow fadeIn'>
                        <div class='row'>
                            <div class='col-xs-12'>
                                <div class='col-xs-3'>
                                    <p><small>Готівкові гроші</small></p>
                                    <p><span class='fontnumb'><i class='dollar'>$</i><span class='spincrement'><?php echo $user->player[$tableconf['TABLE_MONEY']]; ?></span></span></p>
                                </div>
                                <div class='col-xs-3'>
                                    <p><small>Банківський рахунок</small></p>
                                    <p><span class='fontnumb'><i class='dollar'>$</i><span class='spincrement'><?php echo $user->player[$tableconf['TABLE_CASH']]; ?></span></p>
                                </div>
                                <div class='col-xs-3'>
                                    <p><small>Пожертвування</small></p>
                                    <p><span class='fontnumb'><i class='dollar'>$</i><span class='spincrement'><?php echo $user->player[$tableconf['TABLE_SCHADMONEY']]; ?></span></p>
                                </div>
                                <div class='col-xs-3'>
                                    <p class='yellow'><small>Пожертвування</small></p>
                                    <p><span class='fontnumb'><span class='spincrement'><?php echo $user->player[$tableconf['TABLE_DONATE']]; ?></span> <sup>грн.</sup></span></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <h3 class='blockTitle wow fadeIn'>Працевлаштування</h3>
                    <div class='darkBlock wow fadeIn'>
                        <div class='row'>
                            <div class='col-xs-12'>
                                <div class='col-xs-6'>
                                    <p><small>Організація</small></p>
                                    <p><span class='fontnumb'><span class='spincrement'><?php echo $func->getOrgan($user->player[$tableconf['TABLE_MEMBER']]); ?></span></span></p>
                                </div>
                                <div class='col-xs-6'>
                                    <p><small>Звання</small></p>
                                    <p><span class='fontnumb'><span class='spincrement'><?php echo $func->getUnit($user->player[$tableconf['TABLE_UNIT']]); ?></span></p>
                                </div><br>
                                <div class='col-xs-12'>
                                    <p><small>Робота</small></p>
                                    <p><span class='fontnumb'><span class='spincrement'><?php echo $func->getWork($user->player[$tableconf['TABLE_WORK']]); ?></span></p>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class='row'>
                        <div class='col-xs-12'>
                            <div class='col-xs-6'>
                                <h3 class='blockTitle wow fadeIn'>Ліцензії</h3>
                                <div class='darkBlock lic wow fadeIn'>
                                    <?php echo $func->getWals("lics"); ?>
                                </div>
                            </div>
                            <div class='col-xs-6'>
                                <h3 class='blockTitle wow fadeIn'>Володіння зброєю</h3>
                                <div class='darkBlock gun wow fadeIn'>
                                    <?php echo $func->getWals(); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
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