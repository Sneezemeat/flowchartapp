<template>
    <g>
        <defs>
            <marker id="arrow" viewBox="0 0 10 10" refX="5" refY="5"
                    markerWidth="3" markerHeight="3"
                    orient="auto-start-reverse">
                <path d="M 0 0 L 10 5 L 0 10 z"/>
            </marker>
        </defs>
        <foreignObject v-if="data.originPosition === linePosition.Top" :x="data.x1 + 15" :y="data.y1 - 75" height="30px"
                       width="200px">
            <div class="text">{{data.text}}</div>
        </foreignObject>
        <foreignObject v-if="data.originPosition === linePosition.Right" :x="data.x1 + 65" :y="data.y1 + 10"
                       height="30px" width="200px">
            <div class="text">{{data.text}}</div>
        </foreignObject>
        <foreignObject v-if="data.originPosition === linePosition.Bottom" :x="data.x1 + 15" :y="data.y1 + 50"
                       height="30px" width="200px">
            <div class="text">{{data.text}}</div>
        </foreignObject>
        <foreignObject v-if="data.originPosition === linePosition.Left" :x="data.x1 - 270" :y="data.y1 + 10"
                       height="30px" width="200px">
            <div class="textLeft">{{data.text}}</div>
        </foreignObject>
        <path v-if="(data.originPosition === linePosition.Bottom) && data.targetPosition === linePosition.Top"
              :d="'M ' + data.x1 + ' ' + data.y1 + ' V ' + (data.targetElement !== null ? data.targetElement.getY() - (Math.abs(data.targetElement.getY() - data.y1)/2) : data.y2 - (Math.abs(data.y2 - data.y1)/2)) + ' H ' + data.x2 + ' V ' +  (data.targetElement !== null && !data.targetMovable ? data.targetElement.getY() - 6.5 : data.y2)"
              @mousedown="makeTargetMovable" v-on:touchstart="makeTargetMovable"
              fill="transparent" stroke="black" stroke-width="5px" stroke-linecap="round"
              marker-end="url(#arrow)"></path>
        <path v-if="(data.originPosition === linePosition.Bottom) && data.targetPosition === linePosition.Bottom"
              :d="'M ' + data.x1 + ' ' + data.y1 + ' V ' + (data.y2 - (Math.abs(data.y2 - data.y1)/2)) + ' H ' + data.x2 + ' V ' +  (data.targetElement !== null && !data.targetMovable ? data.targetElement.data.y + 6.5 : data.y2)"
              @mousedown="makeTargetMovable" v-on:touchstart="makeTargetMovable"
              fill="transparent" stroke="black" stroke-width="5px" stroke-linecap="round"
              marker-end="url(#arrow)"></path>
        <path v-if="(data.originPosition === linePosition.Top) && data.targetPosition === linePosition.Bottom"
              :d="'M ' + data.x1 + ' ' + data.y1 + ' V ' + (data.targetElement !== null ? data.targetElement.data.y + data.targetElement.getHeight() + (Math.abs((data.targetElement.data.y + data.targetElement.getHeight()) - data.y1)/2) : data.y2 + (Math.abs(data.y2 - data.y1)/2)) + ' H ' + data.x2 + ' V ' +  (data.targetElement !== null && !data.targetMovable ? data.targetElement.data.y + data.targetElement.getHeight() + 6.5 : data.y2)"
              @mousedown="makeTargetMovable" v-on:touchstart="makeTargetMovable"
              fill="transparent" stroke="black" stroke-width="5px" stroke-linecap="round"
              marker-end="url(#arrow)"></path>
        <path v-if="(data.originPosition === linePosition.Top) && data.targetPosition === linePosition.Top"
              :d="'M ' + data.x1 + ' ' + data.y1 + ' V ' + (data.y2 + (Math.abs(data.y2 - data.y1)/2)) + ' H ' + data.x2 + ' V ' +  (data.targetElement !== null && !data.targetMovable ? data.targetElement.getY()- 6.5 : data.y2)"
              @mousedown="makeTargetMovable" v-on:touchstart="makeTargetMovable"
              fill="transparent" stroke="black" stroke-width="5px" stroke-linecap="round"
              marker-end="url(#arrow)"></path>
        <path v-if="data.originPosition === linePosition.Bottom && data.targetPosition === linePosition.Right"
              :d="'M ' + data.x1 + ' ' + data.y1 +  ' V ' +  data.y2 + ' H ' + (data.targetElement !== null && !data.targetMovable ? data.targetElement.data.x + data.targetElement.getWidth() + 6.5 : data.x2)"
              @mousedown="makeTargetMovable" v-on:touchstart="makeTargetMovable"
              fill="transparent" stroke="black" stroke-width="5px" stroke-linecap="round"
              marker-end="url(#arrow)"></path>
        <path v-if="data.originPosition === linePosition.Top && data.targetPosition === linePosition.Right"
              :d="'M ' + data.x1 + ' ' + data.y1 +  ' V ' +  data.y2 + ' H ' + (data.targetElement !== null && !data.targetMovable ? data.targetElement.data.x + data.targetElement.getWidth() + 6.5 : data.x2)"
              @mousedown="makeTargetMovable" v-on:touchstart="makeTargetMovable"
              fill="transparent" stroke="black" stroke-width="5px" stroke-linecap="round"
              marker-end="url(#arrow)"></path>
        <path v-if=" data.originPosition === linePosition.Bottom && data.targetPosition === linePosition.Left"
              :d="'M ' + data.x1 + ' ' + data.y1 +  ' V ' +  data.y2 + ' H ' + (data.targetElement !== null && !data.targetMovable ? data.targetElement.data.x - 6.5 : data.x2)"
              @mousedown="makeTargetMovable" v-on:touchstart="makeTargetMovable"
              fill="transparent" stroke="black" stroke-width="5px" stroke-linecap="round"
              marker-end="url(#arrow)"></path>
        <path v-if="data.originPosition === linePosition.Top && data.targetPosition === linePosition.Left"
              :d="'M ' + data.x1 + ' ' + data.y1 +  ' V ' +  data.y2 + ' H ' + (data.targetElement !== null && !data.targetMovable ? data.targetElement.data.x - 6.5 : data.x2)"
              @mousedown="makeTargetMovable" v-on:touchstart="makeTargetMovable"
              fill="transparent" stroke="black" stroke-width="5px" stroke-linecap="round"
              marker-end="url(#arrow)"></path>
        <path v-if="data.originPosition === linePosition.Left && data.targetPosition === linePosition.Top "
              :d="'M ' + data.x1 + ' ' + data.y1 + ' H ' + data.x2 + ' V ' +  (data.targetElement !== null && !data.targetMovable ? data.targetElement.getY()- 6.5 : data.y2)"
              @mousedown="makeTargetMovable" v-on:touchstart="makeTargetMovable"
              fill="transparent" stroke="black" stroke-width="5px" stroke-linecap="round"
              marker-end="url(#arrow)"></path>
        <path v-if="data.originPosition === linePosition.Right && data.targetPosition === linePosition.Top "
              :d="'M ' + data.x1 + ' ' + data.y1 + ' H ' + data.x2 + ' V ' +  (data.targetElement !== null && !data.targetMovable ? data.targetElement.getY() - 6.5 : data.y2)"
              @mousedown="makeTargetMovable" v-on:touchstart="makeTargetMovable"
              fill="transparent" stroke="black" stroke-width="5px" stroke-linecap="round"
              marker-end="url(#arrow)"></path>
        <path v-if="data.originPosition === linePosition.Right && data.targetPosition === linePosition.Bottom "
              :d="'M ' + data.x1 + ' ' + data.y1 + ' H ' + data.x2 + ' V ' +  (data.targetElement !== null && !data.targetMovable ? data.targetElement.data.y + data.targetElement.getHeight() + 6.5 : data.y2)"
              @mousedown="makeTargetMovable" v-on:touchstart="makeTargetMovable"
              fill="transparent" stroke="black" stroke-width="5px" stroke-linecap="round"
              marker-end="url(#arrow)"></path>
        <path v-if=" data.originPosition === linePosition.Left && data.targetPosition === linePosition.Bottom"
              :d="'M ' + data.x1 + ' ' + data.y1 + ' H ' + data.x2 + ' V ' +  (data.targetElement !== null && !data.targetMovable ? data.targetElement.data.y + data.targetElement.getHeight() + 6.5 : data.y2)"
              @mousedown="makeTargetMovable" v-on:touchstart="makeTargetMovable"
              fill="transparent" stroke="black" stroke-width="5px" stroke-linecap="round"
              marker-end="url(#arrow)"></path>
        <path v-if="(data.originPosition === linePosition.Right) && data.targetPosition === linePosition.Left "
              :d="'M ' + data.x1 + ' ' + data.y1 + ' H ' + (data.targetElement !== null ? data.targetElement.data.x - (Math.abs(data.targetElement.data.x - data.x1)/2) : data.x1 + (Math.abs(data.x2 - data.x1) / 2)) + ' V ' +  data.y2 + ' H ' + (data.targetElement !== null && !data.targetMovable ? data.targetElement.data.x - 6.5 : data.x2)"
              @mousedown="makeTargetMovable" v-on:touchstart="makeTargetMovable"
              fill="transparent" stroke="black" stroke-width="5px" stroke-linecap="round"
              marker-end="url(#arrow)"></path>
        <path v-if="(data.originPosition === linePosition.Right) && data.targetPosition === linePosition.Right"
              :d="'M ' + data.x1 + ' ' + data.y1 + ' H ' + (data.x2 - (Math.abs(data.x2 - data.x1)/2)) + ' V ' +  data.y2 + ' H ' + (data.targetElement !== null && !data.targetMovable ? data.targetElement.data.x + data.targetElement.getWidth() + 6.5 : data.x2)"
              @mousedown="makeTargetMovable" v-on:touchstart="makeTargetMovable"
              fill="transparent" stroke="black" stroke-width="5px" stroke-linecap="round"
              marker-end="url(#arrow)"></path>
        <path v-if="(data.originPosition === linePosition.Left) &&  data.targetPosition === linePosition.Right"
              :d="'M ' + data.x1 + ' ' + data.y1 + ' H ' + (data.targetElement !== null ? data.targetElement.data.x + data.targetElement.getWidth() + (Math.abs((data.targetElement.data.x + data.targetElement.getWidth()) - data.x1)/2) : data.x1 - (Math.abs(data.x2 - data.x1) / 2)) + ' V ' +  data.y2 + ' H ' + (data.targetElement !== null && !data.targetMovable ? data.targetElement.data.x  + data.targetElement.getWidth() + 6.5 : data.x2)"
              @mousedown="makeTargetMovable" v-on:touchstart="makeTargetMovable"
              fill="transparent" stroke="black" stroke-width="5px" stroke-linecap="round"
              marker-end="url(#arrow)"></path>
        <path v-if="(data.originPosition === linePosition.Left) && data.targetPosition === linePosition.Left"
              :d="'M ' + data.x1 + ' ' + data.y1 + ' H ' + (data.x2 + (Math.abs(data.x2 - data.x1)/2)) + ' V ' +  data.y2 + ' H ' + (data.targetElement !== null && !data.targetMovable ? data.targetElement.data.x - 6.5 : data.x2)"
              @mousedown="makeTargetMovable" v-on:touchstart="makeTargetMovable"
              fill="transparent" stroke="black" stroke-width="5px" stroke-linecap="round"
              marker-end="url(#arrow)"></path>
    </g>
