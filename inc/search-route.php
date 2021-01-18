<?php 
// syntax register_rest_route( string $namespace, string $route, array $args = array(), bool $override = false )
// 3 arguemnent 
// 1) name space university instead of wp which is wordpress core ipa  
// 2) route is the ending part of the trail it's the specif thing that you want to do 
// 3) this is where we simply supply an array describes what should when someone visits url // inside the array use the crud method 

// callback => 'uni..' = we want to set this to a function and then whatever this function returns is the json data that will be displayed.
add_action('rest_api_init', 'universityRegisterSearch');

function universityRegisterSearch () {
   register_rest_route(
       'university/v1',
       'search',
       array(
         'methods' => WP_REST_SERVER::READABLE, // aka get
         'callback' => 'universitySearchResults'
       )
   );
}

// while loop is going to add new item to this empty array, each time it runs, the new item is a associative array, so its an array within an array
// within the parenthesis of the WP_Query() methodyou just give it an array of options that describe which posts you're looking for. config a post 
// data for the 10 most recently created in this wp_query can be changed on the dashboard
//return $professors->posts; // all details , -> will look inside it

// 1) we've got this query that's going to contain results from different sorts of post types.
// 2) and then when we loop through it depending on the post type of the current result we will just push it into the appropriate empty array. 

function universitySearchResults($data) {
 $mainQuery = new WP_Query( array(
    'post_type' => array('post', 'page' , 'event', 'campus' , 'program', 'professor'), // post = blog, page = about   
    's' => sanitize_text_field($data['term']) //look inside of the array for a property called term and you will get the value //wrapped in sanitize_text_field which Sanitizes a string from user input or from the database. //s means search 
 ));

    $results = array(
        'generalInfo' => array(),
        'professors' => array(),
        'programs' => array(),
        'events' => array(),
        'campuses' => array()

    );
    // however many posts live within this collection is how many times this loop shoudl run or repeat itself. condition below 
    // the_post() this gets all the relevant data for the current post, ready and assesacbile
    // array push the first arguement is the array that you wanto add onto, second arg what you want to add 
    // below loop code
while ($mainQuery->have_posts()) {
    $mainQuery->the_post();

    if(get_post_type() == 'post' || get_post_type() == 'page') {
    array_push($results['generalInfo'], array(
        'title' => get_the_title(),
        'permalink' => get_the_permalink(),
        'postType' => get_post_type(),
        'authorName' => get_the_author()
    ));
    }

    if(get_post_type() == 'professor' ) {
   
        array_push($results['professors'], array(
            'title' => get_the_title(),
            'permalink' => get_the_permalink(),
            'image' => get_the_post_thumbnail_url(0, 'professorLandscape')
            // 2 arguements 1) 0 is wp code for saying that the current post 2) size of image look in php and dashboard
        ));

     }



        if(get_post_type() == 'program' ) {
            $relatedCampuses = get_field('related_campus');

            if ($relatedCampuses) {
              foreach($relatedCampuses as $campus) {
                  array_push($results['campuses'], array(
                     'title' => get_the_title($campus),
                     'permalink' => get_the_permalink($campus)
     
                  ));
     
              }
     
            }
            array_push($results['programs'], array(
                'title' => get_the_title(),
                'permalink' => get_the_permalink(),
                'id' => get_the_id()
            ));
            }
            // -> is how you look within an object in php 
            if(get_post_type() == 'event' ) {

                $eventDate = new DateTime(get_field('event_date'));
                
                $description;
                if (has_excerpt()) {
                  $description = get_the_excerpt();
                } else {
                  $description = wp_trim_words(get_the_content(), 18);
                }

                array_push($results['events'], array(
                    'title' => get_the_title(),
                    'permalink' => get_the_permalink(),
                    'month' => $eventDate->format('M'),
                    'day' => $eventDate->format('d'),
                    'description' => $description
                ));
                }
    
                if(get_post_type() == 'campus' ) {
                    array_push($results['campuses'], array(
                        'title' => get_the_title(),
                        'permalink' => get_the_permalink()
                    ));
                    }
}


// programs to find a relationship with =======================================
// array(array()) the reason we do this is because wp lets you string together multiple filters meaning you could have multiple inner arays each one being a filter and they're nested within the outer array. // Basically meta_query parameter of WP_Query allows you to search WordPress posts / pages / custom post types by their meta data and sort the result. // meta data means a set of data that describes and gives information about other data. // so this query is saying/was saying give us any prossors where one of the related programs is biology
// relation or pick one array not all relation and picks all // 'key' => 'related_programs' customfield name with the programs biology/math/english // 'compare' => 'LIKE', in this case we are looking for an exact match nor are we comparing actual numbers we are actually searching for numbers disguised as strings nested inside an array // 'value' => '87 is biology if only set to 87 than only proffersers with a custom field of biology would be shown // looking for value             
if ($results['programs']) {

    $programsMetaQuery = array('relation' => 'OR'); // to push into 

    foreach($results['programs'] as $item) {
        array_push( $programsMetaQuery, array(
          'key' => 'related_programs',
          'compare' => 'LIKE',
          'value' => '"' . $item['id'] . '"'
    
        ));
    }

    $programRelationshipQuery = new WP_Query( array(
        'post_type' => array('professor', 'event'),
        'meta_query' =>  $programsMetaQuery // the outcome of for how many ids come from the array that the foreach has dealt with 
    ));
    
    while ($programRelationshipQuery->have_posts()) {
        $programRelationshipQuery->the_post();

        if(get_post_type() == 'event' ) {

            $eventDate = new DateTime(get_field('event_date'));
            
            $description;
            if (has_excerpt()) {
              $description = get_the_excerpt();
            } else {
              $description = wp_trim_words(get_the_content(), 18);
            }

            array_push($results['events'], array(
                'title' => get_the_title(),
                'permalink' => get_the_permalink(),
                'month' => $eventDate->format('M'),
                'day' => $eventDate->format('d'),
                'description' => $description
            ));
            }
    
    
    
        if(get_post_type() == 'professor' ) {
            array_push($results['professors'], array(
                'title' => get_the_title(),
                'permalink' => get_the_permalink(),
                'image' => get_the_post_thumbnail_url(0, 'professorLandscape')
                // 2 arguements 1) 0 is wp code for saying that the current post 2) size of image look in php and dashboard
            ));
            } 
    }
    
    //remove dublicates 
    //The array_unique() function removes duplicate values from an array. If two or more array values are the same, the first appearance will be kept and the other will be removed. 
    //Syntax array_unique(Specifying an array, sorttype)
    //array_values() Return all the values of an array (not the keys) just removes the numbers infront 
    
    
    $results['professors'] = array_values(array_unique($results['professors'], SORT_REGULAR));
    $results['events'] = array_values(array_unique($results['events'], SORT_REGULAR));

} // end of if 



return $results;

}

