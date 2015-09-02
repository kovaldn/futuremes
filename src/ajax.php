<?php

    require_once('config.php');
    require_once('database.php');

    $db = new Database(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);

    if ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {
        $name = $_POST['mail'];
        $date = $_POST['date'];
        $mes = $_POST['mes'];

        $dt = explode('/', $_POST['date']);

        array_unshift($dt, array_pop($dt));

        $error = array();
        $data = array();

        if (strtotime($date) < strtotime(date('m/d/Y'))) {
            $error['date_less'] = 'Дата не может быть меньше ' . date('d.m.Y');
        }

        if ($name === '') {
            $error['name'] = 'Заполните все поля!';
        } else if (!filter_var($name, FILTER_VALIDATE_EMAIL)) {
            $error['name'] = 'Проверьте заполнение поля Email';
        }

        if ($date === '') {
            $error['date'] = 'Заполните все поля!';
        }
        if ($mes === '') {
            $error['message'] = 'Заполните все поля!';
        }

        if (count($error) != 0) {
            $data['status'] = 'error';
            $data['text'] = $error;
        } else {
            $data['status'] = 'OK';
            $data['text'] = 'Вы молодец, не забыли заполнить все поля';

//            $db->query("INSERT INTO scheduler SET email='" . $_POST['mail'] . "', message='" . $db->escape($_POST['mes']) . "', dt='" . $db->escape(implode('.', $dt)) . "'");
            $db->query("INSERT INTO scheduler SET email='" . $_POST['mail'] . "', message='" . $db->escape($_POST['mes']) . "', dt='" . $db->escape(date('Y.m.d', strtotime($_POST['date']))) . "'");
        }

        header("Content-Type: application/json");
        echo json_encode($data);
    } else if (!$_SERVER['REQUEST_METHOD']) {
        $messsages = $db->query("SELECT * FROM scheduler WHERE status=0 AND dt='" . $db->escape(date('Y.m.d')) . "'");

        foreach ($messsages->rows as $message) {
            if (writeLetter(EMAIL, $message->email, SUBJECT, $message->message)) {
                $db->query("UPDATE scheduler SET status=1 WHERE id=" . $message->id);
            }
        }
    }

    function writeLetter($from, $to, $subject, $message, $format = 'plain', $encoding = 'utf-8', $bcc = '')
    {
        $subject = "=?$encoding?B?" . base64_encode($subject) . '?=';
        $header = "Content-type: text/$format; charset=\"$encoding\"\r\n";
        $header .= "From: " . $from . "\r\n";
        $header .= "Subject: $subject\r\n";
        $header .= $bcc ? "BCC: $bcc\r\n" : '';
        return mail($to, $subject, $message, $header);
    }