<?php 
require get_theme_file_path('/inc/like-route.php');
require get_theme_file_path('/inc/search-route.php');
//register_rest_field( string|array $object_type, string $attribute, array $args = array() )
//Registers a new field on an existing WordPress object type.

// 1)$object_type //(string|array) (Required) Object(s) the field is being registered to, "post"|"term"|"comment" etc.
// 2) the name for the json to read 
// 3) value to return, the content in an array that has 3 options get_callback is a callback that a returned value is the value to return 
// get_callback will return a value to author_name to able to be read in the rest api 
   function uniCustomRest() {
     register_rest_field('post' , 'author_name' , array (
        'get_callback' => function() { return get_the_author();}
     ));
  //    register_rest_field('note' , 'userNoteCount' , array (
  //     'get_callback' => function() { return count_user_posts(get_current_user_id(),'note');} 
  //     //count_user_posts( int $userid, array|string $post_type = 'note', bool $public_only = false )
  //  ));
     // you can create as many register_rest_field() {} you want
   }

   add_action('rest_api_init', 'uniCustomRest');

# if nothing is passed the defualt value is null //null = no value 
//$args is an array when inside the function 
function pageBanner($args = NULL) {

    // default/fallback values
    //check if theres isn't a title, subtitle, img banner content inside other php files, if there is content below will excuate 
    //php logic can also be inside the actual template below as a if else statement
    if (!$args['title']) {
        $args['title'] = get_the_title();  
    }

    if (!$args['subtitle']) {
        $args['subtitle'] = get_field('page_banner_subtitle_');
    }

    if (!$args['photo']) {
        if (get_field('page_banner_background_image_')) {
          //good 
          //default page banner if not spefic photo is added 
          $args['photo'] = get_field('page_banner_background_image_')['sizes']['pageBanner'];
        } else {
          $args['photo'] = get_theme_file_uri('/images/ocean.jpg');
         //bad last line of defence getting image inside the theme file 
        }  
    }
    // if there is content do below and output the content, almost filtering through 
    if ($args) 
    //dont forget the way we get the bespoke page banner img and title is through the get_fields function and customfields on the backend
    //custom fields is goinng to return an array to work with for the banner so treat the variable as an array
    //and remember page banners subtitle and imgs are done through custom fields and only goes to the defualt depending on what has been done on each each page.php
   ?>
    <div class="page-banner">
    <div class="page-banner__bg-image" style="background-image: url( <?php
       echo $args['photo'] ?> );"></div>
    <div class="page-banner__content container container--narrow">
      <h1 class="page-banner__title"><?php echo $args['title'] ?></h1>
      <div class="page-banner__intro">
        <p><?php echo $args['subtitle']; ?></p>
      </div>
    </div>  
  </div>
<?php } // end of function 


