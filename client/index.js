import Vue from 'vue'
import VueRouter from 'vue-router'
import ToggleButton from 'vue-js-toggle-button'

import { router } from './router'
import { socket } from './socket'
import events from './events'
import {
    SOCKET_SET_IDENTIFICATION,
    SOCKET_SET_SETTINGS
} from './constants'

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
        socket,
        settings: false
    }
};

/**
 * The root component
 */

new Vue({
    router,
    data,
    mounted() {
        this.bindSocket();
    },
    methods: {
        bindSocket() {
            this.socket.on(SOCKET_SET_IDENTIFICATION, identity => {
                localStorage.setItem('identity', identity);
            });

            this.socket.on(SOCKET_SET_SETTINGS, settings => {
                this.settings = settings;
                events.$emit(SOCKET_SET_SETTINGS, settings);
            });
        }
    },
    render: (h) => h(Root)
}).$mount('#app');