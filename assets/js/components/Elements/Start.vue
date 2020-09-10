<template>
    <svg :x="x" :y="y" :width="width" :height="height">
        <rect :width="width - 4" :height="height - 4" :fill="'#ffffff'" fill-opacity="1"
              :stroke="colour" :x="2" y="2"
              stroke-width="2px" ry="15" rx="15"
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
    export default class Start extends GeometryElement {


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
        line-height: normal;
        letter-spacing: normal;
    }

</style>