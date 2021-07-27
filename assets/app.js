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

console.log('Hello Webpack Encore! Edit me in assets/app.js');


const snack = document.getElementById('snack');

//alert(1);   //finally is working :)


// try with aking user that he is sure about removing entry
if (snack) {
    snack.addEventListener('click', e => {
        if (e.target.className === 'remove') {
            if(confirm('Na pewno chcesz usunąć wpis?')) {
                const id = e.target.getAttribute('id');

                fetch(`/wpisy/delete/{id}`, {
                    method: 'DELETE'

                }).then(res => window.location.reload());
            }
        }
    });
}