<?php
use Orbit\Security\Csrf;
$errors = $errors ?? [];
$old = $old ?? [];
?>
<main>
    <h1>Sign in</h1>
    <p>Return to your ORBIT beta account.</p>

    <?php if ($errors): ?>
        <div role="alert">
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="post" action="/login">
        <?php echo Csrf::field(); ?>
        <p><label>Email<br><input type="email" name="email" value="<?php echo e($old['email'] ?? ''); ?>" required></label></p>
        <p><label>Password<br><input type="password" name="password" required></label></p>
        <button type="submit">Sign in</button>
    </form>
</main>
