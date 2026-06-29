<?php
use Orbit\Security\Csrf;
$errors = $errors ?? [];
$selected = $selected ?? [];
?>
<main>
    <h1>What are you looking for?</h1>
    <p>Choose the connection types that feel right. You can refine privacy and visibility later.</p>

    <?php if ($errors): ?>
        <div role="alert">
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="post" action="/onboarding/intents">
        <?php echo Csrf::field(); ?>
        <?php foreach ($intents as $intent): ?>
            <p>
                <label>
                    <input type="checkbox" name="intent_ids[]" value="<?php echo (int) $intent['id']; ?>" <?php echo in_array((int) $intent['id'], $selected, true) ? 'checked' : ''; ?>>
                    <?php echo e($intent['label']); ?>
                    <small><?php echo e($intent['category']); ?></small>
                </label>
            </p>
        <?php endforeach; ?>
        <button type="submit">Finish setup</button>
    </form>
</main>
