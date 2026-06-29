<?php
$profile = $profile ?? [];
$selectedIntents = $selectedIntents ?? [];
$allIntents = $allIntents ?? [];
$chosenLabels = [];
foreach ($allIntents as $intent) {
    if (in_array((int) $intent['id'], $selectedIntents, true)) {
        $chosenLabels[] = $intent['label'];
    }
}
?>
<main>
    <h1>Your ORBIT</h1>
    <p><strong><?php echo e($profile['headline'] ?? ''); ?></strong></p>
    <p><?php echo nl2br(e($profile['bio'] ?? '')); ?></p>
    <p><strong>Area:</strong> <?php echo e(trim(($profile['town'] ?? '') . ' ' . ($profile['postcode_prefix'] ?? ''))); ?></p>

    <h2>Connection intents</h2>
    <?php if ($chosenLabels): ?>
        <ul>
            <?php foreach ($chosenLabels as $label): ?>
                <li><?php echo e($label); ?></li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>No intents selected yet. <a href="/onboarding/intents">Choose yours</a>.</p>
    <?php endif; ?>

    <p><a href="/matches">View suggestions</a></p>
    <p><a href="/onboarding/profile">Edit profile</a> | <a href="/onboarding/intents">Edit intents</a> | <a href="/onboarding/psychology">Edit connection style</a></p>

    <form method="post" action="/logout">
        <?php echo \Orbit\Security\Csrf::field(); ?>
        <button type="submit">Sign out</button>
    </form>
</main>
