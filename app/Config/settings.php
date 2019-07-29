<?php

$config['displayErrorDetails'] = true;
$config['addContentLengthHeader'] = false;

$config['db']['host']   = '127.0.0.1';
$config['db']['username']   = 'root';
$config['db']['password']   = '';
$config['db']['database'] = 'db_name';

$config['logger']['path'] = dirname(__DIR__) . '/Logs/app.log';
$config['logger']['name'] = 'logger_name';