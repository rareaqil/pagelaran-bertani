import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
// import jquery and select2
import $ from 'jquery';
import select2 from 'select2';
window.$ = $; // <-- jquery must be set
select2(); // <-- select2 must be called
