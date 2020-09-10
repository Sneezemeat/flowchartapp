<template>
    <svg :x="x" :y="y" :width="width" :height="height">
        <defs>
            <polygon :id="'input_' + scale" fill-opacity="1"
                     class="draggable"
                     points="16,1  118,1  104,59  2,59" :transform="'scale(' + scale + ',' + scale + ')'"/>
            <clipPath :id="'clipInput_' + scale">
                <use v-bind="{'xlink:href' : '#input_' + scale}"/>
            </clipPath>
        </defs>`
        <g>
            <use v-bind="{'xlink:href' : '#input_' + scale}" :stroke="colour" stroke-width="5px" stroke-dasharray="4" fill="#1e3d7b"
                 :clip-path="'url(#clipInput_' + scale + ')'"></use>
        </g>
        <foreignObject width="75%" height="100%" x="13%">
            <div class="box" :style="'height:' + height + 'px'">
                {{text}}
            </div>
        </foreignObject>
    </svg>
</template>

<script lang="ts">
    import {Component} from 'vue-property-decorator'
    import GeometryElement from "./GeometryElement";


    @Component
    export default class Input extends GeometryElement {


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
    }

</style>