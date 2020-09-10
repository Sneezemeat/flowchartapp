

export default class ElementDTO {
    public id: string;
    public type: string;
    public x: number;
    public y: number;
    public width: number;
    public height: number;
    public connections: [];
    public text: string;
    public loop: boolean;
    public loopHeight: number;
    public loopPart: string;
    public loopElements: [];

    constructor(type: string, id: string, x: number, y: number, width: number, height: number, text: string, loop: boolean, loopHeight: number) {
        this.type = type;
        this.id = id;
        this.x = x;
        this.y = y;
        this.width = width;
        this.height = height;
        this.connections = [];
        this.text = text;
        this.loop = loop;
        this.loopHeight = loopHeight;
        this.loopElements = [];
    }

    equals(element): boolean {
        return this.x == element.x && this.y == element.y && this.width == element.width && this.height == element.height;
    }

}