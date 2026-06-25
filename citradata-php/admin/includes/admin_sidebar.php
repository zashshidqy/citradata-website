<?php
if (!isset($activePage)) $activePage = '';
$nav = [
    ['href' => url('admin/index.php'),       'icon' => 'fa-gauge',      'label' => 'Dashboard',    'key' => 'dashboard'],
    ['href' => url('admin/projects.php'),    'icon' => 'fa-building',   'label' => 'Projects',     'key' => 'projects'],
    ['href' => url('admin/news.php'),        'icon' => 'fa-newspaper',  'label' => 'Latest News',  'key' => 'news'],
    ['href' => url('admin/testimonials.php'),'icon' => 'fa-quote-left', 'label' => 'Testimonials', 'key' => 'testimonials'],
    ['href' => url('admin/hero_slides.php'), 'icon' => 'fa-image',      'label' => 'Hero Slides',  'key' => 'hero_slides'],
    ['href' => url('admin/logos.php'),       'icon' => 'fa-images',     'label' => 'Logos',        'key' => 'logos'],
    ['href' => url('admin/messages.php'),    'icon' => 'fa-envelope',   'label' => 'Messages',     'key' => 'messages'],
    ['href' => url('admin/users.php'),       'icon' => 'fa-users',      'label' => 'Users',        'key' => 'users'],
];
?>
<aside class="w-64 min-h-screen bg-slate-900 text-slate-300 flex flex-col shrink-0">
    <div class="px-6 py-5 border-b border-slate-800">
        <img src="<?php echo asset('assets/images/citradata-logo.png'); ?>" alt="Citradata" class="h-8 object-contain filter brightness-0 invert opacity-80 mb-1">
        <p class="text-[10px] text-slate-500 uppercase tracking-widest font-semibold">Admin Panel</p>
    </div>
    <nav class="flex-grow px-3 py-4 space-y-1">
        <?php foreach ($nav as $item): ?>
        <a href="<?php echo $item['href']; ?>"
           class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-colors
                  <?php echo $activePage === $item['key'] ? 'bg-brandBlue text-white shadow' : 'text-slate-400 hover:bg-slate-800 hover:text-white'; ?>">
            <i class="fas <?php echo $item['icon']; ?> w-4 text-center"></i>
            <?php echo $item['label']; ?>
        </a>
        <?php endforeach; ?>
    </nav>
    <div class="px-4 py-4 border-t border-slate-800 space-y-2">
        <a href="<?php echo url('index.php'); ?>" class="flex items-center gap-3 px-4 py-2 rounded-xl text-sm text-slate-400 hover:bg-slate-800 hover:text-white transition-colors">
            <i class="fas fa-arrow-left w-4 text-center"></i> Back to Website
        </a>
        <a href="<?php echo url('includes/logout.php'); ?>" class="flex items-center gap-3 px-4 py-2 rounded-xl text-sm text-slate-400 hover:bg-red-900/30 hover:text-red-400 transition-colors">
            <i class="fas fa-right-from-bracket w-4 text-center"></i> Logout
        </a>
    </div>
</aside>