</template>

<script lang="ts">
    import {Prop, Component, Mixins, Watch} from 'vue-property-decorator'
    import {CoordiantesMixin} from "../mixins/CoordiantesMixin";
    import LineData from "../DTO/LineData";
    import {LinePosition} from "../DTO/LinePosition";
    import LoopElement from "./LoopElement.vue";


    @Component
    export default class LineConnection extends Mixins(CoordiantesMixin) {
        @Prop() public data!: LineData;

        private linePosition = LinePosition;

        constructor($options?) {
            super($options);
            this.makeMovable();
        }

        //Initialize Line to make dragging possible
        public makeMovable() {
            // event listener to detect object dragging
            this.data.svg.addEventListener('mousemove', this.moveTargetWithMouse);
            this.data.svg.addEventListener('mouseup', this.makeTargetUnmovable);

            this.data.svg.addEventListener('touchmove', this.moveTargetWithTouch);
            this.data.svg.addEventListener('touchend', this.makeTargetUnmovable);
            this.data.svg.addEventListener('touchleave', this.makeTargetUnmovable);
            this.data.svg.addEventListener('touchcancel', this.makeTargetUnmovable);

            this.pt = this.data.svg.createSVGPoint();
        }

        //Calculate new position for element while dragging. count in cursor position relative to draggable element
        public moveTargetWithMouse(event: MouseEvent) {
            if (this.data.targetMovable) {

                const coords = this.cursorPoint(event, this.data.svg);
                this.moveTarget(coords.x, coords.y);
                this.$emit('checkLine');
            }
        }

        //Calculate new position for element while dragging. count in cursor position relative to draggable element
        public moveTargetWithTouch(event: TouchEvent) {
            if (this.data.targetMovable) {
                const coords = this.touchPoint(event, this.data.svg);
                this.moveTarget(coords.x, coords.y);
                this.$emit('checkLine');
            }
        }

        //Set Line Origin to given Position
        public moveOrigin(x: number, y: number) {
            this.data.x1 = x;
            this.data.y1 = y;
            this.checkPosition();
        }

        //Set Line Target to given Position
        public moveTarget(x: number, y: number) {
            this.data.x2 = x;
            this.data.y2 = y;
            this.checkPosition();
        }


        //check position of targetElement in relation to originElement to adjust on which side of the elements the line will connect
        public checkPosition() {
            const originElement = this.data.originElement;
            let targetX, targetY, targetLoopY, targetHeight, targetLoopHeight, targetLoopWidth;
            if (this.data.targetElement !== null) {
                targetX = this.data.targetElement.data.x;
                targetY = this.data.targetElement.data.y;
                targetLoopY = this.data.targetElement.getY();
                targetHeight = this.data.targetElement.getHeight();
                targetLoopHeight = this.data.targetElement.data.type === 'LoopStart' ? (this.data.targetElement as LoopElement).data.loopPart.data.loopHeight : this.data.targetElement.getHeight();
                targetLoopWidth = this.data.targetElement.data.type === 'LoopStart' ? (this.data.targetElement as LoopElement).data.loopPart.data.loopWidth : this.data.targetElement.getWidth();

            } else {
                targetX = this.data.x2;
                targetY = this.data.y2;
                targetLoopY = targetY;
                targetHeight = 0;
                targetLoopHeight = 0;
                targetLoopWidth = 0;
            }
            /*right of originElement
            *   |       |       |   x   |
            *   |       |       |   x   |
            *   |-------|Element|---x---|
            *   |       |       |   x   |
            *   |       |       |   x   |
             */
            if (targetX > (originElement.data.x + originElement.getWidth())) {
                /*lower than originElement
               *   |       |       |       |
               *   |       |       |       |
               *   |-------|Element|-------|
               *   |       |       |   x   |
               *   |       |       |   x   |
                */
                if (targetY > (originElement.data.y + (originElement.getHeight()/2) + 15)) {
                        this.data.targetPosition = this.linePosition.Top;
                        this.data.originPosition = this.linePosition.Right;
                }
                /*higher than originElement
                  *   |       |       |   x   |
                  *   |       |       |   x   |
                  *   |-------|Element|-------|
                  *   |       |       |       |
                  *   |       |       |       |
                   */
                else if ((targetY + targetLoopHeight) < (originElement.data.y + (originElement.getHeight()/2) - 15)) {
                        this.data.targetPosition = this.linePosition.Bottom;
                        this.data.originPosition = this.linePosition.Right;
                }
                //on same height as OriginElement
                else {
                    this.data.targetPosition = this.linePosition.Left;
                    this.data.originPosition = this.linePosition.Right;
                }
            }
            /*left of originElement
              *   |   x   |       |       |
              *   |   x   |       |       |
              *   |---x---|Element|-------|
              *   |   x   |       |       |
              *   |   x   |       |       |
               */
            else if ((targetX + targetLoopWidth) < (originElement.data.x)) {
                /*lower than originElement
                *   |       |       |       |
                *   |       |       |       |
                *   |-------|Element|-------|
                *   |   x   |       |       |
                *   |   x   |       |       |
                 */
                if (targetY > (originElement.data.y + (originElement.getHeight()/2) + 15)) {
                        this.data.targetPosition = this.linePosition.Top;
                        this.data.originPosition = this.linePosition.Left;
                }
                /*higher than originElement
               *   |   x   |       |       |
               *   |   x   |       |       |
               *   |-------|Element|-------|
               *   |       |       |       |
               *   |       |       |       |
                */
                else if ((targetY + targetLoopHeight) < (originElement.data.y + (originElement.getHeight()/2) - 15)) {
                        this.data.targetPosition = this.linePosition.Bottom;
                        this.data.originPosition = this.linePosition.Left;
                }
                //on same height as OriginElement
                else {
                    this.data.targetPosition = this.linePosition.Right;
                    this.data.originPosition = this.linePosition.Left;
                }
            }
            /*same x-position as originElement
            *   |       |   x   |       |
            *   |       |   x   |       |
            *   |-------|Element|-------|
            *   |       |   x   |       |
            *   |       |   x   |       |
             */
            else {
                /*lower than originElement
                *   |       |       |       |
                *   |       |       |       |
                *   |-------|Element|-------|
                *   |       |   x   |       |
                *   |       |   x   |       |
                 */
                if (targetY + targetHeight > (originElement.data.y + originElement.getHeight())) {
                    this.data.targetPosition = this.linePosition.Top;
                    this.data.originPosition = this.linePosition.Bottom;
                }
                /*higher than originElement
                  *   |       |   x   |       |
                  *   |       |   x   |       |
                  *   |-------|Element|-------|
                  *   |       |       |       |
                  *   |       |       |       |
                   */
                else {
                    this.data.targetPosition = this.linePosition.Bottom;
                    this.data.originPosition = this.linePosition.Top;
                }
            }
        }

        //Set the Line to draggable, so that the target moves with the cursor. Emit Event so that all Element listen for this line in order to connect to it
        public makeTargetMovable() {
            this.data.targetMovable = true;
            this.$emit('checkLine');
        }

        //Reset the Line to not draggable. Emit Event so that no Element listen anymore
        public makeTargetUnmovable() {
            if (this.data.targetElement !== null && this.data.targetMovable) {
                this.data.targetElement.move(this.data.targetElement.data.x, this.data.targetElement.data.y);
                this.data.targetElement.data.colour = this.data.targetElement.data.primaryColour;
            }
            this.data.targetMovable = false;

            this.$emit('lineDropped', this.data);
            this.$forceUpdate();
        }

    }
</script>

<style scoped>
    .line {
        stroke:       rgb(0, 0, 0);
        stroke-width: 10;
    }

    .textLeft {
        text-align: right;
    }

    .text {
        text-align: left;
    }
</style>