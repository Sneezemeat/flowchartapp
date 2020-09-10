
import ElementData from "./ElementData";
import LoopElement from "../components/LoopElement.vue";
import Movable from "../components/Movable";


export default class LoopElementData extends ElementData {
    public loopElements: Movable[];
    public isLoop: boolean;
    public loopHeight: number;
    public loopWidth: number;
    public loopSizeOld: number;
    public loopPart: LoopElement;
    public enlargeLoopHorizontal: boolean;
    public enlargeLoopVertical: boolean;
    public highlight: boolean;

    constructor(type: string, id: number, x: number, y: number, width: number, height: number, text: string, svg: SVGSVGElement, isLoop: boolean, loopHeight: number) {
        super(type, id, x, y, width, height, text, svg);
        this.isLoop = isLoop;
        this.loopElements = [];
        this.loopHeight = loopHeight;
        this.loopWidth = 0;
        this.enlargeLoopHorizontal = false;
        this.enlargeLoopVertical = false;
        this.loopSizeOld = 0;
        switch (type) {
            case 'LoopStart': {
                this.colour = '#cc9900';
                break;
            }
            case 'LoopEnd': {
                this.colour = '#cc9900';
                break;
            }
        }
    }

    equals(element): boolean {
        return this.x == element.data.x && this.y == element.data.y && this.width == element.data.width && this.height == element.data.height;
    }

}