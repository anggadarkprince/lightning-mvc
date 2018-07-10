// import global variable
import variables from "./components/variables";

// jquery and bootstrap is main library of this app.
try {
    window.$ = window.jQuery = require('jquery');

    // setup ajax call for csrf token
    $.ajaxSetup({
        headers: {
            "X-CSRFToken": variables.csrfToken
        }
    });

    require('bootstrap');
} catch (e) {}

// import another libraries regarding templates and forms
// pointing to direct js in template
// another option is download library package independently and import as module

// import main global component for initialization and theme
require('./components/libs');

// import pages scripts (the function Modules automatically executed when imported)
// another option, include the script specific pages where they are needed for optimization
import Home from './pages/home';
Home.init();

// include sass (but extracted by webpack into separated css file)
import '../sass/app.scss';
