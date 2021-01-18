<?php 
//create new post type
//if therer is a assiative arrays with a the main assiative aray that means its a paraemeter 
// removed 'supports' => 'custom-fields'
function university_post_types() {

   // campus post type 
   register_post_type('campus' , array(
      'capability_type' => 'campus role',
      'map_meta_cap' => true,
      'supports' => array('title' , 'editor', 'excerpt'),
      'rewrite' => array('slug' => 'campuses'),
      'has_archive' => true,
      'public' => true,
      'labels' => array(
         'name' => 'Campuses', 
         'add_new_item' => 'Add New Campus',
         'edit_item' => 'Edit Campus',
         'all_items' => 'All Campuses',
         'singular_name' => 'Campus'


      ),
      #continued top level assiative array 
      'menu_icon' => 'dashicons-location-alt'
   ));
   // event post type 
    register_post_type('event' , array(
       'capability_type' => 'event',
       'map_meta_cap' => true,
       'supports' => array('title' , 'editor', 'excerpt'),
       'rewrite' => array('slug' => 'events'),
       'has_archive' => true,
       'public' => true,
       'labels' => array(
          'name' => 'Events', 
          'add_new_item' => 'Add New Event',
          'edit_item' => 'Edit Event',
          'all_items' => 'All Events',
          'singular_name' => 'Event!'


       ),
       #continued top level assiative array 
       'menu_icon' => 'dashicons-calendar'
    ));

    // program post type /////////////////////////////////////////////
    register_post_type('program' , array(
      'supports' => array('title'),
      'rewrite' => array('slug' => 'programs'),
      'has_archive' => true,
      'public' => true,
      //on wordpress front-end 
      'labels' => array(
         'name' => 'Programs', 
         'add_new_item' => 'Add New Program',
         'edit_item' => 'Edit Program',
         'all_items' => 'All Programs',
         'singular_name' => 'Program!'


      ),
      #continued top level assiative array 
      'menu_icon' => 'dashicons-awards'
   ));

  // professor post type /////////////////////////////////////////////
   register_post_type('professor' , array(
      'show_in_rest' => true,
      'supports' => array('title' , 'editor', 'thumbnail'),
      'public' => true,
      //on wordpress front-end 
      'labels' => array(
         'name' => 'Professors', 
         'add_new_item' => 'Add New Professor',
         'edit_item' => 'Edit Professor',
         'all_items' => 'All Professors',
         'singular_name' => 'Professor!'


      ),
      #continued top level assiative array 
      'menu_icon' => 'dashicons-welcome-learn-more'
   ));
   
   // Note post type /////////////////////////////////////////////
   register_post_type('note' , array(
      'capability_type' => 'note', //so by saying capability type equals something new and unique we are setting up brand new permissions that only apply to this post type
      'map_meta_cap' => true, //this line of code will actually enforce and require the permissions at the right time and at the right place cap stands for capability 
      'show_in_rest' => true, // do use in the wp rest api 
      'supports' => array('title' , 'editor'),
      'public' => false, // to be private so not in public queries or search results
      'show_ui' => true,  //show in the admin dashboard UI
      'labels' => array(
         'name' => 'Notes', 
         'add_new_item' => 'Add New note',
         'edit_item' => 'Edit note',
         'all_items' => 'All notes',
         'singular_name' => 'note!'


      ),
      #continued top level assiative array 
      'menu_icon' => 'dashicons-welcome-write-blog'
   ));

      // Like post type ///////////////////////
      register_post_type('like' , array(
        'supports' => array('title', 'editor'),
         'public' => false, // to be private so not in public queries or search results
         'show_ui' => true,  //show in the admin dashboard UI
         'labels' => array(
            'name' => 'Likes', 
            'add_new_item' => 'Add New Like',
            'edit_item' => 'Edit Like',
            'all_items' => 'All Likes',
            'singular_name' => 'Like!'
   
   
         ),
         #continued top level assiative array 
         'menu_icon' => 'dashicons-heart'
      ));

}

add_action('init', 'university_post_types');
?>


