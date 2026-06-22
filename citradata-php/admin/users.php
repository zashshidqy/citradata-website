<?php
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../config/database.php';
requireAdmin();

$pdo        = getDbConnection();
$adminTitle = 'Users';
$activePage = 'users';

$action = $_POST['action'] ?? ($_GET['action'] ?? '');

// Delete (cannot delete self)
if ($action === 'delete' && isset($_GET['id']) && (int)$_GET['id'] !== (int)$_SESSION['user_id']) {
    $pdo->prepare('DELETE FROM users WHERE id=:id')->execute([':id'=>(int)$_GET['id']]);
    header('Location: ' . url('admin/users.php?deleted=1')); exit;
}
// Toggle active
if ($action === 'toggle' && isset($_GET['id']) && (int)$_GET['id'] !== (int)$_SESSION['user_id']) {
    $pdo->prepare('UPDATE users SET is_active = NOT is_active WHERE id=:id')->execute([':id'=>(int)$_GET['id']]);
    header('Location: ' . url('admin/users.php')); exit;
}

// Create / Update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && in_array($action, ['create','update'])) {
    $id        = (int)($_POST['id'] ?? 0);
    $name      = trim($_POST['name']  ?? '');
    $email     = trim($_POST['email'] ?? '');
    $role      = in_array($_POST['role'] ?? '', ['admin','member','trial']) ? $_POST['role'] : 'member';
    $is_active = isset($_POST['is_active']) ? 1 : 0;
    $password  = trim($_POST['password'] ?? '');

    if ($name !== '' && filter_var($email, FILTER_VALIDATE_EMAIL)) {
        if ($action === 'create') {
            if ($password === '') {
                $_SESSION['admin_flash_error'] = 'Password wajib diisi untuk user baru.';
                header('Location: ' . url('admin/users.php?new=1')); exit;
            }
            $hash = password_hash($password, PASSWORD_BCRYPT, ['cost'=>12]);
            $pdo->prepare('INSERT INTO users (name,email,password_hash,role,is_active) VALUES(:n,:e,:p,:r,:a)')
                ->execute([':n'=>$name,':e'=>$email,':p'=>$hash,':r'=>$role,':a'=>$is_active]);
        } else {
            if ($password !== '') {
                $hash = password_hash($password, PASSWORD_BCRYPT, ['cost'=>12]);
                $pdo->prepare('UPDATE users SET name=:n,email=:e,password_hash=:p,role=:r,is_active=:a WHERE id=:id')
                    ->execute([':n'=>$name,':e'=>$email,':p'=>$hash,':r'=>$role,':a'=>$is_active,':id'=>$id]);
            } else {
                $pdo->prepare('UPDATE users SET name=:n,email=:e,role=:r,is_active=:a WHERE id=:id')
                    ->execute([':n'=>$name,':e'=>$email,':r'=>$role,':a'=>$is_active,':id'=>$id]);
            }
        }
        header('Location: ' . url('admin/users.php?saved=1')); exit;
    }
}

$editItem = null;
if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare('SELECT * FROM users WHERE id=:id');
    $stmt->execute([':id'=>(int)$_GET['edit']]);
    $editItem = $stmt->fetch();
}

$flashError = $_SESSION['admin_flash_error'] ?? null;
unset($_SESSION['admin_flash_error']);

$users = $pdo->query('SELECT * FROM users ORDER BY created_at DESC')->fetchAll();

