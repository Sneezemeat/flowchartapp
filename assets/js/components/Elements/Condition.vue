<template>
    <svg :x="x" :y="y" :width="width" :height="height" class="svg">
        <defs>
            <polygon :id="'condition_' + scale" :width="width" :height="height"  fill-opacity="1"
                     class="draggable"
                     points="60,1  119,45  60,89  1,45"
                     :transform="'scale(' + scale + ',' + scale + ')' "/>
            <clipPath :id="'clipCondition_' + scale">
                <use v-bind="{'xlink:href' : '#condition_' + scale}"/>
            </clipPath>
        </defs>
        <g>
            <use v-bind="{'xlink:href' : '#condition_' + scale}" :stroke="colour" stroke-width="5px" stroke-dasharray="4" fill="#cc9900"
                 :clip-path="'url(#clipCondition_' + scale + ')'"></use>
        </g>
        <foreignObject width="65%" height="47%" x="17.5%" y="28%">
            <div class="box" :class="template ? 'template-text' : 'text'" :style="'height:' + height * 0.45 + 'px'">
                <span>{{text}}</span>
            </div>
        </foreignObject>
    </svg>
</template>

<script lang="ts">
    import {Prop, Component} from 'vue-property-decorator'
    import GeometryElement from "./GeometryElement";


    @Component
    export default class Condition extends GeometryElement {
        @Prop() public template!: boolean;

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

    .text {
        font-size: 12px;
    }

    .template-text {
        font-size: 12px;
    }

    @media (max-width: 576px) {
        .template-text {
            font-size: 9px;
        }
    }


</style>