<?php
require_once 'config/database.php';
require_once 'includes/sidebar.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: index.php');
    exit();
}

$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM comptes WHERE id = ?");
$stmt->execute([$id]);
$compte = $stmt->fetch();

if (!$compte) {
    header('Location: index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $_POST['nom'] ?? '';
    $prenom = $_POST['prenom'] ?? '';
    $numero_compte = $_POST['numero_compte'] ?? '';
    $solde = $_POST['solde'] ?? 0;
    $type_compte = $_POST['type_compte'] ?? 'courant';
    $email = $_POST['email'] ?? '';
    $telephone = $_POST['telephone'] ?? '';

    $checkStmt = $pdo->prepare("SELECT id FROM comptes WHERE numero_compte = ? AND id != ?");
    $checkStmt->execute([$numero_compte, $id]);

    if ($checkStmt->fetch()) {
        $error = "Ce numéro de compte est déjà utilisé par un autre client !";
    } else {
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

<div class="animate-fadeIn">
    <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
        <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 px-8 py-6">
            <h1 class="text-3xl font-bold text-white">
                <i class="fas fa-edit mr-3"></i>
                Modifier le compte
            </h1>
            <p class="text-yellow-100 mt-2">Client : <?php echo htmlspecialchars($compte['prenom'] . ' ' . $compte['nom']); ?></p>
        </div>

        <div class="p-8">
            <?php if (isset($error)): ?>
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-lg">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-gray-700 font-bold mb-2">Nom *</label>
                        <input type="text" name="nom" required value="<?php echo htmlspecialchars($compte['nom']); ?>"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-300">
                    </div>

                    <div>
                        <label class="block text-gray-700 font-bold mb-2">Prénom *</label>
                        <input type="text" name="prenom" required value="<?php echo htmlspecialchars($compte['prenom']); ?>"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-300">
                    </div>

                    <div>
                        <label class="block text-gray-700 font-bold mb-2">Numéro de compte *</label>
                        <input type="text" name="numero_compte" required value="<?php echo htmlspecialchars($compte['numero_compte']); ?>"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-300 font-mono">
                    </div>

                    <div>
                        <label class="block text-gray-700 font-bold mb-2">Solde</label>
                        <input type="number" name="solde" step="0.01" value="<?php echo $compte['solde']; ?>"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-300">
                    </div>

                    <div>
                        <label class="block text-gray-700 font-bold mb-2">Type de compte *</label>
                        <select name="type_compte" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-300">
                            <option value="courant" <?php echo $compte['type_compte'] == 'courant' ? 'selected' : ''; ?>>🏦 Compte Courant</option>
                            <option value="epargne" <?php echo $compte['type_compte'] == 'epargne' ? 'selected' : ''; ?>>🐷 Compte Épargne</option>
                            <option value="entreprise" <?php echo $compte['type_compte'] == 'entreprise' ? 'selected' : ''; ?>>🏢 Compte Entreprise</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-gray-700 font-bold mb-2">Email</label>
                        <input type="email" name="email" value="<?php echo htmlspecialchars($compte['email']); ?>"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-300">
                    </div>

                    <div>
                        <label class="block text-gray-700 font-bold mb-2">Téléphone</label>
                        <input type="tel" name="telephone" value="<?php echo htmlspecialchars($compte['telephone']); ?>"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-300">
                    </div>
                </div>

                <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                    <a href="index.php" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-3 px-6 rounded-lg transition-all duration-300 transform hover:scale-105">
                        <i class="fas fa-times mr-2"></i>Annuler
                    </a>
                    <button type="submit" class="bg-gradient-to-r from-yellow-500 to-yellow-600 hover:from-yellow-600 hover:to-yellow-700 text-white font-bold py-3 px-6 rounded-lg transition-all duration-300 transform hover:scale-105 shadow-lg">
                        <i class="fas fa-save mr-2"></i>Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

</main>
</body>

</html>