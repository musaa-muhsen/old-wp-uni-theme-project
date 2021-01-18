<div class="event-summary">
          <a class="event-summary__date event-summary__date--beige t-center" href="<?php the_permalink(); ?>">
          <!-- ///////////////////// month span /////////////////// -->
            <span class="event-summary__month">
              <?php 
             // the_field('event_date'); 
                $eventDate = new DateTime(get_field('event_date'));
                echo $eventDate->format('M');
              ?> </span>
            <span class="event-summary__day">
            <?php echo $eventDate->format('d');
            #d = day M = month ?>
            </span>  
          </a>
          <div class="event-summary__content">
            <h5 class="event-summary__title headline headline--tiny"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h5>
            <p><?php if (has_excerpt()) {
              echo get_the_excerpt();
            } else {
               echo wp_trim_words(get_the_content(), 18);
            } ?> <a href="<?php the_permalink(); ?>" class="nu gray">Read more</a></p>
          </div>
        </div> 