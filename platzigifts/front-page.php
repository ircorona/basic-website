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
        <!-- Selector de Categorías -->
        <div class="row mb-4">
            <div class="col-12">
                <select class="form-control" name="categorias-productos" id="categorias-prod">
                    <option value="">Todas las categorías</option>
                    <?php 
                    $terms = get_terms(array(
                        'taxonomy' => 'product-categories',
                        'hide_empty' => true,
                    ));
                    
                    if (!is_wp_error($terms) && !empty($terms)) {
                        foreach ($terms as $term) {
                            echo '<option value="' . esc_attr($term->slug) . '">' . esc_html($term->name) . '</option>';
                        }
                    }
                    ?>
                </select>
            </div>
        </div>

        <div class="row productos-container">
            <?php
            $args = array(
                'post_type'      => 'product',
                'posts_per_page' => -1, 
                'order'          => 'ASC',
                'orderby'        => 'title',
            );

            $productos = new WP_Query($args);

            if ($productos->have_posts()) :
                while ($productos->have_posts()) : $productos->the_post(); 
                    // Obtener las categorías del producto actual
                    $product_cats = wp_get_post_terms(get_the_ID(), 'product-categories', array('fields' => 'slugs'));
                    ?>
                    <div class="col-4 producto-item" data-categorias='<?php echo esc_attr(json_encode($product_cats)); ?>'>
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

            wp_reset_postdata();
            ?>
        </div>
    </div>

    <!-- Sección de Novedades -->
    <div class="my-5">
        <h2 class="text-center mb-4">NOVEDADES</h2>
        <div id="resultado-novedades" class="row">
            <!-- Aquí se cargarán dinámicamente las novedades via JavaScript -->
        </div>
    </div>
</main>

<?php get_footer(); ?>