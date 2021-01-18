<?php get_header();
// old events pages
pageBanner(
  array(
    'title' =>  'Past Events',
    'subtitle' => 'A recap of our past events.'
  )
);
  
?>

  <div class="container container--narrow page-section">
     <?php 

    #same as return format on custom fields 
    $today = date('Ymd');
    $pastEvents = new WP_Query( array(
        //paged value enables us to get the number that is up in the url bar, if we see the number two at the end of the URL, thats the number we want to use in our query, 
       'paged' => get_query_var('paged', 1) , // this is saying go out and get that number at the end of the page url and if there isnt one to get the probably just means we're on the first page of results so therefore use number one. 
       #syntax get_query_var(string $var required, optional mixed)
      'posts_per_page' => 3,
      'post_type' => 'event', 
      'meta_key' => 'event_date',
      'orderby' => 'meta_value_num',
      'order' => 'DESC',
      #filters the old events 
      'meta_query' => array(
        array(
            #less than todays date
          'key' => 'event_date', #refering to the plugin 
          'compare' => '<=',
          'value' => $today,
          'type' => 'numeric'
        )
      )

    ));

      # the loop code without the past events object query wont work 
  
      while ($pastEvents->have_posts()) { 
         
     $pastEvents->the_post(); 
     
     get_template_part('template-parts/content-event');
        } 
    echo paginate_links( array (
        #The total amount of pages (configured above). Default is the value WP_Query's max_num_pages or 1
        'total' => $pastEvents->max_num_pages
        //

    ));
    ?>
</div>
<?php get_footer(); ?>