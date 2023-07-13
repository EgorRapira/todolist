<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= $page ?></title>
    <link rel="stylesheet" href="<?= "/templates/partials/styles/main.css" ?>">
    <link rel="icon" href="<?= "/templates/partials/images/logo.png" ?>">
    <link rel="stylesheet" href="<?php if ($page === "cabinet") echo "/templates/partials/styles/cabinet.css" ?>">
</head>
<body>
    <div class="container">