<?php

get_header(); ?>

<main class="container">
    <?php if (have_posts()) {
        while (have_posts()) {
            the_post(); ?>
            <h1 class="my-3"><?php the_title(); ?></h1>
            <div class="row">
                <div class="col-md-4">
                    <?php if (has_post_thumbnail()) {
                        the_post_thumbnail('large', ['class' => 'img-fluid']);
                    } ?>
                </div>
                <div class="col-md-8">
                    <?php the_content(); ?>
                </div>
            </div>
    <?php 
        } 
    } ?>
</main>

<?php get_footer(); ?>
