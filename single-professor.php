<?php 

get_header();

while(have_posts()) {
  the_post(); 
  pageBanner();

  
  ?>
  
  

  <div class="container container--narrow page-section">

      <div class="generic-content">
         <div class="row group">

           <div class="one-third">
             <?php the_post_thumbnail('professorPortrait'); ?>
           </div>
           <div class="two-thirds">
             <?php 
             //to get
// so were on the professor page looking for the custom_field (liked_professor_id) with equal data to the current professor 
// this query will find all the likes for each professor 
             $likeCount = new WP_Query( array(
               'post_type' => 'like',
               'meta_query' => array (
                 array (
                   'key' => 'liked_professor_id', // the number from custom fields that's on likes post/page
                   'compare' => '=', //exact match
                   'value' => get_the_ID() //Retrieve the ID of the current item/professor in the WordPress Loop.
                 )
               )

             ));
             
             $existStatus = 'no';
           
             if (is_user_logged_in()) {
               // when the current user is on the page above is for the total amount so the current user liked current professor to add data-exists="yes"
             $existQuery = new WP_Query( array(
              'author' => get_current_user_id(), //the author needs to equal what whatever user is currently viewing the page of the loop //Get the current user’s ID
              'post_type' => 'like',
              'meta_query' => array (
                array (
                  'key' => 'liked_professor_id',
                  'compare' => '=',
                  'value' => get_the_ID() //basically gives a token of one then $likeCount->found_posts would be one
                )
              )
            ));
           // if this is anything above 0 than it will be true 
            if ($existQuery->found_posts) {
              $existStatus = 'yes';
            }
             }              
                
// we need to use meta_query because we only want to pull in like posts where the liked professor ID value matches the id of the current professor page that you're viewing.
                
             ?>
             <span class="like-box" data-like="<?php echo $existQuery->posts[0]->ID; //this will contain a number that just so happens to be the iD for the relevant like post, this is the id number of the post that we want to delete ?>" 
             data-professor="<?php the_ID(); ?>" data-exists="<?php echo $existStatus ?>">
               <i class="fa fa-heart-o" aira-hidden="true"></i>
               <i class="fa fa-heart" aira-hidden="true"></i>
               <span class="like-count"><?php echo $likeCount->found_posts //this will give us the total number of posts the query found ?></span>
             </span>
           <?php the_content(); ?>

           </div>

         </div>
      </div>

      <?php 
        $relatedPrograms = get_field('related_programs');

        if ($relatedPrograms) {
        echo '<hr class="section-break">';
        echo '<h2 class="headline headline--medium">Subject(s) Taught</h2>';
        echo '<ul class="link-list min-list">';
        foreach($relatedPrograms as $program) { ?>
          <li><a href="<?php echo get_the_permalink($program); ?>"> <?php  echo get_the_title($program); ?></a></li>
       <?php  } 
       echo '</ul>';
        }
       ?>


  </div>


<?php }

get_footer();

?> 