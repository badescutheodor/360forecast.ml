<template>
    <div>
        <search></search>
        <div class="container"
             v-bind:class="{blurred: isBlurred}">
            <weather></weather>
            <forecast></forecast>
        </div>
    </div>
</template>

<script>
    import GoogleMaps from '@google/maps'
    import Search from './Search.vue'
    import Forecast from './Forecast.vue'
    import Weather from './Weather.vue'
    import events from '../../events'
    import {
        EVENT_SHOW_OVERLAY,
        EVENT_HIDE_SEARCH,
        EVENT_HIDE_OVERLAY,
        SOCKET_ACTION_SEARCH,
        EVENT_DATA_LOADED
    } from '../../constants'

    export default {
        mounted() {
            this.$root.socket.reconnect();
            events.$on(EVENT_SHOW_OVERLAY, () => { this.showBlur(); });
            events.$on(EVENT_HIDE_SEARCH, () => { this.hideBlur(); });
            this.bindSocket();
        },

        data() {
            return {
                isBlurred: false,
                data: false
            }
        },

        methods: {
            bindSocket() {
                let socket = this.$root.socket;
                socket.on(SOCKET_ACTION_SEARCH, (data) => {
                    this.data = data;
                    events.$emit(EVENT_DATA_LOADED, data);
                    events.$emit(EVENT_HIDE_OVERLAY);
                });
            },

            showBlur() {
                this.isBlurred = true;
            },

            hideBlur() {
                this.isBlurred = false;
            }
        },

        components: {
            search: Search,
            forecast: Forecast,
            weather: Weather
        }
    }
</script>