<?php
require_once 'config/database.php';
require_once 'includes/sidebar.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $_POST['nom'] ?? '';
    $prenom = $_POST['prenom'] ?? '';
    $numero_compte = $_POST['numero_compte'] ?? '';
    $solde = $_POST['solde'] ?? 0;
    $type_compte = $_POST['type_compte'] ?? 'courant';
    $email = $_POST['email'] ?? '';
    $telephone = $_POST['telephone'] ?? '';

    $checkStmt = $pdo->prepare("SELECT id FROM comptes WHERE numero_compte = ?");
    $checkStmt->execute([$numero_compte]);
    if ($checkStmt->fetch()) {
        $error = "Ce numéro de compte existe déjà !";
    } else {
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

<div class="animate-fadeIn">
    <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
        <div class="bg-gradient-to-r from-green-500 to-green-600 px-8 py-6">
            <h1 class="text-3xl font-bold text-white">
                <i class="fas fa-plus-circle mr-3"></i>
                Nouveau compte bancaire
            </h1>
            <p class="text-green-100 mt-2">Remplissez le formulaire pour ajouter un nouveau client</p>
        </div>

        <div class="p-8">
            <?php if (isset($error)): ?>
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-lg animate-slideInLeft">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-gray-700 font-bold mb-2">
                            <i class="fas fa-user text-blue-500 mr-2"></i>Nom *
                        </label>
                        <input type="text" name="nom" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300">
                    </div>

                    <div>
                        <label class="block text-gray-700 font-bold mb-2">
                            <i class="fas fa-user text-blue-500 mr-2"></i>Prénom *
                        </label>
                        <input type="text" name="prenom" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300">
                    </div>

                    <div>
                        <label class="block text-gray-700 font-bold mb-2">
                            <i class="fas fa-credit-card text-blue-500 mr-2"></i>Numéro de compte *
                        </label>
                        <input type="text" name="numero_compte" required placeholder="FR76XXXXXXXXXX"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300 font-mono">
                    </div>

                    <div>
                        <label class="block text-gray-700 font-bold mb-2">
                            <i class="fas fa-euro-sign text-blue-500 mr-2"></i>Solde initial
                        </label>
                        <input type="number" name="solde" step="0.01" value="0"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300">
                    </div>

                    <div>
                        <label class="block text-gray-700 font-bold mb-2">
                            <i class="fas fa-tag text-blue-500 mr-2"></i>Type de compte *
                        </label>
                        <select name="type_compte" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300">
                            <option value="courant">🏦 Compte Courant</option>
                            <option value="epargne">🐷 Compte Épargne</option>
                            <option value="entreprise">🏢 Compte Entreprise</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-gray-700 font-bold mb-2">
                            <i class="fas fa-envelope text-blue-500 mr-2"></i>Email
                        </label>
                        <input type="email" name="email"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300">
                    </div>

                    <div>
                        <label class="block text-gray-700 font-bold mb-2">
                            <i class="fas fa-phone text-blue-500 mr-2"></i>Téléphone
                        </label>
                        <input type="tel" name="telephone"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300">
                    </div>
                </div>

                <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                    <a href="index.php" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-3 px-6 rounded-lg transition-all duration-300 transform hover:scale-105">
                        <i class="fas fa-times mr-2"></i>Annuler
                    </a>
                    <button type="submit" class="bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white font-bold py-3 px-6 rounded-lg transition-all duration-300 transform hover:scale-105 shadow-lg">
                        <i class="fas fa-save mr-2"></i>Créer le compte
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

</main>
</body>

</html>