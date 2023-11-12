//* npm install axios
import axios from 'axios';

const tokenJWT = 'Bearer ' + localStorage.getItem("token") || ''

const apiJson = axios.create({
  baseURL: process.env.VUE_APP_API_BASE_URL,
  headers: {
    'Authorization': tokenJWT,
    'Content-type': 'application/json'
  }
});

const apiMult = axios.create({
  baseURL: process.env.VUE_APP_API_BASE_URL,
  headers: {
    'Authorization': tokenJWT,
    'Content-type': 'multipart/form-data'
  }
});

export { apiJson, apiMult };