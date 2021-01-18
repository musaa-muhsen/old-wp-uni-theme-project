<?php 

if (!is_user_logged_in()) { // is user NOT logged in 
   wp_redirect(esc_url(site_url('/')));             // re direct to home page 
      exit;  // exit once it performs the redirect it can stop and rest and not use server resources 
}

get_header(); 

while(have_posts()) {
  the_post(); 
  pageBanner();
  ?>

  <div class="container container--narrow page-section">
      <div class="create-note">
          <h2 class="headline headline-medium">Create New Note</h2>
          <input class="new-note-title" classplaceholder="Title">
          <textarea class="new-note-body" placeholder="Your note here"></textarea>
          <span class="submit-note">Create Note</span>
          <span class="note-limit-message">Note limit reached: delete an existing note to make room for a new one</span>

      </div>
   
   <ul class="min-list link-list" id="my-notes">
      <!-- WP-Query is how we can ask the database for only the exact posts we're interested in. we use an array to say what we are looking for   -->
     <?php 
     $userNotes = new WP_Query( array(
       'post_type' => 'note',
       'posts_per_page' => -1, 
       'author' => get_current_user_id() //to make things user spefic      
     ));

     while($userNotes->have_posts()) {
         $userNotes->the_post(); ?>

            <li data-id="<?php the_ID(); ?>">
              <li></li>
               <input readonly class="note-title-field" value="<?php echo str_replace('Private: ','',esc_attr(get_the_title())); ?>"> 
               <span class="edit-note"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</span>
               <span class="delete-note"><i class="fa fa-trash-o" aria-hidden="true"></i> Delete</span>
               <textarea readonly class="note-body-field"><?php echo esc_attr(get_the_content()); ?></textarea>
               <span class="update-note btn btn--blue btn--small"><i class="fa fa-arrow-right" aria-hidden="true"></i> Save</span>
            </li>

   <?php  }
     ?>
   </ul>
  </div>

<?php }

get_footer();

?>