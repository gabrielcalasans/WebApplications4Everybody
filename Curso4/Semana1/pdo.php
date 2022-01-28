<?php
$pdo = new PDO('mysql:host=localhost;port=3306;dbname=curso4assign1', 'fred', 'zap');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
