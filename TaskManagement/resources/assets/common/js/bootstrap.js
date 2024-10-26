import axios from "axios";
import * as Popper from "@popperjs/core";
import loadash from "lodash";
import jQuery from "jquery";


window._ = loadash;
window.Popper = Popper;
window.axios = axios;
window.axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";
window.axios.defaults.withCredentials = true;
Object.assign(window, { $: jQuery, jQuery });
window.jQuery = window.$ = $;


const token = document.head.querySelector('meta[name="csrf-token"]');
axios.defaults.withCredentials = true;
if (token) {
  axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

} else {
  console.error(
    "CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token"
  );
}


