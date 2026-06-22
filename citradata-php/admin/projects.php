<?php
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../config/database.php';
requireAdmin();

$pdo        = getDbConnection();
$adminTitle = 'Projects';
$activePage = 'projects';

$sectors  = ['Residential','Office','Retail','Industrial','Hospitality','Hospital','Mixed Use','Infrastructure','Other'];
$statuses = ['Planning','Design','Tender','Construction','Completed','On Hold'];

$action = $_POST['action'] ?? ($_GET['action'] ?? '');

if ($action === 'delete' && isset($_GET['id'])) {
    $pdo->prepare('DELETE FROM projects WHERE id=:id')->execute([':id'=>(int)$_GET['id']]);
    header('Location: '.url('admin/projects.php?deleted=1')); exit;
}
if ($action === 'toggle' && isset($_GET['id'])) {
    $pdo->prepare('UPDATE projects SET is_active=NOT is_active WHERE id=:id')->execute([':id'=>(int)$_GET['id']]);
    header('Location: '.url('admin/projects.php')); exit;
}
if ($action === 'toggle_featured' && isset($_GET['id'])) {
    $pdo->prepare('UPDATE projects SET is_featured=NOT is_featured WHERE id=:id')->execute([':id'=>(int)$_GET['id']]);
    header('Location: '.url('admin/projects.php')); exit;
}

if ($_SERVER['REQUEST_METHOD']==='POST' && in_array($action,['create','update'])) {
    $id = (int)($_POST['id'] ?? 0);
    $f  = [
        'project_name'       => trim($_POST['project_name']      ?? ''),
        'sector'             => $_POST['sector']                  ?? 'Other',
        'status'             => $_POST['status']                  ?? 'Planning',
        'location_city'      => trim($_POST['location_city']      ?? ''),
        'location_address'   => trim($_POST['location_address']   ?? ''),
        'project_value'      => $_POST['project_value']!=='' ? (int)preg_replace('/[^0-9]/','',$_POST['project_value']) : null,
        'developer_name'     => trim($_POST['developer_name']     ?? ''),
        'developer_contact'  => trim($_POST['developer_contact']  ?? ''),
        'developer_phone'    => trim($_POST['developer_phone']    ?? ''),
        'developer_email'    => trim($_POST['developer_email']    ?? ''),
        'contractor_name'    => trim($_POST['contractor_name']    ?? ''),
        'contractor_contact' => trim($_POST['contractor_contact'] ?? ''),
        'contractor_phone'   => trim($_POST['contractor_phone']   ?? ''),
        'contractor_email'   => trim($_POST['contractor_email']   ?? ''),
        'consultant_name'    => trim($_POST['consultant_name']    ?? ''),
        'description'        => trim($_POST['description']        ?? ''),
        'start_date'         => $_POST['start_date'] ?: null,
        'end_date'           => $_POST['end_date']   ?: null,
        'is_active'          => isset($_POST['is_active'])   ? 1 : 0,
        'is_featured'        => isset($_POST['is_featured']) ? 1 : 0,
    ];
    if ($f['project_name']!=='') {
        $keys   = array_keys($f);
        $named  = array_map(fn($k)=>":$k", $keys);
        $vals   = array_combine($named, array_values($f));
        if ($action==='create') {
            $pdo->prepare('INSERT INTO projects ('.implode(',',$keys).') VALUES ('.implode(',',$named).')')->execute($vals);
        } else {
            $sets = implode(',', array_map(fn($k)=>"$k=:$k",$keys));
            $vals[':id'] = $id;
            $pdo->prepare("UPDATE projects SET $sets WHERE id=:id")->execute($vals);
        }
        header('Location: '.url('admin/projects.php?saved=1')); exit;
    }
}

$editItem = null;
if (isset($_GET['edit'])) {
    $s = $pdo->prepare('SELECT * FROM projects WHERE id=:id');
    $s->execute([':id'=>(int)$_GET['edit']]);
    $editItem = $s->fetch();
}

$search  = trim($_GET['q']      ?? '');
$fSector = $_GET['sector']      ?? '';
$fStatus = $_GET['status']      ?? '';
$sortCol = in_array($_GET['sort']??'',['id','project_name','sector','status','location_city','created_at']) ? $_GET['sort'] : 'id';
$sortDir = ($_GET['dir']??'desc')==='asc'?'ASC':'DESC';

