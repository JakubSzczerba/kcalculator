/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.css';

// start the Stimulus application
import './bootstrap';


import $ from 'jquery';
import askOfRemoveEntry from './js/askOfRemoveEntry';

//$(askOfRemoveEntry);  // jquery smiga

/*  Functions for views  */

$("#changeBGC").click(function(){
    $("body").css("background-color","black");
  })

  $(document).ready(function(){
    $("button").click(function(){
      $("#searchingField").load("searchproducts.html.twig",  function(responseTxt, statusTxt, xhr){
        if(statusTxt == "success")
          alert("External content loaded successfully!");
        if(statusTxt == "error")
          alert("Error: " + xhr.status + ": " + xhr.statusText);
      });
    });
  });


