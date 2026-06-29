<?php
$title = $title ?? 'ORBIT';
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php echo e($title); ?></title>
</head>
<body>
<div style="font-family:system-ui;max-width:900px;margin:0 auto;padding:24px;">
<header style="display:flex;justify-content:space-between;align-items:center;gap:16px;margin-bottom:32px;">
<a href="/"><strong>ORBIT</strong></a>
<nav><a href="/login">Login</a> | <a href="/register">Join beta</a></nav>
</header>
<?php echo $content ?? ''; ?>
</div>
</body>
</html>
