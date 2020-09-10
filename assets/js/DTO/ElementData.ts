
import Line from "../components/LineConnection.vue";
import {Connection} from "./Connection";
import { v4 as uuidv4 } from 'uuid';

export default class ElementData {
    // public id: number;
    public id: string;
    public type: string;
    public x: number;
    public y: number;
    public xGrid: number;
    public yGrid: number;
    public width: number;
    public height: number;
    public scale: number;
    public colour: string;
    public primaryColour: string;
    public text: string;
    public svg: SVGSVGElement;
    public hover = false;
    public connect = false;
    public self = false;
    public creatorHoverT: boolean;
    public creatorHoverR: boolean;
    public creatorHoverB: boolean;
    public creatorHoverL: boolean;
    public offset = {
        x: 0,
        y: 0,
    };
    public targetMovable!: boolean;
    public lineOrigins: Line[];
    public lineEnds: Line[];
    public connections: Connection[];
    public moving: boolean;
    public deleteLines: boolean;
    public highlight: boolean;

    constructor(type: string, id: number, x: number, y: number, width: number, height: number, text: string, svg: SVGSVGElement) {
        this.type = type;
        // this.id = id;
        this.id = uuidv4();
        this.scale = 1;
        this.x = x;
        this.y = y;
        this.width = width;
        this.height = height;
        this.text = text;
        this.svg = svg;
        this.moving = false;
        this.deleteLines = false;
        this.lineOrigins = [];
        this.lineEnds = [];
        this.connections = [];
        this.hover = false;
        this.connect = false;
        this.highlight = false;
        this.creatorHoverT = false;
        this.creatorHoverR = false;
        this.creatorHoverB = false;
        this.creatorHoverL = false;
        switch (type) {
            case 'Process': {
                this.colour = '#006600';
                break;
            }
            case 'SubProcess': {
                this.colour = '#006600';
                break;
            }
            case 'Input': {
                this.colour = '#1e3d7b';
                break;
            }
            case 'Output': {
                this.colour = '#1e3d7b';
                break;
            }
            case 'Condition': {
                this.colour = '#cc9900';
                break;
            }
            case 'Start': {
                this.colour = '#000000';
                break;
            }


        }
        this.primaryColour = this.colour;
    }

    equals(element): boolean {
        return this.x == element.data.x && this.y == element.data.y && this.width == element.data.width && this.height == element.data.height;
    }

}