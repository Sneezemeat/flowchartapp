import LineConnection from "../components/LineConnection.vue";
import {Connection} from "../DTO/Connection";
import LoopElementData from "../DTO/LoopElementData";
import Movable from "../components/Movable";

export class IntersectionService {

    //Check if an element is dragged in or out of a loop, then enlarge or downsize loop
    checkLoop(element, refElement, elements, refElements, elementDropped): boolean {
        elements.forEach(l => {
            const loop = l as LoopElementData;
            const loopPart = loop.loopPart as Movable;

            if (element.id !== loop.id
                && (loop.type === "LoopEnd")
                && element.type !== "Start"
                && this.checkLoopIntersection(element.x + (element.width / 2), element.y + (element.height / 2), refElements.find(e => e.data.id === loop.id))
                && (loopPart !== null && typeof loopPart !== "undefined" && element.id !== loopPart.data.id)) {

                element.deleteLines = false;
                loop.highlight = true;

                //If Element is dragged to the lower border of the loop, enlarge it vertically
                if (loop.loopElements !== null && typeof loop.loopElements !== "undefined" && loop.loopElements.length === 0 || (element.y + (element.height / 2)) >= (loop.y + loop.loopHeight)) {
                    refElements.find(e => e.data.id === loop.id).enlargeLoopVertical(refElement);
                }
                //If the Element has already enlarged the Loop Veritcaly once, and it does not touch the lower border of the loop anymore, downsize it back.
                else if (!element.enlargeLoopVertical && (element.y + (element.height / 2)) < (loop.y + loop.loopSizeOld)) {
                    refElements.find(e => e.data.if === loop.id).downsizeLoop(refElement);
                }

                //If Element is dragged to the right border of the loop, enlarge it horizontally
                if (loop.loopElements !== null && typeof loop.loopElements !== "undefined" && loop.loopElements.length !== 0 && ((element.x + (element.width / 2)) >= (loop.x + loop.width + loop.loopWidth))) {
                    refElements.find(e => e.data.id === loop.id).enlargeLoopHorizontal(refElement);
                }
                //If the Element has already enlarged the Loop horizontally once, and it does not touch the right border of the loop anymore, downsize it back.
                else if (loop.loopElements.length !== 0 && !element.enlargeLoopHorizontal && ((element.x + (element.width / 2)) < (loop.x + loop.width + loop.loopSizeOld - 15))) {
                    refElements.find(e => e.data.id === loop.id).downsizeLoop(refElement);
                }

                //If element is dropped inside loop, add it to the list of elements of the loop
                if ((typeof loop.loopElements !== "undefined" && loop.loopElements.length > 0) || elementDropped) {
                    const index = loop.loopElements.indexOf(refElement);
                    if (index === -1) {
                        element.enlargeLoopVertical = true;
                        element.enlargeLoopHorizontal = true;
                        loop.loopElements.push(refElement);
                    }
                    elementDropped = false;
                    if (!element.targetMovable) {
                        loop.highlight = false;
                    }
                }

            } else if (
                loop.loopElements !== null
                && typeof loop.loopElements !== "undefined"
                && loop.loopElements.length !== 0
                && element !== loop
                && loop.type === "LoopEnd"
                && element.type !== "Start"
                && !this.checkLoopIntersection(element.x + (element.width / 2), element.y + (element.height / 2), refElements.find(e => e.data.id === loop.id))
                && (element !== loopPart.data)) {

                const index = loop.loopElements.indexOf(refElement);
                if (index !== -1) {
                    loop.loopElements.splice(index, 1);
                    refElements.find(e => e.data.id === loop.id).downsizeLoop(refElement);
                    element.deleteLines = true;
                }
                loop.highlight = false;
            }
        });
        return elementDropped;
    }

    //Check if line is dragged over a element, then connect it to it. Or unconnect it, if it was dragged outside of element
    checkLine(line, elements, refLines, refElements, edited) {
        elements.forEach(element => {
            //If intersection is detected, and element is not already connected to this line, connect it
            if (line.originElement.data !== element && this.checkIntersection(line.x2, line.y2, refElements.find(e => e.data.id === element.id))) {
                element.colour = "#000d80";
                element.connect = true;
                element.self = false;
                if (element.lineEnds.find(l => l.data.id === line.id) === undefined) {
                    refElements.find(e => e.data.id === element.id).addLineEnd(refLines.find(l => l.data.id === line.id) as LineConnection);
                }
                const connection = line.originElement.data.connections.find(con => con.element.id === element.id);
                if (line.originElement.data.connections.indexOf(connection) === -1) {
                    edited = true;
                    const conObj: Connection = {
                        element: element,
                        line: line
                    };
                    line.originElement.addConnection(conObj);
                    line.targetElement = refElements.find(e => e.data.id === element.id);
                }
                //Else remove connection between element and line
            } else {
                element.colour = element.primaryColour;
                element.connect = false;

                if (line.originElement.data === element) {
                    element.self = true;
                }
                const lineIndex = element.lineEnds.indexOf(refLines.find(l => l.data.id === line.id));
                if (lineIndex !== -1) {
                    edited = true;
                    line.targetElement = null;
                    element.lineEnds.splice(lineIndex, 1);
                    const connection = line.originElement.data.connections.find(con => con.element.id === element.id);
                    const index = line.originElement.data.connections.indexOf(connection);
                    if (index !== -1) {
                        line.originElement.data.connections.splice(index, 1);
                    }
                }
            }
        })
        return edited;
    }


    //Check if a position is inside a loop
    checkLoopIntersection(x, y, loop) {
        return x >= loop.data.x
            && x <= loop.data.x + loop.getWidth()
            && y >= loop.data.y
            && y <= loop.data.y + loop.getHeight();
    }

    //Check if a position is inside an element
    checkIntersection(x, y, element) {
        return x >= element.data.x
            && x <= element.data.x + element.getWidth()
            && y >= element.getY()
            && y <= element.data.y + element.getHeight();
    }

}