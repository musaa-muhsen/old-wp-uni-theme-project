<?php get_header(); 
  pageBanner(
    array(
      'title' => 'All Events',
      'subtitle' => 'See what is going on in our world.'
    )
  )
  
?>



  <div class="container container--narrow page-section">
     <?php 
            //  wp_reset_postdata();

      #while(have_posts()) = keep looping as long as the following is true have posts 
      while(have_posts()) {
      #always start with the function named the_post() because that will get the appropriate data ready for each post 
        the_post(); 
        get_template_part('template-parts/content-event');      
       
  } 
    echo paginate_links();
    ?>
    <hr class="section-break">
    <p>Looking for past events?<a href="<?php echo site_url('/past-events'); ?>">Check out out past events archive</a></p>
</div>
<?php get_footer(); ?>