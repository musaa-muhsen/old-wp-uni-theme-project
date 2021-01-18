<?php get_header(); 
// the blog
   pageBanner(
     array (
       'title' => 'Search Results',
       'subtitle' => 'You searched for &ldquo;' . get_search_query() . '&rdquo;'
     ));
?>



  <div class="container container--narrow page-section">
     <?php 
         //if statement for error handling 
        if (have_posts()) {

          while(have_posts()) {
            the_post(); 
            
            get_template_part('template-parts/content', get_post_type()); //content-post for blog 
                        
            } 
           echo paginate_links();
        } else {
          echo '<h2 class="headline headline--small-plus">No results match that search.</h2>';

        }

        get_search_form();
     
     ?>
    </div>
<?php get_footer(); ?>