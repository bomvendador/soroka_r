<?php
    function osc_getRelativeWebURL() {
        $url = $_SERVER['REQUEST_URI'];
        $pos = strpos($url, '/oc-includes');
        return substr($url, 0, strpos($url, '/oc-includes'));
    }

    function osc_getAbsoluteWebURL() {
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on' ? 'https' : 'http';
        return $protocol . '://' . $_SERVER['HTTP_HOST'] . osc_getRelativeWebURL();
    }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="en-US" xml:lang="en-US">
    <head>
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
            <title>Osclass - инструкция</title>
            <link rel="stylesheet" type="text/css" media="all" href="<?php echo osc_getAbsoluteWebURL(); ?>/oc-includes/osclass/installer/install.css" />
    </head>
    <body>
        <div id="wrapper">
            <div id="container">
                <div id="header" class="readme">
                    <h1 id="logo">
                        <a href="http://os-class.ru/" target="_blank">
                            <img src="<?php echo osc_getAbsoluteWebURL(); ?>/oc-includes/images/osclass-logo.png" alt="Osclass" title="Osclass" />
                        </a>
                        <br/>
                        Version 3.5.6
                    </h1>
                </div>
                <div id="content">
                    <div id="introduction">
                        <h2 class="title">Инструкция по установке и обновлению.</h2>
                        <div class="space-left-10">
                            <p>
                                OSClass - это проект с открытым кодом для создания доски объявлений. В несколько шагов и вы запустите
                                доску объявленй. Некоторые особенности: простота установки, многоязычная поддержка, расширяемость с помощью плагинов
                                (карта сайта, сео URL) и многие другие возможности.
                            </p>
                        </div>
                    </div>
                    <div id="install">
                        <h2 class="title">Установка</h2>
                        <div class="space-left-10">
                            <p>Ниже описан пошаговый процесс установки:</p>
                            <ol>
                                <li>Скачайте и распакуйте OSClass на вашем компьютере.</li>
                                <li>Переместите распакованные файлы OSClass на ваш сервер.</li>
                                <li>Запустите скрипт установки OSClass по ссылке <code>oc-includes/osclass/install.php</code> в вашем браузере:
                                    <ul>
                                        <li>Если вы установили его в корневой каталог домена, вам придется перейти по ссылке: <code>http://example.com/oc-includes/osclass/install.php</code></li>
                                        <li>Если вы установили его в подкаталоге внутри домена, <em>classifieds</em>, например так, перейдите по такой ссылке: <code>http://example.com/classifieds/oc-includes/osclass/install.php</code></li>
                                    </ul>
                                </li>
                                <li>Следуйте инструкциям установщика:
                                    <ul>
                                        <li>Прежде всего, убедитесь, что сервер имеет необходимые разрешения для записи в файлов и каталогов. Это позволит вам создать базовый файл конфигурации, а также загружать изображения, документы и т.д.</li>
                                        <li>Шаг 1: Добавьте информацию для доступа к базе данных. Если вы еще не создали ее еще нет, программа установки просит другую учетную запись с разрешениямикоторый позволяет вам сделать за вас.</li>
                                        <li>Шаг 2: Добавьте основные сведения для установки и выберите ваши данные для добавления объявлений: страна, регионы, города ...</li>
                                        <li>Шаг 3: Выберите категории, которые хотите использовать на своем сайте. Если вы не выберите категорию, вам придется добавить его позже из админ-панели.</li>
                                        <li>Установка завершена! Используйте автоматически сгенерированный пароль для доступа к админ-панели (/oc-admin).</li>
                                    </ul>
                                </li>
                            </ol>
                        </div>
                    </div>
                     <div id="upgrade">
                        <h2 class="title">Как обновить</h2>
                        <p>
                            OSClass выводит сообщение о автоматическом обновлении в панели администратора, если вышла новая (и стабильная) версия. Только нужно следовать инструкциям до начала обновления.
                            Мы рекомендуем сделать резервную копию, прежде чем пытаться обновить OSClass, вы можете выполнить это из панели администратора (если вы изменили 
                            основной файл, онвероятно будетт заменен новым версии программного обеспечения. Будьте осторожны).
                        </p>
                        <div class="space-left-10"><h3 style="border-bottom: 1px solid grey;color: #444444;">Автообновление</h3>
                            <p>Функция автоматического обновления выполнить следующие шаги:
                                <ul>
                                    <li>Шаг 1: Проверит, есть ли новые версии OSClass.</li>
                                    <li>Шаг 2: Загрузит последнюю версию.</li>
                                    <li>Шаг 3: Разархивирует его.</li>
                                    <li>Шаг 4: Удаление старых файлов, копирование новых (помните: если вы редактировали файлы ядра, они будет заменены новыми).</li>
                                    <li>Шаг 5: Выполнение изменений в таблицах (при необходимости).</li>
                                    <li>Шаг 6: Экстра-действия (при необходимости).</li>
                                </ul>
                            </p>
                         </div>
                        <p>Перейдите по ссылке и через несколько секунд вы будете наслаждаться
                            новой версией вашего любимого скрипта доски объявлений. Вы наверно ожидали больше шагов или сложных инструкций? Извините, но мы сделали все просто.
                        </p>
                        <div class="space-left-10"><h3 style="border-bottom: 1px solid grey;color: #444444;">Обновление вручную</h3>
                            <p>
                                Вы также можете обновить OSClass загрузив пакет обновления, распаковать его и заменить файлы на сервере с теми путями.
                                Затем запустить вручную oc-includes/osclass/upgrade-funcs.php для окончания установки.
                            </p>
                        </div>
                        <p>Если у вас возникли проблемы во время процесса обновления, пожалуйста, не стесняйтесь связаться с нами на <a href="http://os-class.ru/frm/">форуме OSClass Rus Team</a>.
                            Мы рекомендуем выполнить резервное копирование базы данных и файлов перед каждым обновлением. Вы можете сделать резервную копию данных с помощью опции "Backup" админ-панели.
                            Если вы хотите запустить автоматическое обновление вручную, вам необходимо сделать это по следующей ссылке: http://www.yourdomain.com/path/to/osclass/oc-admin/tools.php?action=upgrade
                        </p>
                    </div>
                    <div id="resources">
                        <h2 class="title">Интернет-ресурсы</h2>
                        <div class="space-left-10">
                            <p>Если у вас есть вопросы, которые не рассматриваются в данном документе, пожалуйста, посмотрите ответы в интернет-ресурсах:</p>
                            <dl class="space-left-25">
                                <dt><a href="http://doc.osclass.org/" target="_blank">OSClass Wiki</a></dt>
                                <dt><a href="http://os-class.ru/wiki/index.php" target="_blank">OSClass Wiki Rus</a></dt>
                                <dd>
                                    Вики, где находится вся информация об OSClass.
                                </dd>
                                <dt><a href="http://osclass.org/blog/" target="_blank">OSClass Blog</a></dt>
                                <dt><a href="http://os-class.ru/category/articles" target="_blank">Статьи об OSClass</a></dt>
                                <dd>
                                    Здесь вы найдете последние обновления и новости, связанные с OSClass.
                                </dd>
                                <dt><a href="http://forums.osclass.org/" target="_blank">OSClass Support Forums</a></dt>
                                <dt><a href="http://os-class.ru/frm/" target="_blank">Форум Русской поддержки OSClass</a></dt>
                                <dd>
                                    Если вы смотрели везде и до сих пор не может найти ответа.
                                    Свяжитесь с нами, мы всегда готовы помочь вам!
                                </dd>
                            </dl>
                        </div>
                    </div>
                    <div id="license">
                        <h2 class="title">Лицензия</h2>
                        <p class="space-left-10">Osclass выпускается под лицензией GPLv3 (смотрите <a href="<?php echo osc_getAbsoluteWebURL(); ?>/licenses.txt" target="_blank">licenses.txt</a>).</p>
                    </div>
                </div>
                <div id="footer">
                    <ul>
                        <li><a href="http://admin.osclass.org/feedback.php" target="_blank">Feedback</a></li>
                        <li><a href="http://forums.osclass.org/index.php" target="_blank">Forums</a></li>
                        <li><a href="http://os-class.ru/?do=feedback" target="_blank">Обратная связь</a></li>
                        <li><a href="http://os-class.ru/frm/" target="_blank">Форум</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </body>
</html>