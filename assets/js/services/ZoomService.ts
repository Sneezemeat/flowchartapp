
export class ZoomService {

    public lastTouchDistance;
    public scale = 1;
    public zoomable = false;
    public svgSize;
    private elements = [];
    private lines = [];
    private viewBox;
    private svg;

    constructor(elements, lines, viewBox, svg) {

        this.elements = elements;
        this.lines = lines;
        this.viewBox = viewBox;
        this.svg = svg;

        this.svgSize = {w: this.svg.getAttribute('width'), h: this.svg.getAttribute('height')};
    }

    //initialize zoom if two-finger-touch is used. calculate starting distance between both touch points
    startZoom(event) {
        //Zoom by using to fingers on touch
        if (event.touches && event.touches.length == 2) {
            this.zoomable = true;
            //Only zoomable if no element or line is touched
            this.elements.forEach(element => {
                if (element.targetMovable) {
                    this.zoomable = false;
                }
            });
            this.lines.forEach(line => {
                if (line.targetMovable) {
                    this.zoomable = false;
                }
            });

            this.lastTouchDistance = this.touchDistance(event.touches[0], event.touches[1]);
        }
        //Zoom by using ctrl+scroll on desktop
        if (event.ctrlKey) {
            this.zoomable = true;
        }
    }

    touchDistance(touch1: Touch, touch2: Touch): number {
        //Vector between two touch points
        const vector = {x: 0, y: 0};
        vector.x = touch1.clientX - touch2.clientX;
        vector.y = touch1.clientY - touch2.clientY;
        //Length of vector
        const x2 = Math.pow(vector.x, 2);
        const y2 = Math.pow(vector.y, 2);
        return Math.sqrt(x2 + y2);
    }

    //zoom in on svg-document with viewbox by setting new viewbox position. differentiate two-finger-touch and desktop-scroll.
    zoom(e, viewBox) {

        let zoomCenterX, zoomCenterY, zoomSpeedX, zoomSpeedY;
        const viewboxWidth = viewBox.w;
        const viewboxHeight = viewBox.h;

        if (!this.zoomable) {
            return;
        }

        e.preventDefault();
        //Zoom by using to fingers on touch
        if (e.touches && e.touches.length == 2) {
            const distance = this.touchDistance(e.touches[0], e.touches[1]);
            /*Calculate difference between new and old distance of
            *two fingers and calculate center of both fingers for direction of zooming*/
            const zoomDirection = Math.sign(-(this.lastTouchDistance - distance));
            this.lastTouchDistance = distance;
            zoomCenterX = (e.touches[0].clientX + e.touches[1].clientX) / 2;
            zoomCenterY = (e.touches[0].clientY + e.touches[1].clientY) / 2;

            //Calculate "speed" of zooming in relation to svg-document size
            zoomSpeedX = viewboxWidth * zoomDirection * 0.025;
            zoomSpeedY = viewboxHeight * zoomDirection * 0.025;
        }
        //Zoom by using ctrl+scroll on desktop
        else {
            zoomCenterX = e.x;
            zoomCenterY = e.y;
            const zoomDirection =  Math.sign(-e.deltaY);
            //Calculate "speed" of zooming in relation to viewbox size
            zoomSpeedX = viewboxWidth * zoomDirection * 0.05;
            zoomSpeedY = viewboxHeight * zoomDirection * 0.05;
        }

        //Calculate new position of viewbox in relation to svg-document size
        const viewboxX = (zoomSpeedX * zoomCenterX) / this.svgSize.w;
        const viewboxY = (zoomSpeedY * zoomCenterY) / this.svgSize.h;

        const newViewBox =  {
            x: viewBox.x + viewboxX,
            y: viewBox.y + viewboxY,
            w: viewBox.w - zoomSpeedX,
            h: viewBox.h - zoomSpeedY
        };
        //important for panning
        this.scale = newViewBox.w / newViewBox.w;

        return newViewBox;
    }

    stopZoom() {
        this.zoomable = false;

    }
}