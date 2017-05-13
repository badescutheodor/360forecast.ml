<style lang="scss">
    .forecast {
        margin-top: 60px;

        h3 {
            margin-bottom: 0;
        }

        p {
            color: #dedede;
        }

        .table {
            margin-top: 30px;

            .day {
                background: #353535;
                display: inline-block;
                border: 1px solid #424242;
                height: 260px;
                padding: 0 5px;
                margin-right: 7px;
                border-top: 8px solid #00497d;

                .header {
                    font-size: 19px;
                    text-align: center;
                }

                .weather {
                    text-align: center;
                    font-size: 24px;
                }

                .precipitations, .humidity {
                    text-align: center;
                    font-size: 16px;

                    i {
                        margin-right: 9px;
                    }
                }

                div {
                    padding: 12px 12px 0 12px;

                    &.header:after, &.weather:after, &.humidity:after, &.precipitations:after {
                        content: '';
                        background-color: rgba(74, 74, 74, 0.3);
                        display: block;
                        width: 75%;
                        height: 1px;
                        margin: 10px auto 0 auto;
                    }
                }

                .limits {
                    text-align: center;
                }

                .min, .max {
                    display: inline-block;
                    padding: 5px 10px;
                }

                &.current {
                    height: 280px;
                    position: relative;
                    bottom: 11px;
                    background: #ffffff;
                    color: #353535;
                    box-shadow: 0 0 14px 0 rgba(255, 255, 255, 0.63);
                    border-color: #e4e4e4;
                    border-top-color: #ffddbb;

                    .header:after,
                    .weather:after,
                    .humidity:after,
                    .precipitations:after {
                        background-color: rgba(232, 232, 232, 0.3);
                    }
                }
            }
        }

        @media only screen and (max-width : 425px) {
            & {
                margin-top: 34px;
            }

            .day {
                width: 100%;
            }
        }
    }
</style>

<template>
    <div class="row forecast" v-if="!isLoading">
        <div class="col-lg-12">
            <h3>Weather forecast</h3>
            <p>5 days forecast</p>
        </div>
        <div class="col-lg-12">
            <div class="table">
                <div class="day" v-for="(item, index) in items" v-bind:class="{'current': index === 0}">
                    <div class="header">
                        {{ item.day }}
                    </div>
                    <div class="weather">
                        <i class="wi wi-day-snow icon"></i>
                        {{ item.temperature }}
                    </div>
                    <div class="humidity">
                        <i class="wi wi-humidity"></i>
                        {{ item.humidity }}%
                    </div>
                    <div class="precipitations">
                        <i class="wi wi-raindrop"></i>
                        {{ item.rain }} mm
                    </div>
                    <div class="limits">
                        <div class="min">
                            <i class="wi wi-moonrise"></i>
                            {{ item.min }} °C
                        </div>
                        <div class="max">
                            <i class="wi wi-day-cloudy"></i>
                            {{ item.max }} °C
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import events from '../../events'
    import _ from 'lodash'
    import moment from 'moment'
    import {
        EVENT_DATA_LOADED
    } from '../../constants'

    export default {
        mounted() {
            events.$on(EVENT_DATA_LOADED, (data) => { this.loadData(data) });
        },

        data() {
            return {
                items: [],
                isLoading: true
            }
        },

        methods: {
            loadData(data) {
                if ( data.response
                    && data.response.cod === '404' )
                {
                    return;
                }

                let res   = data.response;
                let list  = res.list;
                let items = [];

                _.each(list, (item) => {
                    items.push({
                        day: moment(item.dt * 1000).format('dddd'),
                        temperature: `${item.temp.day.toFixed(0)} °C`,
                        rain: item.rain ? item.rain : 0,
                        humidity: item.humidity,
                        min: item.temp.night.toFixed(0),
                        max: item.temp.max.toFixed(0)
                    });
                });

                this.items     = items;
                this.isLoading = false;
            }
        }
    }
</script>