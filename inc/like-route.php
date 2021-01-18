<?php
//a route is basically just a rest api url. 
add_action('rest_api_init','universityLikeRoutes'); // add a new route or add a new field to our route
// register_rest_route(this can be anything,spefic route/url,c); 

function universityLikeRoutes() {
// a callback is just a function that we want to run when a request is sent to on these routes 
   register_rest_route('university/v1', 'manageLike', array(
     'methods' => 'POST',
     'callback' => 'createLike'  
   )); 

   register_rest_route('university/v1', 'manageLike', array(
    'methods' => 'DELETE',
    'callback' => 'deleteLike'   
  )); 
}

// whatever these functions return is the data that will be available to our javascript 
function createLike($data) {

    if (is_user_logged_in()) {
        //if logged in 
        $professor = sanitize_text_field($data['professorId']); // taken from the ajax data been wrapped in sanitizer 
        // this will let us create a post right within out php code 

        $existQuery = new WP_Query( array(
            'author' => get_current_user_id(), //the author needs to equal what whatever user is currently viewing the page of the loop //Get the current user’s ID
            'post_type' => 'like',
            'meta_query' => array (
              array (
                'key' => 'liked_professor_id', 
                'compare' => '=',
                'value' => $professor // ajax number results equal 
                //basically gives a token of one then $likeCount->found_posts would be one
              )
            )
          ));
      
//so if the current user has not already liked the requested professor then go ahead and create the like post else 
//like does not already exist aka no like still, if 0 run the true if condition  
        if ($doesQueryExist->found_posts == 0 AND get_post_type($professor) == 'professor') {
            // create new like post 
            return wp_insert_post( array(
                'post_type' =>'like',
                'post_status' => 'publish',
                'meta_input' => array (
                'liked_professor_id' => $professor // js data to send to wp  
                )
                
            )); 
        } else {
            die('Only logged in users can create a like.');
        } // end of WP_Query else if 

        } else {
            die("Invalid professor ID");
        } // end of is_user_logged_in() else if 
       

}

function deleteLike($data) {
    $likeId = sanitize_text_field($data['like']); //sanitize the data within the array and we want to match the name of the property that you're sending from your js.
    if (get_current_user_id() == get_post_field('post_author' , $likeId) AND get_post_type($likeId) == 'like') {
        wp_delete_post($likeId, true); // true you're skipping the trash step 
        // good practise after excuting above 
        return 'Congrats, like deleted.';
    } else {
        die("You do not have permission to delete that.");

    }
    
    // so this way a malicious user can't just go around deleting whatever post they feel like instead they can only delete something are ther one who created it.
    
    
}