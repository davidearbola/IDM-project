import 'bootstrap/dist/css/bootstrap.min.css';
import 'bootstrap';

import Toast from 'vue-toastification';
import 'vue-toastification/dist/index.css';

import './assets/main.css';

import { createApp } from 'vue'
import App from './App.vue'
import router from './router'
import axios from './axios'

const app = createApp(App);

app.use(Toast);
app.use(router);

app.mount('#app');
