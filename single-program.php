<?php 

// loop inside a loop 
get_header();

while(have_posts()) {
  the_post(); 
  pagebanner() 
  ?>

  <div class="container container--narrow page-section">

  <!-- middle part //////////////////////////////////// -->

     <div class="metabox metabox--position-up metabox--with-home-link">
      <p><a class="metabox__blog-home-link" href="<?php echo get_post_type_archive_link('program'); ?>"><i class="fa fa-home" aria-hidden="true">
      </i>All programs</a> 
      <span class="metabox__main"><?php the_title(); ?></span></p>
     </div>
<!-- wp_query parts ////////////////////////////////////////////// -->
<div class="generic-content"><?php the_field('main_body_content'); ?></div>

  <?php 
        $relatedProfessors = new WP_Query( array(
            'posts_per_page' => -1,
            'post_type' => 'professor', 
            'orderby' => 'title',
            'order' => 'ASC',
            #above changes the order of the date below gets rid of old events 
            # type numeric comparing numbers 
            'meta_query' => array(
                #filter up coming events 
               array (
                #2nd "filter" array for programs
                'key' => 'related_programs',
                'compare' => 'LIKE',
                'value' => '"' . get_the_ID() . '"'  #Retrieve the ID of the current item in the WordPress Loop
   
              )
            )
         )); //end of wp_query
         wp_reset_postdata();

       // 2nd loop for teachers    
          if ($relatedProfessors->have_posts()) {
           echo '<hr class="section-break">';
           echo '<h2 class="headline headline--medium">' . get_the_title() . ' Professors</h2>';
            #look inside of the var/obj for condition of the loop and the loop actions
          echo '<ul class="professor-cards">';  
           while($relatedProfessors->have_posts()){
            $relatedProfessors->the_post(); ?>
            <!-- loop content professor -->
            <li class="professor-card__list-item">
                <a class="professor-card" href="<?php the_permalink(); ?>">
                   <img class="professor-card__image" src="<?php the_post_thumbnail_url('professorLandscape') ?>">
                   <span class="professor-card__name"><?php the_title(); ?></span>
                </a>
             </li>   
           <?php } //end while 
           echo '</ul>';
          } //end if 
          wp_reset_postdata();
 // 2nd WP_Query  ================================================================

       #same as return format on custom fields 
       $today = date('Ymd');
       $homepageEvents = new WP_Query( array(
         'posts_per_page' => 4,
         'post_type' => 'event', 
         'meta_key' => 'event_date',
         'orderby' => 'meta_value_num',
         'order' => 'ASC',
         #above changes the order of the date below gets rid of old events 
         # type numeric comparing numbers 
         'meta_query' => array(
             #filter up coming events 
           array(
             'key' => 'event_date',
             'compare' => '>=',
             'value' => $today,
             'type' => 'numeric'
             ), array (
             #2nd "filter" array 
             'key' => 'related_programs',
             'compare' => 'LIKE',
             'value' => '"' . get_the_ID() . '"'  #Retrieve the ID of the current item in the WordPress Loop

           )
         )
      )); //end of wp_query
      wp_reset_postdata();

       
       if ($homepageEvents->have_posts()) {
        echo '<hr class="section-break">';
        echo '<h2 class="headline headline--medium">Upcoming ' . get_the_title() . ' Events</h2>';         
        while($homepageEvents->have_posts()){
          $homepageEvents->the_post(); 
          get_template_part('template-parts/content-event');      
        }
       } 
       wp_reset_postdata();
       #getting campus details if there is any, there is no need for to use custom query because it's not a native type post we can just look at the value straight from the custom field plugin that has any relationship with the current post. aka directley related to the custom_fields plugin and not anything else 
       $relatedCampuses = get_field('related_campus');

       if ($relatedCampuses) {
        echo '<h2 class="headline">' . get_the_title() . ' is Available At These Campuses: </h2>'; #title of program 

        echo '<ul class="min-list link-list">';
        foreach($relatedCampuses as $campus) {
          ?> 
          <li><a href="<?php echo get_the_permalink($campus); ?>"><?php echo get_the_title($campus); ?> </a></li>
       <?php  } // end of for each 
       echo '</ul>';      
       } //end of condition 
      ?>
 </div> 
 <!-- end of content -->




<?php }

get_footer();

?>