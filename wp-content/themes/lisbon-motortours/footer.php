<footer class="site-footer">
    <div class="footer-container">

        <div class="footer-brand">
            <span class="footer-logo"><?php bloginfo('name'); ?></span>
            <p><?php bloginfo('description'); ?></p>
            <p><img src="<?php echo get_template_directory_uri(); ?>/assets/images/logo_TdP.png" alt="Turismo de Portugal"></p>
            <p>RNAAT nº 000/2026</p>
        </div>


        <div class="footer-links">
            <h4>Explorar</h4>
            <ul>
                <li>
                    <a href="<?php echo esc_url(home_url('/contact/')); ?>">Contacto</a>
                </li>
            </ul>
        </div>

        <div class="footer-links">
            <h4>Segue-nos</h4>
            <ul class="footer-social">

                <li>
                    <a href="https://www.facebook.com/profile.php?id=61588386073149&locale=pt_PT" target="_blank" rel="noopener">
                        <svg viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M22 12a10 10 0 1 0-11.5 9.9v-7H8v-3h2.5V9.5A3.5 3.5 0 0 1 14.3 6h2.7v3h-2c-.8 0-1 .4-1 1v1.9H17l-.5 3h-2v7A10 10 0 0 0 22 12z"/>
                        </svg>
                        Facebook
                    </a>
                </li>

                <li>
                    <a href="https://www.instagram.com/lisbon_motortours/" target="_blank" rel="noopener">
                        <svg viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M7 2C4.2 2 2 4.2 2 7v10c0 2.8 2.2 5 5 5h10c2.8 0 5-2.2 5-5V7c0-2.8-2.2-5-5-5H7zm10 2a3 3 0 0 1 3 3v10a3 3 0 0 1-3 3H7a3 3 0 0 1-3-3V7a3 3 0 0 1 3-3h10zm-5 3.5A4.5 4.5 0 1 0 16.5 12 4.5 4.5 0 0 0 12 7.5zm0 7.4A2.9 2.9 0 1 1 14.9 12 2.9 2.9 0 0 1 12 14.9zM17.5 6.4a1.1 1.1 0 1 0 1.1 1.1 1.1 1.1 0 0 0-1.1-1.1z"/>
                        </svg>
                        Instagram
                    </a>
                </li>

            </ul>
        </div>

    </div>

    <div class="footer-bottom">
        © <?php echo date('Y'); ?> Lisbon MotorTours · Todos os direitos reservados
    </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
