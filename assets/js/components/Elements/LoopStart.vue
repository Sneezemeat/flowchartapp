<template>
    <g>
        <svg :x="x" :y="y" :width="width" :height="height">
            <polygon :fill="'#cc9900'" fill-opacity="1"
                     class="draggable"
                     :points="'16,1 ' + (104 + loopWidth) + ',1 ' + (118 + loopWidth) + ',15 ' + (118 + loopWidth) + ',29  2,29 2,15'"
                     :transform="'scale(' + scale + ',' + scale + ')'"/>
            <polygon :fill="'#cc9900'" fill-opacity="0"
                     :stroke="colour" stroke-dasharray="4"
                     stroke-width="3px" class="draggable"
                     :points="'17,3 ' + (103 + loopWidth) + ',3 ' + (116 + loopWidth) + ',14 ' + (116 + loopWidth) + ',27  4,27 4,15'"
                     :transform="'scale(' + scale + ',' + scale + ')'"/>
            <foreignObject width="75%" :height="height" x="13%">
                <div class="box" :style="'height:' + height + 'px'">
                    {{text}}
                </div>
            </foreignObject>
        </svg>
    </g>
</template>

<script lang="ts">
    import {Prop, Component} from 'vue-property-decorator'
    import GeometryElement from "./GeometryElement";


    @Component
    export default class LoopStart extends GeometryElement {
        @Prop() public loopHeight!: number;
        @Prop() public loopWidth!: number;

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