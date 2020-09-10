import {Prop, Component, Mixins} from 'vue-property-decorator'
import LineConnection from "./LineConnection.vue"
import {CoordiantesMixin} from "../mixins/CoordiantesMixin";
import ElementData from "../DTO/ElementData";
import Process from "./Elements/Process.vue";
import Condition from "./Elements/Condition.vue";
import Input from "./Elements/Input.vue";
import Output from "./Elements/Output.vue";
import SubProcess from "./Elements/SubProcess.vue";
import Start from "./Elements/Start.vue";
import LoopStart from "./Elements/LoopStart.vue";
import LoopEnd from "./Elements/LoopEnd.vue";
import {Connection} from "../DTO/Connection";
import LoopElementData from "../DTO/LoopElementData";

@Component({
    components: {Process, Condition, Input, Output, SubProcess, Start, LoopStart, LoopEnd}
})
export default class Movable extends Mixins(CoordiantesMixin) {
    @Prop() public data!: ElementData;
    @Prop() public grid!: number;

    created() {
        this.makeMovable();
    }

    destruct(){
        this.data.svg.removeEventListener('mousemove', this.moveTarget);
        this.data.svg.removeEventListener('mouseup', this.makeTargetUnmovable);

        this.data.svg.removeEventListener('touchmove', this.moveTargetTouch);
        this.data.svg.removeEventListener('touchend', this.makeTargetUnmovable);
        this.data.svg.removeEventListener('touchleave', this.makeTargetUnmovable);
        this.data.svg.removeEventListener('touchcancel', this.makeTargetUnmovable);
    }

    //Initialize Element to make dragging possible
    public makeMovable() {
        // event listeners to detect object dragging
        this.data.svg.addEventListener('mousemove', this.moveTarget);
        this.data.svg.addEventListener('mouseup', this.makeTargetUnmovable);

        this.data.svg.addEventListener('touchmove', this.moveTargetTouch);
        this.data.svg.addEventListener('touchend', this.makeTargetUnmovable);
        this.data.svg.addEventListener('touchleave', this.makeTargetUnmovable);
        this.data.svg.addEventListener('touchcancel', this.makeTargetUnmovable);

        this.pt = this.data.svg.createSVGPoint();
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
            this.$emit('ElementMoved');
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
            this.$emit('ElementMoved');
        }
    }

    //Move an element to a new position. if it is a loop, also move all elements inside loop
    public move(x, y) {
        this.data.moving = true;

        this.data.x = x;
        this.data.y = y;

        this.moveLines();

        this.$emit('checkLoop');
    }

    //move all lines connected to this element
    public moveLines() {
        this.data.moving = true;
        this.data.lineOrigins.forEach(l => {
            const line = l as LineConnection;
            // eslint-disable-next-line @typescript-eslint/ban-ts-ignore
            // @ts-ignore
            line.moveOrigin(this.data.x + (this.getWidth() / 2), this.data.y + (this.data.height / 2) + (this.data.type === "LoopEnd" ? (this.data as LoopElementData).loopHeight : 0));
        });

        this.data.lineEnds.forEach(l => {
            const line = l as LineConnection;
            // eslint-disable-next-line @typescript-eslint/ban-ts-ignore
            // @ts-ignore
            line.moveTarget(this.data.x + ((this.getWidth()) / 2), this.data.y + (this.data.height / 2) + (this.data.type === "LoopEnd" ? (this.data as LoopElementData).loopHeight : 0));
        });
    }



    //Calculate offset of svg-root element to document and set element to draggable
    public makeTargetMovable(event: MouseEvent) {
        const coords = this.cursorPoint(event, this.data.svg);
        this.data.offset.x = coords.x - this.data.x;
        this.data.offset.y = coords.y - this.data.y;
        this.data.targetMovable = true;
        this.data.colour = 'black';
        this.$emit('ElementClicked', this.data);
    }

    //Calculate offset of svg-root element to document and set element to draggable
    public makeTargetMovableTouch(event: TouchEvent) {
        const coords = this.touchPoint(event, this.data.svg);
        this.data.offset.x = coords.x - this.data.x;
        this.data.offset.y = coords.y - this.data.y;
        this.data.targetMovable = true;
        this.data.colour = 'black';
        this.$emit('ElementClicked', this.data);
    }

    //reset element to not draggable
    public makeTargetUnmovable() {
        this.data.targetMovable = false;
        this.moveLines();
        this.data.self = false;
        this.data.connect = false;
        this.$emit("ElementDropped", this.data);
    }

    //check if this element is equal to another
    public equal(movable: Movable) {
        return this.data.x === movable.data.x && this.data.y === movable.data.y && this.data.width === movable.data.width && this.data.height === movable.data.height;
    }

    public addLineOrigin(line: LineConnection) {
        if (this.data.lineOrigins === null) {
            this.data.lineOrigins = [];
        }
        this.data.lineOrigins.push(line as LineConnection);
    }

    public addLineEnd(line: LineConnection) {
        this.data.lineEnds.push(line as LineConnection);
    }

    public addConnection(connection: Connection) {
        this.data.connections.push(connection);
    }

    public getHeight(){
        return this.data.height;
    }

    public getWidth(){
        return this.data.width;
    }

    public getY(){
        return this.data.y;
    }

    public getX(){
        return this.data.x;
    }

}
