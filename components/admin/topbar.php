<?php
require_once __DIR__ . "/../../middleware/auth.php";
$user = getAuthUser();
?>
<header class="app-topbar">
  <div class="page-title">
    <h1><?= $pageTitle ?></h1>
    <p><?= $pageSubtitle ?></p>
  </div>
  <div class="topbar-user">
    <span class="avatar"><?= ucfirst(substr($user['name'] ?? 'Guest', 0, 2)) ?></span>
    <div>
      <?= htmlspecialchars($user['name'] ?? 'Guest') ?><br>
      <span class="badge badge-member" style="margin-top:2px;"><?= $user['role'] ?? 'guest' ?></span>
      <?php if ($user): ?>
        <a href="../../actions/auth/logout.php" style="margin-left: 10px; color: inherit; text-decoration: underline;">Logout</a>
      <?php endif; ?>
    </div>
  </div>
</header>