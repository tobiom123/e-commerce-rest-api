<?php

$username = isset($_GET['username']) ? $_GET['username'] : '';
$password = isset($_GET['password']) ? $_GET['password'] : '';
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

$pdo = new PDO('sqlite::memory:');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$pdo->exec("DROP TABLE IF EXISTS users");
$pdo->exec("CREATE TABLE users (username VARCHAR(255), password VARCHAR(255))");

$rootPassword = password_hash('secret', PASSWORD_DEFAULT);
$pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)")->execute(['root', $rootPassword]);

$statement = $pdo->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
$statement->execute([$username, $hashedPassword]);

if (count($statement->fetchAll())) {
    echo "Access granted to $username!<br>\n";
} else {
    echo "Access denied for $username!<br>\n";
}
