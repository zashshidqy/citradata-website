<?php
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../config/database.php';
requireAdmin();

$pdo        = getDbConnection();
$adminTitle = 'Contact Messages';
$activePage = 'messages';

if (isset($_GET['delete'])) {
    $pdo->prepare('DELETE FROM contact_messages WHERE id=:id')->execute([':id'=>(int)$_GET['delete']]);
    header('Location: ' . url('admin/messages.php?deleted=1')); exit;
}

$page     = max(1, (int)($_GET['page'] ?? 1));
$perPage  = 20;
$offset   = ($page - 1) * $perPage;
$total    = (int)$pdo->query('SELECT COUNT(*) FROM contact_messages')->fetchColumn();
$pages    = max(1, ceil($total / $perPage));
$messages = $pdo->prepare('SELECT * FROM contact_messages ORDER BY created_at DESC LIMIT :lim OFFSET :off');
$messages->bindValue(':lim', $perPage, PDO::PARAM_INT);
$messages->bindValue(':off', $offset,  PDO::PARAM_INT);
$messages->execute();
$messages = $messages->fetchAll();

// Detail view
$detail = null;
if (isset($_GET['view'])) {
    $stmt = $pdo->prepare('SELECT * FROM contact_messages WHERE id=:id');
    $stmt->execute([':id'=>(int)$_GET['view']]);
    $detail = $stmt->fetch();
}

require __DIR__ . '/includes/admin_head.php';
require __DIR__ . '/includes/admin_sidebar.php';
?>
<div class="flex-grow p-8">
    <h1 class="text-2xl font-bold text-slate-900 mb-6">Contact Messages</h1>

    <?php if (isset($_GET['deleted'])): ?><div class="mb-4 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl text-sm font-medium">Deleted.</div><?php endif; ?>

    <?php if ($detail): ?>
    <div class="bg-white rounded-2xl p-7 shadow-sm border border-slate-100 mb-8">
        <div class="flex items-center justify-between mb-5">
            <h2 class="font-semibold text-slate-800">Message Detail</h2>
            <a href="<?php echo url('admin/messages.php'); ?>" class="text-sm text-brandBlue hover:underline">← Back to list</a>
        </div>
        <div class="grid sm:grid-cols-2 gap-4 text-sm mb-5">
            <div><p class="text-slate-400 text-xs uppercase tracking-wide font-semibold mb-0.5">Name</p><p class="text-slate-800 font-medium"><?php echo e($detail['name']); ?></p></div>
            <div><p class="text-slate-400 text-xs uppercase tracking-wide font-semibold mb-0.5">Company</p><p class="text-slate-800 font-medium"><?php echo e($detail['company'] ?? '–'); ?></p></div>
            <div><p class="text-slate-400 text-xs uppercase tracking-wide font-semibold mb-0.5">Email</p><p><a href="mailto:<?php echo e($detail['email']); ?>" class="text-brandBlue hover:underline"><?php echo e($detail['email']); ?></a></p></div>
            <div><p class="text-slate-400 text-xs uppercase tracking-wide font-semibold mb-0.5">Mobile</p><p class="text-slate-800"><?php echo e($detail['mobile'] ?? '–'); ?></p></div>
            <div><p class="text-slate-400 text-xs uppercase tracking-wide font-semibold mb-0.5">Subject</p><p class="text-slate-800"><?php echo e($detail['subject'] ?? '–'); ?></p></div>
            <div><p class="text-slate-400 text-xs uppercase tracking-wide font-semibold mb-0.5">Received</p><p class="text-slate-800"><?php echo date('d M Y H:i', strtotime($detail['created_at'])); ?></p></div>
        </div>
        <?php if ($detail['message']): ?>
        <div class="bg-slate-50 rounded-xl p-5 text-sm text-slate-700 whitespace-pre-wrap"><?php echo e($detail['message']); ?></div>
        <?php endif; ?>
    </div>
    <?php endif; ?>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 border-b border-slate-100">
                <tr>
                    <th class="px-5 py-3 text-left font-semibold text-slate-500 text-xs uppercase tracking-wide">Sender</th>
                    <th class="px-5 py-3 text-left font-semibold text-slate-500 text-xs uppercase tracking-wide hidden md:table-cell">Subject</th>
                    <th class="px-5 py-3 text-center font-semibold text-slate-500 text-xs uppercase tracking-wide hidden lg:table-cell">Email Sent</th>
                    <th class="px-5 py-3 text-center font-semibold text-slate-500 text-xs uppercase tracking-wide">Date</th>
                    <th class="px-5 py-3 text-right font-semibold text-slate-500 text-xs uppercase tracking-wide">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                <?php foreach ($messages as $msg): ?>
                <tr class="hover:bg-slate-50/50 transition-colors">
                    <td class="px-5 py-4">
                        <p class="font-medium text-slate-800"><?php echo e($msg['name']); ?></p>
                        <p class="text-slate-400 text-xs"><?php echo e($msg['email']); ?></p>
                    </td>
                    <td class="px-5 py-4 hidden md:table-cell text-slate-600 text-xs max-w-[200px] truncate"><?php echo e($msg['subject'] ?? '–'); ?></td>
                    <td class="px-5 py-4 text-center hidden lg:table-cell">
                        <span class="inline-flex px-2 py-1 rounded-lg text-xs font-semibold <?php echo $msg['email_sent'] ? 'bg-green-50 text-green-700' : 'bg-slate-100 text-slate-400'; ?>">
                            <?php echo $msg['email_sent'] ? 'Yes' : 'No'; ?>
                        </span>
                    </td>
                    <td class="px-5 py-4 text-center text-slate-400 text-xs"><?php echo date('d M Y', strtotime($msg['created_at'])); ?></td>
                    <td class="px-5 py-4 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="<?php echo url('admin/messages.php?view='.$msg['id']); ?>" class="px-3 py-1.5 bg-slate-100 text-slate-700 text-xs font-semibold rounded-lg hover:bg-slate-200 transition-colors">View</a>
                            <a href="<?php echo url('admin/messages.php?delete='.$msg['id']); ?>" onclick="return confirm('Delete this message?')" class="px-3 py-1.5 bg-red-50 text-red-600 text-xs font-semibold rounded-lg hover:bg-red-100 transition-colors">Delete</a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($messages)): ?><tr><td colspan="5" class="px-5 py-10 text-center text-slate-400 text-sm">No messages yet.</td></tr><?php endif; ?>
            </tbody>
        </table>

        <?php if ($pages > 1): ?>
        <div class="px-5 py-4 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
            <span>Showing <?php echo count($messages); ?> of <?php echo $total; ?></span>
            <div class="flex gap-1">
                <?php for ($p=1; $p<=$pages; $p++): ?>
                <a href="<?php echo url('admin/messages.php?page='.$p); ?>"
                   class="px-3 py-1.5 rounded-lg font-medium transition-colors <?php echo $p===$page ? 'bg-brandBlue text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'; ?>">
                    <?php echo $p; ?>
                </a>
                <?php endfor; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>
</body>
</html>
