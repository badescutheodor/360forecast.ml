import Vue from 'vue'
import VueRouter from 'vue-router'

import { router } from './router'
import { socket } from './socket'

import Root from './components/Root.vue'
import './styles/app.scss'

/**
 * Register the plugins
 */

Vue.use(VueRouter);

/**
 * Initial data
 * @returns {{}}
 */

const data = () => {
    return {
        socket
    }
};

/**
 * The root component
 */

new Vue({
    router,
    data,
    render: (h) => h(Root)
}).$mount('#app');