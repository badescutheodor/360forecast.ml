<template>
    <div id="app">
        <overlay></overlay>
        <router-view></router-view>
    </div>
</template>

<script>
    import Overlay from './Other/Overlay.vue'

    export default {
        components: {
            overlay: Overlay
        }
    }
</script>