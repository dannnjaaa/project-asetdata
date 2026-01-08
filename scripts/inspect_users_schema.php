<?php
$pdo = new PDO('sqlite:' . __DIR__ . '/../database/database.sqlite');
$stmt = $pdo->query('PRAGMA table_info(users)');
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
if (empty($rows)) {
    echo "No users table or no columns\n";
    exit;
}
foreach ($rows as $row) {
    echo $row['cid'] . '|' . $row['name'] . '|' . $row['type'] . '|' . $row['notnull'] . '|' . $row['dflt_value'] . PHP_EOL;
}
