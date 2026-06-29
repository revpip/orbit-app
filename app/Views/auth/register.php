<?php
use Orbit\Security\Csrf;
$errors = $errors ?? [];
$old = $old ?? [];
?>
<main>
    <h1>Join ORBIT</h1>
    <p>Create your beta account and start building a more trusted circle.</p>

    <?php if ($errors): ?>
        <div role="alert">
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="post" action="/register">
        <?php echo Csrf::field(); ?>
        <p><label>Name<br><input name="display_name" value="<?php echo e($old['display_name'] ?? ''); ?>" required></label></p>
        <p><label>Email<br><input type="email" name="email" value="<?php echo e($old['email'] ?? ''); ?>" required></label></p>
        <p><label>Password<br><input type="password" name="password" minlength="10" required></label></p>
        <button type="submit">Create account</button>
    </form>
</main>
