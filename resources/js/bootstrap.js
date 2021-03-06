window._ = require('lodash');

/**
 * We'll load jQuery and the Bootstrap jQuery plugin which provides support
 * for JavaScript based Bootstrap features such as modals and tabs. This
 * code may be modified to fit the specific needs of your application.
 */

try {
  window.Popper = require('popper.js').default;
  window.$ = window.jQuery = require('jquery');

  require('bootstrap');
} catch (e) { }

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

window.axios = require('axios');

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

import store from '@/store';
import router from './router';
/**
 * Next we will register the CSRF Token as a common header with Axios so that
 * all outgoing HTTP requests automatically have it attached. This is just
 * a simple convenience so we don't have to attach every token manually.
 */

let token = document.head.querySelector('meta[name="csrf-token"]');

axios.interceptors.request.use(config => {
  config.baseURL = process.env.MIX_API_BASER_URL;
  config.headers.Authorization = `Bearer ${store.getters['user/token']}`;
  return config;
});

const UNAUTHORIZED = 401;
const NOT_FOUND_ERROR = 404;
const INTERNAL_SERVER_ERROR = 500;

axios.interceptors.response.use(
  response => response,
  error => {
    const { status } = error.response;
    if (status === UNAUTHORIZED) {
      store.dispatch('user/logout');
    } else if (status === NOT_FOUND_ERROR) {
      router.push('/404');
    } else if (status === INTERNAL_SERVER_ERROR) {
      router.push('/500');
    }
    return Promise.reject(error);
  }
);

if (token) {
  window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
  console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}