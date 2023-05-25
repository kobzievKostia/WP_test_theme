<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
    <div class="wrapper">
        <header class="site-header">
            <div class="container">
                <div class="logo">
                    <?php if (get_theme_mod('logo_image')) : ?>
                        <img src="<?php echo esc_url(get_theme_mod('logo_image')); ?>" alt="Logo">
                    <?php else : ?>
                        <h1><?php bloginfo('name'); ?></h1>
                    <?php endif; ?>
                </div>
                <div class="site-info">
                    <div class="phone-number">
                        <?php echo esc_html(get_theme_mod('phone_number')); ?>
                    </div>
                </div>
            </div>
        </header>
        <div class="content">