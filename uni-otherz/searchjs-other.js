/*
javascript solutions 
1) wrapped into an IIFE.

(function () {
    // Selectors
    const searchButton = document.querySelector(".search-trigger");
    const closeButton = document.querySelector(".search-overlay__close");
    const searchOverlay = document.querySelector(".search-overlay");
    
    // Actions
    const events = {
        openOverlay() {
            searchOverlay.classList.add("search-overlay--active");
        },
        closeOverlay() {
            searchOverlay.classList.remove("search-overlay--active");
        }
    }
    
    // Events
    searchButton.addEventListener('click', events.openOverlay)
    closeButton.addEventListener('click', events.closeOverlay)
})();


I also put it into a file named search.js and linked it to the page with

  //FILES
function university_files() {
    wp_enqueue_script('search-js', get_theme_file_uri('/js/search.js'), NULL, microtime(), true);

2) 2nd way 

// Selectors
const searchButton = document.querySelector(".search-trigger");
const closeButton = document.querySelector(".search-overlay__close");
const searchOverlay = document.querySelector(".search-overlay");
 
// Actions
const events = {
  openOverlay() {
    searchOverlay.classList.add("search-overlay--active");
  },
  closeOverlay() {
    searchOverlay.classList.remove("search-overlay--active");
  }
}
 
// Events
searchButton.addEventListener('click', events.openOverlay)
closeButton.addEventListener('click', events.closeOverlay)

////////////////////////////////////////////////////////////////// 

So the key to understand the this keyword here is, everytime a function runs it will generate a so called execution context which will, by default, assign this to itself. Unless we specify otherwise with .bind(this) or .call(this).

Because we are working with an object and we're jumping from one function to another we need to ensure that the context remains constant all around by binding it.

Another way around this is to use arrow functions which is just a different syntax in JavaScript and has the advantage of not binding the context of this to itself. This is how I wrote it:

this.openBtn.addEventListener('click', () => this.openOverlay());
this.closeBtn.addEventListener('click', () => this.closeOverlay());
On click, the function openOverlay which lives within this object is called (notice I did use the parenthesis). The context doesn't get redefined and is passed onto the function called so I don't need to bind it


*/


function myFunction() {
    const name1 = ['tom', 'andy'];
    const name2 = ['emma','jane','sam'];
    const name3 = ['scott', 'lyle'];
    const allNames = name1.concat(name2, name3)
    return allNames;
}

document.getElementById('demo').innerHTML = myFunction();

/*

Promise.all syntax.

Each fetch call is a Promise object and you should store each in a variable.

const postsRequest = fetch(...);
const pagesRequest = fetch(...);
Then, you can use Promise.all (which is itself a Promise) to wait for all Promises you provide it (in an array) to complete. That Promise's return value is an array of the input Promises' return values (concatenated).

const results = await Promise.all([postsRequest, pagesRequest]);

*/

/*
getResults() {
            fetchURLs();
            async function fetchURLs() {
                try {
                  let data = await Promise.all([
                    fetch(`${universityData.root_url}/wp-json/wp/v2/posts/?search=${searchField.value}`).then((response) => response.json()),
                    fetch(`${universityData.root_url}/wp-json/wp/v2/pages/?search=${searchField.value}`).then((response) => response.json())
                  ]);
                  
                  resultsDiv.innerHTML = `
                    <h2 class="search-overlay__section-title">General Information</h2>
                    <ul class="link-list min-list">
                        ${!data[0].length && !data[1].length ? `<p>No General Information Matches That Search<p>` :
                        `${data[0].map(arr => `<li><a href="${arr.link}">${arr.title.rendered}</a></li>`).join(''). concat(data[1].map(arr => `<li><a href="${arr.link}">${arr.title.rendered}</a></li>`).join(''))}    
                    </ul> `}     
                `;
                } catch (error) {
                    resultsDiv.innerHTML = `<p>There was a problem with the search.<p>`;
                }
                isSpinnerVisable = false;
              }
        }
*/

/*

$.get( "test.php" ).then(
  function() {
    alert( "$.get succeeded" );
  }, function() {
    alert( "$.get failed!" );
  }
);

*/

// when is working with two get json requests and is running together at the same time asynchronously and then only once they both complete, then run the code.
$.when(
  $.getJSON(universityData.root_url + '/wp-json/wp/v2/posts?search=' + this.searchField.val()),
  $.getJSON(universityData.root_url + '/wp-json/wp/v2/pages?search=' + this.searchField.val())
).then((posts, pages) => {
  const combinedResults = posts[0].concat(pages[0]);
  this.resultsDiv.html(`
  <h2 class="search-overlay__section-title"> General Information</h2>
    ${combinedResults.length ? '<ul class="link-list min-list">' : '<p>No general information matches that search</p>'}
    ${combinedResults.map(item => `<li><a href="${item.link}">${item.title.rendered}</a> ${item.type == 'post' ? `by ${item.author_name}` : ''} </li>`).join('')}
    ${combinedResults.length ? '</ul>' : ''}
   `);
   isSpinnerVisible = false; //this is going to hide the spinner wants it's ready then put the spinner back to false // as soon as the above code runs this will replace or hide the spinner
}, () => {
  this.resultsDiv.html('<p>Unxpected error, please try again.</p>'); // need this. because doesn't know where to point.
});
//<li><a href="${posts[0].link}">${posts[0].title.rendered}</a></li> old one 
  // this.resultsDiv.html("Imagine real search results here..."); //resultsDiv 2  