function university_files() {
  wp_enqueue_script('googleMap','//maps.googleapis.com/maps/api/js?key=AIzaSyDPhz4-Lv0-qCoDFZ-SD32jVYADTxzJHVw' , NULL, '1.0', true); # to slashes are used instead of http or https(secure) so there will be no erros 

    wp_enqueue_script('main-university-js', get_theme_file_uri('/js/scripts-bundled.js'), NULL, microtime(), true); #true means at the bottom of the script, 1.0 just means the version, NULL there any dependencies 
    wp_enqueue_style('custom-google-fonts','//fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i" rel="stylesheet' );
    wp_enqueue_style('font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
    wp_enqueue_style('university_main_styles', get_stylesheet_uri(), NULL, microtime());
    //so this is a wordpress function that will let us output a little bit of javascript data into the HTML source of web page, within the parentheses this function takes three arguements
    //first arguement we want to include the name or handfle of our main js file that you want to make flexible the main handle here is main-university-js
    //second arguement we just want to make up a variable name for the html 
    //third argument we just create an array of data that we want to be in javascript 
    //get_site_url() will return the URL for the current wp installation 
    wp_localize_script('main-university-js', 'universityData' , array(
      'root_url' => get_site_url(),  // to generate out URL
      'nonce' => wp_create_nonce('wp_rest')
    ));
}

add_action('wp_enqueue_scripts', 'university_files');

function university_features() {
    #adding dynamic menus 
    // register_nav_menu('headerMenuLocation' , 'Header Menu Dashboard');
    // register_nav_menu('footerLocationOne' , 'Footer One Dashboard');   
    // register_nav_menu('footerLocationTwo' , 'Footer Two Dashboard');   

    add_theme_support('title-tag');
    add_theme_support('post-thumbnails'); //aka featured images 
    add_image_size('professorLandscape', 400, 260, true); // (width,height,true) true if you wanted it cropped
    add_image_size('professorPortrait', 350, 500, true);
    add_image_size('pageBanner', 1500, 350, true);
}

add_action('after_setup_theme', 'university_features');

#pass in the pre_get_posts hook object as $query
function universityAdjustQueries($query) {

  if (!is_admin() && is_post_type_archive('campus') && $query->is_main_query()) {
    $query->set('posts_per_page', -1); #just to make sure there is no limit to the archive-campus.php map amount
}
    

    if (!is_admin() && is_post_type_archive('program') && $query->is_main_query()) {
         $query->set('orderby','title'); 
         $query->set('order','DESC'); #ascending means increasing in size or importance from the top 
         $query->set('posts_per_page', -1);
     }


     if (!is_admin() AND is_post_type_archive('event') && $query->is_main_query()) {
      $query->set('posts_per_page', 3);

        $today = date('Ymd');         #taken from front-page.php
         $query->set('meta_key', 'event_date');
         $query->set('orderby','meta_value_num'); // numeric meta value 
         $query->set('order','ASC'); #ascending means increasing in size or importance from the top 
         #bottom to get rid of old dates 
         $query->set('meta_query' , array(
            array(
                'key' => 'event_date',
                'compare' => '>=',
                'value' => $today,
                'type' => 'numeric'
              )
         )); 
     }



}

add_action('pre_get_posts' , 'universityAdjustQueries');
/*
1) wp event we want to hook onto is pre_get_posts = right before wp sends it query off the database it will give our function the last word, it will give us a chance to adjust the query, hence the name of the even pre get posts right before we get the posts with the query.
now the cool part here is when wp call our function (function above it) its going to give it a reference to the wp query object. So when we are defining the function within the parentheses, add a parameter named $query so this way when wp call out function 
along the wp query object we can now manipulate that object within the body of out function.     
codex = Fires after the query variable object is created, but before the actual query is run.
2) is_main_query() = Determines whether the query is the main query.  used in conditional to check if the condition is NOT a custom query ie a secondary query loop within a loop in this example its the event loop, which is a main loop 
3) to make sure the url is the archives events you can use many conditional checks to make it robust 
4) Set method, syntax for WP_Query/$this->set( string $query_var, mixed $value ). Furtheremore,  new WP_Query('posts_per_page' => 1) same as in the function $query->set('posts_per_page', '1');
5) 

*/

//below for api keys the custom fields plugin gave us this piece of data to wok with we manipulated a property of it and then we returned it right back. 
function universityMapKey($api) {
   $api['key'] = '';
   return $api;
}

add_filter('acf/fields/google_map/api', 'universityMapKey');

// redirect subscriber account out of admin and onto homepage 
// wp function that we want to hook up on to 
// $ourCurrentUser is an object which contains arrays, ->roles will enable us to see all the different roles for the user 
add_action('admin_init', 'redirectSubsToFrontend');

function redirectSubsToFrontend() {
  $ourCurrentUser = wp_get_current_user();

  if (count($ourCurrentUser->roles) == 1 AND $ourCurrentUser->roles[0] == 'subscriber') {
     wp_redirect(site_url('/')); // re direct some
     exit;

  }
}

//no dashboard on top
add_action('wp_loaded', 'noAdminBar');

function noAdminBar() {
  $ourCurrentUser = wp_get_current_user();

  if (count($ourCurrentUser->roles) == 1 AND $ourCurrentUser->roles[0] == 'subscriber') {
    show_admin_bar(false);
  }
}

//customize login screen logo?
add_filter('login_headerurl', 'ourHeaderUrl');

function ourHeaderUrl() {
  return esc_url(site_url('/'));
}

// changing login style
add_action('login_enqueue_scripts', 'ourLoginCSS');

function ourLoginCSS() {
  wp_enqueue_style('university_main_styles', get_stylesheet_uri(), NULL, microtime());
  wp_enqueue_style('custom-google-fonts','//fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i" rel="stylesheet' );
}
// just change name link? least important 
add_filter('login_headertitle' , 'ourLoginTitle');

function ourLoginTitle() {
  return get_bloginfo('name');
}


// Force note posts to be private
add_filter('wp_insert_post_data', 'makeNotePrivate', 10, 2);

function makeNotePrivate($data, $postarr) {
  if ($data['post_type'] == 'note') {
    if(count_user_posts(get_current_user_id(), 'note') > 4 AND !$postarr['ID']) {
      die("yo, You have reached your note limit.");
    }

    $data['post_content'] = sanitize_textarea_field($data['post_content']);
    $data['post_title'] = sanitize_text_field($data['post_title']);
  }

  if($data['post_type'] == 'note' AND $data['post_status'] != 'trash') {
    $data['post_status'] = "private";
  }
  
  return $data;
}


/*
// allows us to intercept requests right before/update data gets saved into the wp database. also when update post
add_filter('wp_insert_post_data' , 'makeNotePrivate', 10 , 2); // 10 means priority of 10 and 2 is how many para padding through
// above has higher prioperty because the number is lower add_filter('wp_insert_post_data' , 'makeNotePrivate', 99, 1);  
function makeNotePrivate ($murkywater, $postarr) {
  if ($murkywater['post_type'] == 'note') {
     if(count_user_posts(get_current_user_id(), 'note') > 4 AND !$postarr['ID'])
      {
        die("You have reached your note limit.");
    }   
      $murkywater['post_title'] = sanitize_text_field($murkywater['post_content']); 
      $murkywater['post_content'] = sanitize_textarea_field($murkywater['post_content']); 
  }

  // force note posts to be private 

  if ($murkywater['post_type'] == 'note' AND $murkywater['post_status'] != 'trash') {
    $murkywater['post_status'] = "private";
  }
  return $murkywater; //this is the water exiting our filter and the water in this context is all of the data about the post that's about to be saved into the database but before that the data needs to maniplutated 
}
*/
?>



