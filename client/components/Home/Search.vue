<style lang="scss">
    .search {
        float: right;
        margin-top: 52px;
    }

    .search button {
        background: transparent;
        border: 2px solid #fff;
        width: 45px;
        height: 45px;
        border-radius: 45px;

        span {
            font-size: 19px;
        }
    }

    .search-box #location {
        margin: 45px 0;
        z-index: 4;
        position: relative;
        background: none;
        border: none;
        color: #fff;
        font-size: 32px;
        width: 100%;
        margin-bottom: -82px;

        @media (max-width: 650px) {
            & {
                font-size: 26px;
            }
        }

        @media (max-width: 400px) {
            & {
                font-size: 18px;
            }
        }

        &:focus {
            outline: none;
        }

        &::placeholder {
            color: #fff;
        }
    }

    .pac-container {
        background: #fff !important;
        border-radius: 0 !important;
    }

    .pac-container:after {
        background-image: none !important;
        height: 0;
    }

    .pac-icon {
        display: none;
    }

    .pac-item {
        background: #fff;
        padding: 5px 0;
        border: none !important;
    }

    .pac-item-query {
        padding-left: 13px;
    }
</style>

<template>
    <div class="container search-box" v-show="isOpen">
        <div class="row">
            <div class="col-lg-12">
                <autocomplete type="text"
                              id="location"
                              v-focus
                              types="(cities)"
                              v-on:placechanged="onPlaceChanged"
                              @keypress="onKeyPress"
                              placeholder="Search for a location..." />
            </div>
        </div>
    </div>
</template>

<script>
    import VueGoogleAutocomplete from 'vue-google-autocomplete'
    import events from '../../events'
    import '../../directives/focus'
    import {
        EVENT_SHOW_SEARCH,
        EVENT_HIDE_SEARCH,
        SOCKET_ACTION_SEARCH
    } from '../../constants'

    export default {
        mounted() {
            events.$on(EVENT_SHOW_SEARCH, () => { this.showSearch(); });
            events.$on(EVENT_HIDE_SEARCH, () => { this.hideSearch(); });
        },

        data() {
            return {
                isOpen: false
            }
        },

        methods: {
            showSearch() {
                this.isOpen = true;
            },

            hideSearch() {
                this.isOpen = false;
            },

            onPlaceChanged(data) {
                if ( data.locality )
                    this.$root.socket.emit(SOCKET_ACTION_SEARCH, data.locality);
            },

            onKeyPress(e) {
                if ( e.which !== 13 )
                {
                    return;
                }

                let suggestions = document.querySelectorAll('.pac-item-query');

                if ( suggestions.length && suggestions[0].innerText)
                {
                    this.$root.socket.emit(SOCKET_ACTION_SEARCH, suggestions[0].innerText);
                    return;
                }

                this.$root.socket.emit(SOCKET_ACTION_SEARCH, e.target.value);
            }
        },

        components: {
            autocomplete: VueGoogleAutocomplete
        }
    }
</script>