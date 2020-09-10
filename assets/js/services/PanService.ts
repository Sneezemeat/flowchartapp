import {ZoomService} from "./ZoomService";

export class PanService {

    private startPoint;
    private endPoint;
    public panningPossible = true;
    public isPanning = false;
    private elements = [];
    private lines = [];
    private viewBox;
    private svg;

    constructor(elements, lines, viewBox, svg) {
        this.elements = elements;
        this.lines = lines;
        this.viewBox = viewBox;
        this.svg = svg;
    }

    //Start moving whole svg-document. Set to movable if no element is touched
    startPanning(e) {
        if ((e.touches && e.touches.length == 2) || !this.panningPossible) {
            this.isPanning = false;
            this.panningPossible = true;
            return;
        }
        if (e.touches) {
            this.startPoint = {x: e.touches[0].clientX, y: e.touches[0].clientY};
        } else {
            this.startPoint = {x: e.x, y: e.y};
        }
        this.isPanning = true;
        this.elements.forEach(element => {
            if (element.targetMovable) {
                this.isPanning = false;
            }
        });
        this.lines.forEach(line => {
            if (line.targetMovable) {
                this.isPanning = false;
            }
        });
        return this.isPanning;
    }

    //Move whole svg-document by moving the viewbox to different position
    panning(e, viewBox, scale) {

        if (this.isPanning) {
            if (e.touches) {
                this.endPoint = {x: e.touches[0].clientX, y: e.touches[0].clientY};
            } else {
                this.endPoint = {x: e.x, y: e.y};

            }

            const dx = (this.startPoint.x - this.endPoint.x) / scale;
            const dy = (this.startPoint.y - this.endPoint.y) / scale;
            return {
                x: viewBox.x + dx,
                y: viewBox.y + dy,
                w: viewBox.w,
                h: viewBox.h
            };
        }
        else{
            return viewBox;
        }
    }

    //Stop moving the svg-whole document. Calculate final viewbox position
    stopPanning(e, viewBox, scale) {
        if (this.isPanning) {
            if (e.changedTouches) {
                this.endPoint = {x: e.changedTouches[0].clientX, y: e.changedTouches[0].clientY};
            } else {
                this.endPoint = {x: e.x, y: e.y};
            }
            const dx = (this.startPoint.x - this.endPoint.x) / scale;
            const dy = (this.startPoint.y - this.endPoint.y) / scale;
            const newViewBox = {x: viewBox.x + dx, y: viewBox.y + dy, w: viewBox.w, h: viewBox.h};
            this.svg.setAttribute('viewBox', `${newViewBox.x} ${newViewBox.y} ${newViewBox.w} ${newViewBox.h}`);
            this.isPanning = false;
            return newViewBox;
        }

    }
}