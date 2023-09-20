<?php

$filename = __DIR__ . "/data/todos.json";

$_GET = filter_input_array(INPUT_GET, FILTER_SANITIZE_FULL_SPECIAL_CHARS) .
    // on récupère l'id
    $id = $_GET['id'] ?? '';
// On vérifie que l'id existe
if ($id) {
    $data = file_get_contents($filename);
    // On récupère la liste de todos
    $todos = json_decode($data, true) ?? [];
    if (count($todos)) {
        // On récupère l'index de notre élément
        $todoIndex = (int)array_search($id, array_column($todos, 'id'));
        // On veut inverser la valeur de 'done'
        $todos[$todoIndex]['done'] = !$todos[$todoIndex]['done'];
        // On enregistre
        file_put_contents($filename, json_encode($todos));
    }
}




// Rediriger l'utilisateur
header('location: /');
