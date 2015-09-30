<?php
/*
 * Copyright 2014 Osclass
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

/*
 * The url of the site
 *
 * @since 1.2
 *
 * @return string The url of the site
 */
function get_absolute_url( ) {
    $protocol = ( isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on' ) ? 'https' : 'http';
    $pos      = strpos($_SERVER['REQUEST_URI'], 'oc-includes');
    $URI      = rtrim( substr( $_SERVER['REQUEST_URI'], 0, $pos ), '/' ) . '/';
    return $protocol . '://' . $_SERVER['HTTP_HOST'] . $URI;
}

/*
 * The relative url on the domain url
 *
 * @since 1.2
 *
 * @return string The relative url on the domain url
 */
function get_relative_url( ) {
    $url = $_SERVER['REQUEST_URI'];
    return substr($url, 0, strpos($url, '/oc-includes')) . "/";
}

/*
 * Get the requirements to install Osclass
 *
 * @since 1.2
 *
 * @return array Requirements
 */
function get_requirements( ) {
    $array = array(
        'Версия PHP >= 5.x' => array(
            'requirement' => __('Версия PHP >= 5.x'), 
            'fn' => version_compare(PHP_VERSION, '5.0.0', '>='), 
            'solution' => __('PHP5 необходим для запуска OSClass. Вы можете уточнить версию PHP на вашем сервере.')),

        'Библиотека MySQLi для PHP' => array(
            'requirement' => __('MySQLi библиотека PHP'), 
            'fn' => extension_loaded('mysqli'), 
            'solution' => __('MySQLi расширение обязательно. Как <a target="_blank" href="http://www.php.net/manual/en/mysqli.setup.php">установить/настроить</a>.')),

        'Библиотека GD для PHP' => array(
            'requirement' => __('GD extension for PHP'), 
            'fn' => extension_loaded('gd'), 
            'solution' => __('GD extension is required. How to <a target="_blank" href="http://www.php.net/manual/en/image.setup.php">install/configure</a>.')),

        'Папка <code>oc-content/uploads</code> существует' => array(
            'requirement' => __('Папка <code>oc-content/uploads</code> существует'), 
            'fn' => file_exists( ABS_PATH . 'oc-content/uploads/' ), 
            'solution' => sprintf(__('Вы должны создать папку <code>uploads</code>, например: <code>mkdir %soc-content/uploads/</code>' ), ABS_PATH)),

        'Папка <code>oc-content/uploads</code> доступна для записи' => array(
            'requirement' => __('<code>oc-content/uploads</code> папка для записи'), 
            'fn' => is_writable( ABS_PATH . 'oc-content/uploads/' ), 
            'solution' => sprintf(__('<code>uploads</code> папка должна быть доступна для записи, например: <code>chmod a+w %soc-content/uploads/</code>'), ABS_PATH)),

        'Папка <code>oc-content/languages</code> существует' => array(
            'requirement' => __('<code>oc-content/languages</code> папка существует'), 
            'fn' => file_exists( ABS_PATH . 'oc-content/languages/' ), 
            'solution' => sprintf(__('Вы должны создать папку <code>languages</code>, например: <code>mkdir %soc-content/languages/</code>'), ABS_PATH)),

        'Папка <code>oc-content/downloads</code> доступна для записи' => array(
            'requirement' => __('Папка доступна для записи <code>oc-content/downloads</code>'),
            'fn' => is_writable( ABS_PATH . 'oc-content/downloads/' ),
            'solution' => sprintf(__('Папка <code>downloads</code>должна быть доступна для записи, т.е .: <code>chmod a+w %soc-content/downloads/</code>'), ABS_PATH)),
        // oc-content/languages
        'Папка <code>oc-content/languages</code> существует' => array(
            'requirement' => __('Папка <code>oc-content/languages</code> существует'),
            'fn' => file_exists( ABS_PATH . 'oc-content/languages/' ),
            'solution' => sprintf(__('Вы должны создать папку <code>languages</code>, т.е.: <code>mkdir %soc-content/languages/</code>'), ABS_PATH)),

        'Папка <code>oc-content/languages</code> доступна для записи' => array(
            'requirement' => __('Папка <code>oc-content/languages</code> доступна для записи'),
            'fn' => is_writable( ABS_PATH . 'oc-content/languages/' ),
            'solution' => sprintf(__('Папка <code>languages</code> должна быть доступна для записи, т.е.: <code>chmod a+w %soc-content/languages/</code>'), ABS_PATH)),
    );

    $config_writable = false;
    $root_writable = false;
    $config_sample = false;
    if( file_exists(ABS_PATH . 'config.php') ) {
        if( is_writable(ABS_PATH . 'config.php') ) {
            $config_writable = true;
        }
        $array['Файл <code>config.php</code> доступен для записи'] = array(
            'requirement' => __('<code>config.php</code> файл доступен для записи'), 
            'fn' => $config_writable, 
            'solution' => sprintf(__('Файл <code>config.php</code> должен быть доступен для записи, например: <code>chmod a+w %sconfig.php</code>'), ABS_PATH));
    } else {
        if (is_writable(ABS_PATH) ) {
            $root_writable = true;
        }
        $array['Корневой каталог доступен для записи'] = array(
            'requirement' => __('Корневой каталог доступен для записи'), 
            'fn' => $root_writable, 
            'solution' => sprintf(__('Корневой каталог д.б. доступен для записи, например: <code>chmod a+w %s</code>'), ABS_PATH));

        if( file_exists(ABS_PATH . 'config-sample.php') ) {
            $config_sample = true;
        }
        $array['Файл <code>config-sample.php</code> существует'] = array(
            'requirement' => __('<code>config-sample.php</code> файл существует'), 
            'fn' => $config_sample, 
            'solution' => __('<code>config-sample.php</code> требуется файл, вы должны повторно загрузить OSClass.'));
    }

    return $array;
}


/**
 * Check if some of the requirements to install Osclass are correct or not
 *
 * @since 1.2
 *
 * @return boolean Check if all the requirements are correct
 */
function check_requirements($array) {
    foreach($array as $k => $v) {
        if( !$v['fn'] ) return true;
    }
    return false;
}

/**
 * Check if allowed to send stats to Osclass
 *
 * @return boolean Check if allowed to send stats to Osclass
 */
function reportToOsclass() {
    return $_COOKIE['osclass_save_stats'];
}

/**
 * insert/update preference allow_report_osclass
 * @param boolean $bool
 */
function set_allow_report_osclass($value) {
    $values = array(
        's_section' => 'osclass',
        's_name'    => 'allow_report_osclass',
        's_value'   => $value,
        'e_type'    => 'BOOLEAN'
    );

    Preference::newInstance()->insert($values);
}

/*
 * Install Osclass database
 *
 * @since 1.2
 *
 * @return mixed Error messages of the installation
 */
function oc_install( ) {
    $dbhost      = Params::getParam('dbhost');
    $dbname      = Params::getParam('dbname');
    $username    = Params::getParam('username');
    $password    = Params::getParam('password', false, false);
    $tableprefix = Params::getParam('tableprefix');
    $createdb    = false;
    require_once LIB_PATH . 'osclass/helpers/hSecurity.php';

    if( $tableprefix == '' ) {
        $tableprefix = 'oc_';
    }

    if( Params::getParam('createdb') != '' ) {
        $createdb = true;
    }

    if ( $createdb ) {
        $adminuser = Params::getParam('admin_username');
        $adminpwd  = Params::getParam('admin_password', false, false);

        $master_conn = new DBConnectionClass($dbhost, $adminuser, $adminpwd, '');
        $error_num   = $master_conn->getErrorConnectionLevel();

        if( $error_num > 0 ) {
            if( reportToOsclass() ) {
                LogOsclassInstaller::instance()->error(sprintf(__('Cannot connect to the database. Error number: %s') , $error_num ), __FILE__."::".__LINE__);
            }

            switch ($error_num) {
                case 1049:  return array('error' => __("База данных не существует. Необходимо проверить возможность подключения к базе данных. Номер ошибки: \"Create DB\" флажок и заполнить имя пользователя и пароль с правом привилегий")) ;
                break;
                case 1045:  return array('error' => __('Не удается подключиться к базе данных. Проверьтеесли у пользователя права.')) ;
                break;
                case 1044:  return array('error' => __('Не удается подключиться к базе данных. Проверьте имя пользователя и пароль.')) ;
                break;
                case 2005:  return array('error' => __("Не могу определить MySQL хост. Проверьте правильность хоста.")) ;
                break;
                default:    return array('error' => sprintf(__('Не удается подключиться к базе данных. Номер ошибки: %s')), $error_num) ;
                break;
            }
        }

        $m_db = $master_conn->getOsclassDb();
        $comm = new DBCommandClass( $m_db );
        $comm->query( sprintf("CREATE DATABASE IF NOT EXISTS %s DEFAULT CHARACTER SET 'UTF8' COLLATE 'UTF8_GENERAL_CI'", $dbname) );

        $error_num = $comm->getErrorLevel();

        if( $error_num > 0 ) {
            if( reportToOsclass() ) {
                LogOsclassInstaller::instance()->error(sprintf(__("Не удается подключиться к базе данных. Номер ошибки: %s"), $error_num) , __FILE__."::".__LINE__) ;
            }

            if( in_array( $error_num, array(1006, 1044, 1045) ) ) {
                return array('error' => __("Не удается подключиться к базе данных. Проверьте имя пользователя и пароль администратора.")) ;
            }

            return array('error' => sprintf(__("Невозможно создать базу данных. Номер ошибки: %s"),  $error_num)) ;
        }

        unset($conn);
        unset($comm);
        unset($master_conn);
    }

    $conn      = new DBConnectionClass($dbhost, $username, $password, $dbname);
    $error_num = $conn->getErrorConnectionLevel();

    if( $error_num == 0 ) {
        $error_num = $conn->getErrorLevel();
    }

    if( $error_num > 0 ) {
        if( reportToOsclass() ) {
            LogOsclassInstaller::instance()->error(sprintf(__('Cannot connect to the database. Error number: %s'), $error_num) , __FILE__."::".__LINE__);
        }

        switch( $error_num ) {
            case 1049:  return array('error' => __("База данных не существует. Необходимо проверить \"Create DB\" флажок и заполнить имя пользователя и пароль с правом привилегий")) ;
            break ;
            case 1045:  return array('error' => __('Не удается подключиться к базе данных. Проверьте если у пользователя есть привилегии.')) ;
            break ;
            case 1044:  return array('error' => __('Не удается подключиться к базе данных. Проверьте имя пользователя и пароль.')) ;
            break ;
            case 2005:  return array('error' => __("Не могу определить MySQL хост.")) ;
            break ;
            default:    return array('error' => sprintf(__('Не удается подключиться к базе данных. Номер ошибки: %s'), $error_num)) ;
            break ;
        }
    }

    if( file_exists(ABS_PATH . 'config.php') ) {
        if( !is_writable(ABS_PATH . 'config.php') ) {
            if( reportToOsclass() ) {
                LogOsclassInstaller::instance()->error(__("Не удается выполнить запись в файл config.php. Проверьте доступность файла для записи.") , __FILE__."::".__LINE__) ;
            }
            return array('error' => __("Не удается выполнить запись в файл config.php. Проверьте доступность файла для записи."));
        }
        create_config_file($dbname, $username, $password, $dbhost, $tableprefix);
    } else {
        if( !file_exists(ABS_PATH . 'config-sample.php') ) {
            if( reportToOsclass() ) {
                LogOsclassInstaller::instance()->error(__("config-sample.php не существует. Проверьте все ли файлы правильно распакованы.") , __FILE__."::".__LINE__) ;
            }

            return array('error' => __("config-sample.php не существует. Проверьте все ли файлы правильно распакованы."));
        }
        if( !is_writable(ABS_PATH) ) {
            if( reportToOsclass() ) {
                LogOsclassInstaller::instance()->error(__('Не удается скопировать config-sample.php. Проверьте доступен ли для записи корневой каталог.') , __FILE__."::".__LINE__) ;
            }

            return array('error' => __('Не удается скопировать config-sample.php. Проверьте доступен ли для записи корневой каталог.'));
        }
        copy_config_file($dbname, $username, $password, $dbhost, $tableprefix);
    }

    require_once ABS_PATH . 'config.php';

    $sql = file_get_contents( ABS_PATH . 'oc-includes/osclass/installer/struct.sql' ) ;

    $c_db = $conn->getOsclassDb();
    $comm = new DBCommandClass( $c_db );
    $comm->importSQL($sql);

    $error_num = $comm->getErrorLevel();

    if( $error_num > 0 ) {
        if( reportToOsclass() ) {
            LogOsclassInstaller::instance()->error(sprintf(__("Не удается создать структуру базы данных. Номер ошибки: %s"), $error_num)  , __FILE__."::".__LINE__) ;
        }

        switch ($error_num) {
            case 1050:  return array('error' => __('Таблицы с таким же именем уже существуют в базе данных. Измените префикс таблиц или базу данных и повторите попытку.')) ;
            break;
            default:    return array('error' => sprintf(__("Не удается создать структуру базы данных. Номер ошибки: %s"), $error_num)) ;
            break;
        }
    }

    require_once LIB_PATH . 'osclass/model/OSCLocale.php';
    $localeManager = OSCLocale::newInstance();

    $locales = osc_listLocales();
    $values = array(
        'pk_c_code'         => $locales[osc_current_admin_locale()]['code'],
        's_name'            => $locales[osc_current_admin_locale()]['name'],
        's_short_name'      => $locales[osc_current_admin_locale()]['short_name'],
        's_description'     => $locales[osc_current_admin_locale()]['description'],
        's_version'         => $locales[osc_current_admin_locale()]['version'],
        's_author_name'     => $locales[osc_current_admin_locale()]['author_name'],
        's_author_url'      => $locales[osc_current_admin_locale()]['author_url'],
        's_currency_format' => $locales[osc_current_admin_locale()]['currency_format'],
        's_date_format'     => $locales[osc_current_admin_locale()]['date_format'],
        'b_enabled'         => 1,
        'b_enabled_bo'      => 1
    );

    if( isset($locales[osc_current_admin_locale()]['stop_words']) ) {
        $values['s_stop_words'] = $locales[osc_current_admin_locale()]['stop_words'];
    }
    $localeManager->insert($values);


    $required_files = array(
        ABS_PATH . 'oc-includes/osclass/installer/basic_data.sql',
        ABS_PATH . 'oc-includes/osclass/installer/pages.sql',
        ABS_PATH . 'oc-content/languages/' . osc_current_admin_locale() . '/mail.sql',
    );

    $sql = '';
    foreach($required_files as $file) {
        if ( !file_exists($file) ) {
            if( reportToOsclass() ) {
                LogOsclassInstaller::instance()->error(sprintf(__('Файл %s не существует'), $file) , __FILE__."::".__LINE__) ;
            }

            return array('error' => sprintf(__('Файл %s не существует'), $file) );
        } else {
            $sql .= file_get_contents($file);
        }
    }
    $comm->importSQL($sql);

    $error_num = $comm->getErrorLevel();

    if( $error_num > 0 ) {
        if( reportToOsclass() ) {
            LogOsclassInstaller::instance()->error(sprintf(__("Не удается установить базовую конфигурацию. Номер ошибки: %s"), $error_num)  , __FILE__."::".__LINE__) ;
        }

        switch ($error_num) {
            case 1471:  return array('error' => __("Не удается установить базовую конфигурацию. Этот пользователь не имеет привилегий делать записи 'INSERT' в базе данных."));
            break;
            default:    return array('error' => sprintf(__("Не удается установить базовую конфигурацию. Номер ошибки: %s"), $error_num));
            break;
        }
    }

    osc_set_preference('language', osc_current_admin_locale());
    osc_set_preference('admin_language', osc_current_admin_locale());
    osc_set_preference('csrf_name', 'CSRF'.mt_rand(0,mt_getrandmax()));

    oc_install_example_data();


    if( reportToOsclass() ) {
        set_allow_report_osclass( true );
    } else {
        set_allow_report_osclass( false );
    }

    return false;
}

/*
 * Insert the example data (categories and emails) on all available locales
 *
 * @since 2.4
 *
 * @return mixed Error messages of the installation
 */
function oc_install_example_data() {
    require_once LIB_PATH . 'osclass/formatting.php';
    require LIB_PATH . 'osclass/installer/basic_data.php';
    require_once LIB_PATH . 'osclass/model/Category.php';
    $mCat = Category::newInstance();

    if(!function_exists('osc_apply_filter')) {
        function osc_apply_filter($dummyfilter, $str) {
            return $str;
        }
    }

    foreach($categories as $category) {

        $fields['pk_i_id']              = $category['pk_i_id'];
        $fields['fk_i_parent_id']       = $category['fk_i_parent_id'];
        $fields['i_position']           = $category['i_position'];
        $fields['i_expiration_days']    = 0;
        $fields['b_enabled']            = 1;

        $aFieldsDescription[osc_current_admin_locale()]['s_name'] = $category['s_name'];

        $mCat->insert($fields, $aFieldsDescription);
    }

    require_once LIB_PATH . 'osclass/model/Item.php';
    require_once LIB_PATH . 'osclass/model/ItemComment.php';
    require_once LIB_PATH . 'osclass/model/ItemLocation.php';
    require_once LIB_PATH . 'osclass/model/ItemResource.php';
    require_once LIB_PATH . 'osclass/model/ItemStats.php';
    require_once LIB_PATH . 'osclass/model/User.php';
    require_once LIB_PATH . 'osclass/model/Country.php';
    require_once LIB_PATH . 'osclass/model/Region.php';
    require_once LIB_PATH . 'osclass/model/City.php';
    require_once LIB_PATH . 'osclass/model/CityArea.php';
    require_once LIB_PATH . 'osclass/model/Field.php';
    require_once LIB_PATH . 'osclass/model/Page.php';
    require_once LIB_PATH . 'osclass/model/Log.php';

    require_once LIB_PATH . 'osclass/model/CategoryStats.php';
    require_once LIB_PATH . 'osclass/model/CountryStats.php';
    require_once LIB_PATH . 'osclass/model/RegionStats.php';
    require_once LIB_PATH . 'osclass/model/CityStats.php';

    require_once LIB_PATH . 'osclass/helpers/hSecurity.php';
    require_once LIB_PATH . 'osclass/helpers/hValidate.php';
    require_once LIB_PATH . 'osclass/helpers/hUsers.php';
    require_once LIB_PATH . 'osclass/ItemActions.php';

    $mItem = new ItemActions(true);

    foreach($item as $k => $v) {
        if($k=='description' || $k=='title') {
            Params::setParam($k, array(osc_current_admin_locale() => $v));
        } else {
            Params::setParam($k, $v);
        }
    }

    $mItem->prepareData(true);
    $successItem = $mItem->add();

    $successPageresult = Page::newInstance()->insert(
        array(
            's_internal_name' => $page['s_internal_name'],
            'b_indelible' => 0,
            's_meta' => json_encode('')
        ),
        array(
            osc_current_admin_locale() => array(
                's_title' => $page['s_title'],
                's_text' => $page['s_text']
            )
        ));

}

/*
 * Create config file from scratch
 *
 * @since 1.2
 *
 * @param string $dbname Database name
 * @param string $username User of the database
 * @param string $password Password for user of the database
 * @param string $dbhost Database host
 * @param string $tableprefix Prefix for table names
 * @return mixed Error messages of the installation
 */
function create_config_file($dbname, $username, $password, $dbhost, $tableprefix) {
    $password = addslashes($password);
    $abs_url = get_absolute_url();
    $rel_url = get_relative_url();
    $config_text = <<<CONFIG
<?php
/**
 * The base MySQL settings of Osclass
 */
define('MULTISITE', 0);

/** MySQL database name for Osclass */
define('DB_NAME', '$dbname');

/** MySQL database username */
define('DB_USER', '$username');

/** MySQL database password */
define('DB_PASSWORD', '$password');

/** MySQL hostname */
define('DB_HOST', '$dbhost');

/** Database Table prefix */
define('DB_TABLE_PREFIX', '$tableprefix');

define('REL_WEB_URL', '$rel_url');

define('WEB_PATH', '$abs_url');

CONFIG;

    file_put_contents(ABS_PATH . 'config.php', $config_text);
}

/*
 * Create config from config-sample.php file
 *
 * @since 1.2
 */
function copy_config_file($dbname, $username, $password, $dbhost, $tableprefix) {
    $password = addslashes($password);
    $abs_url = get_absolute_url();
    $rel_url = get_relative_url();
    $config_sample = file(ABS_PATH . 'config-sample.php');

    foreach ($config_sample as $line_num => $line) {
        switch (substr($line, 0, 16)) {
            case "define('DB_NAME'":
                $config_sample[$line_num] = str_replace("database_name", $dbname, $line);
                break;
            case "define('DB_USER'":
                $config_sample[$line_num] = str_replace("'username'", "'$username'", $line);
                break;
            case "define('DB_PASSW":
                $config_sample[$line_num] = str_replace("'password'", "'$password'", $line);
                break;
            case "define('DB_HOST'":
                $config_sample[$line_num] = str_replace("localhost", $dbhost, $line);
                break;
            case "define('DB_TABLE":
                $config_sample[$line_num] = str_replace('oc_', $tableprefix, $line);
                break;
            case "define('REL_WEB_":
                $config_sample[$line_num] = str_replace('rel_here', $rel_url, $line);
                break;
            case "define('WEB_PATH":
                $config_sample[$line_num] = str_replace('http://localhost', $abs_url, $line);
                break;
        }
    }

    $handle = fopen(ABS_PATH . 'config.php', 'w');
    foreach( $config_sample as $line ) {
        fwrite($handle, $line);
    }
    fclose($handle);
    chmod(ABS_PATH . 'config.php', 0666);
}


function is_osclass_installed( ) {
    if( !file_exists( ABS_PATH . 'config.php' ) ) {
        return false;
    }

    require_once ABS_PATH . 'config.php';

    $conn = new DBConnectionClass( osc_db_host(), osc_db_user(), osc_db_password(), osc_db_name() );
    $c_db = $conn->getOsclassDb();
    $comm = new DBCommandClass( $c_db );
    $rs   = $comm->query( sprintf( "SELECT * FROM %st_preference WHERE s_name = 'osclass_installed'", DB_TABLE_PREFIX ) );

    if( $rs == false ) {
        return false;
    }

    if( $rs->numRows() != 1 ) {
        return false;
    }

    return true;
}

function finish_installation( $password ) {
    require_once LIB_PATH . 'osclass/model/Admin.php';
    require_once LIB_PATH . 'osclass/model/Category.php';
    require_once LIB_PATH . 'osclass/model/Item.php';
    require_once LIB_PATH . 'osclass/helpers/hPlugins.php';
    require_once LIB_PATH . 'osclass/compatibility.php';
    require_once LIB_PATH . 'osclass/classes/Plugins.php';

    $data = array();

    $mAdmin = new Admin();

    $mPreference = Preference::newInstance();
    $mPreference->insert (
        array(
            's_section' => 'osclass'
        ,'s_name' => 'osclass_installed'
        ,'s_value' => '1'
        ,'e_type' => 'BOOLEAN'
        )
    );

    $admin = $mAdmin->findByPrimaryKey(1);

    $data['s_email'] = $admin['s_email'];
    $data['admin_user'] = $admin['s_username'];
    $data['password'] = $password;

    return $data;
}

/* Menus */
function display_database_config() {
?>
<form action="install.php" method="post">
    <input type="hidden" name="step" value="3" />
    <h2 class="target"><?php _e('Информация о базе данных'); ?></h2>
    <div class="form-table">
        <table>
            <tbody>
                <tr>
                    <th align="left"><label for="dbhost"><?php _e('Хост'); ?></label></th>
                    <td><input type="text" id="dbhost" name="dbhost" value="localhost" size="25" /></td>
                    <td class="small"><?php _e('Имя сервера или IP где находится ядро базы данных'); ?></td>
                </tr>
                <tr>
                    <th align="left"><label for="dbname"><?php _e('Название базы данных'); ?></label></th>
                    <td><input type="text" id="dbname" name="dbname" value="osclass" size="25" /></td>
                    <td class="small"><?php _e('Название базы данных, для работы OSClass'); ?></td>
                </tr>
                <tr>
                    <th align="left"><label for="username"><?php _e('имя пользователя'); ?></label></th>
                    <td><input type="text" id="username" name="username" size="25" /></td>
                    <td class="small"><?php _e('Имя пользователя MySQL'); ?></td>
                </tr>
                <tr>
                    <th align="left"><label for="password"><?php _e('Пароль'); ?></label></th>
                    <td><input type="password" id="password" name="password" value="" size="25" /></td>
                    <td class="small"><?php _e('Пароль MySQL'); ?></td>
                </tr>
                <tr>
                    <th align="left"><label for="tableprefix"><?php _e('Префикс таблиц'); ?></label></th>
                    <td><input type="text" id="tableprefix" name="tableprefix" value="oc_" size="25" /></td>
                    <td class="small"><?php _e('Если вы хотите установить несколько сайтов вместе с OSClass, измените данное поле'); ?></td>
                </tr>
                </tbody>
            </table>
            <div id="advanced_install" class="shrink">
                <div class="text">
                    <span><?php _e('Дополнительно'); ?></span>
                </div>
            </div>
            <script type="text/javascript">
                $(document).ready(function() {
                    $('#advanced_install').click(function() {
                        $('#more-options').toggle();
                        if( $('#advanced_install').attr('class') == 'shrink' ) {
                            $('#advanced_install').removeClass('shrink');
                            $('#advanced_install').addClass('expanded');
                        } else {
                            $('#advanced_install').addClass('shrink');
                            $('#advanced_install').removeClass('expanded');
                        }
                    });
                    $('#createdb').on('click', function() {
                        if($("#createdb").is(':checked')) {
                            if ($("#admin_username").attr("value") == '') {
                                $("#admin_username").attr("value", $("#username").attr("value"));
                            };
                            if ($("#admin_password").attr("value") == '') {
                                $("#admin_password").attr("value", $("#password").attr("value"));
                                $("#password_copied").show();
                            };
                        } else {
                            $("#password_copied").hide();
                        };
                    });
                    $("#password_copied").hide();
                });
            </script>
            <div style="clear:both;"></div>
            <table id="more-options" style="display:none;">
                <tbody>
                <tr>
                    <th></th>
                    <td><input type="checkbox" id="createdb" name="createdb" onclick="db_admin();" value="1" /><label for="createdb"><?php _e('Создать БД'); ?></label></td>
                    <td class="small"><?php _e('Поставьте галочку, если база данных не создана, и вы хотите создать его сейчас'); ?></td>
                </tr>
                <tr id="admin_username_row">
                    <th align="left"><label for="admin_username"><?php _e('Имя пользователя администратора DB'); ?></label></th>
                    <td><input type="text" id="admin_username" name="admin_username" size="25" disabled="disabled" /></td>
                    <td></td>
                </tr>
                <tr id="admin_password_row">
                    <th align="left"><label for="admin_password"><?php _e('Пароль администратора DB'); ?></label></th>
                    <td><input type="password" id="admin_password" name="admin_password" value="" size="25" disabled="disabled" /></td>
                    <td></td>
                </tr>
                </tbody>
            </table>
        </div>
        <div class="clear"></div>
        <p class="margin20">
            <input type="submit" class="button" name="submit" value="Продолжить" />
        </p>
        <div class="clear"></div>
    </form>
<?php
}

function display_target() {
    $country_list = osc_file_get_contents('http://geo.osclass.org/newgeo.services.php?action=countries');
    $country_list = json_decode(substr($country_list, 1, strlen($country_list)-2), true);

    $region_list = array();

    $country_ip = '';
    if(preg_match('|([a-z]{2})-([A-Z]{2})|', @$_SERVER['HTTP_ACCEPT_LANGUAGE'], $match)) {
        $country_ip = $match[2];
        $region_list = osc_file_get_contents('http://geo.osclass.org/newgeo.services.php?action=regions&country='.$match[2]);
        $region_list = json_decode(substr($region_list, 1, strlen($region_list)-2), true);
    }

    if(!isset($country_list[0]) || !isset($country_list[0]['s_name'])) {
        $internet_error = true;
    }


    ?>
    <form id="target_form" name="target_form" action="#" method="post" onsubmit="return false;">
        <h2 class="target"><?php _e('Необходимая информация'); ?></h2>
        <div class="form-table">
            <h2 class="title"><?php _e('Администратор'); ?></h2>
            <table class="admin-user">
                <tbody>
                <tr>
                    <th><label for="admin_user"><?php _e('Имя пользователя'); ?></label></th>
                    <td>
                        <input size="25" id="admin_user" name="s_name" type="text" value="admin" />
                    </td>
                    <td>
                        <span id="admin-user-error" class="error" aria-hidden="true" style="display:none;"><?php _e('Пользователь админ обязательное поле'); ?></span>
                    </td>
                </tr>
                <tr>
                    <th><label for="s_passwd"><?php _e('Пароль'); ?></label></th>
                    <td>
                        <input size="25" class="password_test" name="s_passwd" id="s_passwd" type="password" value="" />
                    </td>
                    <td></td>
                </tr>
            </tbody>
        </table>
        <div class="admin-user">
            <?php _e('Пароль будет автоматически сгенерирован для Вас, если Вы оставите это поле пустым.'); ?>
            <img src="<?php echo get_absolute_url() ?>oc-includes/images/question.png" class="question-skip vtip" title="<?php _e('Вы можете изменить имя пользователя и пароль, просто измените значение.'); ?>" alt="" />
        </div>
        <h2 class="title"><?php _e('Контактная информация'); ?></h2>
        <table class="contact-info">
            <tbody>
                <tr>
                    <th><label for="webtitle"><?php _e('Заголовок сайта'); ?></label></th>
                    <td><input type="text" id="webtitle" name="webtitle" size="25" /></td>
                    <td></td>
                </tr>
                <tr>
                    <th><label for="email"><?php _e('Контактный e-mail'); ?></label></th>
                    <td><input type="text" id="email" name="email" size="25" /></td>
                    <td><span id="email-error" class="error" style="display:none;"><?php _e('Введите ваш e-mail'); ?></span></td>
                </tr>
                </tbody>
            </table>
            <h2 class="title"><?php _e('Местонахождение'); ?></h2>
            <p class="space-left-25 left no-bottom"><?php _e('Выберите страну/город, где находятся ваша целевая аудитория'); ?></p>
            <div id="location-question" class="left question">
                <img class="vtip" src="<?php echo get_absolute_url(); ?>oc-includes/images/question.png" title="<?php echo osc_esc_html(__("Как только вы введете страну, вы сможете выбрать регион и город. После установки Osclass вы сможете импортировать другие регионы.")); ?>" alt="" />
            </div>
            <div class="clear"></div>
            <div id="location">
                <?php if(!$internet_error) { ?>
                    <input type="hidden" id="skip-location-input" name="skip-location-input" value="0" />
                    <input type="hidden" id="country-input" name="country-input" value="" />
                    <input type="hidden" id="region-input" name="region-input" value="" />
                    <input type="hidden" id="city-input" name="city-input" value="" />
                    <div id="country-box">

                        <select name="country_select" id="country_select" >
                            <option value="skip"><?php _e("Skip location"); ?></option>
                            <!-- <option value="all"><?php _e("International"); ?></option> -->
                            <?php foreach($country_list as $c) { ?>
                                <option value="<?php echo $c['code']; ?>" <?php if($c['code']==$country_ip) { echo 'selected="selected"'; }; ?>><?php echo $c['s_name']; ?></option>
                            <?php }; ?>
                        </select>

                        <select name="region_select" id="region_select" style="display: none;">
                            <option value="all"><?php _e("Все регионы"); ?></option>
                        </select>

                        <select name="city_select" id="city_select" style="display: none;">
                            <option value="all"><?php _e("Все города"); ?></option>
                        </select>

                        <div id="no_region_text" aria-hidden="true" style="display: none;"><?php _e("Нет регионов доступных для данной страны"); ?></div>

                        <div id="no_city_text" aria-hidden="true" style="display: none;"><?php _e("Нет городов доступных для данного региона"); ?></div>


                    </div>
                <?php } else { ?>
                    <div id="location-error">
                        <?php _e('В данный момент нет подключения к интернету. Вы можете продолжить установку системы, стран и регионов позже.'); ?>
                        <input type="hidden" id="skip-location-input" name="skip-location-input" value="1" />
                    </div>
                <?php }; ?>
            </div>
        </div>
        <div class="clear"></div>
        <p class="margin20">
            <a href="#" class="button" onclick="validate_form();">Далее</a>
        </p>
        <div class="clear"></div>
    </form>
    <div id="lightbox" style="display:none;">
        <div class="center">
            <img src="<?php echo get_absolute_url(); ?>oc-includes/images/loading.gif" alt="<?php echo osc_esc_html(__("Загрузка...")); ?>" title="" />
        </div>
    </div>
<?php
}

function display_database_error($error ,$step) {
    ?>
    <h2 class="target"><?php _e('Error'); ?></h2>
    <p class="bottom space-left-10">
        <?php echo $error['error']?>
    </p>
    <a href="<?php echo get_absolute_url(); ?>oc-includes/osclass/install.php?step=<?php echo $step; ?>" class="button"><?php _e('Вернуться'); ?></a>
    <div class="clear bottom"></div>
<?php
}

function ping_search_engines($bool){
    $mPreference = Preference::newInstance();
    if($bool == 1){
        $mPreference->insert (
            array(
                's_section' => 'osclass'
            ,'s_name'   => 'ping_search_engines'
            ,'s_value'  => '1'
            ,'e_type'   => 'BOOLEAN'
            )
        );
        // GOOGLE
        osc_doRequest( 'http://www.google.com/webmasters/sitemaps/ping?sitemap='.urlencode(osc_search_url(array('sFeed' => 'rss') )), array());
        // YANDEX
        osc_doRequest( 'http://webmaster.yandex.ru/addurl.xml'.urlencode( osc_search_url(array('sFeed' => 'rss') ) ), array());
        // BING
        osc_doRequest( 'http://www.bing.com/webmaster/ping.aspx?siteMap='.urlencode( osc_search_url(array('sFeed' => 'rss') ) ), array());
        // YAHOO!
        osc_doRequest( 'http://search.yahooapis.com/SiteExplorerService/V1/ping?sitemap='.urlencode( osc_search_url(array('sFeed' => 'rss') ) ), array());
    } else {
        $mPreference->insert (
            array(
                's_section' => 'osclass'
            ,'s_name'   => 'ping_search_engines'
            ,'s_value'  => '0'
            ,'e_type'   => 'BOOLEAN'
            )
        );
    }
}
function display_finish($password) {
    $data = finish_installation( $password );
    ?>
    <?php if(Params::getParam('error_location') == 1) { ?>
        <script type="text/javascript">
            setTimeout (function(){
                $('.error-location').fadeOut('slow');
            }, 2500);
        </script>
        <div class="error-location">
            <?php _e('Выбранные регионы невозможно установить!'); ?>
        </div>
    <?php } ?>
    <h2 class="target"><?php _e('Поздравляем!');?></h2>
    <p class="space-left-10"><?php _e("OSClass Rus Team успешно установлен. Теперь перейдите в панель администрирования, установите язык и начните работать с системой!");?></p>
    <p class="space-left-10"><?php echo sprintf(__('На ваш e-mail отправлено письмо с вашим паролем к панели администрирования oc-admin: %s'), $data['s_email']);?></p>
	<p class="space-left-10"><?php _e('Вам понравился Osclass помогите развивать проект! Скажите СПАСИБО');?></p>
	<p class="space-left-10">
	<form action="https://advisor.wmtransfer.com/Spasibo.aspx" method="post" 
		target="_blank" title="Передать $пасибо! нашему сайту">
		<input type="hidden" name="url" value="http://os-class.ru"/>
		<input type="image" src="//advisor.wmtransfer.com/img/Spasibo!.png" 
		border="0" name="submit"/>
	</form>
	</p>
    <div style="clear:both;"></div>
    <div class="form-table finish">
        <table>
            <tbody>
            <tr>
                <th><span class="label-like"><?php _e('Пользователь');?></span></th>
                <td>
                    <div class="s_name">
                        <span style="float:left;" ><?php echo $data['admin_user']; ?></span>
                    </div>
                </td>
            </tr>
            <tr>
                <th><span class="label-like"><?php _e('Пароль');?></span></th>
                <td>
                    <div class="s_passwd">
                        <span style="float: left;"><?php echo osc_esc_html($data['password']); ?></span>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
</div>
<p class="margin20">
    <a target="_blank" href="<?php echo get_absolute_url() ?>oc-admin/index.php" class="button"><?php _e('Закончить и перейти в панель администрирования');?></a>
</p>
<?php
}
?>
