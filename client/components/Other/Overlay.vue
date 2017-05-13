<style lang="scss">
    .overlay.display {
        height: 100%;
        width: 100%;
        background: rgba(99, 99, 99, 0.69);
        position: absolute;
        z-index: 2;
        transition: .3s;
    }
</style>

<template>
    <div class="overlay"
         @click="hideOverlay"
         v-bind:class="{display: isOpen}">
    </div>
</template>

<script>
    import events from '../../events'
    import {
        EVENT_SHOW_OVERLAY,
        EVENT_HIDE_OVERLAY,
        EVENT_HIDE_SEARCH,
        EVENT_HIDE_SETTINGS
    } from '../../constants'

    export default {
        mounted() {
            events.$on(EVENT_SHOW_OVERLAY, () => { this.showOverlay() });
            events.$on(EVENT_HIDE_OVERLAY, () => { this.hideOverlay() });
        },

        data() {
            return {
                isOpen: false
            }
        },

        methods: {
            showOverlay() {
                this.isOpen = true;
            },

            hideOverlay() {
                events.$emit(EVENT_HIDE_SEARCH);
                events.$emit(EVENT_HIDE_SETTINGS);
                this.isOpen = false;
            }
        }
    }
</script>