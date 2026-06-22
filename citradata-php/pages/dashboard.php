<?php
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../config/database.php';

if (session_status() === PHP_SESSION_NONE) session_start();
requireLogin();

$pageTitle = 'Dashboard – Citradata';
$useSwiper = false;

$welcomeMsg = $_SESSION['dashboard_welcome'] ?? null;
unset($_SESSION['dashboard_welcome']);

require __DIR__ . '/../includes/head.php';
?>
<body class="bg-slate-50 text-slate-800 antialiased flex flex-col min-h-screen">
<?php require __DIR__ . '/../includes/nav.php'; ?>

<main class="flex-grow py-16 px-4">
    <div class="max-w-4xl mx-auto">

        <!-- Welcome -->
        <div class="bg-white rounded-[2rem] p-8 md:p-12 shadow-sm border border-slate-100 mb-8">
            <?php if ($welcomeMsg): ?>
            <div class="mb-6 rounded-2xl border border-green-200 bg-green-50 text-green-800 px-5 py-4 flex items-center gap-3 text-sm font-medium">
                <i class="fas fa-circle-check text-green-500"></i> <?php echo e($welcomeMsg); ?>
            </div>
            <?php endif; ?>
            <div class="flex items-center gap-5">
                <div class="w-14 h-14 rounded-2xl bg-brandBlue/10 flex items-center justify-center text-brandBlue font-bold text-2xl shrink-0">
                    <?php echo strtoupper(substr($_SESSION['user_name'], 0, 1)); ?>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-slate-900">Welcome, <?php echo e($_SESSION['user_name']); ?>!</h1>
                    <p class="text-slate-500 text-sm mt-1">
                        Role: <span class="capitalize font-semibold text-brandBlue"><?php echo e($_SESSION['user_role']); ?></span>
                    </p>
                </div>
            </div>
        </div>

        <?php if ($_SESSION['user_role'] === 'trial'): ?>
        <!-- Free Trial banner -->
        <div class="bg-amber-50 border border-amber-200 rounded-2xl p-6 mb-8 flex items-start gap-4">
            <i class="fas fa-info-circle text-amber-500 text-xl mt-0.5"></i>
            <div>
                <p class="font-semibold text-amber-800 mb-1">You are on a Free Trial</p>
                <p class="text-amber-700 text-sm">You have preview access to construction project data. To unlock full access, please <a href="<?php echo url('pages/contact.php'); ?>" class="underline font-semibold">contact us</a> to upgrade your membership.</p>
            </div>
        </div>
        <?php endif; ?>

        <!-- Quick links -->
        <div class="grid sm:grid-cols-2 gap-4">
            <a href="<?php echo url('pages/product.php'); ?>" class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm hover:shadow-md transition-shadow group flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-red-50 flex items-center justify-center text-brandRed group-hover:scale-105 transition-transform">
                    <i class="fas fa-building text-xl"></i>
                </div>
                <div>
                    <p class="font-semibold text-slate-800">Construction Data</p>
                    <p class="text-slate-400 text-xs mt-0.5">Browse project information</p>
                </div>
                <i class="fas fa-arrow-right ml-auto text-slate-300 group-hover:text-brandBlue transition-colors"></i>
            </a>

            <a href="<?php echo url('pages/product.php'); ?>" class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm hover:shadow-md transition-shadow group flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center text-brandBlue group-hover:scale-105 transition-transform">
                    <i class="fas fa-chart-pie text-xl"></i>
                </div>
                <div>
                    <p class="font-semibold text-slate-800">Market Insights</p>
                    <p class="text-slate-400 text-xs mt-0.5">Analysis & reports</p>
                </div>
                <i class="fas fa-arrow-right ml-auto text-slate-300 group-hover:text-brandBlue transition-colors"></i>
            </a>

            <a href="<?php echo url('pages/contact.php'); ?>" class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm hover:shadow-md transition-shadow group flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-green-50 flex items-center justify-center text-green-600 group-hover:scale-105 transition-transform">
                    <i class="fas fa-envelope text-xl"></i>
                </div>
                <div>
                    <p class="font-semibold text-slate-800">Contact Us</p>
                    <p class="text-slate-400 text-xs mt-0.5">Get support or upgrade</p>
                </div>
                <i class="fas fa-arrow-right ml-auto text-slate-300 group-hover:text-brandBlue transition-colors"></i>
            </a>

            <?php if ($_SESSION['user_role'] === 'admin'): ?>
            <a href="<?php echo url('admin/index.php'); ?>" class="bg-slate-900 rounded-2xl p-6 border border-slate-800 shadow-sm hover:shadow-md transition-shadow group flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-white/10 flex items-center justify-center text-white group-hover:scale-105 transition-transform">
                    <i class="fas fa-cog text-xl"></i>
                </div>
                <div>
                    <p class="font-semibold text-white">Admin Panel</p>
                    <p class="text-slate-400 text-xs mt-0.5">Manage content</p>
                </div>
                <i class="fas fa-arrow-right ml-auto text-slate-600 group-hover:text-white transition-colors"></i>
            </a>
            <?php endif; ?>
        </div>

    </div>
</main>

<?php $footerVariant = 'light'; require __DIR__ . '/../includes/footer.php'; ?>
<?php require __DIR__ . '/../includes/scripts.php'; ?>
</body>
</html>
