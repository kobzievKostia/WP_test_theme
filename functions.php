<?php

function enqueue_custom_styles()
{
    wp_enqueue_style('custom-style', get_template_directory_uri() . '/style.css');
}

add_action('wp_enqueue_scripts', 'enqueue_custom_styles');
add_theme_support('post-thumbnails');


function create_car_post_type()
{
    $labels = array(
        'name' => 'Cars',
        'singular_name' => 'Car',
        'menu_name' => 'Cars',
        'add_new' => 'Add New',
        'add_new_item' => 'Add New Car',
        'edit_item' => 'Edit Car',
        'new_item' => 'New Car',
        'view_item' => 'View Car',
        'search_items' => 'Search Cars',
        'not_found' => 'No cars found',
        'not_found_in_trash' => 'No cars found in trash',
        'parent_item_colon' => '',
        'all_items' => 'All Cars',
    );

    $args = array(
        'labels' => $labels,
        'public' => true,
        'menu_icon' => 'dashicons-car',
        'supports' => array('title', 'editor', 'thumbnail'),
        'has_archive' => true,
        'rewrite' => array('slug' => 'cars'),
    );

    register_post_type('car', $args);
}
add_action('init', 'create_car_post_type');
function create_car_taxonomies()
{
    register_taxonomy(
        'brand',
        'car',
        array(
            'label' => 'Brand',
            'rewrite' => array('slug' => 'brand'),
            'hierarchical' => true,
        )
    );

    register_taxonomy(
        'country',
        'car',
        array(
            'label' => 'Country',
            'rewrite' => array('slug' => 'country'),
            'hierarchical' => true,
        )
    );
}
add_action('init', 'create_car_taxonomies');
function add_car_meta_boxes()
{
    add_meta_box('car_color', 'Color', 'car_color_callback', 'car', 'normal', 'default');
    add_meta_box('car_fuel', 'Fuel', 'car_fuel_callback', 'car', 'normal', 'default');
    add_meta_box('car_power', 'Power', 'car_power_callback', 'car', 'normal', 'default');
    add_meta_box('car_price', 'Price', 'car_price_callback', 'car', 'normal', 'default');
}
add_action('add_meta_boxes', 'add_car_meta_boxes');

function car_color_callback($post)
{
    $value = get_post_meta($post->ID, 'car_color', true);
    echo '<input type="color" name="car_color" value="' . esc_attr($value) . '">';
}

function car_fuel_callback($post)
{
    $value = get_post_meta($post->ID, 'car_fuel', true);
    echo '<select name="car_fuel">
        <option value="petrol" ' . selected($value, 'petrol', false) . '>Petrol</option>
        <option value="diesel" ' . selected($value, 'diesel', false) . '>Diesel</option>
        <option value="electricity" ' . selected($value, 'electricity', false) . '>Electricity</option>
        <option value="hybrid" ' . selected($value, 'hybrid', false) . '>Hybrid</option>
        <option value="lpg" ' . selected($value, 'lpg', false) . '>LPG</option>
    </select>';
}

function car_power_callback($post)
{
    $value = get_post_meta($post->ID, 'car_power', true);
    echo '<input type="number" name="car_power" value="' . esc_attr($value) . '">';
}

function car_price_callback($post)
{
    $value = get_post_meta($post->ID, 'car_price', true);
    echo '<input type="number" name="car_price" value="' . esc_attr($value) . '">';
}

function save_car_meta($post_id)
{
    if (isset($_POST['car_color'])) {
        update_post_meta($post_id, 'car_color', sanitize_text_field($_POST['car_color']));
    }

    if (isset($_POST['car_fuel'])) {
        update_post_meta($post_id, 'car_fuel', sanitize_text_field($_POST['car_fuel']));
    }

    if (isset($_POST['car_power'])) {
        update_post_meta($post_id, 'car_power', sanitize_text_field($_POST['car_power']));
    }

    if (isset($_POST['car_price'])) {
        update_post_meta($post_id, 'car_price', sanitize_text_field($_POST['car_price']));
    }
}
add_action('save_post_car', 'save_car_meta');
function theme_customizer_settings($wp_customize)
{
    // Добавляем раздел "Настройки темы"
    $wp_customize->add_section('theme_settings', array(
        'title' => 'Настройки темы',
        'priority' => 30,
    ));

    // Добавляем поле для загрузки логотипа
    $wp_customize->add_setting('logo_image');
    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'logo_image', array(
        'label' => 'Логотип',
        'section' => 'theme_settings',
        'settings' => 'logo_image',
    )));

    // Добавляем поле для ввода номера телефона
    $wp_customize->add_setting('phone_number');
    $wp_customize->add_control('phone_number', array(
        'label' => 'Номер телефона',
        'section' => 'theme_settings',
        'type' => 'text',
    ));
}
add_action('customize_register', 'theme_customizer_settings');

function get_latest_cars()
{
    $args = array(
        'post_type' => 'car',
        'posts_per_page' => 10,
    );

    $query = new WP_Query($args);

    if ($query->have_posts()) {
        $output = '<div class="latest-cars-container">';
        while ($query->have_posts()) {
            $query->the_post();
            $output .= '<div class="latest-car-item">';
            $output .= '<a href="' . get_permalink() . '">';
            $output .= get_the_post_thumbnail(get_the_ID(), 'thumbnail');
            $output .= '<h3>' . get_the_title() . '</h3>';
            $output .= '</a>';
            $output .= '</div>';
        }
        $output .= '</div>';

        wp_reset_postdata();

        return $output;
    }

    return 'No cars found.';
}

add_shortcode('latest_cars', 'get_latest_cars');
function car_slider_shortcode($atts)
{
    $args = array(
        'post_type' => 'car',
        'posts_per_page' => -1,
    );
    $cars = new WP_Query($args);

    ob_start();
    if ($cars->have_posts()) :
?>
        <div class="car-slider">
            <?php while ($cars->have_posts()) : $cars->the_post(); ?>
                <div class="car-slide">
                    <?php
                    $car_color = get_post_meta(get_the_ID(), 'car_color', true);
                    $car_fuel = get_post_meta(get_the_ID(), 'car_fuel', true);
                    $car_power = get_post_meta(get_the_ID(), 'car_power', true);
                    $car_price = get_post_meta(get_the_ID(), 'car_price', true);
                    ?>
                    <div class="car-category"><?php echo wp_get_post_terms(get_the_ID(), 'brand', array('fields' => 'names'))[0]; ?></div>
                    <div class="car-image"><?php the_post_thumbnail('thumbnail'); ?></div>
                    <div class="car-title"><?php the_title(); ?></div>
                    <div class="car-meta">
                        <div class="car-color" style="background-color: <?php echo esc_attr($car_color); ?>;"></div>
                        <span class="meta-field">Fuel: <?php echo esc_html($car_fuel); ?></span>
                        <span class="meta-field">Power: <?php echo esc_html($car_power); ?></span>
                        <span class="meta-field">Price: <?php echo esc_html($car_price); ?>$</span>
                        <span class="meta-field">Brand: <?php echo wp_get_post_terms(get_the_ID(), 'brand', array('fields' => 'names'))[0]; ?></span>
                        <span class="meta-field">Country: <?php echo wp_get_post_terms(get_the_ID(), 'country', array('fields' => 'names'))[0]; ?></span>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
<?php
    endif;
    wp_reset_postdata();

    return ob_get_clean();
}

add_shortcode('car_slider', 'car_slider_shortcode');
