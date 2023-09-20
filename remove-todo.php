<?php

$filename = __DIR__ . "/data/todos.json";

$_GET = filter_input_array(INPUT_GET, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$id = $_GET['id'] ?? '';
if ($id) {
    $todos = json_decode(file_get_contents($filename), true);
    // Retourne un tableau avce la valeur d'une clé
    $todoIndex = array_search($id, array_column($todos, 'id'));
    // On supprime un élément
    array_splice($todos, $todoIndex, 1);
    file_put_contents($filename, json_encode($todos));
}


header('Location: /');
