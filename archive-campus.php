<?php get_header(); 
// this page is powering the programs page
   pageBanner(
     array(
       'title' => 'Our Campuses',
       'subtitle' => 'We have several conveniently located campuses.'
     )
   )
?>

  <div class="container container--narrow page-section">

  <div class="acf-map">
     <?php 
      while(have_posts()) {
      #always start with the function named the_post() because that will get the appropriate data ready for each post 
      the_post(); 
      $mapLocation = get_field('map_location_')
      ?>
    <div class="marker" data-lat="<?php echo $mapLocation['lat']; ?>" data-lng="<?php echo $mapLocation['lng']; ?>">
      <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
      <?php echo $mapLocation['address']; ?>
    
    
    </div>
     <?php }  ?>
    </div>
   
</div> 

<?php get_footer(); ?>