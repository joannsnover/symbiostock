<?php
/**
 * The Sidebar containing the main widget areas.
 *
 * @package symbiostock
 * @since symbiostock 1.0
 */
?>
        <div id="secondary" class="widget-area panel panel-default" role="complementary">
            <?php do_action( 'before_sidebar' ); ?>
            <?php if ( ! dynamic_sidebar( 'sidebar-1' ) ) : ?>

                <aside id="archives" class="widget">
                    <div class="panel-heading">
                        <h1 class="widget-title panel-title"><?php _e( 'Archives', 'symbiostock' ); ?></h1>
                    </div>
                    <div class="panel-body">
                        <ul>
                            <?php wp_get_archives( array( 'type' => 'monthly' ) ); ?>
                        </ul>
                    </div>
                </aside>
                <aside id="meta" class="widget">
                    <div class="panel-heading">
                        <h1 class="widget-title panel-title"><?php _e( 'Meta', 'symbiostock' ); ?></h1>
                    </div>
                    <div class="panel-body">
                        <ul>
                            <?php wp_register(); ?>
                            <li><?php wp_loginout(); ?></li>
                            <?php wp_meta(); ?>
                        </ul>
                    </div>
                </aside>
            <?php endif; // end sidebar widget area ?>
        </div><!-- #secondary .widget-area -->