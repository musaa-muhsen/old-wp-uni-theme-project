import $ from 'jquery';
//$ = jQuery.noConflict();


class Search {

    // 1. descitbe and create/initiate our object
    // faster if js looks at its own properties than constantly looking at the dom 
    //re usable variable 
    constructor() {
      this.addSearchHTML();
      this.resultsDiv = $("#search-overlay__results");

      this.openButton = $(".js-search-trigger");
      this.closeButton = $(".search-overlay__close");
      this.searchOverlay = $(".search-overlay");
      this.searchField = $("#search-term");

      // important that dom elements are all before the event function is invoked and any property 

      this.events();
      this.isOverlayOpen = false;
      this.isSpinnerVisible = false;
      this.typingTimer;
      this.previousValue;

    }

    // 2. events
    // on() = $(selector).on(event,childSelector,data,function,map)
    // bind() //bind an object to a function //reference it using 'this'
    // bind(this) is refering back to the constructor instead of the events
    // The bind() method creates a new function that, when called, has its this keyword set to the provided value, with a given sequence of arguments preceding any provided when the new function is called. 

     events() {
         this.openButton.on("click", this.openOverlay.bind(this));
         this.closeButton.on("click", this.closeOverlay.bind(this));
         $(document).on("keydown", this.keyPressDispatcher.bind(this));
         this.searchField.on("keyup", this.typingLogic.bind(this)); 
     }

    // 3. methods(function,action...) this.getResults.bind(this)

     typingLogic() {
          //adding clearTimeout(this.typingTimer); first will clear or set the timer firstly 
         // this.typingTimer refering to this ->typingTimer a property that lives within our constructor, this way we are giving this timeout a name we can access instead of floating in outer space so to speak
         // we dont want to send seven different requests to the wp server aka we dont want to create seven differnt timers 
         //getResults.bind(this) = so that we can access our object properties and methods (in the constructor) from within the getResults method. 
         //if value run the statement if there isnt value than the value of the if previousValue would be the same 
         if (this.searchField.val() != this.previousValue) {
            clearTimeout(this.typingTimer);
         
              if (this.searchField.val()) { //if there is value
               //when typing 
                 if (this.isSpinnerVisible === false) {
                   this.resultsDiv.html('<div class="spinner-loader"> </div>'); //resultsDiv 1 
                   this.isSpinnerVisible = true;
                  }
                 this.typingTimer = setTimeout(this.getResults.bind(this), 750);
                  } else {
                  // no loader if empty input 
                  this.resultsDiv.html('');
                this.isSpinnerVisible = false; 
                         }
        } // end of main if 
         //The val() method returns or sets the value attribute of the selected elements. j
               this.previousValue = this.searchField.val();
        } // end of method 

 // getResults() 
 //Loads JSON-encoded data from a server using a HTTP GET request
       // $(selector).getJSON(url,data,success(data,status,xhr))
       // so in our method lets just say dollar sign to begin using jquery and then period to look inside the query object 
       // no need for }.bind(this) if using arrow function because the arrow function does not change the value of this keyword, so when this code runs the will still be pointing towards our main object.  
       // attonery operator if no match meaning false meaning 0 meaning nothing //0 in the matches   
      //$ to use jquery 
      /* $.when(a, b).then(function (a-posts, b-pages) {
         excucate code
      });
      */

