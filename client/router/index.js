import VueRouter from 'vue-router'

import Home from '../components/Home/Home.vue'
import NotFound from '../components/Other/NotFound.vue'

/**
 * Application routes
 * @type {{}}
 */
const routes = [
    { path: '/', component: Home },
    { path: '*', component: NotFound }
];

/**
 * Application router
 * @type {{}}
 */
export const router = new VueRouter({
    routes,
    mode: 'history',
    base: ''
});