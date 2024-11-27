<?php

get_header();
?>

<main class="container">
    <?php if (have_posts()) : ?>
        <?php while (have_posts()) : the_post(); ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                <header class="entry-header">
                    <h1 class="entry-title my-3"><?php the_title(); ?></h1>
                </header>
                <div class="entry-content">
                    <?php the_content(); ?>
                </div>
                <?php if (get_edit_post_link()) : ?>
                    <footer class="entry-footer">
                        <span class="edit-link">
                            <?php edit_post_link(__('Edit', 'textdomain'), '<span class="screen-reader-text">' . __('Edit', 'textdomain') . '</span>', ''); ?>
                        </span>
                    </footer>
                <?php endif; ?>
            </article>
        <?php endwhile; ?>
    <?php else : ?>
        <p><?php esc_html_e('Sorry, no posts matched your criteria.', 'textdomain'); ?></p>
    <?php endif; ?>
</main>

<?php
get_footer();
?>
