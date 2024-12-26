<?php

get_header(); ?>

<main class="container my-4">
    <?php if (have_posts()) : ?>
        <?php while (have_posts()) : the_post(); ?>
            <!-- Post Title -->
            <h1 class="my-3 text-center">Este producto es: <?php the_title(); ?></h1>

            <!-- Display Taxonomy Terms -->
            <div class="text-center mb-4">
                <?php
                $taxonomy = get_the_terms(get_the_ID(), 'product-categories'); // Replace with your taxonomy name
                if ($taxonomy && !is_wp_error($taxonomy)) : ?>
                    <p class="text-muted" style="color: #ffffff !important;">
                        Categorías: 
                        <?php foreach ($taxonomy as $term) : ?>
                            <a href="<?php echo esc_url(get_term_link($term)); ?>">
                                <?php echo esc_html($term->name); ?>
                            </a>
                        <?php endforeach; ?>
                    </p>
                <?php else : ?>
                    <p class="text-muted">No categories assigned.</p>
                <?php endif; ?>
            </div>

            <!-- Post Content -->
            <div class="row my-5">
                <!-- Post Thumbnail (Left Side) -->
                <div class="col-md-6 product-thumbnail">
                    <?php if (has_post_thumbnail()) : ?>
                        <div class="thumbnail-container">
                            <?php the_post_thumbnail('large', ['class' => 'img-fluid rounded shadow']); ?>
                        </div>
                    <?php else : ?>
                        <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/img/no-image.png'); ?>" alt="No image available" class="img-fluid rounded shadow">
                    <?php endif; ?>
                </div>

                <!-- Contact Form (Right Side) -->
                <div class="col-md-6 contact-form-container">
                    <div class="contact-form">
                        <h3 class="text-center mb-4">Contact Us</h3>
                        <?php echo do_shortcode('[contact-form-7 id="5e8b5b7" title="Contact form 1"]'); ?>
                    </div>
                </div>
            </div>

            <!-- Related Products Section -->
            <?php
            if ($taxonomy && !is_wp_error($taxonomy)) {
                $args = array(
                    'post_type'      => 'product', // Post type for products
                    'posts_per_page' => -1,        // Show all related products (no limit)
                    'order'          => 'ASC',     // Order by ascending
                    'orderby'        => 'title',   // Order by title
                    'tax_query'      => array(
                        array(
                            'taxonomy' => 'product-categories', // Taxonomy name
                            'field'    => 'slug',              // Use slug to filter
                            'terms'    => wp_list_pluck($taxonomy, 'slug'), // Get all slugs of terms
                        ),
                    ),
                    'post__not_in'   => array(get_the_ID()), // Exclude current product
                );

                $related_products = new WP_Query($args);

                if ($related_products->have_posts()) : ?>
                    <section class="related-products my-5">
                        <h2 class="text-center mb-4">Related Products</h2>
                        <div class="row justify-content-center">
                            <?php while ($related_products->have_posts()) : $related_products->the_post(); ?>
                                <div class="col-6 col-md-4 col-lg-3 my-3">
                                    <div class="related-product text-center">
                                        <a href="<?php the_permalink(); ?>">
                                            <?php if (has_post_thumbnail()) : ?>
                                                <?php the_post_thumbnail('thumbnail', ['class' => 'img-fluid rounded']); ?>
                                            <?php else : ?>
                                                <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/img/no-image.png'); ?>" alt="No image available" class="img-fluid rounded">
                                            <?php endif; ?>
                                            <p class="mt-2"><?php the_title(); ?></p>
                                        </a>
                                    </div>
                                </div>
                            <?php endwhile; ?>
                        </div>
                    </section>
                <?php else : ?>
                    <p class="text-center text-muted">No related products found.</p>
                <?php endif;

                wp_reset_postdata();
            } else {
                echo '<p class="text-center text-muted">No categories assigned to this product.</p>';
            }
            ?>
        <?php endwhile; ?>
    <?php else : ?>
        <!-- No Products Found -->
        <div class="row">
            <div class="col-12 text-center">
                <p class="text-muted">No product found. Please check back later!</p>
            </div>
        </div>
    <?php endif; ?>
</main>

<?php get_footer(); ?>
