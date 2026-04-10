<?php
// Déterminer la page active
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion Bancaire Pro</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="assets/style.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    animation: {
                        'fade-in': 'fadeIn 0.5s ease-out',
                        'slide-in': 'slideInLeft 0.5s ease-out',
                    }
                }
            }
        }
    </script>
</head>

<body class="bg-gradient-to-br from-gray-50 to-gray-100 font-sans antialiased">

    <!-- Mobile menu button -->
    <div class="lg:hidden fixed top-4 left-4 z-50">
        <button onclick="toggleSidebar()" class="bg-blue-600 text-white p-3 rounded-lg shadow-lg hover:bg-blue-700 transition-all duration-300">
            <i class="fas fa-bars text-xl"></i>
        </button>
    </div>

    <!-- Overlay pour mobile -->
    <div id="overlay" class="fixed inset-0 bg-black bg-opacity-50 z-30 hidden lg:hidden" onclick="toggleSidebar()"></div>

    <!-- Sidebar -->
    <aside id="sidebar" class="fixed top-0 left-0 h-full w-72 bg-gradient-to-b from-gray-900 via-gray-800 to-gray-900 shadow-2xl z-40 transform transition-transform duration-300 ease-in-out lg:translate-x-0 -translate-x-full sidebar-mobile overflow-y-auto">

        <!-- Logo et branding -->
        <div class="p-6 border-b border-gray-700">
            <div class="flex items-center space-x-3 animate-slideInLeft">
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 p-3 rounded-xl shadow-lg">
                    <i class="fas fa-university text-white text-2xl"></i>
                </div>
                <div>
                    <h1 class="text-white text-xl font-bold">Bank<span class="text-blue-400">Pro</span></h1>
                    <p class="text-gray-400 text-xs">Gestion Bancaire Premium</p>
                </div>
            </div>
        </div>

        <!-- Menu principal -->
        <nav class="p-4">
            <div class="mb-8">
                <p class="text-gray-500 text-xs uppercase tracking-wider mb-3 px-4">Navigation</p>
                <a href="index.php" class="sidebar-link <?php echo $current_page == 'index.php' ? 'active' : ''; ?> flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-300 hover:text-white transition-all duration-300 group">
                    <i class="fas fa-tachometer-alt text-lg <?php echo $current_page == 'index.php' ? 'text-white' : 'text-gray-400 group-hover:text-blue-400'; ?>"></i>
                    <span class="font-medium">Tableau de bord</span>
                    <?php if ($current_page == 'index.php'): ?>
                        <i class="fas fa-chevron-right ml-auto text-sm"></i>
                    <?php endif; ?>
                </a>

                <a href="create.php" class="sidebar-link <?php echo $current_page == 'create.php' ? 'active' : ''; ?> flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-300 hover:text-white transition-all duration-300 group mt-2">
                    <i class="fas fa-plus-circle text-lg <?php echo $current_page == 'create.php' ? 'text-white' : 'text-gray-400 group-hover:text-green-400'; ?>"></i>
                    <span class="font-medium">Nouveau compte</span>
                    <?php if ($current_page == 'create.php'): ?>
                        <i class="fas fa-chevron-right ml-auto text-sm"></i>
                    <?php endif; ?>
                </a>
            </div>

            <!-- Statistiques rapides -->
            <div class="mb-8">
                <p class="text-gray-500 text-xs uppercase tracking-wider mb-3 px-4">Informations</p>

                <?php
                // Récupérer les stats pour la sidebar
                require_once 'config/database.php';
                $statsQuery = $pdo->query("SELECT COUNT(*) as total, SUM(solde) as total_solde, 
                                        SUM(CASE WHEN type_compte = 'courant' THEN 1 ELSE 0 END) as courant,
                                        SUM(CASE WHEN type_compte = 'epargne' THEN 1 ELSE 0 END) as epargne,
                                        SUM(CASE WHEN type_compte = 'entreprise' THEN 1 ELSE 0 END) as entreprise
                                        FROM comptes");
                $stats = $statsQuery->fetch();
                ?>

                <div class="space-y-3">
                    <div class="bg-gray-800 bg-opacity-50 rounded-lg p-3 hover:bg-opacity-70 transition-all duration-300">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-400 text-sm">Total comptes</span>
                            <span class="text-white font-bold text-lg"><?php echo $stats['total']; ?></span>
                        </div>
                    </div>

                    <div class="bg-gray-800 bg-opacity-50 rounded-lg p-3 hover:bg-opacity-70 transition-all duration-300">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-400 text-sm">Capital total</span>
                            <span class="text-green-400 font-bold text-lg"><?php echo number_format($stats['total_solde'], 0, ',', ' '); ?> €</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Types de comptes -->
            <div>
                <p class="text-gray-500 text-xs uppercase tracking-wider mb-3 px-4">Types de comptes</p>
                <div class="space-y-2">
                    <div class="flex items-center justify-between px-4 py-2 rounded-lg bg-gray-800 bg-opacity-30">
                        <div class="flex items-center space-x-2">
                            <i class="fas fa-wallet text-blue-400"></i>
                            <span class="text-gray-300 text-sm">Comptes courants</span>
                        </div>
                        <span class="text-white font-semibold text-sm"><?php echo $stats['courant']; ?></span>
                    </div>
                    <div class="flex items-center justify-between px-4 py-2 rounded-lg bg-gray-800 bg-opacity-30">
                        <div class="flex items-center space-x-2">
                            <i class="fas fa-piggy-bank text-green-400"></i>
                            <span class="text-gray-300 text-sm">Comptes épargne</span>
                        </div>
                        <span class="text-white font-semibold text-sm"><?php echo $stats['epargne']; ?></span>
                    </div>
                    <div class="flex items-center justify-between px-4 py-2 rounded-lg bg-gray-800 bg-opacity-30">
                        <div class="flex items-center space-x-2">
                            <i class="fas fa-building text-purple-400"></i>
                            <span class="text-gray-300 text-sm">Comptes entreprise</span>
                        </div>
                        <span class="text-white font-semibold text-sm"><?php echo $stats['entreprise']; ?></span>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Footer sidebar -->
        <div class="absolute bottom-0 left-0 right-0 p-4 border-t border-gray-700">
            <div class="bg-gray-800 rounded-lg p-3">
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center">
                        <i class="fas fa-shield-alt text-white text-sm"></i>
                    </div>
                    <div>
                        <p class="text-white text-xs font-semibold">Sécurisé</p>
                        <p class="text-gray-500 text-xs">Données protégées</p>
                    </div>
                </div>
            </div>
        </div>
    </aside>

    <!-- Contenu principal -->
    <main class="lg:ml-72 min-h-screen">
        <div class="container mx-auto px-4 py-8">

            <!-- Barre d'en-tête avec recherche et profil -->
            <div class="bg-white rounded-xl shadow-sm p-4 mb-8 flex justify-between items-center animate-slideInRight">
                <div class="flex items-center space-x-4">
                    <div class="relative">
                        <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        <input type="text" placeholder="Rechercher un client..." id="searchInput"
                            class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 w-64">
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="relative">
                        <i class="fas fa-bell text-gray-500 text-xl cursor-pointer hover:text-blue-600 transition-colors"></i>
                        <span class="absolute -top-1 -right-1 w-3 h-3 bg-red-500 rounded-full animate-pulse"></span>
                    </div>
                    <div class="flex items-center space-x-3 cursor-pointer hover:bg-gray-50 rounded-lg p-2 transition-all">
                        <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-blue-600 rounded-full flex items-center justify-center">
                            <i class="fas fa-user text-white"></i>
                        </div>
                        <div class="hidden md:block">
                            <p class="text-sm font-semibold text-gray-700">Administrateur</p>
                            <p class="text-xs text-gray-500">Banque Pro</p>
                        </div>
                    </div>
                </div>
            </div>

            <script>
                // Fonction pour toggle la sidebar sur mobile
                function toggleSidebar() {
                    const sidebar = document.getElementById('sidebar');
                    const overlay = document.getElementById('overlay');
                    sidebar.classList.toggle('open');
                    overlay.classList.toggle('hidden');
                }

                // Fonction de recherche (à implémenter selon la page)
                document.addEventListener('DOMContentLoaded', function() {
                    const searchInput = document.getElementById('searchInput');
                    if (searchInput) {
                        searchInput.addEventListener('keyup', function() {
                            const searchTerm = this.value.toLowerCase();
                            const rows = document.querySelectorAll('tbody tr');
                            rows.forEach(row => {
                                const text = row.textContent.toLowerCase();
                                row.style.display = text.includes(searchTerm) ? '' : 'none';
                            });
                        });
                    }
                });
            </script>