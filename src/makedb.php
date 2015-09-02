<?php

    require_once('config.php');
    require_once('database.php');

    $db = new Database(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD);

    $result = $db->create_db(DB_DATABASE);

    if ($result === true) {
        $result = $db->query('CREATE TABLE scheduler (
                        id INT(11) NOT NULL AUTO_INCREMENT,
                        email VARCHAR(100) NOT NULL,
                        message TEXT NOT NULL,
                        dt TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                        status TINYINT(1) NOT NULL DEFAULT 0,
                        PRIMARY KEY (id),
                        INDEX dt_status (dt, status)
                    )
                    COLLATE="utf8_general_ci"
                    ENGINE=MyISAM;');
        if ($result === true) {
            echo 'База данных ' . DB_DATABASE . ' создана успешно';
        }
    } else {
        echo 'Не удалось создать базу данных ' . DB_DATABASE . ' по причине: ' . $result;
    }