require __DIR__ . '/includes/admin_head.php';
require __DIR__ . '/includes/admin_sidebar.php';
?>
<div class="flex-grow p-8 max-w-5xl">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-slate-900">Users</h1>
        <a href="<?php echo url('admin/users.php?new=1'); ?>" class="px-5 py-2 bg-brandBlue text-white text-sm font-semibold rounded-xl hover:bg-blue-800 transition-colors">+ Add User</a>
    </div>

    <?php if (isset($_GET['saved'])): ?><div class="mb-4 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl text-sm font-medium">Saved successfully.</div><?php endif; ?>
    <?php if (isset($_GET['deleted'])): ?><div class="mb-4 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl text-sm font-medium">Deleted.</div><?php endif; ?>
    <?php if ($flashError): ?><div class="mb-4 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl text-sm font-medium"><?php echo e($flashError); ?></div><?php endif; ?>

    <?php if (isset($_GET['new']) || $editItem): ?>
    <div class="bg-white rounded-2xl p-7 shadow-sm border border-slate-100 mb-8">
        <h2 class="font-semibold text-slate-800 mb-5"><?php echo $editItem ? 'Edit User' : 'Add User'; ?></h2>
        <form method="POST" action="<?php echo url('admin/users.php'); ?>" class="space-y-4">
            <input type="hidden" name="action" value="<?php echo $editItem ? 'update' : 'create'; ?>">
            <?php if ($editItem): ?><input type="hidden" name="id" value="<?php echo $editItem['id']; ?>"><?php endif; ?>

            <div class="grid sm:grid-cols-2 gap-4">
                <div><label class="block text-sm font-semibold text-slate-700 mb-1">Full Name *</label>
                <input type="text" name="name" required value="<?php echo e($editItem['name'] ?? ''); ?>" class="w-full h-11 px-4 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brandBlue/20 focus:border-brandBlue transition-all"></div>

                <div><label class="block text-sm font-semibold text-slate-700 mb-1">Email *</label>
                <input type="email" name="email" required value="<?php echo e($editItem['email'] ?? ''); ?>" class="w-full h-11 px-4 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brandBlue/20 focus:border-brandBlue transition-all"></div>
            </div>

            <div class="grid sm:grid-cols-2 gap-4">
                <div><label class="block text-sm font-semibold text-slate-700 mb-1">Password <?php echo $editItem ? '(leave empty to keep)' : '*'; ?></label>
                <input type="password" name="password" autocomplete="new-password" class="w-full h-11 px-4 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brandBlue/20 focus:border-brandBlue transition-all"></div>

                <div><label class="block text-sm font-semibold text-slate-700 mb-1">Role</label>
                <select name="role" class="w-full h-11 px-4 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brandBlue/20 focus:border-brandBlue transition-all">
                    <option value="member" <?php echo ($editItem['role'] ?? 'member') === 'member' ? 'selected' : ''; ?>>Member</option>
                    <option value="trial"  <?php echo ($editItem['role'] ?? 'member') === 'trial'  ? 'selected' : ''; ?>>Free Trial</option>
                    <option value="admin"  <?php echo ($editItem['role'] ?? 'member') === 'admin'  ? 'selected' : ''; ?>>Admin</option>
                </select></div>
            </div>

            <label class="flex items-center gap-2 text-sm font-medium text-slate-700 cursor-pointer">
                <input type="checkbox" name="is_active" value="1" <?php echo ($editItem['is_active'] ?? 1) ? 'checked' : ''; ?> class="rounded">
                Active (can log in)
            </label>

            <div class="flex gap-3 pt-2">
                <button type="submit" class="px-6 py-2.5 bg-brandBlue text-white text-sm font-semibold rounded-xl hover:bg-blue-800 transition-colors">Save</button>
                <a href="<?php echo url('admin/users.php'); ?>" class="px-6 py-2.5 bg-slate-100 text-slate-700 text-sm font-semibold rounded-xl hover:bg-slate-200 transition-colors">Cancel</a>
            </div>
        </form>
    </div>
    <?php endif; ?>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 border-b border-slate-100">
                <tr>
                    <th class="px-5 py-3 text-left font-semibold text-slate-500 text-xs uppercase tracking-wide">User</th>
                    <th class="px-5 py-3 text-center font-semibold text-slate-500 text-xs uppercase tracking-wide">Role</th>
                    <th class="px-5 py-3 text-center font-semibold text-slate-500 text-xs uppercase tracking-wide hidden lg:table-cell">Last Login</th>
                    <th class="px-5 py-3 text-center font-semibold text-slate-500 text-xs uppercase tracking-wide">Status</th>
                    <th class="px-5 py-3 text-right font-semibold text-slate-500 text-xs uppercase tracking-wide">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                <?php foreach ($users as $usr): ?>
                <tr class="hover:bg-slate-50/50 transition-colors">
                    <td class="px-5 py-4">
                        <p class="font-medium text-slate-800"><?php echo e($usr['name']); ?></p>
                        <p class="text-slate-400 text-xs"><?php echo e($usr['email']); ?></p>
                    </td>
                    <td class="px-5 py-4 text-center">
                        <?php
                        $roleColors = ['admin'=>'bg-red-50 text-red-700','member'=>'bg-blue-50 text-blue-700','trial'=>'bg-amber-50 text-amber-700'];
                        $rc = $roleColors[$usr['role']] ?? 'bg-slate-50 text-slate-700';
                        ?>
                        <span class="inline-flex px-2 py-1 rounded-lg text-xs font-semibold capitalize <?php echo $rc; ?>"><?php echo e($usr['role']); ?></span>
                    </td>
                    <td class="px-5 py-4 text-center text-slate-400 text-xs hidden lg:table-cell">
                        <?php echo $usr['last_login_at'] ? date('d M Y H:i', strtotime($usr['last_login_at'])) : 'Never'; ?>
                    </td>
                    <td class="px-5 py-4 text-center">
                        <?php if ((int)$usr['id'] !== (int)$_SESSION['user_id']): ?>
                        <a href="<?php echo url('admin/users.php?action=toggle&id='.$usr['id']); ?>"
                           class="inline-flex px-2 py-1 rounded-lg text-xs font-semibold <?php echo $usr['is_active'] ? 'bg-green-50 text-green-700' : 'bg-slate-100 text-slate-400'; ?>">
                            <?php echo $usr['is_active'] ? 'Active' : 'Inactive'; ?>
                        </a>
                        <?php else: ?>
                        <span class="inline-flex px-2 py-1 rounded-lg text-xs font-semibold bg-green-50 text-green-700">Active (you)</span>
                        <?php endif; ?>
                    </td>
                    <td class="px-5 py-4 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="<?php echo url('admin/users.php?edit='.$usr['id']); ?>" class="px-3 py-1.5 bg-slate-100 text-slate-700 text-xs font-semibold rounded-lg hover:bg-slate-200 transition-colors">Edit</a>
                            <?php if ((int)$usr['id'] !== (int)$_SESSION['user_id']): ?>
                            <a href="<?php echo url('admin/users.php?action=delete&id='.$usr['id']); ?>" onclick="return confirm('Delete this user?')" class="px-3 py-1.5 bg-red-50 text-red-600 text-xs font-semibold rounded-lg hover:bg-red-100 transition-colors">Delete</a>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($users)): ?><tr><td colspan="5" class="px-5 py-10 text-center text-slate-400 text-sm">No users found.</td></tr><?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>
