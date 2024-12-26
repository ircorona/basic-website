<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <?php wp_head(); ?>
</head>
<body>
    <header>
        <div class="container d-flex justify-content-between align-items-center">
            <div class="logo">
                <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/img/logo.png'); ?>" alt="Logo">
            </div>
            <nav>
                <?php wp_nav_menu(
                    array(
                        'theme_location' => 'top_menu',
                        'menu_class' => 'menu-principal d-flex',
                        'container_class' => 'container-menu'
                    )
                ); ?>
            </nav>
        </div>
    </header>
</body>


</html>
