<?php
require_once 'config/database.php';

// Récupérer tous les comptes
$stmt = $pdo->query("SELECT * FROM comptes ORDER BY date_creation DESC");
$comptes = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion Bancaire</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>

<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <!-- En-tête -->
        <div class="bg-gradient-to-r from-blue-600 to-blue-800 rounded-xl shadow-lg p-6 mb-8">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-white">🏦 Gestion Bancaire</h1>
                    <p class="text-blue-100 mt-2">Gérez tous vos comptes en un clin d'œil</p>
                </div>
                <a href="create.php" class="bg-green-500 hover:bg-green-600 text-white font-bold py-3 px-6 rounded-lg transition duration-300 transform hover:scale-105">
                    <i class="fas fa-plus mr-2"></i>Nouveau Compte
                </a>
            </div>
        </div>

        <!-- Messages de notification -->
        <?php if (isset($_GET['success'])): ?>
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-md">
                <?php
                if ($_GET['success'] == 'ajoute') echo "✅ Compte ajouté avec succès !";
                if ($_GET['success'] == 'modifie') echo "✅ Compte modifié avec succès !";
                if ($_GET['success'] == 'supprime') echo "✅ Compte supprimé avec succès !";
                ?>
            </div>
        <?php endif; ?>

        <!-- Liste des comptes -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
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
                            <tr class="hover:bg-gray-50 transition duration-200">
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10 bg-blue-100 rounded-full flex items-center justify-center">
                                            <i class="fas fa-user text-blue-600"></i>
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
                                </td>
                                <td class="px-6 py-4">
                                    <?php
                                    $badgeColor = [
                                        'courant' => 'bg-blue-100 text-blue-800',
                                        'epargne' => 'bg-green-100 text-green-800',
                                        'entreprise' => 'bg-purple-100 text-purple-800'
                                    ][$compte['type_compte']] ?? 'bg-gray-100 text-gray-800';
                                    ?>
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full <?php echo $badgeColor; ?>">
                                        <?php echo ucfirst($compte['type_compte']); ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <span class="text-lg font-bold <?php echo $compte['solde'] >= 0 ? 'text-green-600' : 'text-red-600'; ?>">
                                        <?php echo number_format($compte['solde'], 2, ',', ' '); ?> €
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex justify-center space-x-2">
                                        <a href="edit.php?id=<?php echo $compte['id']; ?>"
                                            class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded transition">
                                            <i class="fas fa-edit"></i> Modifier
                                        </a>
                                        <a href="delete.php?id=<?php echo $compte['id']; ?>"
                                            onclick="return confirm('Supprimer ce compte ?')"
                                            class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded transition">
                                            <i class="fas fa-trash"></i> Supprimer
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Stats rapides -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-8">
            <?php
            $totalComptes = count($comptes);
            $totalSolde = array_sum(array_column($comptes, 'solde'));
            $moyenneSolde = $totalComptes > 0 ? $totalSolde / $totalComptes : 0;
            ?>
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="bg-blue-100 rounded-full p-3 mr-4">
                        <i class="fas fa-users text-blue-600 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-gray-500 text-sm">Total comptes</p>
                        <p class="text-2xl font-bold text-gray-800"><?php echo $totalComptes; ?></p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="bg-green-100 rounded-full p-3 mr-4">
                        <i class="fas fa-euro-sign text-green-600 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-gray-500 text-sm">Capital total</p>
                        <p class="text-2xl font-bold text-green-600"><?php echo number_format($totalSolde, 2, ',', ' '); ?> €</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="bg-purple-100 rounded-full p-3 mr-4">
                        <i class="fas fa-chart-line text-purple-600 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-gray-500 text-sm">Solde moyen</p>
                        <p class="text-2xl font-bold text-gray-800"><?php echo number_format($moyenneSolde, 2, ',', ' '); ?> €</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>