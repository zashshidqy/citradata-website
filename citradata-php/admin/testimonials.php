<?php
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../config/database.php';
requireAdmin();

$pdo        = getDbConnection();
$adminTitle = 'Testimonials';
$activePage = 'testimonials';

$action = $_POST['action'] ?? ($_GET['action'] ?? '');

if ($action === 'delete' && isset($_GET['id'])) {
    $pdo->prepare('DELETE FROM testimonials WHERE id=:id')->execute([':id' => (int)$_GET['id']]);
    header('Location: ' . url('admin/testimonials.php?deleted=1')); exit;
}
if ($action === 'toggle' && isset($_GET['id'])) {
    $pdo->prepare('UPDATE testimonials SET is_active = NOT is_active WHERE id=:id')->execute([':id' => (int)$_GET['id']]);
    header('Location: ' . url('admin/testimonials.php')); exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && in_array($action, ['create','update'])) {
    $id          = (int)($_POST['id'] ?? 0);
    $author_name = trim($_POST['author_name'] ?? '');
    $author_role = trim($_POST['author_role'] ?? '');
    $company     = trim($_POST['company']     ?? '');
    $content     = trim($_POST['content']     ?? '');
    $rating      = min(5, max(1, (int)($_POST['rating'] ?? 5)));
    $is_active   = isset($_POST['is_active']) ? 1 : 0;
    $sort_order  = (int)($_POST['sort_order'] ?? 0);

    if ($author_name !== '' && $content !== '') {
        if ($action === 'create') {
            $pdo->prepare('INSERT INTO testimonials (author_name,author_role,company,content,rating,is_active,sort_order) VALUES(:n,:r,:co,:c,:ra,:a,:o)')
                ->execute([':n'=>$author_name,':r'=>$author_role,':co'=>$company,':c'=>$content,':ra'=>$rating,':a'=>$is_active,':o'=>$sort_order]);
        } else {
            $pdo->prepare('UPDATE testimonials SET author_name=:n,author_role=:r,company=:co,content=:c,rating=:ra,is_active=:a,sort_order=:o WHERE id=:id')
                ->execute([':n'=>$author_name,':r'=>$author_role,':co'=>$company,':c'=>$content,':ra'=>$rating,':a'=>$is_active,':o'=>$sort_order,':id'=>$id]);
        }
        header('Location: ' . url('admin/testimonials.php?saved=1')); exit;
    }
}

$editItem = null;
if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare('SELECT * FROM testimonials WHERE id=:id');
    $stmt->execute([':id' => (int)$_GET['edit']]);
    $editItem = $stmt->fetch();
}

$items = $pdo->query('SELECT * FROM testimonials ORDER BY sort_order ASC, created_at DESC')->fetchAll();

