
import {LinePosition} from "./LinePosition";
import Movable from "../components/Movable";
import { v4 as uuidv4 } from 'uuid';

export default class LineData {
    // public id: number;
    public id: string;
    public x1: number;
    public y1: number;
    public x2: number;
    public y2: number;
    public svg: SVGSVGElement;
    public originElement: Movable;
    public originPosition: LinePosition;
    public targetElement: Movable;
    public targetPosition: LinePosition;
    public targetMovable: boolean;
    public validationText: string;
    public text: string;
    public textWidth: number;

    constructor(id: number, x1: number, y1: number, x2: number, y2: number, originPosition: LinePosition, targetPosition: LinePosition, svg: SVGSVGElement, originElement: Movable) {
        // this.id = id;
        this.id = uuidv4();
        this.x1 = x1;
        this.y1 = y1;
        this.x2 = x2;
        this.y2 = y2;
        this.originPosition = originPosition;
        this.targetPosition = targetPosition;
        this.svg = svg;
        this.originElement = originElement;
        this.targetElement = null;
        this.text = "";
        this.textWidth = 0;
    }


}