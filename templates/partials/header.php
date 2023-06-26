<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><? $title ?></title>
    <link rel="stylesheet" href="<?= "{$_SERVER['DOCUMENT_ROOT']}/templates/partials/styles/main.css" ?>">
    <?php if ($title === "cabinet") echo '<link rel="stylesheet" href="/cabinet.css">' ?>
</head>
<body>
    <div class="container">