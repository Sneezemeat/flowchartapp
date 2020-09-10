<template>
    <g>
        <svg :x="x" :y="y" :width="width" :height="height">
            <polygon :fill="'#cc9900'" fill-opacity="1"
                     class="draggable"
                     :points="'2,1 ' + (118 + loopWidth) + ',1 ' + (118 + loopWidth) + ',15 ' + (104 + loopWidth) + ',29 16,29 2,15'"
                     :transform="'scale(' + scale + ',' + scale + ')'"/>
            <polygon :fill="'#cc9900'" fill-opacity="0"
                     :stroke="colour" stroke-dasharray="4"
                     stroke-width="3px" class="draggable"
                     :points="'4,3 ' + (116+ loopWidth) + ',3 ' + (116 + loopWidth) + ',14 ' + (103 + loopWidth) + ',27 17,27 4,14'"
                     :transform="'scale(' + scale + ',' + scale + ')'"/>
            <foreignObject width="75%" :height="height" x="13%">
                <div class="box" :style="'height:' + height + 'px'">
                    {{text}}
                </div>
            </foreignObject>
        </svg>
        <svg :x="x" :y="y - loopHeight" :width="width + 15" :height="height + loopHeight + 30" v-if="highlight">
            <line x1="2" y1="30" x2="2" :y2="loopHeight" stroke="#cc9900" stroke-width="2px"></line>
            <line :x1="width - 2" :y1="30" :x2="width - 2" :y2="loopHeight" stroke="#cc9900" stroke-width="2px"></line>

            <svg :x="width" :y="loopHeight / 2 + 7.5" width="15px" height="15px">
                <line x1="5" y1="0" x2="15" y2="7.5" stroke-width="2px" stroke="black"></line>
                <line x1="5" y1="15" x2="15" y2="7.5" stroke-width="2px" stroke="black"></line>
            </svg>
            <svg :x="((width) / 2) - 7.5" :y="loopHeight + 30" width="15px" height="15px">
                <line x1="0" y1="5" x2="7.5" y2="15" stroke-width="2px" stroke="black"></line>
                <line x1="15" y1="5" x2="7.5" y2="15" stroke-width="2px" stroke="black"></line>
            </svg>
        </svg>
    </g>
</template>

<script lang="ts">
    import {Prop, Component} from 'vue-property-decorator'
    import GeometryElement from "./GeometryElement";


    @Component
    export default class LoopEnd extends GeometryElement {
        @Prop() public loopHeight!: number;
        @Prop() public loopWidth!: number;
        @Prop() public highlight!: boolean;

        constructor($options?) {
            super($options);
        }

    }
</script>

<style scoped>
    .box {
        display:         flex;
        justify-content: center;
        align-items:     center;
        height:          100%;
        width:           100%;
        word-break:      break-word;
        line-height:     normal;
        letter-spacing:  normal;
        font-size:       12px;
    }
</style>