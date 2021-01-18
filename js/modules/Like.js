import $ from 'jquery';

// this is deciding to fire off below 

class Like {
    //overall class or object
    constructor() {
      this.events(); // that our events gets added soon as the page loads

    }

    events() {
        $('.like-box').on('click', this.ourClickDispatcher.bind(this))

    }
    //add a like or remove a like // e = our event handler passes along information on which element got clicked on.
    ourClickDispatcher(e) {
         //if the data attribute has a value of yes  
         const currentLikeBox = $(e.target).closest(".like-box");

         if (currentLikeBox.attr('data-exists') == 'yes') {
           this.deleteLike(currentLikeBox);
        } else {
            this.createLike(currentLikeBox);
        }


    }
    
    createLike(currentLikeBox) {
        //$.ajax({name:value, name:value, ... })
        //html() basically innerHTML
       $.ajax({
        beforeSend: (xhrArgs) => {
            xhrArgs.setRequestHeader('X-WP-Nonce' , universityData.nonce);
          },
           url: universityData.root_url + '/wp-json/university/v1/manageLike',
           type: 'POST',
           data: {'professorId': currentLikeBox.data('professor')},
           success: (response) => {
               currentLikeBox.attr('data-exists', 'yes');// fill in the heart 
               var likeCount = parseInt( currentLikeBox.find('.like-count').html(),10); // change the object string to number 
               likeCount++; //num by one 
               currentLikeBox.find(".like-count").html(likeCount);  
               currentLikeBox.attr('data-like', response);
               console.log('success post');
               console.log(response);

           }, 
           error: (response) => {
            console.log('error post');
            console.log(response);
           }
       });
    }

    deleteLike(currentLikeBox) {
        $.ajax({
            beforeSend: (xhrArgs) => {
                xhrArgs.setRequestHeader('X-WP-Nonce' , universityData.nonce);
              },
            url: universityData.root_url + '/wp-json/university/v1/manageLike',
            data: {'like': currentLikeBox.attr('data-like')},
            type: 'DELETE',
            success: (response) => {
                currentLikeBox.attr('data-exists', 'no');
                var likeCount = parseInt( currentLikeBox.find('.like-count').html(),10); // change the object string to number 
                likeCount--; //minus one 
                currentLikeBox.find(".like-count").html(likeCount);  
                currentLikeBox.attr('data-like', '');
 
            }, 
            error: (response) => {
             console.log('error delete');
             console.log(response);
            }
        });       
    }


}

export default Like;