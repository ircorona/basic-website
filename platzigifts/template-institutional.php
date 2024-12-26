<?php
/*
Template Name: Institutional Page
*/
get_header();

// Fetch custom fields
$fields = get_fields();
?>

<main class="container my-5">
    <!-- Display the title if available -->
    <?php if (!empty($fields['title'])) : ?>
        <h1 class="my-3"><?php echo esc_html($fields['title']); ?></h1>
    <?php endif; ?>

    <!-- Check if there are posts -->
    <?php if (have_posts()) : ?>
        <?php while (have_posts()) : the_post(); ?>
            <!-- Display the image if available -->
            <?php if (!empty($fields['image'])) : ?>
                <img src="<?php echo esc_url($fields['image']); ?>" alt="<?php echo esc_attr($fields['title'] ?? 'Image'); ?>" />
            <?php endif; ?>

            <hr /> <!-- Separator line -->

            <!-- Display the content -->
            <?php the_content(); ?>
        <?php endwhile; ?>
    <?php else : ?>
        <p>No content available.</p>
    <?php endif; ?>
</main>

<?php get_footer(); ?>
