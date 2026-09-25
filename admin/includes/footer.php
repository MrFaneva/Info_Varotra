<footer>
    <p>&copy; <?= date('Y') ?> <strong>Info_Varotra</strong> - Tous droits réservés.</p>
    <p style="font-size: 0.85rem; margin-top: 0.5rem;">
            Matériels Informatiques & Services de Maintenance | Projet Développement Local
            <a href="/Info_Varotra/admin/login.php" style="color: #94a3b8; text-decoration: none; font-size: 0.85rem;"> | Espace Administration</a>
        </p>
</footer>

<!-- Script JavaScript pour le menu Hamburger Mobile Admin -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const toggleBtn = document.getElementById('admin-menu-toggle');
    const navLinks = document.getElementById('admin-nav-links');

    if (toggleBtn && navLinks) {
        toggleBtn.addEventListener('click', function() {
            navLinks.classList.toggle('active');
        });
    }
});
</script>

</body>
</html>
