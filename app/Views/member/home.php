<main>
    <h1>Your ORBIT</h1>
    <p>This is the first protected member area. Next modules will add onboarding, profile creation and match suggestions.</p>
    <form method="post" action="/logout">
        <?php echo \Orbit\Security\Csrf::field(); ?>
        <button type="submit">Sign out</button>
    </form>
</main>
