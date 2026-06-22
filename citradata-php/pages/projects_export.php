<?php
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../config/database.php';

if (session_status() === PHP_SESSION_NONE) session_start();
requireLogin();

$userRole = $_SESSION['user_role'] ?? 'trial';

if (!in_array($userRole, ['member','admin'])) {
    $_SESSION['export_error'] = 'Export CSV hanya tersedia untuk Member dan Admin. Silakan upgrade akun Anda.';
    header('Location: ' . url('pages/projects.php'));
    exit;
}

$pdo = getDbConnection();

$search  = trim($_GET['q']      ?? '');
$fSector = $_GET['sector']      ?? '';
$fStatus = $_GET['status']      ?? '';
$fCity   = trim($_GET['city']   ?? '');

$where  = ['is_active = 1'];
$params = [];

if ($search !== '') {
    $where[]       = '(project_name LIKE :q OR location_city LIKE :q2 OR developer_name LIKE :q3)';
    $params[':q']  = "%$search%";
    $params[':q2'] = "%$search%";
    $params[':q3'] = "%$search%";
}
if ($fSector !== '') { $where[] = 'sector = :sec';         $params[':sec']  = $fSector; }
if ($fStatus !== '') { $where[] = 'status = :st';          $params[':st']   = $fStatus; }
if ($fCity   !== '') { $where[] = 'location_city = :city'; $params[':city'] = $fCity; }

$whereStr = implode(' AND ', $where);
$stmt = $pdo->prepare("SELECT * FROM projects WHERE $whereStr ORDER BY created_at DESC");
$stmt->execute($params);
$rows = $stmt->fetchAll();

$filename = 'citradata-projects-' . date('Y-m-d') . '.csv';

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Pragma: no-cache');
header('Expires: 0');

$out = fopen('php://output', 'w');
fwrite($out, "\xEF\xBB\xBF"); // UTF-8 BOM for Excel

fputcsv($out, [
    'ID','Project Name','Sector','Status',
    'City','Address','Project Value (IDR)',
    'Developer Name','Developer Contact','Developer Phone','Developer Email',
    'Contractor Name','Contractor Contact','Contractor Phone','Contractor Email',
    'Consultant','Description',
    'Start Date','End Date',
    'Active','Featured','Created At',
]);

foreach ($rows as $r) {
    fputcsv($out, [
        $r['id'],
        $r['project_name'],
        $r['sector'],
        $r['status'],
        $r['location_city']      ?? '',
        $r['location_address']   ?? '',
        $r['project_value']      ?? '',
        $r['developer_name']     ?? '',
        $r['developer_contact']  ?? '',
        $r['developer_phone']    ?? '',
        $r['developer_email']    ?? '',
        $r['contractor_name']    ?? '',
        $r['contractor_contact'] ?? '',
        $r['contractor_phone']   ?? '',
        $r['contractor_email']   ?? '',
        $r['consultant_name']    ?? '',
        $r['description']        ?? '',
        $r['start_date']         ?? '',
        $r['end_date']           ?? '',
        $r['is_active']   ? 'Yes' : 'No',
        $r['is_featured'] ? 'Yes' : 'No',
        $r['created_at'],
    ]);
}

fclose($out);
exit;
