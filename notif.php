<?php
session_start();

header('Content-Type: application/json');

// Handle storing notifications
if (isset($_GET['message']) && isset($_GET['type'])) {
    $_SESSION['notification'] = [
        'message' => $_GET['message'],
        'type' => $_GET['type']
    ];
    echo json_encode(['success' => true]);
    exit;
}

// Handle clearing notifications
if (isset($_GET['action']) && $_GET['action'] === 'clear') {
    unset($_SESSION['notification']);
    echo json_encode(['success' => true]);
    exit;
}

// Retrieve notifications
if (isset($_SESSION['notification'])) {
    echo json_encode($_SESSION['notification']);
} else {
    echo json_encode([]);
}
?>
