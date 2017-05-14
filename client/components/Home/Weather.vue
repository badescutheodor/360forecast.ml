<style lang="scss">
    .data {
        margin-top: 23px;

        .icon {
            font-size: 64px;
            margin-top: 19px;
            position: relative;
            bottom: 10px;
        }

        .info {
            padding-left: 11px;
            display: inline-block;
        }

        .weather-icon {
            display: inline-block;
        }

        h1 {
            margin-bottom: 5px;
        }

        @media only screen and (max-width : 480px) {
            h1 {
                font-size: 26px;
            }

            p {
                font-size: 14px;
            }

            .icon {
                font-size: 60px;
                margin-top: 16px;
            }
        }

        @media only screen and (max-width : 365px) {
            .icon {
                font-size: 32px;
            }
        }

        p {
            font-size: 21px;
            font-family: "Helvetica Neue", Arial;
        }
    }

    .search button {
        float: right;
    }

    @media only screen and (max-width : 768px) {
        .icon {
            font-size: 32px;
        }

        .search {
            margin-top: 32px;
        }

        .info {
            padding-left: 0 !important;

            h1 {
                margin-top: 0;
            }
        }
    }
</style>

<template>
    <div class="row" v-if="!isLoading">
        <div class="data col-lg-8 col-md-8 col-sm-8 col-xs-8">
            <div class="weather-icon hidden-xs">
                <i class="wi icon" v-bind:class="icon"></i>
            </div>
            <div class="info">
                <h1>{{ location }}</h1>
                <p>{{ temperature }}</p>
            </div>
        </div>
        <div class="search col-lg-4 col-md-4 col-sm-4 col-xs-4">
            <button type="button" @click="showSearch">
                <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
            </button>
        </div>
    </div>
</template>

<script>
    import events from '../../events'
    import _ from 'lodash'
    import { iconize } from '../../helpers'
    import {
        EVENT_SHOW_OVERLAY,
        EVENT_SHOW_SEARCH,
        EVENT_DATA_LOADED
    } from '../../constants'

    export default {
        mounted() {
            events.$on(EVENT_DATA_LOADED, (data) => { this.loadData(data); });
        },

        data() {
            return {
                location: false,
                temperature: false,
                icon: false,
                isLoading: true
            }
        },

        methods: {
            showSearch() {
                events.$emit(EVENT_SHOW_OVERLAY);
                events.$emit(EVENT_SHOW_SEARCH);
            },

            loadData(data) {
                if ( data.response
                     && data.response.cod === '404' )
                {
                    return;
                }

                let res          = data.response;
                this.icon        = `wi-owm-${res.list[0].weather[0].id}`;
                this.location    = `${res.city.name}, ${_.capitalize(res.city.country)}`;
                this.temperature = `${_.capitalize(res.list[0].weather[0].description)}, ${res.list[0].temp.day.toFixed(0)} °C`;
                this.isLoading   = false;
            }
        }
    }
</script>