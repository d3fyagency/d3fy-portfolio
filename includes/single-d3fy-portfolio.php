<?php while (have_posts()) : the_post(); ?>
  <header class="page-header">
  <h2 class="entry-title"><?php the_title(); ?></h2>
</header>
  <article <?php post_class(); ?>>
    <div class="entry-content">
      <?php the_content(); ?>
    </div>
    <footer>
      <?php wp_link_pages(['before' => '<nav class="page-nav"><p>'.__('Pages:', 'sage'), 'after' => '</p></nav>']); ?>
    </footer>
  </article>
<?php endwhile; ?>
