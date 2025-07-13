<?php
require 'db.php';
header('Content-Type: application/json');
$db = getDB();

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        if (isset($_GET['id'])) {
            $stmt = $db->prepare("SELECT * FROM items WHERE id = ?");
            $stmt->execute([$_GET['id']]);
            echo json_encode($stmt->fetch(PDO::FETCH_ASSOC));
        } else {
            echo json_encode($db->query("SELECT * FROM items")->fetchAll(PDO::FETCH_ASSOC));
        }
        break;

    case 'POST':
        $data = json_decode(file_get_contents('php://input'), true);
        $stmt = $db->prepare("INSERT INTO items (name, description) VALUES (?, ?)");
        $stmt->execute([$data['name'], $data['description']]);
        echo json_encode(['success' => true]);
        break;

    case 'PUT':
        $data = json_decode(file_get_contents('php://input'), true);
        $stmt = $db->prepare("UPDATE items SET name = ?, description = ? WHERE id = ?");
        $stmt->execute([$data['name'], $data['description'], $data['id']]);
        echo json_encode(['success' => true]);
        break;

    case 'DELETE':
        parse_str(file_get_contents('php://input'), $data);
        $stmt = $db->prepare("DELETE FROM items WHERE id = ?");
        $stmt->execute([$data['id']]);
        echo json_encode(['success' => true]);
        break;
}
?>
