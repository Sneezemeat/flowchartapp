<template>
        <!-- Bar to display all creatable Elements -->
<!--    this.elementDropped = this.intersectionService.checkLoop(element, this.$refs.elements[element.id], this.elements, this.$refs.elements, this.elementDropped);-->
        <div class="left_bar" :class="active ? 'left_bar_up' : 'left_bar_down'">
            <b-row class="pt-lg-5 pt-xl-5 noselect">
                <b-col cols="4" sm="4" md="4" lg="12" xl="12" class="mt-3 mt-sm-3 mt-lg-3" order="1" v-if="!$store.getters.user || ($store.getters.user && ($store.getters.user.level + 1) >= 0)">
                    <Process :x="15" :y="70" :scale="1" :width="120" :height="60" :colour="'#006600'"
                             :text="'Vorgang'"
                             @mousedown.native="$emit('createElementByDrag', $event, false, 'Process', 1440, 850, 120, 60, 'Vorgang', false, 0)"
                             @touchstart.native="$emit('createElementByDrag', $event, true, 'Process', 1440, 850, 120, 60, 'Vorgang', false, 0)"
                             class="element_template d-none d-sm-block d-md-block d-lg-block d-xl-block m-auto"></Process>
                    <Process :x="15" :y="70" :scale="0.8" :width="96" :height="48" :colour="'#006600'"
                             :text="'Vorgang'"
                             @mousedown.native="$emit('createElementByDrag',$event, false, 'Process',1230, 850, 120, 60, 'Vorgang', false, 0)"
                             @touchstart.native="$emit('createElementByDrag',$event, true, 'Process',1230, 850, 120, 60, 'Vorgang', false, 0)"
                             class="element_template d-block d-sm-none d-md-none d-lg-none d-xl-none m-auto"></Process>
                </b-col>
                <b-col cols="4" sm="4" md="4" lg="12" xl="12" class="mt-3 mt-sm-3 mt-lg-3 mt-lg-4 mt-xl-4" order="4"
                       order-sm="4" order-md="4" order-lg="2" order-xl="2" v-if="!$store.getters.user || ($store.getters.user && ($store.getters.user.level + 1) >= 2)">
                    <SubProcess :x="15" :y="150" :scale="1" :width="120" :height="60" :colour="'#006600'"
                                :text="'Unter programm'"
                                @mousedown.native="$emit('createElementByDrag',$event, false, 'SubProcess', 1440, 850, 120, 60, 'Unter programm',false,  0)"
                                @touchstart.native="$emit('createElementByDrag',$event, true, 'SubProcess', 1440, 850, 120, 60, 'Unter programm',false,  0)"
                                class="element_template d-none d-sm-block d-md-block d-lg-block d-xl-block m-auto"></SubProcess>
                    <SubProcess :x="15" :y="150" :scale="0.8" :width="96" :height="48" :colour="'#006600'"
                                :text="'Unter programm'"
                                @mousedown.native="$emit('createElementByDrag',$event, false,'SubProcess', 1230, 850, 120, 60, 'Unter programm', false, 0)"
                                @touchstart.native="$emit('createElementByDrag',$event, true,'SubProcess', 1230, 850, 120, 60, 'Unter programm', false, 0)"
                                class="element_template d-block d-sm-none d-md-none d-lg-none d-xl-none m-auto"></SubProcess>
                </b-col>
                <b-col cols="4" sm="4" md="4" lg="12" xl="12" class="mt-3 mt-sm-3 mt-lg-3 mt-lg-4 mt-xl-4" order="2"
                       order-sm="2" order-md="2" order-lg="3" order-xl="3" v-if="!$store.getters.user || ($store.getters.user && ($store.getters.user.level + 1) >= 3)">
                    <Input :x="15" :y="230" :scale="1" :width="120" :height="60" :colour="'#1e3d7b'"
                           :text="'Eingabe'"
                           @mousedown.native="$emit('createElementByDrag',$event, false, 'Input', 1440, 850, 120, 60, 'Eingabe', false, 0)"
                           @touchstart.native="$emit('createElementByDrag',$event, true, 'Input', 1440, 850, 120, 60, 'Eingabe', false, 0)"
                           class="element_template d-none d-sm-block d-md-block d-lg-block d-xl-block m-auto"></Input>
                    <Input :x="15" :y="230" :scale="0.8" :width="96" :height="48" :colour="'#1e3d7b'"
                           :text="'Eingabe'"
                           @mousedown.native="$emit('createElementByDrag',$event, false, 'Input', 1230, 850, 120, 60, 'Eingabe', false, 0)"
                           @touchstart.native="$emit('createElementByDrag',$event, true, 'Input', 1230, 850, 120, 60, 'Eingabe', false, 0)"
                           class="element_template d-block d-sm-none d-md-none d-lg-none d-xl-none m-auto"></Input>
                </b-col>
                <b-col cols="4" sm="4" md="4" lg="12" xl="12" class="mt-3 mt-sm-3 mt-lg-3 mt-lg-4 mt-xl-4" order="5"
                       order-sm="5" order-md="5" order-lg="4" order-xl="4" v-if="!$store.getters.user || ($store.getters.user && ($store.getters.user.level + 1) >= 3)">
                    <Output :x="15" :y="310" :scale="1" :width="120" :height="60" :colour="'#1e3d7b'"
                            :text="'Ausgabe'"
                            @mousedown.native="$emit('createElementByDrag',$event, false,'Output', 1440, 850, 120, 60, 'Ausgabe', false, 0)"
                            @touchstart.native="$emit('createElementByDrag',$event, true, 'Output', 1440, 850, 120, 60, 'Ausgabe', false, 0)"
                            class="element_template d-none d-sm-block d-md-block d-lg-block d-xl-block m-auto"></Output>
                    <Output :x="15" :y="310" :scale="0.8" :width="96" :height="48" :colour="'#1e3d7b'"
                            :text="'Ausgabe'"
                            @mousedown.native="$emit('createElementByDrag',$event, false, 'Output', 1230, 850, 120, 60, 'Ausgabe', false, 0)"
                            @touchstart.native="$emit('createElementByDrag',$event, true, 'Output', 1230, 850, 120, 60, 'Ausgabe', false, 0)"
                            class="element_template d-block d-sm-none d-md-none d-lg-none d-xl-none m-auto"></Output>
                </b-col>
                <b-col cols="4" sm="4" md="4" lg="12" xl="12" class="mt-3 mt-sm-3 mt-lg-3 mt-lg-4 mt-xl-4" order="3"
                       order-sm="3" order-md="3" order-lg="5" order-xl="5" >
                    <Condition :x="15" :y="390" :scale="1" :width="120" :height="90" :colour="'#cc9900'"
                               :text="'Bedingung'"
                               @mousedown.native="$emit('createElementByDrag',$event, false, 'Condition', 1440, 850, 120, 90, 'Bedingung', false, 0)"
                               @touchstart.native="$emit('createElementByDrag',$event, true, 'Condition', 1440, 850, 120, 90, 'Bedingung', false, 0)"
                               class="element_template condition_template d-none d-sm-block d-md-block d-lg-block d-xl-block m-auto"
                               v-if="!$store.getters.user || ($store.getters.user && ($store.getters.user.level + 1) >= 4)"></Condition>
                    <Condition :x="15" :y="390" :scale="0.7" :width="84" :height="63" :colour="'#cc9900'"
                               :text="'Bedingung'" :template="true"
                               @mousedown.native="$emit('createElementByDrag',$event, false, 'Condition', 1230, 850, 120, 90, 'Bedingung', false, 0)"
                               @touchstart.native="$emit('createElementByDrag',$event, true, 'Condition', 1230, 850, 120, 90, 'Bedingung', false, 0)"
                               class="element_template condition_template d-block d-sm-none d-md-none d-lg-none d-xl-none m-auto"
                               v-if="!$store.getters.user || ($store.getters.user && ($store.getters.user.level + 1) >= 4)"></Condition>
                </b-col>
                <b-col cols="4" sm="4" md="4" lg="12" xl="12" class="mt-3 mt-sm-3 mt-lg-3 mt-lg-4 mt-xl-4"
                       order="6" v-if="!$store.getters.user || ($store.getters.user && ($store.getters.user.level + 1) >= 5)">
                    <svg x="15" y="0" width="150" height="150"
                         class="d-none d-sm-inline d-md-inline d-lg-inline d-xl-inline">
                        <LoopStart :x="20" :y="0" :scale="0.9" :width="108" :height="27" :colour="'#cc9900'"
                                   :loopWidth="0"
                                   :text="'Schleife'"
                                   @mousedown.native="$emit('createLoop', $event, false, 1440, 850)"
                                   @touchstart.native="$emit('createLoop', $event, true, 1440, 850)"
                                   class="element_template m-auto"></LoopStart>
                        <LoopEnd :x="20" :y="35" :scale="0.9" :width="108" :height="27" :colour="'#cc9900'"
                                 :loopWidth="0"
                                 :loopHeight="0"
                                 :highlight="false"
                                 :text="''"
                                 @mousedown.native="$emit('createLoop', $event, false, 1440, 850)"
                                 @touchstart.native="$emit('createLoop', $event, true, 1440, 850)"
                                 class="element_template mr-auto mb-auto ml-auto mt-2"></LoopEnd>

                    </svg>
                    <svg x="15" y="0" width="85" height="150"
                         class="d-inline d-sm-none d-md-none d-lg-none d-xl-none">
                        <LoopStart :x="0" :y="0" :scale="0.7" :width="84" :height="21" :colour="'#cc9900'"
                                   :loopWidth="0"
                                   :text="'Schleife'" :template="true"
                                   @mousedown.native="$emit('createLoop', $event, false, 1230, 850)"
                                   @touchstart.native="$emit('createLoop', $event, true, 1230, 850)"
                                   class="element_template m-auto"></LoopStart>
                        <LoopEnd :x="0" :y="30" :scale="0.7" :width="100" :height="21" :colour="'#cc9900'"
                                 :loopWidth="0"
                                 :loopHeight="0"
                                 :highlight="false"
                                 :text="''" :template="true"
                                 @mousedown.native="$emit('createLoop', $event, false, 1230, 850)"
                                 @touchstart.native="$emit('createLoop', $event, true, 1230, 850)"
                                 class="element_template  mr-auto mb-auto ml-auto mt-1"></LoopEnd>
                    </svg>
                </b-col>
            </b-row>
        </div>
