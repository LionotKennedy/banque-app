<?php
require_once 'config/database.php';
require_once 'includes/sidebar.php';

// Récupérer tous les comptes
$stmt = $pdo->query("SELECT * FROM comptes ORDER BY date_creation DESC");
$comptes = $stmt->fetchAll();
?>

<!-- Messages de notification -->
<?php if (isset($_GET['success'])): ?>
    <div class="fixed top-4 right-4 z-50 animate-slideInRight" id="notification">
        <div class="bg-green-500 text-white px-6 py-4 rounded-lg shadow-2xl flex items-center space-x-3">
            <i class="fas fa-check-circle text-2xl"></i>
            <div>
                <p class="font-bold">Succès !</p>
                <p class="text-sm">
                    <?php
                    if ($_GET['success'] == 'ajoute') echo "Compte ajouté avec succès !";
                    if ($_GET['success'] == 'modifie') echo "Compte modifié avec succès !";
                    if ($_GET['success'] == 'supprime') echo "Compte supprimé avec succès !";
                    ?>
                </p>
            </div>
        </div>
    </div>
    <script>
        setTimeout(() => {
            const notif = document.getElementById('notification');
            if (notif) notif.style.display = 'none';
        }, 3000);
    </script>
<?php endif; ?>

<!-- Statistiques en haut -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8 animate-fadeIn">
    <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-2xl shadow-lg p-6 text-white card-hover">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-blue-100 text-sm">Total comptes</p>
                <p class="text-4xl font-bold mt-2"><?php echo count($comptes); ?></p>
            </div>
            <div class="bg-white bg-opacity-20 rounded-full p-3">
                <i class="fas fa-users text-2xl"></i>
            </div>
        </div>
        <div class="mt-4">
            <span class="text-blue-100 text-sm">+12% ce mois</span>
        </div>
    </div>

    <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-2xl shadow-lg p-6 text-white card-hover">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-green-100 text-sm">Capital total</p>
                <p class="text-4xl font-bold mt-2"><?php echo number_format(array_sum(array_column($comptes, 'solde')), 0, ',', ' '); ?> €</p>
            </div>
            <div class="bg-white bg-opacity-20 rounded-full p-3">
                <i class="fas fa-chart-line text-2xl"></i>
            </div>
        </div>
        <div class="mt-4">
            <span class="text-green-100 text-sm">+8% de croissance</span>
        </div>
    </div>

    <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-2xl shadow-lg p-6 text-white card-hover">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-purple-100 text-sm">Solde moyen</p>
                <p class="text-4xl font-bold mt-2"><?php echo number_format(array_sum(array_column($comptes, 'solde')) / max(1, count($comptes)), 0, ',', ' '); ?> €</p>
            </div>
            <div class="bg-white bg-opacity-20 rounded-full p-3">
                <i class="fas fa-wallet text-2xl"></i>
            </div>
        </div>
        <div class="mt-4">
            <span class="text-purple-100 text-sm">Portefeuille sain</span>
        </div>
    </div>
</div>

<!-- Liste des comptes -->
<div class="bg-white rounded-2xl shadow-xl overflow-hidden animate-fadeIn">
    <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center bg-gray-50">
        <h2 class="text-xl font-bold text-gray-800">
            <i class="fas fa-list-ul text-blue-600 mr-2"></i>
            Liste des comptes bancaires
        </h2>
        <a href="create.php" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg transition-all duration-300 transform hover:scale-105 inline-flex items-center">
            <i class="fas fa-plus mr-2"></i>
            Nouveau compte
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Client</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">N° Compte</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Solde</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php foreach ($comptes as $compte): ?>
                    <tr class="hover:bg-gray-50 transition-all duration-200 transform hover:scale-[1.01]">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10 bg-gradient-to-br from-blue-400 to-blue-600 rounded-full flex items-center justify-center text-white font-bold">
                                    <?php echo strtoupper(substr($compte['prenom'], 0, 1) . substr($compte['nom'], 0, 1)); ?>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">
                                        <?php echo htmlspecialchars($compte['prenom'] . ' ' . $compte['nom']); ?>
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        <?php echo htmlspecialchars($compte['email']); ?>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-sm font-mono text-gray-900"><?php echo htmlspecialchars($compte['numero_compte']); ?></span>
                            <div class="text-xs text-gray-500"><?php echo htmlspecialchars($compte['telephone']); ?></div>
                        </td>
                        <td class="px-6 py-4">
                            <?php
                            $badgeConfig = [
                                'courant' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-800', 'icon' => 'fa-wallet'],
                                'epargne' => ['bg' => 'bg-green-100', 'text' => 'text-green-800', 'icon' => 'fa-piggy-bank'],
                                'entreprise' => ['bg' => 'bg-purple-100', 'text' => 'text-purple-800', 'icon' => 'fa-building']
                            ];
                            $config = $badgeConfig[$compte['type_compte']] ?? $badgeConfig['courant'];
                            ?>
                            <span class="px-3 py-1 text-xs font-semibold rounded-full <?php echo $config['bg'] . ' ' . $config['text']; ?> inline-flex items-center">
                                <i class="fas <?php echo $config['icon']; ?> mr-1 text-xs"></i>
                                <?php echo ucfirst($compte['type_compte']); ?>
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div>
                                <span class="text-lg font-bold <?php echo $compte['solde'] >= 0 ? 'text-green-600' : 'text-red-600'; ?>">
                                    <?php echo number_format($compte['solde'], 2, ',', ' '); ?> €
                                </span>
                            </div>
                            <div class="text-xs text-gray-500"><?php echo date('d/m/Y', strtotime($compte['date_creation'])); ?></div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex justify-center space-x-2">
                                <a href="edit.php?id=<?php echo $compte['id']; ?>"
                                    class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-2 rounded-lg transition-all duration-300 transform hover:scale-105 inline-flex items-center tooltip"
                                    data-tooltip="Modifier">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="delete.php?id=<?php echo $compte['id']; ?>"
                                    onclick="return confirm('⚠️ Êtes-vous sûr de vouloir supprimer ce compte ? Cette action est irréversible !')"
                                    class="bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded-lg transition-all duration-300 transform hover:scale-105 inline-flex items-center tooltip"
                                    data-tooltip="Supprimer">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

</main>
</body>

</html>