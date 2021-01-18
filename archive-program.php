<?php get_header(); 
// this page is powering the programs page
   pageBanner(
     array(
       'title' => 'All Programs',
       'subtitle' => 'There is something for everyone.'
     )
   )
?>

  <div class="container container--narrow page-section">
      <ul class="link-list min-list">
     <?php 
      while(have_posts()) {
      #always start with the function named the_post() because that will get the appropriate data ready for each post 
        the_post(); ?>
             <li><a href="<?php echo the_permalink() ?>"><?php the_title(); ?></li> 

    <?php          
   //echo get_post_type(13);
    } 
    echo paginate_links();
    ?>
    </ul>
   
</div>
<?php get_footer(); ?>