<?php $reports = $reports ?? []; ?>
<main>
    <h1>Moderation queue</h1>
    <p>Open reports requiring review.</p>

    <?php if (!$reports): ?>
        <p>No open reports.</p>
    <?php endif; ?>

    <?php foreach ($reports as $report): ?>
        <article style="border-top:1px solid #ddd;padding:18px 0;">
            <h2>Report #<?php echo (int) $report['id']; ?></h2>
            <p><strong>Reporter:</strong> <?php echo e($report['reporter_name']); ?></p>
            <p><strong>Reported:</strong> <?php echo e($report['reported_name']); ?></p>
            <p><strong>Reason:</strong> <?php echo e($report['reason']); ?></p>
            <p><?php echo nl2br(e($report['details'] ?? '')); ?></p>
            <p><strong>Status:</strong> <?php echo e($report['status']); ?></p>
        </article>
    <?php endforeach; ?>
</main>
