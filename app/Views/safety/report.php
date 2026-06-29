<?php
use Orbit\Security\Csrf;
$errors = $errors ?? [];
$reportedUserId = (int) ($reportedUserId ?? 0);
?>
<main>
    <h1>Report a concern</h1>
    <p>Tell us what happened. Reports help keep ORBIT safer, calmer and more accountable.</p>

    <?php if ($errors): ?>
        <div role="alert"><ul><?php foreach ($errors as $error): ?><li><?php echo e($error); ?></li><?php endforeach; ?></ul></div>
    <?php endif; ?>

    <form method="post" action="/safety/report">
        <?php echo Csrf::field(); ?>
        <input type="hidden" name="reported_user_id" value="<?php echo $reportedUserId; ?>">
        <p><label>Reason<br>
            <select name="reason" required>
                <option value="">Choose...</option>
                <option value="misleading-profile">Misleading profile</option>
                <option value="unwanted-contact">Unwanted contact</option>
                <option value="poor-boundaries">Poor boundaries</option>
                <option value="scam-concern">Scam concern</option>
                <option value="other">Other</option>
            </select>
        </label></p>
        <p><label>Details<br><textarea name="details" rows="6"></textarea></label></p>
        <button type="submit">Submit report</button>
    </form>
</main>
