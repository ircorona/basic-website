<?php get_header(); ?>

<main class="container text-center my-5">
    <div class="pagina404">
        <h1 class="display-4">404 - Página No Encontrada</h1>
        <p class="lead">Lo sentimos, la página que buscas no existe o ha sido movida.</p>
        <!-- Call-to-action button -->
        <a href="<?php echo esc_url(home_url('/')); ?>" class="btn btn-primary mt-4">
            Volver a la Página Principal
        </a>
    </div>
</main>

<?php get_footer(); ?>
