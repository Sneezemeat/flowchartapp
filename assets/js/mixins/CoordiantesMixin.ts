import Vue from "vue"
import Component from "vue-class-component";
// import {ComponentOptions} from "vue/types/options";

@Component
export class CoordiantesMixin extends Vue {

    protected pt!: SVGPoint;

    // constructor(options?: ComponentOptions<Vue>) {
    //     super(options);
    // }


    //Calculate cursor position relative to svg root element
    protected cursorPoint(evt: MouseEvent, svg) {
        this.pt.x = evt.clientX;
        this.pt.y = evt.clientY;
        const CTM = svg.getScreenCTM();
        if (CTM) {
            return this.pt.matrixTransform(CTM.inverse());
        } else {
            return this.pt;
        }
    }

    //Calculate touch position relative to svg root element
    protected touchPoint(evt: TouchEvent, svg) {

        const touch: Touch = evt.touches[0];

        this.pt.x = touch.clientX;
        this.pt.y = touch.clientY;
        const CTM = svg.getScreenCTM();
        if (CTM) {
            return this.pt.matrixTransform(CTM.inverse());
        } else {
            return this.pt;
        }
    }

}