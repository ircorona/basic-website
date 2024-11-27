<footer>
    <div class="container">
        <div class="row">
            <div class="col-12">
                <?php if (is_active_sidebar('footer')) : ?>
                    <?php dynamic_sidebar('footer'); ?>
                <?php else : ?>
                    <p class="no-widgets">Add widgets to the Footer widget area in the WordPress dashboard to see them here.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</footer>

<?php wp_footer(); ?>

</body>
</html>
