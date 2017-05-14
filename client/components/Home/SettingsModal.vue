<style lang="scss" scoped>
    .modal {
        display: block;
        position: static;
        overflow: hidden;
        outline: 0;
        color: #3a3939 !important;
    }

    .modal-dialog {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%) !important;
        z-index: 3;
    }

    @media only screen and (max-width : 768px) {
        .modal-dialog {
            margin: 0;
            width: 100%;
            padding: 0 5px;
        }
    }

    @media only screen and (max-width : 426px) {
        .modal-dialog {
            top: 15%;
        }
    }

    .modal-content {
        border-radius: 1px;
        border-color: transparent;
        box-shadow: none;
    }

    .option {
        margin-bottom: 15px;
    }

    .option-item {
        font-weight: 600;
    }

    .width-90 {
        width: 90px;
    }
</style>

<template>
    <div class="modal fade in" role="dialog" v-if="isShown">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">
                        <i class="glyphicon glyphicon-cog"></i>
                        Manage Settings
                    </h4>
                </div>
                <div class="modal-body">
                    <div class="option">
                        <p class="option-item">Save my locations for future autocomplete</p>
                        <toggle-button @change="onToggle" :value="settings['save:location']" :sync="true"></toggle-button>
                    </div>

                    <div class="option">
                        <p class="option-item">Number of days to show in forecast</p>
                        <input type="number" class="form-control width-90" v-model="settings['forecast:days:count']" min="2" max="16" />
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" @click="hideSettings">
                        Cancel
                    </button>
                    <button type="button" class="btn btn-primary" data-dismiss="modal" @click="saveSettings">
                        <i class="glyphicon glyphicon-ok"></i>
                        Save
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import events from '../../events'
    import _ from 'lodash'
    import { notify } from '../../helpers'
    import {
        EVENT_SHOW_SETTINGS,
        EVENT_HIDE_SETTINGS,
        EVENT_HIDE_OVERLAY,
        SOCKET_SET_SETTINGS,
        SETTING_FORECAST_COUNT,
        SETTING_SAVE_LOCATION,
        EVENT_DATA_LOADED,
        SOCKET_ACTION_SEARCH
    } from '../../constants'

    export default {
        mounted() {
            events.$on(EVENT_SHOW_SETTINGS,  () => { this.showSettings() });
            events.$on(EVENT_HIDE_SETTINGS,  () => { this.hideSettings() });
            events.$on(SOCKET_SET_SETTINGS,  (data) => { this.setSettings(data); });
            events.$on(EVENT_DATA_LOADED,    (data) => { this.setLastSearch(data); });
        },

        data() {
            return {
                isShown: false,
                updateTimeout: false,
                lastSearch: false,
                settings: {
                    [SETTING_SAVE_LOCATION]: true,
                    [SETTING_FORECAST_COUNT]: 5
                }
            }
        },

        methods: {
            setLastSearch(data) {
                if ( data.response
                    && data.response.cod === '404' )
                {
                    return;
                }

                let res         = data.response;
                this.lastSearch = res.city.name;
            },

            setSettings(data) {
                if ( _.has(data, 'updated') )
                {
                    if ( this.updateTimeout )
                    {
                        clearInterval(this.updateTimeout);
                        this.updateTimeout = false;
                    }

                    /**
                     * Ask for the updated data as we
                     * now have changed the settings
                     */

                    this.$root.socket.emit(SOCKET_ACTION_SEARCH, this.lastSearch);

                    /**
                     * Tell the user settings were updated
                     */

                    setTimeout(() => {
                        delete data.updated;
                        this.settings[SETTING_SAVE_LOCATION]  = ( data[SETTING_SAVE_LOCATION] == 1 );
                        this.settings[SETTING_FORECAST_COUNT] = data[SETTING_FORECAST_COUNT];

                        events.$emit(EVENT_HIDE_OVERLAY);
                        this.isShown = false;

                        setTimeout(() => {
                            notify('success', `
                            <i class="glyphicon glyphicon-ok"></i>
                            Settings successfully saved
                        `);
                        }, 300);
                    }, 300);

                    return;
                }

                this.settings[SETTING_SAVE_LOCATION]  = ( data[SETTING_SAVE_LOCATION] == 1 );
                this.settings[SETTING_FORECAST_COUNT] = data[SETTING_FORECAST_COUNT];
            },

            onToggle(event) {
                this.settings[SETTING_SAVE_LOCATION] = event.value;
            },

            showSettings() {
                this.isShown = true;
            },

            hideSettings(e) {
                e && events.$emit(EVENT_HIDE_OVERLAY);
                this.isShown = false;
            },

            saveSettings() {
                let socket = this.$root.socket;

                this.updateTimeout = setTimeout(() => {
                    events.$emit(EVENT_HIDE_OVERLAY);
                    this.isShown = false;

                    setTimeout(() => {
                        notify('error', `
                            <i class="glyphicon glyphicon-remove"></i>
                            Error occurred while saving settings
                        `);
                    }, 300);
                }, 500);

                socket.emit(SOCKET_SET_SETTINGS, this.settings);
            }
        }
    }
</script>