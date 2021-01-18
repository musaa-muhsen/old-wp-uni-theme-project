import $ from 'jquery';

class MyNotes {
    constructor() {
       this.events(); //so do that the events are executed as soon as the page loads 
        
    }
// use .bind(this) otherwise js will modify the value of this and set it to equal whatever element go clicked on.
    events() {
     $("#my-notes").on('click', ".delete-note", this.deleteNote.bind(this) ); //so now what this line is saying is whenever you click anywhere within the parent unordered list which will always exist when the page first loads if the actualy interior element that you clicked on matches delete-notes then fire off our callback method  
     $("#my-notes").on('click', ".edit-note", this.editNote.bind(this) );
     $("#my-notes").on("click", ".update-note", this.updateNote.bind(this));
     $(".submit-note").on("click", this.createNote.bind(this));
    }
   /*
     when edit button is pressed on whatever post we are on hence thisNote the else 
     would be run because false so makenoteediable/edit then the value of data would be
     bb now the condition is true therefore next time the btn will run makenotereadonly 
     and set the data to cc so the condition goes back to false
   */
    editNote(e) {
        let thisNote = $(e.target).parents('li');
        if (thisNote.data("aa") == "bb") { // nothing special just made up, im checking for a data attribute with the parent list item element and im checking for a spefic value
            //makenotereadonly
           this.makeNoteReadOnly(thisNote)

        } else {
           this.makeNoteEditable(thisNote) 
           //makenoteeditable
        }
    }

    makeNoteEditable(thisNote) { // to edit  
        thisNote.find('.edit-note').html('<i class="fa fa-times" aria-hidden="true"></i> Cancel'); //make edit go cancel
        thisNote.find('.note-title-field, .note-body-field').removeAttr("readonly").addClass("note-active-field"); // for the whole border
        thisNote.find(".update-note").addClass('update-note--visible'); // to show the button 
        thisNote.data('aa', 'bb'); //set the second arguement to one which changes the the data value to two // attach data to div 
    }
     
    //To set an attribute and value //  syntax $(selector).attr(attribute,value) 
    makeNoteReadOnly(thisNote) { // to cancel 
        thisNote.find('.edit-note').html('<i class="fa fa-pencil" aria-hidden="true"></i> Edit'); //make cancel go back to edit remember front end is set to edit 
        thisNote.find('.note-title-field, .note-body-field').attr("readonly", "readonly").removeClass("note-active-field"); // back to readonly
        thisNote.find(".update-note").removeClass('update-note--visible'); // to button remove 
        thisNote.data('aa','cc');
    }


      
    //e to be able to look at the details taken from the click event
    //The parents() method returns all ancestor elements of the selected element. An ancestor is a parent, grandparent, great-grandparent, and so on.
    // e.target property returns which DOM element triggered the event, so we're looking for the li 
    // thisNote.data('id') is taken from <li data-id="<?php the_ID(); ?>">
    deleteNote(e) {
        let thisNote = $(e.target).parents("li");
        $.ajax({
          beforeSend: (xhrArgs) => {
            xhrArgs.setRequestHeader('X-WP-Nonce' , universityData.nonce);
          },
          url: universityData.root_url + '/wp-json/wp/v2/note/' + thisNote.data('id'),
          type: 'DELETE',
          success: (response) => {
              thisNote.slideUp();
              console.log("congrats");
              console.log(response);

          }, 
          error: (response) => {
              console.log("sorry");
              console.log(response)
          }
        });
        
    }


    updateNote(e) {
        let thisNote = $(e.target).parents("li");
        // an object
        const ourUpdatedPost = {
           'title' : thisNote.find(".note-title-field").val() , // getting the title 
         'content' : thisNote.find(".note-body-field").val()// getting the textarea 
        } 


        $.ajax({
          beforeSend: (xhrArgs) => {
            xhrArgs.setRequestHeader('X-WP-Nonce' , universityData.nonce);
          },
          url: universityData.root_url + '/wp-json/wp/v2/note/' + thisNote.data('id'),
          type: 'POST',
          data: ourUpdatedPost,
          success: (response) => {
              this.makeNoteReadOnly(thisNote);
              console.log("congrats");
              console.log(response);
          }, 
          error: (response) => {
              console.log("sorry");
              console.log(response)
          }
        });
        
    }

    createNote(e) {
        // construct an object
        const ourNewPost = {
         'title' : $('.new-note-title').val() , // getting the title 
         'content' : $(".new-note-body").val(),// getting the textarea 
          'status': 'publish' //by default it's set to draft 
        } 


        $.ajax({
          beforeSend: (xhrArgs) => {
            xhrArgs.setRequestHeader('X-WP-Nonce' , universityData.nonce);
          },
          url: universityData.root_url + '/wp-json/wp/v2/note/', //if you wanted sumbit a post/page/campus etc
          type: 'POST',
          data: ourNewPost,
          success: (response) => {
               $('.new-note-title, .new-note-body').val(''); // after successfuly reset fields again
               $(`
               <li data-id="${response.id}">
               <input readonly class="note-title-field" value="${response.title.raw}"> 
               <span class="edit-note"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</span>
               <span class="delete-note"><i class="fa fa-trash-o" aria-hidden="true"></i> Delete</span>
               <textarea readonly class="note-body-field">${response.content.raw}</textarea>
               <span class="update-note btn btn--blue btn--small"><i class="fa fa-arrow-right" aria-hidden="true"></i> Save</span>
            </li>             
               `).prependTo('#my-notes').hide().slideDown();
              console.log("congrats");
              console.log(response);
          }, 
          error: (response) => {
              console.log("sorry");
              console.log(response)
          }
        });
        
    }

} // end of class

/* const request = new XMLHttpRequest();
request.open('DELETE', `${localize.root_url}/wp-json/wp/v2/note/${id}`);
request.setRequestHeader('X-WP-Nonce', localize.nonce);
request.send();
*/

export default MyNotes;