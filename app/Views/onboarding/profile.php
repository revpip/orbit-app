<?php
use Orbit\Security\Csrf;
$errors = $errors ?? [];
$profile = $profile ?? [];
?>
<main>
    <h1>Create your profile</h1>
    <p>This is the start of your trusted ORBIT presence. Keep it warm, honest and clear.</p>

    <?php if ($errors): ?>
        <div role="alert">
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="post" action="/onboarding/profile">
        <?php echo Csrf::field(); ?>
        <p><label>Profile headline<br><input name="headline" value="<?php echo e($profile['headline'] ?? ''); ?>" required></label></p>
        <p><label>Short introduction<br><textarea name="bio" rows="6" required><?php echo e($profile['bio'] ?? ''); ?></textarea></label></p>
        <p><label>Date of birth<br><input type="date" name="date_of_birth" value="<?php echo e($profile['date_of_birth'] ?? ''); ?>"></label></p>
        <p><label>Gender / identity<br><input name="gender" value="<?php echo e($profile['gender'] ?? ''); ?>"></label></p>
        <p><label>Town or nearest area<br><input name="town" value="<?php echo e($profile['town'] ?? ''); ?>" required></label></p>
        <p><label>Postcode prefix<br><input name="postcode_prefix" value="<?php echo e($profile['postcode_prefix'] ?? ''); ?>" placeholder="CW1" required></label></p>
        <input type="hidden" name="country_code" value="GB">
        <input type="hidden" name="visibility" value="members">
        <button type="submit">Continue</button>
    </form>
</main>
