<?php get_header(); ?>

<div class="page-hero" style="background:var(--beige);padding-top:0;">
  <div class="site-header" style="position:absolute;top:0;left:0;right:0;">
    <a href="<?php echo esc_url(home_url('/')); ?>" class="logo"><div class="logo__icon">W</div><span class="logo__text">Womazing</span></a>
    <nav class="main-nav"><?php wp_nav_menu(['theme_location'=>'primary','container'=>false,'menu_class'=>'','items_wrap'=>'%3$s']); ?></nav>
    <div class="header-right">
      <button class="icon-btn call-btn" onclick="openPopup()"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M3 5.5C3 14.06 9.94 21 18.5 21c.386 0 .77-.014 1.148-.042.435-.033.729-.414.729-.85v-3.396c0-.437-.321-.814-.754-.848l-3.154-.25c-.395-.031-.775.178-.96.531l-1.077 2.026C11.75 16.7 8.3 13.25 6.779 9.568l2.026-1.077c.353-.185.562-.565.531-.96l-.25-3.154C9.052 3.944 8.675 3.623 8.238 3.623H4.843c-.436 0-.816.294-.849.729A11.53 11.53 0 0 0 3 5.5Z"/></svg></button>
      <button class="burger" onclick="openMobileNav()"><span></span><span></span><span></span></button>
    </div>
  </div>
  <div style="padding:130px 245px 60px;">
    <?php while(have_posts()):the_post(); ?><h1 class="page-hero__title"><?php the_title(); ?></h1><?php endwhile; rewind_posts(); ?>
  </div>
</div>

<main class="main-wrap" style="padding-top:80px;padding-bottom:100px;">
  <?php while ( have_posts() ) : the_post(); ?>
  <div class="js-fade" style="max-width:800px; font-size:16px; font-weight:400; line-height:1.75;">
    <?php the_content(); ?>
  </div>
  <?php endwhile; ?>
</main>

<?php get_footer(); ?>
