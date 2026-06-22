<?php
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../config/database.php';
requireAdmin();

$pdo        = getDbConnection();
$adminTitle = 'Latest News';
$activePage = 'news';
$flash      = '';

// Handle actions
$action = $_POST['action'] ?? ($_GET['action'] ?? '');

if ($action === 'delete' && isset($_GET['id'])) {
    $pdo->prepare('DELETE FROM latest_news WHERE id = :id')->execute([':id' => (int)$_GET['id']]);
    header('Location: ' . url('admin/news.php?deleted=1'));
    exit;
}

if ($action === 'toggle' && isset($_GET['id'])) {
    $pdo->prepare('UPDATE latest_news SET is_active = NOT is_active WHERE id = :id')->execute([':id' => (int)$_GET['id']]);
    header('Location: ' . url('admin/news.php'));
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && in_array($action, ['create','update'])) {
    $id         = (int)($_POST['id'] ?? 0);
    $title      = trim($_POST['title']      ?? '');
    $summary    = trim($_POST['summary']    ?? '');
    $content    = trim($_POST['content']    ?? '');
    $image_url  = trim($_POST['image_url']  ?? '');
    $is_active  = isset($_POST['is_active']) ? 1 : 0;
    $sort_order = (int)($_POST['sort_order'] ?? 0);

    if ($title !== '') {
        if ($action === 'create') {
            $pdo->prepare('INSERT INTO latest_news (title,summary,content,image_url,is_active,sort_order) VALUES(:t,:s,:c,:i,:a,:o)')
                ->execute([':t'=>$title,':s'=>$summary,':c'=>$content,':i'=>$image_url,':a'=>$is_active,':o'=>$sort_order]);
        } else {
            $pdo->prepare('UPDATE latest_news SET title=:t,summary=:s,content=:c,image_url=:i,is_active=:a,sort_order=:o WHERE id=:id')
                ->execute([':t'=>$title,':s'=>$summary,':c'=>$content,':i'=>$image_url,':a'=>$is_active,':o'=>$sort_order,':id'=>$id]);
        }
        header('Location: ' . url('admin/news.php?saved=1'));
        exit;
    }
}

// Fetch for edit
$editItem = null;
if (isset($_GET['edit'])) {
    $editItem = $pdo->prepare('SELECT * FROM latest_news WHERE id=:id')->execute([':id'=>(int)$_GET['edit']]) ? $pdo->query('SELECT * FROM latest_news WHERE id='.(int)$_GET['edit'])->fetch() : null;
    $stmt = $pdo->prepare('SELECT * FROM latest_news WHERE id = :id');
    $stmt->execute([':id' => (int)$_GET['edit']]);
    $editItem = $stmt->fetch();
}

// List
$items = $pdo->query('SELECT * FROM latest_news ORDER BY sort_order ASC, created_at DESC')->fetchAll();

