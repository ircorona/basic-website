<?php get_header(); ?>

<div class="container my-4">
    <div class="row">
        <div class="col-12 text-center">
            <h1 class="archive-title"><?php the_archive_title(); ?></h1>
            <p class="archive-description"><?php the_archive_description(); ?></p>
        </div>
    </div>

    <div class="row">
        <?php if (have_posts()) : ?>
            <?php while (have_posts()) : the_post(); ?>
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <?php if (has_post_thumbnail()) : ?>
                            <a href="<?php the_permalink(); ?>">
                                <?php the_post_thumbnail('medium', ['class' => 'card-img-top']); ?>
                            </a>
                        <?php endif; ?>
                        <div class="card-body">
                            <h5 class="card-title">
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            </h5>
                            <p class="card-text"><?php echo wp_trim_words(get_the_excerpt(), 20, '...'); ?></p>
                        </div>
                        <div class="card-footer text-muted">
                            Posted on <?php echo get_the_date(); ?>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else : ?>
            <div class="col-12 text-center">
                <p>No posts found. Please check back later!</p>
            </div>
        <?php endif; ?>
    </div>

    <div class="row">
        <div class="col-12">
            <?php
            // Display pagination if applicable
            the_posts_pagination(array(
                'prev_text' => '&laquo; Previous',
                'next_text' => 'Next &raquo;',
            ));
            ?>
        </div>
    </div>
</div>

<?php get_footer(); ?>
