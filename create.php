<?php
require_once 'config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer les données du formulaire
    $nom = $_POST['nom'] ?? '';
    $prenom = $_POST['prenom'] ?? '';
    $numero_compte = $_POST['numero_compte'] ?? '';
    $solde = $_POST['solde'] ?? 0;
    $type_compte = $_POST['type_compte'] ?? 'courant';
    $email = $_POST['email'] ?? '';
    $telephone = $_POST['telephone'] ?? '';

    // Valider que le numéro de compte est unique
    $checkStmt = $pdo->prepare("SELECT id FROM comptes WHERE numero_compte = ?");
    $checkStmt->execute([$numero_compte]);
    if ($checkStmt->fetch()) {
        $error = "Ce numéro de compte existe déjà !";
    } else {
        // Insérer le nouveau compte
        $stmt = $pdo->prepare("INSERT INTO comptes (nom, prenom, numero_compte, solde, type_compte, email, telephone) 
                               VALUES (?, ?, ?, ?, ?, ?, ?)");

        if ($stmt->execute([$nom, $prenom, $numero_compte, $solde, $type_compte, $email, $telephone])) {
            header('Location: index.php?success=ajoute');
            exit();
        } else {
            $error = "Erreur lors de l'ajout du compte";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un compte - Gestion Bancaire</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>

<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8 max-w-2xl">
        <!-- En-tête -->
        <div class="bg-gradient-to-r from-green-600 to-green-800 rounded-xl shadow-lg p-6 mb-8">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-white">
                        <i class="fas fa-plus-circle mr-2"></i>Nouveau Compte
                    </h1>
                    <p class="text-green-100 mt-2">Ajoutez un nouveau client dans la base</p>
                </div>
                <a href="index.php" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded transition">
                    <i class="fas fa-arrow-left mr-2"></i>Retour
                </a>
            </div>
        </div>

        <!-- Formulaire -->
        <div class="bg-white rounded-xl shadow-lg p-8">
            <?php if (isset($error)): ?>
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-gray-700 font-bold mb-2">
                            <i class="fas fa-user mr-2 text-blue-500"></i>Nom *
                        </label>
                        <input type="text" name="nom" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label class="block text-gray-700 font-bold mb-2">
                            <i class="fas fa-user mr-2 text-blue-500"></i>Prénom *
                        </label>
                        <input type="text" name="prenom" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label class="block text-gray-700 font-bold mb-2">
                            <i class="fas fa-credit-card mr-2 text-blue-500"></i>Numéro de compte *
                        </label>
                        <input type="text" name="numero_compte" required placeholder="FR76XXXXXXXXXX"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label class="block text-gray-700 font-bold mb-2">
                            <i class="fas fa-euro-sign mr-2 text-blue-500"></i>Solde initial
                        </label>
                        <input type="number" name="solde" step="0.01" value="0"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label class="block text-gray-700 font-bold mb-2">
                            <i class="fas fa-tag mr-2 text-blue-500"></i>Type de compte *
                        </label>
                        <select name="type_compte" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="courant">Compte Courant</option>
                            <option value="epargne">Compte Épargne</option>
                            <option value="entreprise">Compte Entreprise</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-gray-700 font-bold mb-2">
                            <i class="fas fa-envelope mr-2 text-blue-500"></i>Email
                        </label>
                        <input type="email" name="email"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label class="block text-gray-700 font-bold mb-2">
                            <i class="fas fa-phone mr-2 text-blue-500"></i>Téléphone
                        </label>
                        <input type="tel" name="telephone"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>

                <div class="mt-8 flex justify-end space-x-3">
                    <a href="index.php" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-3 px-6 rounded-lg transition">
                        Annuler
                    </a>
                    <button type="submit" class="bg-green-500 hover:bg-green-600 text-white font-bold py-3 px-6 rounded-lg transition transform hover:scale-105">
                        <i class="fas fa-save mr-2"></i>Créer le compte
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>

</html>