require __DIR__ . '/includes/admin_head.php';
require __DIR__ . '/includes/admin_sidebar.php';
?>
<div class="flex-grow p-8 max-w-5xl">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-slate-900">Testimonials</h1>
        <a href="<?php echo url('admin/testimonials.php?new=1'); ?>" class="px-5 py-2 bg-brandBlue text-white text-sm font-semibold rounded-xl hover:bg-blue-800 transition-colors">+ Add Testimonial</a>
    </div>

    <?php if (isset($_GET['saved'])): ?><div class="mb-4 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl text-sm font-medium">Saved successfully.</div><?php endif; ?>
    <?php if (isset($_GET['deleted'])): ?><div class="mb-4 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl text-sm font-medium">Deleted.</div><?php endif; ?>

    <?php if (isset($_GET['new']) || $editItem): ?>
    <div class="bg-white rounded-2xl p-7 shadow-sm border border-slate-100 mb-8">
        <h2 class="font-semibold text-slate-800 mb-5"><?php echo $editItem ? 'Edit Testimonial' : 'Add Testimonial'; ?></h2>
        <form method="POST" action="<?php echo url('admin/testimonials.php'); ?>" class="space-y-4">
            <input type="hidden" name="action" value="<?php echo $editItem ? 'update' : 'create'; ?>">
            <?php if ($editItem): ?><input type="hidden" name="id" value="<?php echo $editItem['id']; ?>"><?php endif; ?>

            <div class="grid sm:grid-cols-2 gap-4">
                <div><label class="block text-sm font-semibold text-slate-700 mb-1">Author Name *</label>
                <input type="text" name="author_name" required value="<?php echo e($editItem['author_name'] ?? ''); ?>" class="w-full h-11 px-4 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brandBlue/20 focus:border-brandBlue transition-all"></div>

                <div><label class="block text-sm font-semibold text-slate-700 mb-1">Role / Title</label>
                <input type="text" name="author_role" value="<?php echo e($editItem['author_role'] ?? ''); ?>" placeholder="e.g. Sales Director" class="w-full h-11 px-4 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brandBlue/20 focus:border-brandBlue transition-all"></div>
            </div>

            <div><label class="block text-sm font-semibold text-slate-700 mb-1">Company</label>
            <input type="text" name="company" value="<?php echo e($editItem['company'] ?? ''); ?>" class="w-full h-11 px-4 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brandBlue/20 focus:border-brandBlue transition-all"></div>

            <div><label class="block text-sm font-semibold text-slate-700 mb-1">Testimonial Content *</label>
            <textarea name="content" rows="4" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brandBlue/20 focus:border-brandBlue transition-all resize-none"><?php echo e($editItem['content'] ?? ''); ?></textarea></div>

            <div class="flex items-center gap-6">
                <div><label class="block text-sm font-semibold text-slate-700 mb-1">Rating (1–5)</label>
                <select name="rating" class="h-11 px-4 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brandBlue/20 focus:border-brandBlue transition-all">
                    <?php for ($i=5; $i>=1; $i--): ?>
                    <option value="<?php echo $i; ?>" <?php echo ($editItem['rating'] ?? 5) == $i ? 'selected' : ''; ?>><?php echo $i; ?> ★</option>
                    <?php endfor; ?>
                </select></div>

                <div><label class="block text-sm font-semibold text-slate-700 mb-1">Sort Order</label>
                <input type="number" name="sort_order" value="<?php echo (int)($editItem['sort_order'] ?? 0); ?>" class="w-24 h-11 px-4 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brandBlue/20 focus:border-brandBlue transition-all"></div>

                <label class="flex items-center gap-2 text-sm font-medium text-slate-700 mt-5 cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" <?php echo ($editItem['is_active'] ?? 1) ? 'checked' : ''; ?> class="rounded">
                    Active
                </label>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit" class="px-6 py-2.5 bg-brandBlue text-white text-sm font-semibold rounded-xl hover:bg-blue-800 transition-colors">Save</button>
                <a href="<?php echo url('admin/testimonials.php'); ?>" class="px-6 py-2.5 bg-slate-100 text-slate-700 text-sm font-semibold rounded-xl hover:bg-slate-200 transition-colors">Cancel</a>
            </div>
        </form>
    </div>
    <?php endif; ?>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 border-b border-slate-100">
                <tr>
                    <th class="px-5 py-3 text-left font-semibold text-slate-500 text-xs uppercase tracking-wide">Author</th>
                    <th class="px-5 py-3 text-left font-semibold text-slate-500 text-xs uppercase tracking-wide">Preview</th>
                    <th class="px-5 py-3 text-center font-semibold text-slate-500 text-xs uppercase tracking-wide">Rating</th>
                    <th class="px-5 py-3 text-center font-semibold text-slate-500 text-xs uppercase tracking-wide">Status</th>
                    <th class="px-5 py-3 text-right font-semibold text-slate-500 text-xs uppercase tracking-wide">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                <?php foreach ($items as $item): ?>
                <tr class="hover:bg-slate-50/50 transition-colors">
                    <td class="px-5 py-4">
                        <p class="font-medium text-slate-800"><?php echo e($item['author_name']); ?></p>
                        <p class="text-slate-400 text-xs mt-0.5"><?php echo e($item['author_role'] ?? ''); ?><?php if ($item['company']): ?> – <?php echo e($item['company']); ?><?php endif; ?></p>
                    </td>
                    <td class="px-5 py-4 max-w-xs"><p class="text-slate-500 text-xs line-clamp-2 italic">"<?php echo e($item['content']); ?>"</p></td>
                    <td class="px-5 py-4 text-center text-yellow-400 text-xs"><?php echo str_repeat('★', (int)$item['rating']); ?></td>
                    <td class="px-5 py-4 text-center">
                        <a href="<?php echo url('admin/testimonials.php?action=toggle&id=' . $item['id']); ?>"
                           class="inline-flex px-2 py-1 rounded-lg text-xs font-semibold <?php echo $item['is_active'] ? 'bg-green-50 text-green-700' : 'bg-slate-100 text-slate-400'; ?>">
                            <?php echo $item['is_active'] ? 'Active' : 'Hidden'; ?>
                        </a>
                    </td>
                    <td class="px-5 py-4 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="<?php echo url('admin/testimonials.php?edit=' . $item['id']); ?>" class="px-3 py-1.5 bg-slate-100 text-slate-700 text-xs font-semibold rounded-lg hover:bg-slate-200 transition-colors">Edit</a>
                            <a href="<?php echo url('admin/testimonials.php?action=delete&id=' . $item['id']); ?>" onclick="return confirm('Delete this testimonial?')" class="px-3 py-1.5 bg-red-50 text-red-600 text-xs font-semibold rounded-lg hover:bg-red-100 transition-colors">Delete</a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($items)): ?><tr><td colspan="5" class="px-5 py-10 text-center text-slate-400 text-sm">No testimonials yet.</td></tr><?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>
