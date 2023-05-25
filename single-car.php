<?php get_header(); ?>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
        <div class="single-car-container">
            <?php
            $car_color = get_post_meta(get_the_ID(), 'car_color', true);
            $car_fuel = get_post_meta(get_the_ID(), 'car_fuel', true);
            $car_power = get_post_meta(get_the_ID(), 'car_power', true);
            $car_price = get_post_meta(get_the_ID(), 'car_price', true);
            $car_brand = get_the_terms(get_the_ID(), 'brand');
            $car_country = get_the_terms(get_the_ID(), 'country');
            ?>
            <div class="car-category">
                <?php if ($car_brand) : ?>
                    <?php foreach ($car_brand as $brand) : ?>
                        <?php echo esc_html($brand->name); ?>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            <div class="car-image">
                <?php the_post_thumbnail('thumbnail'); ?>
            </div>
            <div class="car-title">
                <h2><?php the_title(); ?></h2>
            </div>
            <div class="car-meta">
                <div class="car-color" style="background-color: <?php echo esc_attr($car_color); ?>;"></div>
                <div class="meta-field">
                    <span class="meta-label">Fuel:</span>
                    <?php echo esc_html($car_fuel); ?>
                </div>
                <div class="meta-field">
                    <span class="meta-label">Power:</span>
                    <?php echo esc_html($car_power); ?>
                </div>
                <div class="meta-field">
                    <span class="meta-label">Price:</span>
                    <?php echo esc_html($car_price); ?>$
                </div>
                <div class="meta-field">
                    <span class="meta-label">Brand:</span>
                    <?php if ($car_brand) : ?>
                        <?php foreach ($car_brand as $brand) : ?>
                            <?php echo esc_html($brand->name); ?>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
                <div class="meta-field">
                    <span class="meta-label">Country:</span>
                    <?php if ($car_country) : ?>
                        <?php foreach ($car_country as $country) : ?>
                            <?php echo esc_html($country->name); ?>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
<?php endwhile;
endif; ?>

<?php get_footer(); ?>