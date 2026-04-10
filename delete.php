<?php
require_once 'config/database.php';

// Vérifier si l'ID est fourni
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: index.php');
    exit();
}

$id = $_GET['id'];

// Supprimer le compte
$stmt = $pdo->prepare("DELETE FROM comptes WHERE id = ?");
$stmt->execute([$id]);

// Rediriger vers la page d'accueil avec un message de succès
header('Location: index.php?success=supprime');
exit();
