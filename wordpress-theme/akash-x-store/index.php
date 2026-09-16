<?php
/**
 * The main template file
 */
get_header();
?>

<main style="max-width: 1200px; margin: 0 auto; padding: 2rem 1.25rem; min-height: 60vh;">
  <?php
  if (have_posts()) :
      while (have_posts()) : the_post();
          ?>
          <article style="background: rgba(13, 25, 48, 0.7); border: 1px solid var(--border-green); border-radius: 16px; padding: 2rem; margin-bottom: 2rem;">
            <h1 style="color: #fff; font-size: 2rem; font-weight: 900; margin-bottom: 1rem;"><?php the_title(); ?></h1>
            <div style="color: var(--text-secondary); line-height: 1.7;"><?php the_content(); ?></div>
          </article>
          <?php
      endwhile;
  else :
      ?>
      <div style="text-align: center; padding: 4rem 1rem; color: #94a3b8;">
        <h2>No content found</h2>
        <a href="<?php echo esc_url(home_url('/')); ?>" style="color: var(--neon-green-bright); text-decoration: none;">Return to Storefront</a>
      </div>
      <?php
  endif;
  ?>
</main>

<?php get_footer(); ?>
