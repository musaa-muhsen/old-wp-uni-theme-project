<?php get_header(); 
// the blog
   pageBanner(
     array (
       'title' => 'welcome to our blog!',
       'subtitle' => 'keep up with our latest news.'
     )
     );
?>



  <div class="container container--narrow page-section">
     <?php 
      #while(have_posts()) = keep looping as long as the following is true have posts 
      while(have_posts()) {
      #always start with the function named the_post() because that will get the appropriate data ready for each post 
        the_post(); ?>
        <div class="post-item">
          <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
          <div class="metabox">
              <p>Posted by <?php the_author_posts_link(); ?> on <?php the_time('d/m/Y'); ?> 
              in <?php echo get_the_category_list(', '); ?></p>
          </div>
          <div class="generic-content">
              <?php the_excerpt(); ?>

              <p><a class="btn btn--blue" href="<?php the_permalink(); ?>">
              continue reading >>> 
            </a></p>
          </div>
        </div>
    <?php } 
    echo paginate_links();
    ?>
</div>
<?php get_footer(); ?>