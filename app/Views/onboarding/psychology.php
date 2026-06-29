<?php
use Orbit\Security\Csrf;
$errors = $errors ?? [];
$profile = $profile ?? [];
function selected_option(array $profile, string $field, string $value): string { return (($profile[$field] ?? '') === $value) ? 'selected' : ''; }
?>
<main>
    <h1>Your connection style</h1>
    <p>These early questions help ORBIT suggest people who feel easier to talk to, meet and build trust with.</p>

    <?php if ($errors): ?>
        <div role="alert"><ul><?php foreach ($errors as $error): ?><li><?php echo e($error); ?></li><?php endforeach; ?></ul></div>
    <?php endif; ?>

    <form method="post" action="/onboarding/psychology">
        <?php echo Csrf::field(); ?>

        <p><label>How do you prefer to communicate?<br>
            <select name="communication_style" required>
                <option value="">Choose...</option>
                <option value="steady" <?php echo selected_option($profile, 'communication_style', 'steady'); ?>>Steady and consistent</option>
                <option value="deep" <?php echo selected_option($profile, 'communication_style', 'deep'); ?>>Deep and thoughtful</option>
                <option value="light" <?php echo selected_option($profile, 'communication_style', 'light'); ?>>Light and easy-going</option>
                <option value="direct" <?php echo selected_option($profile, 'communication_style', 'direct'); ?>>Direct and clear</option>
            </select>
        </label></p>

        <p><label>What is your social energy like?<br>
            <select name="social_energy" required>
                <option value="">Choose...</option>
                <option value="quiet" <?php echo selected_option($profile, 'social_energy', 'quiet'); ?>>Quiet and low-key</option>
                <option value="balanced" <?php echo selected_option($profile, 'social_energy', 'balanced'); ?>>Balanced</option>
                <option value="outgoing" <?php echo selected_option($profile, 'social_energy', 'outgoing'); ?>>Outgoing</option>
                <option value="adventurous" <?php echo selected_option($profile, 'social_energy', 'adventurous'); ?>>Adventurous</option>
            </select>
        </label></p>

        <p><label>When something feels off, what helps most?<br>
            <select name="conflict_style" required>
                <option value="">Choose...</option>
                <option value="space" <?php echo selected_option($profile, 'conflict_style', 'space'); ?>>A little space first</option>
                <option value="talk" <?php echo selected_option($profile, 'conflict_style', 'talk'); ?>>Talking it through</option>
                <option value="reassurance" <?php echo selected_option($profile, 'conflict_style', 'reassurance'); ?>>Reassurance</option>
                <option value="practical" <?php echo selected_option($profile, 'conflict_style', 'practical'); ?>>Practical solutions</option>
            </select>
        </label></p>

        <p><label>What sort of humour do you enjoy?<br>
            <select name="humour_style" required>
                <option value="">Choose...</option>
                <option value="dry" <?php echo selected_option($profile, 'humour_style', 'dry'); ?>>Dry and witty</option>
                <option value="silly" <?php echo selected_option($profile, 'humour_style', 'silly'); ?>>Silly and playful</option>
                <option value="warm" <?php echo selected_option($profile, 'humour_style', 'warm'); ?>>Warm and gentle</option>
                <option value="sharp" <?php echo selected_option($profile, 'humour_style', 'sharp'); ?>>Sharp and cheeky</option>
            </select>
        </label></p>

        <p><label>Reliability, 1-10<br><input type="number" min="1" max="10" name="reliability_self_score" value="<?php echo e((string)($profile['reliability_self_score'] ?? 7)); ?>"></label></p>
        <p><label>Openness to new people, 1-10<br><input type="number" min="1" max="10" name="openness_score" value="<?php echo e((string)($profile['openness_score'] ?? 7)); ?>"></label></p>
        <p><label>Boundary clarity, 1-10<br><input type="number" min="1" max="10" name="boundaries_score" value="<?php echo e((string)($profile['boundaries_score'] ?? 7)); ?>"></label></p>

        <button type="submit">See suggestions</button>
    </form>
</main>
