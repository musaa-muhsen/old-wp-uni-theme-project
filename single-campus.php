<?php 

// loop inside a loop 
get_header();

while(have_posts()) {
  the_post(); 
  pagebanner() 
  ?>

  <div class="container container--narrow page-section">

  <!-- the breadcrumb //////////////////////////////////// -->

     <div class="metabox metabox--position-up metabox--with-home-link">
      <p><a class="metabox__blog-home-link" href="<?php echo get_post_type_archive_link('campus'); ?>"><i class="fa fa-home" aria-hidden="true">
      </i>All Campuses</a> 
      <span class="metabox__main"><?php the_title(); ?></span></p>
     </div>
<!-- the map section ////////////////////////////////////////////// -->

  <div class="generic-content"><?php the_content(); ?></div>

<?php $mapLocation = get_field('map_location_'); ?>

  <div class="acf-map">
    <div class="marker" data-lat="<?php echo $mapLocation['lat']; ?>" data-lng="<?php echo $mapLocation['lng']; ?>">
      <h3><?php the_title(); ?></h3>
      <?php echo $mapLocation['address']; ?>  
    </div>
  </div>
   

  <?php 
        $relatedPrograms = new WP_Query( array(
            'posts_per_page' => -1,
            'post_type' => 'program', 
            'orderby' => 'title',
            'order' => 'ASC',
            'meta_query' => array( 
               array (
                #2nd "filter" array for campus
                // below this new query is saying give us any program posts where the related_campus_ contains the id of the current campus that we're viewing 
                'key' => 'related_campus',
                'compare' => 'LIKE',
                'value' => '"' . get_the_ID() . '"'  #Retrieve the ID of the current item in the WordPress Loop
   
              )
            )
         )); //end of wp_query
          // the 2nd loop 

          if ($relatedPrograms->have_posts()) {
           echo '<hr class="section-break">';
           echo '<h2 class="headline headline--medium">Programs available at this campus</h2>';
          echo '<ul class="min-list link-list">';  
           while($relatedPrograms->have_posts()){
            $relatedPrograms->the_post(); ?>
            <li>
                <a href="<?php the_permalink(); ?>"> <?php the_title(); ?></a>
             </li>   
           <?php } //end while 
           echo '</ul>';
          } //end if 
          wp_reset_postdata();

      ?>
 </div>




<?php }

get_footer();

?>