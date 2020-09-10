<template>
    <svg :x="x" :y="y" :width="width" :height="height">
        <rect width="100%" height="100%" :fill="'#006600'" fill-opacity="1"
              :stroke="colour" stroke-dasharray="4"
              stroke-width="5px" ry="2" rx="2" class="draggable rect"
              ref="rect">
        </rect>
        <line :x1="10" :x2="10" y1="0" y2="100%" stroke="#2c3e50" stroke-width="1px"></line>
        <line :x1="width - 10" :x2="width - 10" y1="0" y2="100%" stroke="#2c3e50" stroke-width="1px"></line>
        <foreignObject :width="width - 24" height="100%" x="12">
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
    export default class SubProcess extends GeometryElement {


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
        height: 100%;
        width: 100%;
        word-break: break-word;
        line-height: normal;
        letter-spacing: normal;
    }

</style>