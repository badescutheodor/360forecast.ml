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
                        <toggle-button @change="onToggle" :value="true"></toggle-button>
                    </div>

                    <div class="option">
                        <p class="option-item">Number of days to show in forecast</p>
                        <input type="number" class="form-control width-90" v-model="settings.forecastCount" min="2" max="16" />
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
    import { notify } from '../../helpers'
    import {
        EVENT_SHOW_SETTINGS,
        EVENT_HIDE_SETTINGS,
        EVENT_HIDE_OVERLAY
    } from '../../constants'

    export default {
        mounted() {
            events.$on(EVENT_SHOW_SETTINGS, () => { this.showSettings() });
            events.$on(EVENT_HIDE_SETTINGS, () => { this.hideSettings() });
        },

        data() {
            return {
                isShown: false,
                settings: {
                    saveLocations: true,
                    forecastCount: 5
                }
            }
        },

        methods: {
            onToggle(value) {
                console.log(value);
            },

            showSettings() {
                this.isShown = true;
            },

            hideSettings(e) {
                e && events.$emit(EVENT_HIDE_OVERLAY);
                this.isShown = false;
            },

            saveSettings() {
                events.$emit(EVENT_HIDE_OVERLAY);
                this.isShown = false;
            }
        }
    }
</script>