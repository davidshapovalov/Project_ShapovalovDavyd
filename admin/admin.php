<?php
session_start(); //открытие СЕССИИ для хранения данных зашел админ или нет
//ХРАНИТ данные между страницами
// ЭТО как глобальная перменная, глобальный СЛОВАРЬ

require_once __DIR__ . '/../classes/Database.php'; //ЭТО как импорт в пайтон, подключает ПХП только один раз если был подключен, больше не подключится
//дир специальная переменная содержащая путь к папке
require_once __DIR__ . '/AdminPanel.php';      
require_once __DIR__ . '/AdminPanel2.php'; 

$adminContacts = new AdminPanel();
$adminTickets = new AdminPanel2();

$adminContacts->render(); 
$adminTickets->render(); 
