<?php
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../config/database.php';
requireAdmin();

$adminTitle = 'Dashboard';
$activePage = 'dashboard';

// Stats
try {
    $pdo = getDbConnection();
    $stats = [
        'projects'     => $pdo->query('SELECT COUNT(*) FROM projects')->fetchColumn(),
        'news'         => $pdo->query('SELECT COUNT(*) FROM latest_news')->fetchColumn(),
        'testimonials' => $pdo->query('SELECT COUNT(*) FROM testimonials')->fetchColumn(),
        'logos'        => $pdo->query('SELECT COUNT(*) FROM client_logos')->fetchColumn(),
        'messages'     => $pdo->query('SELECT COUNT(*) FROM contact_messages')->fetchColumn(),
        'users'        => $pdo->query('SELECT COUNT(*) FROM users')->fetchColumn(),
    ];
    $recentMessages = $pdo->query('SELECT * FROM contact_messages ORDER BY created_at DESC LIMIT 5')->fetchAll();
} catch (Exception $e) {
    $stats = ['projects'=>0,'news'=>0,'testimonials'=>0,'logos'=>0,'messages'=>0,'users'=>0];
    $recentMessages = [];
}

require __DIR__ . '/includes/admin_head.php';
require __DIR__ . '/includes/admin_sidebar.php';
?>

<div class="flex-grow p-8">
    <h1 class="text-2xl font-bold text-slate-900 mb-8">Dashboard</h1>

    <!-- Stats cards -->
    <div class="grid grid-cols-2 lg:grid-cols-6 gap-4 mb-10">
        <?php
        $cards = [
            ['label'=>'Projects',     'count'=>$stats['projects'],     'icon'=>'fa-building',    'color'=>'text-teal-500',   'href'=>url('admin/projects.php')],
            ['label'=>'News',         'count'=>$stats['news'],         'icon'=>'fa-newspaper',   'color'=>'text-blue-500',   'href'=>url('admin/news.php')],
            ['label'=>'Testimonials', 'count'=>$stats['testimonials'], 'icon'=>'fa-quote-left',  'color'=>'text-purple-500', 'href'=>url('admin/testimonials.php')],
            ['label'=>'Logos',        'count'=>$stats['logos'],        'icon'=>'fa-images',      'color'=>'text-green-500',  'href'=>url('admin/logos.php')],
            ['label'=>'Messages',     'count'=>$stats['messages'],     'icon'=>'fa-envelope',    'color'=>'text-orange-500', 'href'=>url('admin/messages.php')],
            ['label'=>'Users',        'count'=>$stats['users'],        'icon'=>'fa-users',       'color'=>'text-brandBlue',  'href'=>url('admin/users.php')],
        ];
        foreach ($cards as $c): ?>
        <a href="<?php echo $c['href']; ?>" class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100 hover:shadow-md transition-shadow">
            <i class="fas <?php echo $c['icon']; ?> text-xl <?php echo $c['color']; ?> mb-3 block"></i>
            <p class="text-2xl font-bold text-slate-900"><?php echo $c['count']; ?></p>
            <p class="text-slate-400 text-xs mt-1"><?php echo $c['label']; ?></p>
        </a>
        <?php endforeach; ?>
    </div>

    <!-- Recent messages -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
            <h2 class="font-semibold text-slate-800">Recent Contact Messages</h2>
            <a href="<?php echo url('admin/messages.php'); ?>" class="text-brandBlue text-xs hover:underline">View all</a>
        </div>
        <?php if (!empty($recentMessages)): ?>
        <div class="divide-y divide-slate-50">
            <?php foreach ($recentMessages as $m): ?>
            <div class="px-6 py-4">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="font-medium text-slate-800 text-sm"><?php echo e($m['name']); ?>
                            <?php if ($m['company']): ?><span class="text-slate-400 font-normal"> – <?php echo e($m['company']); ?></span><?php endif; ?>
                        </p>
                        <p class="text-slate-500 text-xs mt-0.5"><?php echo e($m['email']); ?></p>
                        <?php if ($m['subject']): ?><p class="text-slate-700 text-xs font-medium mt-1">Subject: <?php echo e($m['subject']); ?></p><?php endif; ?>
                    </div>
                    <span class="text-slate-400 text-[11px] shrink-0"><?php echo date('d M Y', strtotime($m['created_at'])); ?></span>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <div class="px-6 py-10 text-center text-slate-400 text-sm">No messages yet.</div>
        <?php endif; ?>
    </div>
</div>

</body>
</html>
