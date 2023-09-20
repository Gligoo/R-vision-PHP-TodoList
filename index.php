<?php

// J'initialise mes constantes d'erreurs

const ERROR_REQUIRED = 'Veuillez renseigner une todo';
const ERROR_TO_SHORT = 'Veuillez renseigner au moins 5 caractères';

$filename = __DIR__ . "/data/todos.json";
$error = '';
$todo = '';
$todos = [];

// Je récupère mes données
if (file_exists($filename)) {
    $data = file_get_contents($filename);
    $todos = json_decode($data, true) ?? [];
}



// Je nettoie mes champs et génère les erreurs si besoin
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $todo = $_POST['todo'] ?? '';
    // Si todo est vide ou si le nombre de caractère est trop cour je génère les erreurs
    if (!$todo) {
        $error = ERROR_REQUIRED;
    } else if (mb_strlen($todo) < 5) {
        $error = ERROR_TO_SHORT;
    }

    // Si pas d'erreur, je génère un nouveau tableau avec mes datas
    if (!$error) {
        $todos = [...$todos, [
            'name' => $todo,
            'done' => false,
            'id' => time(),
        ]];
        // Schema inverse, j'encode $todos avec mes nouvelles données
        file_put_contents($filename, json_encode($todos));
        // Empêche de re soumettre le foirmulaire lorsque je rafraichis la page
        header('Location: /');
    }
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <?php require_once 'includes/head.php' ?>
    <title>Todo</title>
</head>

<body>
    <div class="container">
        <?php require_once 'includes/header.php' ?>
        <div class="content">
            <div class="todo-container">
                <h1>Ma todo</h1>
                <form action="/" method="POST" class="todo-form">
                    <input value="<?= $todo ?>" name="todo" type="text">
                    <button class="btn btn-primary">Ajouter</button>
                </form>
                <?php if ($error) : ?>
                    <p class="text-danger"><?= $error ?></p>
                <?php endif; ?>
                <ul class="todo-list">
                    <?php foreach ($todos as $t) : ?>
                        <li class="todo-item <?= $t['done'] ? 'low-opacity' : '' ?>">
                            <span class="todo-name"><?= $t['name'] ?></span>
                            <a href="/edit-todo.php?id=<?= $t['id'] ?>">
                                <button class="btn btn-primary btn-small"><?= $t['done'] ? 'Annuler' : 'Valider' ?></button>
                            </a>
                            <a href="/remove-todo.php?id=<?= $t['id'] ?>">
                                <button class="btn btn-danger btn-small">Supprimer</button>
                            </a>
                            <span></span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
        <?php require_once 'includes/footer.php' ?>
    </div>

</body>

</html>

<?php

?>


<!-- <form action="index.php" method="POST">
    <div>
        <label for="name">Nom</label><br>
        <input type="text" name="name" id="name">
    </div>
    <br>
    <button>Submit</button>
</form> -->