</template>

<script lang="ts">
    import {Component, Prop, Vue} from 'vue-property-decorator'
    import Process from "./Elements/Process.vue";
    import SubProcess from "./Elements/SubProcess.vue";
    import Input from "./Elements/Input.vue";
    import Output from "./Elements/Output.vue";
    import Condition from "./Elements/Condition.vue";
    import Start from './Elements/Start.vue';
    import LoopStart from './Elements/LoopStart.vue';
    import LoopEnd from './Elements/LoopEnd.vue';
    import Login from "../views/Login.vue";
    import LineConnection from "./LineConnection.vue";
    import Element from "./Element.vue";

    @Component({
        components: {
            Login, LineConnection, Element, Process, SubProcess, Input, Output, Condition, Start, LoopStart, LoopEnd
        }
    })
    export default class ElementBar extends Vue {
        @Prop() public active;
        @Prop() public showGrid;

    }
</script>

<style scoped>
    .element_template {
        z-index: 3;
        cursor: pointer;
    }

    .justify_main_bar {
        justify-content: left;
    }
    .tool_icon {
        border-left: 1px solid white;
        cursor: pointer;
    }

    .tool_icon:focus {
        outline: none;
    }

    .tool_icon_disabled {
        border-left: 1px solid white;
        cursor: default;
        color: lightgrey;
    }

    .tool_icon_disabled:focus {
        outline: none;
    }

    @media (max-width: 992px) {
        .main_bar {
            bottom: 0px;
            top:    auto;
        }

        .justify_main_bar {
            justify-content: center;
        }

        .tool_icon {
            border-left: none;
        }

        .tool_icon_disabled {
            border-left: none;
        }

    }

    @media (min-width: 992px) {
        .main_bar {
            top: 0px;
        }
    }
</style>