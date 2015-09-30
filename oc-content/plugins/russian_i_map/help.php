<?php
/*
Plugin Name: Russian Interactive Map
Plugin URI: https://osclass-pro.com
Description: Russian Interactive Map
Version: 1.0
Author: DIS
Author URI: https://osclass-pro.com
Short Name: russian_i_map
*/
?>

<div id="settings_form" style="border: 1px solid #ccc; background: #eee; ">
    <div style="padding: 0 20px 20px;">
        <div>
            <fieldset>
                <legend>
                    <h1><?php _e('Помощь', 'russian_i_map'); ?></h1>
                </legend>
                <h2>
                    <?php _e('Размер карты можно задать в файле плагина map_ru.php в 86 строке. Дефолтные размеры:width: 960px; height: 500px', 'russian_i_map'); ?>
                </h2>
                <p>
                    <?php _e('Там же можно задать цвета карты строка 19 colorRegion = \'#14A7D1\' Цвет всех регионов и т.д.. В коде есть подсказки.', 'russian_i_map'); ?>
                </p>
                <p>
                    <?php _e('Для вызова карты вставьте в нужном месте, например, в файле Вашего шаблона main.php код', 'russian_i_map'); ?>:
                </p>
                <pre>

                    &lt;?php russian_i_map(); ?&gt;
                </pre>

                <br/>
                
                <center>

                    <h2>
                    Сайт автора: <a href="https://osclass-pro.com">Премиум плагины и шаблоны</a>
                    </h2>

                </center>

            </fieldset>
        </div>
    </div>
</div>