$where=[]; $params=[];
if ($search!=='') { $where[]='(project_name LIKE :q OR location_city LIKE :q2)'; $params[':q']="%$search%"; $params[':q2']="%$search%"; }
if ($fSector!=='') { $where[]='sector=:sec'; $params[':sec']=$fSector; }
if ($fStatus!=='') { $where[]='status=:st';  $params[':st']=$fStatus; }
$whereStr = $where ? 'WHERE '.implode(' AND ',$where) : '';
$stmt = $pdo->prepare("SELECT * FROM projects $whereStr ORDER BY $sortCol $sortDir");
$stmt->execute($params);
$items = $stmt->fetchAll();

function fmtIDR($v): string {
    if ($v===null||$v==='') return '–';
    $v=(int)$v;
    if ($v>=1_000_000_000) return 'Rp '.number_format($v/1_000_000_000,1,',','.').' M';
    if ($v>=1_000_000)     return 'Rp '.number_format($v/1_000_000,0,',','.').' Jt';
    return 'Rp '.number_format($v,0,',','.');
}

function sortLink(string $col,string $label,string $cur,string $dir): string {
    $nd = ($cur===$col&&$dir==='ASC')?'desc':'asc';
    $ico= $cur===$col?($dir==='ASC'?' ↑':' ↓'):'';
    $q  = http_build_query(array_merge($_GET,['sort'=>$col,'dir'=>$nd]));
    return '<a href="?'.htmlspecialchars($q).'" class="hover:text-brandBlue">'.$label.$ico.'</a>';
}

require __DIR__.'/includes/admin_head.php';
require __DIR__.'/includes/admin_sidebar.php';
?>
<div class="flex-grow p-8 overflow-x-auto">
<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold text-slate-900">Projects</h1>
    <a href="<?php echo url('admin/projects.php?new=1'); ?>" class="px-5 py-2 bg-brandBlue text-white text-sm font-semibold rounded-xl hover:bg-blue-800 transition-colors">+ Add Project</a>
</div>
<?php if(isset($_GET['saved'])): ?><div class="mb-4 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl text-sm">Saved.</div><?php endif; ?>
<?php if(isset($_GET['deleted'])): ?><div class="mb-4 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl text-sm">Deleted.</div><?php endif; ?>

<?php if(isset($_GET['new'])||$editItem): ?>
<div class="bg-white rounded-2xl p-7 shadow-sm border border-slate-100 mb-8">
<h2 class="font-semibold text-slate-800 mb-5"><?php echo $editItem?'Edit Project':'Add Project'; ?></h2>
<form method="POST" action="<?php echo url('admin/projects.php'); ?>" class="space-y-5">
<input type="hidden" name="action" value="<?php echo $editItem?'update':'create'; ?>">
<?php if($editItem): ?><input type="hidden" name="id" value="<?php echo $editItem['id']; ?>"><?php endif; ?>

<div class="grid md:grid-cols-2 gap-4">
  <div><label class="block text-sm font-semibold text-slate-700 mb-1">Project Name *</label>
  <input type="text" name="project_name" required value="<?php echo e($editItem['project_name']??''); ?>" class="w-full h-11 px-4 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brandBlue/20 focus:border-brandBlue transition-all"></div>
  <div><label class="block text-sm font-semibold text-slate-700 mb-1">Consultant</label>
  <input type="text" name="consultant_name" value="<?php echo e($editItem['consultant_name']??''); ?>" class="w-full h-11 px-4 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brandBlue/20 focus:border-brandBlue transition-all"></div>
</div>

<div class="grid md:grid-cols-3 gap-4">
  <div><label class="block text-sm font-semibold text-slate-700 mb-1">Sector *</label>
  <select name="sector" required class="w-full h-11 px-4 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brandBlue/20 focus:border-brandBlue transition-all">
    <?php foreach($sectors as $s): ?><option value="<?php echo $s; ?>" <?php echo ($editItem['sector']??'')===$s?'selected':''; ?>><?php echo $s; ?></option><?php endforeach; ?>
  </select></div>
  <div><label class="block text-sm font-semibold text-slate-700 mb-1">Status</label>
  <select name="status" class="w-full h-11 px-4 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brandBlue/20 focus:border-brandBlue transition-all">
    <?php foreach($statuses as $s): ?><option value="<?php echo $s; ?>" <?php echo ($editItem['status']??'Planning')===$s?'selected':''; ?>><?php echo $s; ?></option><?php endforeach; ?>
  </select></div>
  <div><label class="block text-sm font-semibold text-slate-700 mb-1">Project Value (IDR)</label>
  <input type="number" name="project_value" value="<?php echo $editItem['project_value']??''; ?>" placeholder="e.g. 850000000000" class="w-full h-11 px-4 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brandBlue/20 focus:border-brandBlue transition-all"></div>
