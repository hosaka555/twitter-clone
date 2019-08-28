import axios from 'axios';

export const http = {
  request: (method, url, data, successCB = null, errorCB = null, afterCB = null) => {
    axios.request({ url, data, method: method.toLowerCase() }).then(successCB).catch(errorCB).then(afterCB);
  },

  get(url, successCB = null, errorCB = null, afterCB = null) {
    return this.request('get', url, {}, successCB, errorCB, afterCB);
  },

  post(url, data, successCB = null, errorCB = null, afterCB = null) {
    return this.request('post', url, data, successCB, errorCB, afterCB);
  },
};


export const async = {
  request: async(method, url, data, successCB = null, errorCB = null, afterCB = null) => {
    await axios.request({ url, data, method: method.toLowerCase() }).then(successCB).catch(errorCB).then(afterCB);
  },

  get(url, successCB = null, errorCB = null, afterCB = null) {
    return this.request('get', url, {}, successCB, errorCB, afterCB);
  },

  post(url, data, successCB = null, errorCB = null, afterCB = null) {
    return this.request('post', url, data, successCB, errorCB, afterCB);
  },
};