    getResults()  {
      //The html() method sets or returns the content (innerHTML) of the selected elements.
      // results is the name of the parameter that our function is using it contains all the json that the server or url is going to send back then im looking inside results for an array named general info etc 
        $.getJSON(universityData.root_url + '/wp-json/university/v1/search?term=' + this.searchField.val(),(results) => {
          this.resultsDiv.html(`
        <div class="row">
          <div class="one-third">
              <h2 class="search-overlay__section-title">General Information</h2>
                ${results.generalInfo.length ? '<ul class="link-list min-list">' : '<p>No general information matches that search</p>'}
                ${results.generalInfo.map(item => `<li><a href="${item.permalink}">${item.title}</a> ${item.postType == 'post' ? `by ${item.authorName}` : ''} </li>`).join('')}
                ${results.generalInfo.length ? '</ul>' : ''}
              </div>

          <div class="one-third">
              <h2 class="search-overlay__section-title">Programs</h2>
              ${results.programs.length ? '<ul class="link-list min-list">' : `<p>No programs match that search. <a href="${universityData.root_url}/programs">View all programs</a></p>`}
              ${results.programs.map(item => `<li><a href="${item.permalink}">${item.title}</a> </li>`).join('')}
              ${results.programs.length ? '</ul>' : ''}
              <h2 class="search-overlay__section-title">Professors</h2>
              ${results.professors.length ? '<ul class="professor-cards">' : `<p>No professors match that search.</p>`}
              ${results.professors.map(item => `
              <li class="professor-card__list-item">
              <a class="professor-card" href="${item.permalink}">
                 <img class="professor-card__image" src="${item.image}">
                 <span class="professor-card__name">${item.title}</span>
              </a>
           </li>   
              `).join('')}

              ${results.professors.length ? '</ul>' : ''}
              </div>
          
          <div class="one-third">
              <h2 class="search-overlay__section-title">Campuses</h2>
              ${results.campuses.length ? '<ul class="link-list min-list">' : `<p>No campuses matches that search. <a href="${universityData.root_url}/campuses"> View all campuses</a></p>`}
              ${results.campuses.map(item => `<li><a href="${item.permalink}">${item.title}</a> </li>`).join('')}
              ${results.campuses.length ? '</ul>' : ''}
              <h2 class="search-overlay__section-title">Events</h2>
              ${results.events.length ? '' : `<p>No events match that search. <a href="${universityData.root_url}/events"> View all events</a></p>`}
              ${results.events.map(item => `
               <div class="event-summary">
                <a class="event-summary__date event-summary__date--beige t-center" href="${item.permalink}">
                 <span class="event-summary__month">${item.month}</span>
                <span class="event-summary__day">${item.day}</span>  
              </a>
              <div class="event-summary__content">
                <h5 class="event-summary__title headline headline--tiny"><a href="${item.permalink}">${item.title}</a></h5>
               <p>${item.description}<a href="${item.permalink}" class="nu gray">Read more</a></p>
              </div>
            </div>    

              `).join('')}
            </div>
          </div>
            
          `);
          this.isSpinnerVisible = false // so if someone wants to perform another search as soon as they start typing they will see the spinner icon.         
        });
    }

     keyPressDispatcher(e) {       
        // so we only want to open the overlay if it's currently closed so we can just say if the key code that was pressed is 83 and isOverlayOpen is that's false aka if the overlay is not currently open.
        if (e.keyCode == 83 && this.isOverlayOpen == false && !$("input, textarea").is(":focus")) {  //is not a input field etc ////The is() method checks if one of the selected elements matches the selectorElement.             
               this.openOverlay();
           }
        if (e.keyCode === 27 && this.isOverlayOpen) {
            this.closeOverlay();
         }
     }

    //keycode is a js code 
    openOverlay() {
      this.searchOverlay.addClass("search-overlay--active");
      $('body').addClass("body-no-scroll"); // overflow hidden
      this.searchField.val(''); // empty the input when open
      setTimeout(() => this.searchField.focus(), 301); // make 
      //sets it to true
      this.isOverlayOpen = true;
      return false; // for non js 

    }
    
    closeOverlay() {
      this.searchOverlay.removeClass("search-overlay--active");
      $('body').removeClass("body-no-scroll");
      //sets it back to false
      this.isOverlayOpen = false;
    }
//The append() method inserts specified content at the end of the selected elements.
    addSearchHTML() {
       $("body").append(`
       <div class="search-overlay">
       <div class="search-overlay__top">
         <div class="container">
           <i class="fa fa-search search-overlay__icon" aira-hidden="true"></i>
           <input type="text" id="search-term" class="search-term" placeholder="What are you looking for?">
           <i class="fa fa-window-close search-overlay__close" aira-hidden="true"></i>
         </div>
       </div>
      
      <div class="container">
        <div id="search-overlay__results"></div>
      </div>
      </div>
       `);
    }

}

export default Search; 

