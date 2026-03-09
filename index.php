<?php get_header(); ?>
<main class="main-wrap" style="padding:140px 0 100px;">
  <?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
    <article style="margin-bottom:40px;">
      <h2 style="font-size:24px;font-weight:500;margin-bottom:12px;">
        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
      </h2>
      <div><?php the_excerpt(); ?></div>
    </article>
  <?php endwhile; ?>
</main>
<?php get_footer(); ?>
