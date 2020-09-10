<template>
    <svg :x="x" :y="y" :width="width" :height="height">
        <rect width="100%" height="100%" :fill="'#006600'" fill-opacity="1"
              :stroke="colour" stroke-dasharray="4"
              stroke-width="5px" ry="2" rx="2"
              ref="rect">

        </rect>

                <foreignObject width="100%" height="100%">
                    <div class="box" :style="'height:' + height + 'px'">
                        <span>{{text}}</span>
                    </div>
                </foreignObject>
    </svg>
</template>

<script lang="ts">
    import {Component} from 'vue-property-decorator'
    import GeometryElement from "./GeometryElement";

    @Component
    export default class Process extends GeometryElement {

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