require __DIR__ . '/includes/admin_head.php';
require __DIR__ . '/includes/admin_sidebar.php';
?>
<div class="flex-grow p-8 max-w-5xl">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-slate-900">Latest News</h1>
        <a href="<?php echo url('admin/news.php?new=1'); ?>" class="px-5 py-2 bg-brandBlue text-white text-sm font-semibold rounded-xl hover:bg-blue-800 transition-colors">+ Add News</a>
    </div>

    <?php if (isset($_GET['saved'])): ?><div class="mb-4 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl text-sm font-medium">Saved successfully.</div><?php endif; ?>
    <?php if (isset($_GET['deleted'])): ?><div class="mb-4 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl text-sm font-medium">Deleted.</div><?php endif; ?>

    <!-- Form (add/edit) -->
    <?php if (isset($_GET['new']) || $editItem): ?>
    <div class="bg-white rounded-2xl p-7 shadow-sm border border-slate-100 mb-8">
        <h2 class="font-semibold text-slate-800 mb-5"><?php echo $editItem ? 'Edit News' : 'Add News'; ?></h2>
        <form method="POST" action="<?php echo url('admin/news.php'); ?>" class="space-y-4">
            <input type="hidden" name="action" value="<?php echo $editItem ? 'update' : 'create'; ?>">
            <?php if ($editItem): ?><input type="hidden" name="id" value="<?php echo $editItem['id']; ?>"><?php endif; ?>

            <div><label class="block text-sm font-semibold text-slate-700 mb-1">Title *</label>
            <input type="text" name="title" required value="<?php echo e($editItem['title'] ?? ''); ?>" class="w-full h-11 px-4 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brandBlue/20 focus:border-brandBlue transition-all"></div>

            <div><label class="block text-sm font-semibold text-slate-700 mb-1">Summary (short excerpt)</label>
            <textarea name="summary" rows="2" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brandBlue/20 focus:border-brandBlue transition-all resize-none"><?php echo e($editItem['summary'] ?? ''); ?></textarea></div>

            <div><label class="block text-sm font-semibold text-slate-700 mb-1">Full Content (HTML allowed)</label>
            <textarea name="content" rows="6" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-mono focus:outline-none focus:ring-2 focus:ring-brandBlue/20 focus:border-brandBlue transition-all resize-y"><?php echo e($editItem['content'] ?? ''); ?></textarea></div>

            <div><label class="block text-sm font-semibold text-slate-700 mb-1">Image URL (optional)</label>
            <input type="url" name="image_url" value="<?php echo e($editItem['image_url'] ?? ''); ?>" placeholder="https://..." class="w-full h-11 px-4 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brandBlue/20 focus:border-brandBlue transition-all"></div>

            <div class="flex items-center gap-6">
                <div><label class="block text-sm font-semibold text-slate-700 mb-1">Sort Order</label>
                <input type="number" name="sort_order" value="<?php echo (int)($editItem['sort_order'] ?? 0); ?>" class="w-24 h-11 px-4 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brandBlue/20 focus:border-brandBlue transition-all"></div>
                <label class="flex items-center gap-2 text-sm font-medium text-slate-700 mt-5 cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" <?php echo ($editItem['is_active'] ?? 1) ? 'checked' : ''; ?> class="rounded">
                    Active (visible on website)
                </label>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit" class="px-6 py-2.5 bg-brandBlue text-white text-sm font-semibold rounded-xl hover:bg-blue-800 transition-colors">Save</button>
                <a href="<?php echo url('admin/news.php'); ?>" class="px-6 py-2.5 bg-slate-100 text-slate-700 text-sm font-semibold rounded-xl hover:bg-slate-200 transition-colors">Cancel</a>
            </div>
        </form>
    </div>
    <?php endif; ?>

    <!-- List -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 border-b border-slate-100">
                <tr>
                    <th class="px-5 py-3 text-left font-semibold text-slate-500 text-xs uppercase tracking-wide">Title</th>
                    <th class="px-5 py-3 text-center font-semibold text-slate-500 text-xs uppercase tracking-wide">Status</th>
                    <th class="px-5 py-3 text-center font-semibold text-slate-500 text-xs uppercase tracking-wide">Order</th>
                    <th class="px-5 py-3 text-center font-semibold text-slate-500 text-xs uppercase tracking-wide">Date</th>
                    <th class="px-5 py-3 text-right font-semibold text-slate-500 text-xs uppercase tracking-wide">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                <?php foreach ($items as $item): ?>
                <tr class="hover:bg-slate-50/50 transition-colors">
                    <td class="px-5 py-4">
                        <p class="font-medium text-slate-800 line-clamp-1"><?php echo e($item['title']); ?></p>
                        <?php if ($item['summary']): ?><p class="text-slate-400 text-xs mt-0.5 line-clamp-1"><?php echo e($item['summary']); ?></p><?php endif; ?>
                    </td>
                    <td class="px-5 py-4 text-center">
                        <a href="<?php echo url('admin/news.php?action=toggle&id=' . $item['id']); ?>"
                           class="inline-flex items-center gap-1 px-2 py-1 rounded-lg text-xs font-semibold <?php echo $item['is_active'] ? 'bg-green-50 text-green-700' : 'bg-slate-100 text-slate-400'; ?>">
                            <?php echo $item['is_active'] ? 'Active' : 'Hidden'; ?>
                        </a>
                    </td>
                    <td class="px-5 py-4 text-center text-slate-400 text-xs"><?php echo $item['sort_order']; ?></td>
                    <td class="px-5 py-4 text-center text-slate-400 text-xs"><?php echo date('d M Y', strtotime($item['created_at'])); ?></td>
                    <td class="px-5 py-4 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="<?php echo url('admin/news.php?edit=' . $item['id']); ?>" class="px-3 py-1.5 bg-slate-100 text-slate-700 text-xs font-semibold rounded-lg hover:bg-slate-200 transition-colors">Edit</a>
                            <a href="<?php echo url('admin/news.php?action=delete&id=' . $item['id']); ?>"
                               onclick="return confirm('Delete this news item?')"
                               class="px-3 py-1.5 bg-red-50 text-red-600 text-xs font-semibold rounded-lg hover:bg-red-100 transition-colors">Delete</a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($items)): ?>
                <tr><td colspan="5" class="px-5 py-10 text-center text-slate-400 text-sm">No news items yet.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>