</div>

<div class="grid md:grid-cols-2 gap-4">
  <div><label class="block text-sm font-semibold text-slate-700 mb-1">City</label>
  <input type="text" name="location_city" value="<?php echo e($editItem['location_city']??''); ?>" class="w-full h-11 px-4 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brandBlue/20 focus:border-brandBlue transition-all"></div>
  <div><label class="block text-sm font-semibold text-slate-700 mb-1">Full Address</label>
  <input type="text" name="location_address" value="<?php echo e($editItem['location_address']??''); ?>" class="w-full h-11 px-4 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brandBlue/20 focus:border-brandBlue transition-all"></div>
</div>

<p class="text-xs font-bold text-slate-500 uppercase tracking-widest pt-2 border-t border-slate-100">Developer Info <span class="text-amber-500 font-normal normal-case">(sensitive — hidden from trial users)</span></p>
<div class="grid md:grid-cols-2 gap-4">
  <div><label class="block text-sm font-semibold text-slate-700 mb-1">Developer Name</label>
  <input type="text" name="developer_name" value="<?php echo e($editItem['developer_name']??''); ?>" class="w-full h-11 px-4 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brandBlue/20 focus:border-brandBlue transition-all"></div>
  <div><label class="block text-sm font-semibold text-slate-700 mb-1">Contact Person</label>
  <input type="text" name="developer_contact" value="<?php echo e($editItem['developer_contact']??''); ?>" class="w-full h-11 px-4 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brandBlue/20 focus:border-brandBlue transition-all"></div>
  <div><label class="block text-sm font-semibold text-slate-700 mb-1">Phone</label>
  <input type="text" name="developer_phone" value="<?php echo e($editItem['developer_phone']??''); ?>" class="w-full h-11 px-4 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brandBlue/20 focus:border-brandBlue transition-all"></div>
  <div><label class="block text-sm font-semibold text-slate-700 mb-1">Email</label>
  <input type="email" name="developer_email" value="<?php echo e($editItem['developer_email']??''); ?>" class="w-full h-11 px-4 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brandBlue/20 focus:border-brandBlue transition-all"></div>
</div>

<p class="text-xs font-bold text-slate-500 uppercase tracking-widest pt-2 border-t border-slate-100">Contractor Info <span class="text-amber-500 font-normal normal-case">(sensitive)</span></p>
<div class="grid md:grid-cols-2 gap-4">
  <div><label class="block text-sm font-semibold text-slate-700 mb-1">Contractor Name</label>
  <input type="text" name="contractor_name" value="<?php echo e($editItem['contractor_name']??''); ?>" class="w-full h-11 px-4 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brandBlue/20 focus:border-brandBlue transition-all"></div>
  <div><label class="block text-sm font-semibold text-slate-700 mb-1">Contact Person</label>
  <input type="text" name="contractor_contact" value="<?php echo e($editItem['contractor_contact']??''); ?>" class="w-full h-11 px-4 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brandBlue/20 focus:border-brandBlue transition-all"></div>
  <div><label class="block text-sm font-semibold text-slate-700 mb-1">Phone</label>
  <input type="text" name="contractor_phone" value="<?php echo e($editItem['contractor_phone']??''); ?>" class="w-full h-11 px-4 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brandBlue/20 focus:border-brandBlue transition-all"></div>
  <div><label class="block text-sm font-semibold text-slate-700 mb-1">Email</label>
  <input type="email" name="contractor_email" value="<?php echo e($editItem['contractor_email']??''); ?>" class="w-full h-11 px-4 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brandBlue/20 focus:border-brandBlue transition-all"></div>
</div>

