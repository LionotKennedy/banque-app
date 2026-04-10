<?php
require_once 'config/database.php';

// Vérifier si l'ID est fourni
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: index.php');
    exit();
}

$id = $_GET['id'];

// Récupérer le compte à modifier
$stmt = $pdo->prepare("SELECT * FROM comptes WHERE id = ?");
$stmt->execute([$id]);
$compte = $stmt->fetch();

if (!$compte) {
    header('Location: index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer les données modifiées
    $nom = $_POST['nom'] ?? '';
    $prenom = $_POST['prenom'] ?? '';
    $numero_compte = $_POST['numero_compte'] ?? '';
    $solde = $_POST['solde'] ?? 0;
    $type_compte = $_POST['type_compte'] ?? 'courant';
    $email = $_POST['email'] ?? '';
    $telephone = $_POST['telephone'] ?? '';

    // Vérifier que le numéro de compte n'est pas utilisé par un autre compte
    $checkStmt = $pdo->prepare("SELECT id FROM comptes WHERE numero_compte = ? AND id != ?");
    $checkStmt->execute([$numero_compte, $id]);

    if ($checkStmt->fetch()) {
        $error = "Ce numéro de compte est déjà utilisé par un autre client !";
    } else {
        // Mettre à jour le compte
        $updateStmt = $pdo->prepare("UPDATE comptes SET 
                                      nom = ?, prenom = ?, numero_compte = ?, 
                                      solde = ?, type_compte = ?, email = ?, telephone = ? 
                                      WHERE id = ?");

        if ($updateStmt->execute([$nom, $prenom, $numero_compte, $solde, $type_compte, $email, $telephone, $id])) {
            header('Location: index.php?success=modifie');
            exit();
        } else {
            $error = "Erreur lors de la modification";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier le compte - Gestion Bancaire</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>

<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8 max-w-2xl">
        <div class="bg-gradient-to-r from-yellow-600 to-yellow-800 rounded-xl shadow-lg p-6 mb-8">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-white">
                        <i class="fas fa-edit mr-2"></i>Modifier le compte
                    </h1>
                    <p class="text-yellow-100 mt-2">Client : <?php echo htmlspecialchars($compte['prenom'] . ' ' . $compte['nom']); ?></p>
                </div>
                <a href="index.php" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded transition">
                    <i class="fas fa-arrow-left mr-2"></i>Retour
                </a>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-8">
            <?php if (isset($error)): ?>
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-gray-700 font-bold mb-2">Nom *</label>
                        <input type="text" name="nom" required value="<?php echo htmlspecialchars($compte['nom']); ?>"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label class="block text-gray-700 font-bold mb-2">Prénom *</label>
                        <input type="text" name="prenom" required value="<?php echo htmlspecialchars($compte['prenom']); ?>"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label class="block text-gray-700 font-bold mb-2">Numéro de compte *</label>
                        <input type="text" name="numero_compte" required value="<?php echo htmlspecialchars($compte['numero_compte']); ?>"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label class="block text-gray-700 font-bold mb-2">Solde</label>
                        <input type="number" name="solde" step="0.01" value="<?php echo $compte['solde']; ?>"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label class="block text-gray-700 font-bold mb-2">Type de compte *</label>
                        <select name="type_compte" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="courant" <?php echo $compte['type_compte'] == 'courant' ? 'selected' : ''; ?>>Compte Courant</option>
                            <option value="epargne" <?php echo $compte['type_compte'] == 'epargne' ? 'selected' : ''; ?>>Compte Épargne</option>
                            <option value="entreprise" <?php echo $compte['type_compte'] == 'entreprise' ? 'selected' : ''; ?>>Compte Entreprise</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-gray-700 font-bold mb-2">Email</label>
                        <input type="email" name="email" value="<?php echo htmlspecialchars($compte['email']); ?>"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label class="block text-gray-700 font-bold mb-2">Téléphone</label>
                        <input type="tel" name="telephone" value="<?php echo htmlspecialchars($compte['telephone']); ?>"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>

                <div class="mt-8 flex justify-end space-x-3">
                    <a href="index.php" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-3 px-6 rounded-lg transition">
                        Annuler
                    </a>
                    <button type="submit" class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-3 px-6 rounded-lg transition transform hover:scale-105">
                        <i class="fas fa-save mr-2"></i>Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>

</html>