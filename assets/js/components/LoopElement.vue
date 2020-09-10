<template>
    <g @mouseover="() => {!data.connect && !data.self ? data.hover = true : data.hover = false}"
       @mouseout="() => {data.hover = false; data.connect = false}" id="rect_group" class="noselect">
        <Component :x="data.x" :y="data.y + data.loopHeight" :width="data.width + data.loopWidth" :height="data.height"
                   :colour="data.colour"
                   :loopHeight="data.loopHeight"
                   :loopWidth="data.loopWidth"
                   :highlight="data.highlight"
                   :text="data.text" :scale="1"
                   class="draggable"
                   @mousedown.native="makeTargetMovable"
                   v-on:touchstart.native="makeTargetMovableTouch"
                   ref="rect" pointer-events="all"
                   v-bind:is="data.type"
        ></Component>

        <svg :x="data.x + (data.width + data.loopWidth)/2 - 17" :y="data.y + data.loopHeight - 30"
             width="34" :height="[data.type === 'Condition'] ? '45': '32'"
             v-show="data.hover && !data.connect"
             @mouseover="($event) => {data.creatorHoverT = true}"
             @mouseout="() => {data.creatorHoverT = false}"
             @mousedown="$emit('createLine', data.x + (data.width + data.loopWidth)/2, data.y, linePosition.Top)"
             id="creatorHoverT" ref="creatorHoverT">
            <polygon points="9.5,23.5 9.5,12.25 2,12.25 17,1 32,12.25 24.5,12.25 24.5,23.5" stroke="black"
                     stroke-width="2"
                     :fill="[data.creatorHoverT ? 'black' : 'transparent']"></polygon>
            <rect v-if="data.type === 'Condition'" fill="transparent" x="0" y="0" width="30px" height="45px"
                  stroke="transparent"></rect>
            <rect v-else fill="transparent" x="0" y="0" width="30px" height="30px" stroke="transparent"></rect>
        </svg>

        <svg :x="[(data.type === 'Condition' || data.type === 'Input' || data.type === 'Output' || data.type === 'LoopEnd' || data.type === 'LoopStart') ? data.x + data.width + data.loopWidth - 13 : data.x + data.width + data.loopWidth]"
             :y="data.y + data.height/2 + data.loopHeight - 16"
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


        <svg :x="data.x + (data.width + data.loopWidth)/2 - 17"
             :y="[data.type === 'Condition' ? data.y + data.height + data.loopHeight - 13 : data.y + data.height + data.loopHeight]"
             width="34" :height="[data.type === 'Condition'] ? '45': '32'"
             v-show="data.hover && !data.connect"
             @mouseover="() => {data.creatorHoverB = true}"
             @mouseout="() => {data.creatorHoverB = false}"
             @mousedown="$emit('createLine', data.x + (data.width + data.loopWidth)/2, data.y + data.height, linePosition.Bottom)"
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


        <svg :x="data.x - 30" :y="data.y + data.height/2 + data.loopHeight - 16"
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
    import {Prop, Component} from 'vue-property-decorator'
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
    import LoopElementData from "../DTO/LoopElementData";


    @Component({
        components: {Process, Condition, Input, Output, SubProcess, Start, LoopStart, LoopEnd, Movable}
    })
    export default class LoopElement extends Movable {
        @Prop() public data!: LoopElementData;

        private linePosition = LinePosition;

        constructor($options?) {
            super($options);
            this.makeMovable();
        }

        //Calculate new position for element while dragging. count in cursor position relative to draggable element.
        public moveTarget(event: MouseEvent) {
            if (this.data.targetMovable) {
                const coords = this.cursorPoint(event, this.data.svg);
                this.data.xGrid = coords.x - this.data.offset.x;
                this.data.yGrid = coords.y - this.data.offset.y;

                const x = this.grid * Math.round(this.data.xGrid / this.grid);
                const y = this.grid * Math.round(this.data.yGrid / this.grid);
                this.move(x, y);

                this.data.loopPart.moveLoop();
                this.$emit('ElementMoved');
            }
        }

        //Move an element to a new position. if it is a loop, also move all elements inside loop
        public move(x, y) {
            const oldY = this.data.y;
            const oldX = this.data.x;

            this.data.moving = true;

            this.data.x = x;
            this.data.y = y;

            this.moveLines();

            this.data.loopElements.forEach(element => {
                element.move(x + (element.data.x - oldX), y + (element.data.y - oldY));
            });

            this.$emit('checkLoop');
        }

        //if a loop is moved, also move other part of it
        public moveLoop() {
            if (this.data.type === "LoopStart" || this.data.type === "LoopEnd") {
                const x = this.data.loopPart.data.x;
                const y = this.data.loopPart.data.y;
                this.move(x, y);
            }
        }

        //Calculate new position for element while dragging. count in cursor position relative to draggable element.
        //Move start and endline of the element if present
        public moveTargetTouch(event: TouchEvent) {
            if (this.data.targetMovable) {
                this.data.moving = true;
                const coords = this.touchPoint(event, this.data.svg);
                this.data.xGrid = coords.x - this.data.offset.x;
                this.data.yGrid = coords.y - this.data.offset.y;

                const x = this.grid * Math.round(this.data.xGrid / this.grid);
                const y = this.grid * Math.round(this.data.yGrid / this.grid);
                this.move(x, y);

                if (this.data.loopPart !== null && typeof this.data.loopPart !== "undefined") {
                    this.data.loopPart.moveLoop();
                }
            }
        }

        //reset element to not draggable
        public makeTargetUnmovable() {
            this.data.enlargeLoopVertical = true;
            this.data.enlargeLoopHorizontal = true;

            this.data.targetMovable = false;
            this.moveLines();
            this.data.self = false;
            this.data.connect = false;
            this.data.highlight = false;
            this.$emit("ElementDropped", this.data);
        }

        //enlarge a loop vertically with the height of the element that was added or remove it. First remove it so that it cannot enlarged twice
        public enlargeLoopVertical(element) {
            if (this.data.type === "LoopEnd") {
                let height = this.data.loopHeight;
                if (element.data.inLoop !== null && element.data.inLoop !== this && typeof element.data.inLoop !== "undefined") {
                    const index = element.data.inLoop.data.loopElements.indexOf(element);
                    element.data.inLoop.data.loopElements.splice(index);
                }
                if (this.data.loopElements.length !== 0 && element.data.enlargeLoopVertical) {
                    this.data.loopSizeOld = this.data.loopHeight;
                    height = 60 + element.getHeight() + this.data.loopHeight
                    element.data.enlargeLoopVertical = false;
                }
                this.data.loopHeight = height;
                element.data.inLoop = this;
                this.moveLines();
            }
        }

        //enlarge a loop horizontally with the width of the element that was added. First remove it so that it cannot enlarged twice
        public enlargeLoopHorizontal(element) {
            if (this.data.type === "LoopEnd") {
                let width = this.data.loopWidth;
                if (element.data.inLoop !== null && element.data.inLoop !== this && typeof element.data.inLoop !== "undefined") {
                    const index = element.data.inLoop.data.loopElements.indexOf(element);
                    element.data.inLoop.data.loopElements.splice(index);
                }

                if (element.data.enlargeLoopHorizontal) {
                    this.data.loopSizeOld = this.data.loopWidth;
                    width = 15 + element.getWidth() + this.data.loopWidth;
                    element.data.enlargeLoopHorizontal = false;
                }

                this.data.loopWidth = width;
                const loopPart = this.data.loopPart as LoopElement;
                loopPart.data.loopWidth = width;
                element.data.inLoop = this;
                this.moveLines();
            }
        }

        //check the borders of all elements inside loop, then shrink it down to these borders.
        public downsizeLoop(element) {
            if (this.data.loopElements.length > 0) {
                let minX = Infinity, maxX = Number.NEGATIVE_INFINITY,
                    minY = Infinity, maxY = Number.NEGATIVE_INFINITY;

                this.data.loopElements.forEach(loopElement => {
                    if (loopElement.data.x < minX) {
                        minX = loopElement.data.x;
                    }
                    if (loopElement.data.y < minY) {
                        minY = loopElement.data.y;
                    }
                    if (loopElement.data.x + loopElement.getWidth() > maxX) {
                        maxX = loopElement.data.x + loopElement.getWidth();
                    }
                    if (loopElement.data.y + loopElement.getHeight() > maxY) {
                        maxY = loopElement.data.y + loopElement.getHeight();
                    }
                });
                const widthDiff = Math.abs(maxX - this.data.x);
                this.data.loopWidth = widthDiff - this.data.width;
                const heightDiff = Math.abs(maxY - this.data.y);
                this.data.loopHeight = heightDiff + 30;

                this.data.loopPart.data.loopWidth = this.data.loopWidth;
            } else {
                this.data.loopHeight = 150;
                this.data.loopWidth = 0;
                this.data.loopPart.data.loopWidth = 0;
            }
            if (element) {
                element.data.enlargeLoopHorizontal = true;
                element.data.enlargeLoopVertical = true;
            }
        }

        public getHeight() {
            return this.data.height + this.data.loopHeight;
        }

        public getWidth() {
            return this.data.width + this.data.loopWidth;
        }

        public getY() {
            return this.data.y + this.data.loopHeight;
        }

        public getX() {
            return this.data.x + this.data.loopWidth;
        }
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