<div class="grid md:grid-cols-3 gap-4 items-end border-t border-slate-100 pt-4">
  <div><label class="block text-sm font-semibold text-slate-700 mb-1">Start Date</label>
  <input type="date" name="start_date" value="<?php echo e($editItem['start_date']??''); ?>" class="w-full h-11 px-4 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brandBlue/20 focus:border-brandBlue transition-all"></div>
  <div><label class="block text-sm font-semibold text-slate-700 mb-1">End Date</label>
  <input type="date" name="end_date" value="<?php echo e($editItem['end_date']??''); ?>" class="w-full h-11 px-4 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brandBlue/20 focus:border-brandBlue transition-all"></div>
  <div class="flex gap-6 pb-1">
    <label class="flex items-center gap-2 text-sm font-medium text-slate-700 cursor-pointer">
      <input type="checkbox" name="is_active" value="1" <?php echo ($editItem['is_active']??1)?'checked':''; ?> class="rounded"> Active
    </label>
    <label class="flex items-center gap-2 text-sm font-medium text-slate-700 cursor-pointer">
      <input type="checkbox" name="is_featured" value="1" <?php echo ($editItem['is_featured']??0)?'checked':''; ?> class="rounded"> Featured
    </label>
  </div>
</div>

<div><label class="block text-sm font-semibold text-slate-700 mb-1">Description</label>
<textarea name="description" rows="4" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brandBlue/20 focus:border-brandBlue transition-all resize-y"><?php echo e($editItem['description']??''); ?></textarea></div>

<div class="flex gap-3 pt-2">
  <button type="submit" class="px-6 py-2.5 bg-brandBlue text-white text-sm font-semibold rounded-xl hover:bg-blue-800 transition-colors">Save</button>
  <a href="<?php echo url('admin/projects.php'); ?>" class="px-6 py-2.5 bg-slate-100 text-slate-700 text-sm font-semibold rounded-xl hover:bg-slate-200 transition-colors">Cancel</a>
</div>
</form>
</div>
<?php endif; ?>

<!-- Filter bar -->
<form method="GET" action="<?php echo url('admin/projects.php'); ?>" class="bg-white rounded-2xl p-4 shadow-sm border border-slate-100 mb-4 flex flex-wrap gap-3 items-end">
  <div class="flex-grow min-w-[150px]"><label class="block text-xs font-semibold text-slate-500 mb-1">Search</label>
  <input type="text" name="q" value="<?php echo e($search); ?>" placeholder="Name or city…" class="w-full h-10 px-4 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none"></div>
  <div><label class="block text-xs font-semibold text-slate-500 mb-1">Sector</label>
  <select name="sector" class="h-10 px-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none">
    <option value="">All</option>
    <?php foreach($sectors as $s): ?><option value="<?php echo $s; ?>" <?php echo $fSector===$s?'selected':''; ?>><?php echo $s; ?></option><?php endforeach; ?>
  </select></div>
  <div><label class="block text-xs font-semibold text-slate-500 mb-1">Status</label>
  <select name="status" class="h-10 px-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none">
    <option value="">All</option>
    <?php foreach($statuses as $s): ?><option value="<?php echo $s; ?>" <?php echo $fStatus===$s?'selected':''; ?>><?php echo $s; ?></option><?php endforeach; ?>
  </select></div>
  <button type="submit" class="h-10 px-5 bg-brandBlue text-white text-sm font-semibold rounded-xl hover:bg-blue-800 transition-colors">Filter</button>
  <a href="<?php echo url('admin/projects.php'); ?>" class="h-10 px-4 flex items-center bg-slate-100 text-slate-600 text-sm font-semibold rounded-xl hover:bg-slate-200 transition-colors">Reset</a>
</form>

