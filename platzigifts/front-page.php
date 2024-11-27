<?php get_header(); ?>

<main class="container">
    <?php if (have_posts()) : ?>
        <?php while (have_posts()) : the_post(); ?>
            <h1 class="my-3"><?php echo esc_html(get_the_title()); ?>!!</h1>
            <div><?php the_content(); ?></div>
        <?php endwhile; ?>
    <?php else : ?>
        <p class="text-center">No posts found.</p>
    <?php endif; ?>

    <div class="lista-productos my-5">
        <h2 class="text-center">PRODUCTOS</h2>
        <div class="row">
            <?php
            // Custom Query for Products
            $args = array(
                'post_type'      => 'product', // Corrected slug from 'producto' to 'product'
                'posts_per_page' => -1, 
                'order'          => 'ASC',
                'orderby'        => 'title',
            );

            $productos = new WP_Query($args);

            if ($productos->have_posts()) :
                while ($productos->have_posts()) : $productos->the_post(); ?>
                    <div class="col-4">
                        <figure>
                            <?php if (has_post_thumbnail()) : ?>
                                <?php the_post_thumbnail('large', array('class' => 'img-fluid')); ?>
                            <?php else : ?>
                                <img src="<?php echo esc_url(get_template_directory_uri() . '/images/default-thumbnail.jpg'); ?>" alt="Default Thumbnail" class="img-fluid">
                            <?php endif; ?>
                        </figure>
                        <h4 class="my-3 text-center">
                            <a href="<?php echo esc_url(get_permalink()); ?>">
                                <?php echo esc_html(get_the_title()); ?>
                            </a>
                        </h4>
                    </div>
                <?php endwhile;
            else : ?>
                <p class="text-center">No products found.</p>
            <?php endif;

            // Reset Post Data
            wp_reset_postdata();
            ?>
        </div>
    </div>
</main>

<?php get_footer(); ?>
