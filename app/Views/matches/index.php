<?php $matches = $matches ?? []; ?>
<main>
    <h1>Your suggestions</h1>
    <p>Early ORBIT suggestions are ranked by shared connection style, social rhythm and trust-building signals.</p>

    <?php if (!$matches): ?>
        <p>No suggestions yet. As more members complete onboarding, this page will become much more useful.</p>
    <?php endif; ?>

    <?php foreach ($matches as $match): ?>
        <article style="border-top:1px solid #ddd;padding:18px 0;">
            <h2><?php echo e($match['display_name']); ?> · <?php echo (int) $match['compatibility_score']; ?>%</h2>
            <p><strong><?php echo e($match['headline'] ?? ''); ?></strong></p>
            <p><?php echo e($match['town'] ?? ''); ?> <?php echo e($match['postcode_prefix'] ?? ''); ?></p>
            <p><?php echo e($match['reason_summary']); ?></p>
        </article>
    <?php endforeach; ?>

    <p><a href="/dashboard">Back to dashboard</a></p>
</main>
