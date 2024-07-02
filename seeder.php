<?php
/**
 * Plugin Name: WordPress Seeder
 * Plugin URI:
 * Description: A plugin to seed data into the WordPress database.
 * Version: 1.0.0
 * Author: Masaki Araki
 * Author URI: https://github.com/MasakiAraki
 * License: GPLv2
 */

require_once __DIR__ . '/../../../vendor/autoload.php';

use Faker\Factory as FakerFactory;

if ( ! class_exists( 'WP_CLI' ) ) {
  return;
}

class Seeder {

  /**
   * Insert seed data into the database.
   *
   * ## EXAMPLES
   *
   *     wp seed all
   *
   * @when after_wp_load
   */
  function all( $args, $assoc_args ) {
    if ( defined('WP_ENVIRONMENT_TYPE') && WP_ENVIRONMENT_TYPE === 'production' ) {
      WP_CLI::error( "This command cannot be run in production environment." );
      return;
    }

    $this->seed_users();
    $this->seed_terms();
    $this->seed_posts();
    $this->seed_comments();
    
    WP_CLI::success( "All seed data inserted successfully." );
  }

  private function seed_users() {
    $faker = FakerFactory::create();

    for ( $i = 0; $i < 10; $i++ ) {
      $userdata = array(
        'user_login'    => $faker->userName,
        'user_pass'     => 'password',
        'user_email'    => $faker->email,
        'first_name'    => $faker->firstName,
        'last_name'     => $faker->lastName,
        'role'          => 'subscriber',
      );

      wp_insert_user( $userdata );
    }
  }

  private function seed_terms() {
    $faker = FakerFactory::create();

    for ( $i = 0; $i < 10; $i++ ) {
      $term_data = array(
        'description' => $faker->sentence,
        'slug'        => $faker->slug,
        'name'        => $faker->word,
      );

      wp_insert_term( $term_data['name'], 'category', $term_data );
    }
  }

  private function seed_posts() {
    $faker = FakerFactory::create();

    $terms = get_terms(array('taxonomy' => 'category', 'hide_empty' => false, 'number' => 10));
    foreach ( $terms as $term ) {
      for ( $i = 0; $i < 3; $i++ ) {
        $post_data = array(
          'post_title'   => $faker->sentence,
          'post_content' => $faker->paragraph,
          'post_status'  => 'publish',
          'post_author'  => 1,
          'post_category' => array( $term->term_id ),
        );

        wp_insert_post( $post_data );
      }
    }
  }

  private function seed_comments() {
    $faker = FakerFactory::create();

    $posts = get_posts(array('numberposts' => 10));
    foreach ( $posts as $post ) {
      for ( $i = 0; $i < 3; $i++ ) {
        $comment_data = array(
          'comment_post_ID'      => $post->ID,
          'comment_author'       => $faker->name,
          'comment_author_email' => $faker->email,
          'comment_content'      => $faker->paragraph,
          'comment_approved'     => 1,
        );

        wp_insert_comment( $comment_data );
      }
    }
  }
}

WP_CLI::add_command( 'seed', 'Seeder' );