<!-- Table -->
<div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-x-auto">
  <div class="px-5 py-3 border-b border-slate-100 text-xs text-slate-400"><?php echo count($items); ?> project(s)</div>
  <table class="w-full text-sm min-w-[900px]">
    <thead class="bg-slate-50 border-b border-slate-100">
      <tr>
        <th class="px-4 py-3 text-left font-semibold text-slate-500 text-xs uppercase"><?php echo sortLink('id','#',$sortCol,$sortDir); ?></th>
        <th class="px-4 py-3 text-left font-semibold text-slate-500 text-xs uppercase"><?php echo sortLink('project_name','Name',$sortCol,$sortDir); ?></th>
        <th class="px-4 py-3 text-left font-semibold text-slate-500 text-xs uppercase"><?php echo sortLink('sector','Sector',$sortCol,$sortDir); ?></th>
        <th class="px-4 py-3 text-left font-semibold text-slate-500 text-xs uppercase"><?php echo sortLink('status','Status',$sortCol,$sortDir); ?></th>
        <th class="px-4 py-3 text-left font-semibold text-slate-500 text-xs uppercase"><?php echo sortLink('location_city','City',$sortCol,$sortDir); ?></th>
        <th class="px-4 py-3 text-right font-semibold text-slate-500 text-xs uppercase">Value</th>
        <th class="px-4 py-3 text-center font-semibold text-slate-500 text-xs uppercase">Active</th>
        <th class="px-4 py-3 text-center font-semibold text-slate-500 text-xs uppercase">★</th>
        <th class="px-4 py-3 text-left font-semibold text-slate-500 text-xs uppercase"><?php echo sortLink('created_at','Created',$sortCol,$sortDir); ?></th>
        <th class="px-4 py-3 text-right font-semibold text-slate-500 text-xs uppercase">Actions</th>
      </tr>
    </thead>
    <tbody class="divide-y divide-slate-50">
      <?php foreach($items as $item): ?>
      <tr class="hover:bg-slate-50/50 transition-colors">
        <td class="px-4 py-3 text-slate-400 text-xs"><?php echo $item['id']; ?></td>
        <td class="px-4 py-3"><p class="font-medium text-slate-800 line-clamp-1 max-w-[200px]"><?php echo e($item['project_name']); ?></p>
          <?php if($item['developer_name']): ?><p class="text-slate-400 text-xs"><?php echo e($item['developer_name']); ?></p><?php endif; ?>
        </td>
        <td class="px-4 py-3 text-xs text-slate-600"><?php echo e($item['sector']); ?></td>
        <td class="px-4 py-3">
          <span class="px-2 py-1 rounded-lg text-xs font-semibold <?php echo match($item['status']){'Planning'=>'bg-blue-50 text-blue-700','Design'=>'bg-purple-50 text-purple-700','Tender'=>'bg-amber-50 text-amber-700','Construction'=>'bg-green-50 text-green-700','Completed'=>'bg-slate-100 text-slate-500',default=>'bg-red-50 text-red-600'}; ?>"><?php echo e($item['status']); ?></span>
        </td>
        <td class="px-4 py-3 text-xs text-slate-600"><?php echo e($item['location_city']??'–'); ?></td>
        <td class="px-4 py-3 text-right text-xs text-slate-600"><?php echo fmtIDR($item['project_value']); ?></td>
        <td class="px-4 py-3 text-center">
          <a href="<?php echo url('admin/projects.php?action=toggle&id='.$item['id']); ?>"
             class="inline-flex px-2 py-1 rounded-lg text-xs font-semibold <?php echo $item['is_active']?'bg-green-50 text-green-700':'bg-slate-100 text-slate-400'; ?>">
            <?php echo $item['is_active']?'Yes':'No'; ?>
          </a>
        </td>
        <td class="px-4 py-3 text-center">
          <a href="<?php echo url('admin/projects.php?action=toggle_featured&id='.$item['id']); ?>"
             class="inline-flex px-2 py-1 rounded-lg text-xs font-semibold <?php echo $item['is_featured']?'bg-amber-50 text-amber-600':'bg-slate-100 text-slate-400'; ?>">
            <?php echo $item['is_featured']?'★':'☆'; ?>
          </a>
        </td>
        <td class="px-4 py-3 text-xs text-slate-400"><?php echo date('d M Y',strtotime($item['created_at'])); ?></td>
        <td class="px-4 py-3 text-right">
          <div class="flex items-center justify-end gap-2">
            <a href="<?php echo url('admin/projects.php?edit='.$item['id']); ?>" class="px-3 py-1.5 bg-slate-100 text-slate-700 text-xs font-semibold rounded-lg hover:bg-slate-200 transition-colors">Edit</a>
            <a href="<?php echo url('admin/projects.php?action=delete&id='.$item['id']); ?>" onclick="return confirm('Delete this project?')" class="px-3 py-1.5 bg-red-50 text-red-600 text-xs font-semibold rounded-lg hover:bg-red-100 transition-colors">Delete</a>
          </div>
        </td>
      </tr>
      <?php endforeach; ?>
      <?php if(empty($items)): ?>
      <tr><td colspan="10" class="px-5 py-10 text-center text-slate-400 text-sm">No projects found.</td></tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>
</div>
</body>
</html>
