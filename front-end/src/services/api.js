//* npm install axios
import axios from 'axios';

const apiJson = axios.create({
  baseURL: process.env.VUE_APP_API_BASE_URL,
  headers: {
    'Content-type': 'application/json'
  }
});

const apiMult = axios.create({
  baseURL: process.env.VUE_APP_API_BASE_URL,
  headers: {
    'Content-type': 'multipart/form-data'
  }
});

export { apiJson, apiMult };