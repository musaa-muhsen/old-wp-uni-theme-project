<?php 
// about us 
get_header(); 

while(have_posts()) {
  the_post(); 
  pageBanner();
  /*
  bespoke function 
  pageBanner( array (   
     'title' => 'Hello there this is the title',
     'subtitle' => 'subtitle content'
     'subtitle' => 'Hi, this is the subtitle',
  ));*/
  ?>

  <div class="container container--narrow page-section">
   
  <?php 
    #about us is 0 so if 0 then the condition is false therefore the breadcrumb would be hidden
     // 0 is false any number is true 
    $theParentID = wp_get_post_parent_id(get_the_ID());

  // below just means: if (1223) { }
  //get_permalinks is linking to the parent (about us etc) and get_the_title is to get the parent page name 
  if ($theParentID) { ?>
  
    <div class="metabox metabox--position-up metabox--with-home-link">
      <p><a class="metabox__blog-home-link" href="<?php echo get_permalink($theParentID); ?>"><i class="fa fa-home" aria-hidden="true">
      </i> Back to <?php echo get_the_title($theParentID); ?></a> 
      <span class="metabox__main"><?php the_title(); ?></span></p>
    </div>
  <?php } ?>

    <!-- /* side bar menu 
    Now if the current page has children ('child_of' => get_the_ID()) this will return a collection of any and all children pages. 
    On the other hand if the current page doesn't have any children this function won't return anything.
*/ -->
<?php 
$ParentPageDoesHaveChild = get_pages(array(
  'child_of' => get_the_ID()
));

if ($theParentID || $ParentPageDoesHaveChild) { ?> 
     <div class="page-links">
      <h2 class="page-links__title">
          <!-- This will work really nicely with this get_the_title function because if this returns zero which means the current page is a parent page does get_the_title function interprets as 0 as meaning the current page. 
          so 0 will means false which means the page itself  
          so if get_the_title is empty it will return the page its on and because im echoing it thats why it's showing
           -->
          <a href="<?php echo get_permalink($theParentID); ?>"><?php echo get_the_title($theParentID); ?></a></h2>
      <ul class="min-list">
        <?php 
        #ul above
         # wp_get_post_parent_id(get_the_ID()) == so only if the current page has a parent do something
         # important reminder = wp_get_post_parent_id Returns (int|false) Post parent ID, otherwise false. Also if the condition is primitive data type is 0 that means its false, if its any number is true, furtheremore, if you are viewing a parent page the value would be false.
         # The left operand gets set to the value of the expression on the right	
         
         if (wp_get_post_parent_id(get_the_ID())) {
             # this part if got the children so need to find out the parent id 
             $findChildrenOf = wp_get_post_parent_id(get_the_ID());
         } else {
             # when on a parent page 
             $findChildrenOf = get_the_ID();
         }
    #so when on parent the child_of will work 
           wp_list_pages(
               array(
                   'title_li' => NULL,
                   'child_of' => $findChildrenOf,
                   'sort_column' => 'menu_order'
               )
           )
        ?>
      </ul>
    </div>
              <?php } ?>

    <div class="generic-content">
        <!-- site url Retrieves the URL for the current site where WordPress application files (e.g. wp-blog-header.php or the wp-admin/ folder) are accessible. -->
        <?php get_search_form(); ?>
    </div>

  </div>

<?php }

get_footer();

?>