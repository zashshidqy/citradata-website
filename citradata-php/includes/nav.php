<?php
/**
 * Partial: Navigasi atas.
 */

if (session_status() === PHP_SESSION_NONE) session_start();
$loggedIn  = !empty($_SESSION['user_id']);
$userName  = $loggedIn ? e($_SESSION['user_name'] ?? 'Member') : '';
$userRole  = $loggedIn ? ($_SESSION['user_role'] ?? 'member') : '';
?>
<div class="bg-slate-900 text-slate-400 text-[9px] md:text-[10px] py-1.5 px-4 md:px-6 text-center md:text-right tracking-[0.2em] font-semibold uppercase">
    www.citradataconstruction.com
</div>

<nav class="sticky top-0 z-50 bg-white/90 backdrop-blur-xl border-b border-slate-100 transition-all duration-300 shadow-sm relative">
    <div class="max-w-7xl mx-auto px-4 md:px-6 py-3 md:py-4 flex justify-between items-center">
        <a href="<?php echo url('index.php'); ?>" class="flex flex-col hover:opacity-80 transition-opacity">
            <img src="<?php echo asset('assets/images/citradata-logo.png'); ?>" alt="Citradata Logo" class="h-6 md:h-10 object-contain">
        </a>

        <ul class="hidden lg:flex space-x-10 text-sm font-semibold text-slate-500">
            <li><a href="<?php echo url('index.php'); ?>"                class="<?php echo isActivePage('index.php')        ? 'text-brandBlue border-b-2 border-brandBlue pb-1' : 'hover:text-slate-900 transition-colors'; ?>">Home</a></li>
            <li><a href="<?php echo url('pages/product.php'); ?>"        class="<?php echo isActivePage('product.php')      ? 'text-brandBlue border-b-2 border-brandBlue pb-1' : 'hover:text-slate-900 transition-colors'; ?>">Product</a></li>
            <li><a href="<?php echo url('pages/about.php'); ?>"          class="<?php echo isActivePage('about.php')        ? 'text-brandBlue border-b-2 border-brandBlue pb-1' : 'hover:text-slate-900 transition-colors'; ?>">About</a></li>
            <li><a href="<?php echo url('pages/news.php'); ?>"           class="<?php echo isActivePage('news.php')         ? 'text-brandBlue border-b-2 border-brandBlue pb-1' : 'hover:text-slate-900 transition-colors'; ?>">News</a></li>
            <li><a href="<?php echo url('pages/contact.php'); ?>"        class="<?php echo isActivePage('contact.php')      ? 'text-brandBlue border-b-2 border-brandBlue pb-1' : 'hover:text-slate-900 transition-colors'; ?>">Contact</a></li>
        </ul>

        <div class="hidden lg:flex items-center space-x-4">
            <?php if ($loggedIn): ?>
                <span class="text-sm font-medium text-slate-600">Hi, <?php echo $userName; ?></span>
                <?php if ($userRole === 'admin'): ?>
                <a href="<?php echo url('admin/index.php'); ?>" class="px-5 py-2 text-sm font-semibold text-white bg-slate-700 rounded-full shadow-sm hover:bg-slate-900 transition-all">Admin Panel</a>
                <?php endif; ?>
                <a href="<?php echo url('pages/dashboard.php'); ?>" class="px-5 py-2 text-sm font-semibold text-white bg-brandBlue rounded-full shadow-md hover:bg-blue-800 transition-all">Dashboard</a>
                <a href="<?php echo url('pages/projects.php'); ?>" class="px-5 py-2 text-sm font-semibold text-brandBlue bg-white border border-brandBlue rounded-full shadow-sm hover:bg-blue-50 transition-all">Find Projects</a>
                <a href="<?php echo url('includes/logout.php'); ?>" class="px-5 py-2 text-sm font-semibold text-slate-700 bg-white border border-slate-200 rounded-full shadow-sm hover:bg-slate-50 transition-all">Logout</a>
            <?php else: ?>
                <a href="<?php echo url('pages/login.php'); ?>" class="px-6 py-2 text-sm font-semibold text-slate-700 bg-white border border-slate-200 rounded-full shadow-sm hover:bg-slate-50 transition-all">Sign In</a>
                <a href="<?php echo url('pages/register.php'); ?>" class="px-6 py-2 text-sm font-semibold text-brandBlue bg-white border border-brandBlue rounded-full shadow-sm hover:bg-blue-50 transition-all">Sign Up</a>
                <a href="<?php echo url('pages/login.php?trial=1'); ?>" class="px-6 py-2 text-sm font-semibold text-white bg-brandBlue rounded-full shadow-md hover:bg-blue-800 transition-all">Find Projects</a>
            <?php endif; ?>
        </div>

        <button id="mobile-menu-btn" class="lg:hidden text-slate-600 hover:text-brandBlue text-2xl transition-colors">
            <i class="fas fa-bars"></i>
        </button>
    </div>

    <div id="mobile-menu" class="hidden lg:hidden absolute top-full left-0 w-full bg-white/95 backdrop-blur-xl border-b border-slate-200/60 shadow-xl transition-all duration-300">
        <div class="px-4 pt-2 pb-6 space-y-2">
            <a href="<?php echo url('index.php'); ?>"         class="block px-4 py-3 text-sm font-semibold <?php echo isActivePage('index.php')   ? 'text-brandBlue bg-blue-50/50' : 'text-slate-600 hover:text-brandBlue hover:bg-slate-50'; ?> rounded-xl transition-colors">Home</a>
            <a href="<?php echo url('pages/product.php'); ?>" class="block px-4 py-3 text-sm font-semibold <?php echo isActivePage('product.php') ? 'text-brandBlue bg-blue-50/50' : 'text-slate-600 hover:text-brandBlue hover:bg-slate-50'; ?> rounded-xl transition-colors">Product</a>
            <a href="<?php echo url('pages/about.php'); ?>"   class="block px-4 py-3 text-sm font-semibold <?php echo isActivePage('about.php')   ? 'text-brandBlue bg-blue-50/50' : 'text-slate-600 hover:text-brandBlue hover:bg-slate-50'; ?> rounded-xl transition-colors">About</a>
            <a href="<?php echo url('pages/news.php'); ?>"    class="block px-4 py-3 text-sm font-semibold <?php echo isActivePage('news.php')    ? 'text-brandBlue bg-blue-50/50' : 'text-slate-600 hover:text-brandBlue hover:bg-slate-50'; ?> rounded-xl transition-colors">News</a>
            <a href="<?php echo url('pages/contact.php'); ?>" class="block px-4 py-3 text-sm font-semibold <?php echo isActivePage('contact.php') ? 'text-brandBlue bg-blue-50/50' : 'text-slate-600 hover:text-brandBlue hover:bg-slate-50'; ?> rounded-xl transition-colors">Contact</a>

            <div class="pt-4 pb-2 border-t border-slate-100 flex flex-col space-y-3 px-2">
                <?php if ($loggedIn): ?>
                    <a href="<?php echo url('pages/dashboard.php'); ?>" class="w-full text-center px-6 py-3 text-sm font-semibold text-white bg-brandBlue rounded-xl shadow-md hover:bg-blue-800">Dashboard</a>
                    <a href="<?php echo url('pages/projects.php'); ?>" class="w-full text-center px-6 py-3 text-sm font-semibold text-brandBlue bg-white border border-brandBlue rounded-xl hover:bg-blue-50">Find Projects</a>
                    <a href="<?php echo url('includes/logout.php'); ?>" class="w-full text-center px-6 py-3 text-sm font-semibold text-slate-700 bg-white border border-slate-200 rounded-xl">Logout</a>
                <?php else: ?>
                    <a href="<?php echo url('pages/login.php'); ?>"          class="w-full text-center px-6 py-3 text-sm font-semibold text-slate-700 bg-white border border-slate-200 rounded-xl shadow-sm hover:bg-slate-50">Sign In</a>
                    <a href="<?php echo url('pages/register.php'); ?>"        class="w-full text-center px-6 py-3 text-sm font-semibold text-brandBlue bg-white border border-brandBlue rounded-xl hover:bg-blue-50">Sign Up</a>
                    <a href="<?php echo url('pages/login.php?trial=1'); ?>"  class="w-full text-center px-6 py-3 text-sm font-semibold text-white bg-brandBlue rounded-xl shadow-md hover:bg-blue-800">Find Projects</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>
