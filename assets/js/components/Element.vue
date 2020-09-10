<template>
    <g @mouseover="() => {!data.connect && !data.self ? data.hover = true : data.hover = false}"
       @mouseout="() => {data.hover = false; data.connect = false}" id="rect_group" class="noselect test123">
        <Component :x="data.x" :y="data.y" :width="data.width" :height="data.height"
                   :colour="data.colour"
                   :highlight="data.highlight"
                   :text="data.text" :scale="1"
                   class="draggable"
                   @mousedown.native="makeTargetMovable"
                   v-on:touchstart.native="makeTargetMovableTouch"
                   ref="rect" pointer-events="all"
                   v-bind:is="data.type"
        ></Component>

        <svg :x="data.x + (data.width)/2 - 17" :y="data.y - 30"
             width="34" :height="[data.type === 'Condition'] ? '45': '32'"
             v-show="data.hover && !data.connect"
             @mouseover="($event) => {data.creatorHoverT = true}"
             @mouseout="() => {data.creatorHoverT = false}"
             @mousedown="$emit('createLine', data.x + (data.width)/2, data.y, linePosition.Top, data)"
             id="creatorHoverT" ref="creatorHoverT">
            <polygon points="9.5,23.5 9.5,12.25 2,12.25 17,1 32,12.25 24.5,12.25 24.5,23.5" stroke="black"
                     stroke-width="2"
                     :fill="[data.creatorHoverT ? 'black' : 'transparent']"></polygon>
            <rect v-if="data.type === 'Condition'" fill="transparent" x="0" y="0" width="30px" height="45px"
                  stroke="transparent"></rect>
            <rect v-else fill="transparent" x="0" y="0" width="30px" height="30px" stroke="transparent"></rect>
        </svg>

        <svg :x="[(data.type === 'Condition' || data.type === 'Input' || data.type === 'Output' || data.type === 'LoopEnd' || data.type === 'LoopStart') ? data.x + data.width - 13 : data.x + data.width]"
             :y="data.y + data.height/2 - 16"
             height="32"
             :width="[(data.type === 'Condition' || data.type === 'Input' || data.type === 'Output' || data.type === 'LoopEnd' || data.type === 'LoopStart') ? 45 : 30]"
             v-show="data.hover && !data.connect"
             @mouseover="() => {data.creatorHoverR = true}"
             @mouseout="() => {data.creatorHoverR = false}"
             @mousedown="$emit('createLine', data.x + data.width, data.y + data.height/2, linePosition.Right)"
             id="creatorHoverR" ref="creatorHoverR">
            <polygon
                    v-if="data.type === 'Condition' || data.type === 'Input' || data.type === 'Output' || data.type === 'LoopEnd' || data.type === 'LoopStart'"
                    points="8.5,10.5 8.5,-1.25 1,-1.25 16,-12 31,-1.25 23.5,-1.25 23.5,10.5" stroke="black"
                    stroke-width="2"
                    :fill="[data.creatorHoverR ? 'black' : 'transparent']" transform="rotate(90, 15, 15)"></polygon>
            <polygon v-else points="8.5,23.5 8.5,12.25 1,12.25 16,1 31,12.25 23.5,12.25 23.5,23.5" stroke="black"
                     stroke-width="2"
                     :fill="[data.creatorHoverR ? 'black' : 'transparent']" transform="rotate(90, 15, 15)"></polygon>
            <rect v-if="data.type === 'Condition' || data.type === 'Input' || data.type === 'Output' || data.type === 'LoopEnd' || data.type === 'LoopStart'"
                  fill="transparent" x="0" y="0" width="45px" height="30px" stroke="transparent"></rect>
            <rect v-else fill="transparent" x="0" y="0" width="30px" height="30px" stroke="transparent"></rect>
        </svg>


        <svg :x="data.x + (data.width)/2 - 17"
             :y="[data.type === 'Condition' ? data.y + data.height - 13 : data.y + data.height]"
             width="34" :height="[data.type === 'Condition'] ? '45': '32'"
             v-show="data.hover && !data.connect"
             @mouseover="() => {data.creatorHoverB = true}"
             @mouseout="() => {data.creatorHoverB = false}"
             @mousedown="$emit('createLine', data.x + (data.width)/2, data.y + data.height, linePosition.Bottom)"
             id="creatorHoverB" ref="creatorHoverB">
            <polygon v-if="data.type === 'Condition'"
                     points="5.5,9.5 5.5,-1.25 -2,-1.25 13,-12 28,-1.25 20.5,-1.25 20.5,9.5" stroke="black"
                     stroke-width="2"
                     :fill="[data.creatorHoverB ? 'black' : 'transparent']" transform="rotate(180, 15, 15)"></polygon>
            <polygon v-else points="5.5,22.5 5.5,12.25 -2,12.25 13,1 28,12.25 20.5,12.25 20.5,22.5" stroke="black"
                     stroke-width="2"
                     :fill="[data.creatorHoverB ? 'black' : 'transparent']" transform="rotate(180, 15, 15)"></polygon>
            <rect v-if="data.type === 'Condition'" fill="transparent" x="0" y="0" width="30px" height="45px"
                  stroke="transparent"></rect>
            <rect v-else fill="transparent" x="0" y="0" width="30px" height="30px" stroke="transparent"></rect>
        </svg>


        <svg :x="data.x - 30" :y="data.y + data.height/2 - 16"
             :width="[(data.type === 'Condition' || data.type === 'Input' || data.type === 'Output' || data.type === 'LoopEnd' || data.type === 'LoopStart') ? 45 : 34]"
             height="34"
             v-show="data.hover && !data.connect"
             @mouseover="() => {data.creatorHoverL = true}"
             @mouseout="() => {data.creatorHoverL = false}"
             @mousedown="$emit('createLine', data.x, data.y + data.height/2, linePosition.Left)"
             id="creatorHoverL" ref="creatorHoverL">
            <polygon points="6.5,23.5 6.5,12.25 -1,12.25 14,1 29,12.25 21.5,12.25 21.5,23.5" stroke="black"
                     stroke-width="2"
                     :fill="[data.creatorHoverL ? 'black' : 'transparent']" transform="rotate(-90, 15, 15)"></polygon>
            <rect v-if="data.type === 'Condition' || data.type === 'Input' || data.type === 'Output' || data.type === 'LoopEnd' || data.type === 'LoopStart'"
                  fill="transparent" x="0" y="0" width="45px" height="30px" stroke="transparent"></rect>
            <rect v-else fill="transparent" x="0" y="0" width="30px" height="30px" stroke="transparent"></rect>
        </svg>
    </g>
</template>

<script lang="ts">
    import {Component} from 'vue-property-decorator'
    import Process from "./Elements/Process.vue";
    import Condition from "./Elements/Condition.vue";
    import Input from "./Elements/Input.vue";
    import Output from "./Elements/Output.vue";
    import SubProcess from "./Elements/SubProcess.vue";
    import Start from "./Elements/Start.vue";
    import LoopStart from "./Elements/LoopStart.vue";
    import LoopEnd from "./Elements/LoopEnd.vue";
    import {LinePosition} from "../DTO/LinePosition";
    import Movable from "./Movable";


    @Component({
        components: {Process, Condition, Input, Output, SubProcess, Start, LoopStart, LoopEnd}
    })
    export default class Element extends Movable {
        private linePosition = LinePosition;

    }
</script>

<style scoped>

    .draggable {
        cursor: move;
    }

    .foreign {
        text-align: left;
    }

    .insideForeign {
        width:      100%;
        display:    inline-block;
        word-break: break-all;
        text-align: center;
        padding:    5px;
    }


</style>