<?php

require_once 'includes/db.php';
require_once 'includes/auth.php';

header('Content-Type: application/json');

if (!is_logged_in()) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized', 'results' => []]);
    exit();
}

$search_term = isset($_GET['search']) ? trim($_GET['search']) : '';

if (empty($search_term)) {
    echo json_encode(['success' => false, 'message' => 'Search term is required', 'results' => []]);
    exit();
}

$results = search_skills($search_term, $conn);
$data = [];

if ($results && $results->num_rows > 0) {
    while ($row = $results->fetch_assoc()) {
        $data[] = [
            'id' => $row['id'],
            'title' => $row['title'],
            'category' => $row['category'],
            'skill_level' => $row['skill_level'],
            'description' => $row['description'],
            'user_id' => $row['user_id'],
            'username' => $row['username']
        ];
    }
}

echo json_encode(['success' => true, 'results' => $data]);
