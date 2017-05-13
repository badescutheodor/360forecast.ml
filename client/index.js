import Vue from 'vue'
import VueRouter from 'vue-router'
import ToggleButton from 'vue-js-toggle-button'

import { router } from './router'
import { socket } from './socket'
import { SOCKET_SET_IDENTIFICATION } from './constants'

import Root from './components/Root.vue'
import './styles/app.scss'

/**
 * Register the plugins
 */

Vue.use(VueRouter);
Vue.use(ToggleButton);

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
    mounted() {
        this.socket.on(SOCKET_SET_IDENTIFICATION, identity => {
            localStorage.setItem('identity', identity);
        });
    },
    render: (h) => h(Root)
}).$mount('#app');