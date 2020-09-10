<template>
    <section class="diagram">
        <Toolbar :saved="saved" :edited="edited" :activeElement="activeElement" :saveName="saveName"
                 :isTutorial="isTutorial" ref="toolbar"
                 @save="save" @export="exportToPng"
                 @diagramSaved="(res) => {saved = true; dismissCountdown = 5;(res !== null && typeof res !== 'undefined') ? id = res.data.id : id;}"
                 @deleteElement="handleDeleteElement"
                 @toggleElementBar="elementBarActive = !elementBarActive"></Toolbar>
        <div class="d-block d-sm-block d-md-block d-lg-none d-xl-none"
             :class="isTutorial ? 'download_mobile_tutorial' : 'download_mobile'">
            <b-button pill size="sm" @click="exportToPng">
                <b-icon-download></b-icon-download>
            </b-button>
        </div>
        <b-button v-if="isTutorial" class="next_level_button" @click="$emit('checkLevel', elements)">Level abschließen
        </b-button>
        <b-button pill size="sm" v-if="isTutorial" class="info_button d-block d-sm-block d-md-block d-lg-none d-xl-none"
                  @click="$bvModal.show('tutorial-modal')">
            <i class="fas fa-info-circle fa-lg icon"></i>
        </b-button>
        <!--        <b-row style="height: 100%">-->
        <ElementBar ref="elementBar" :active="elementBarActive" :showGrid="showGrid"
                    @createElementByDrag="(event, type, x, y, width, height, text, isLoop, loopHeight) => {this.createElementByDrag(event, type, x, y,  width, height, text, isLoop, loopHeight)}"
                    @createLoop="(event, touch, x, y) => {this.createLoopByDrag(event, touch, x, y)}"
        ></ElementBar>
        <!--        </b-row>-->
        <!-- Main editor area -->
        <!--            <b-col cols="12" sm="12" md="12" lg="10" xl="10" order="1" order-sm="1" order-md="1" order-lg="2"-->
        <!--                   order-xl="2" class="pl-0 editor">-->
        <svg xmlns="http://www.w3.org/2000/svg"
             preserveAspectRatio="none" viewBox="878 596 3000 3000" id="svg"
             ref="container" class="noselect svg_container"
             height="3000" width="3000" font-family="Montserrat">
            <rect x="0" y="0" fill="white" style="width: 100%;height: 100%" height="3000" width="3000"/>
            <g v-if="showGrid">
                <line v-bind:key="'grid_vertical_'+index" v-for="index in gridSize(3000)" :x1="index"
                      :x2="index"
                      y1="0" y2="3000" stroke-width="1px" stroke="lightgrey"></line>
                <line v-bind:key="'grid_horizontal_'+index" v-for="index in gridSize(3000)" x1="0" x2="3000"
                      :y1="index" :y2="index" stroke-width="1px" stroke="lightgrey"></line>
            </g>
            <!-- All elements and lines that are present on editor -->
            <LineConnection :data="line" :key="'line_' + line.id" v-for="line in lines" ref="lines"
                            @lineDropped="handleDroppedLine"></LineConnection>
            <Component v-bind:is="element.constructor.name === 'ElementData' ? 'Element' : 'LoopElement'"
                       :data="element" :grid="grid"
                       :key="'element_' + element.id" v-for="element in elements"
                       ref="elements" @ElementClicked="handleClickedElement(element)"
                       @ElementDropped="handleDroppedElement"
                       @dblclick.native="$bvModal.show('edit-modal')">
            </Component>
        </svg>
        <svg xmlns="http://www.w3.org/2000/svg"
             preserveAspectRatio="none" viewBox="878 596 3000 3000"
             ref="svgTemp" class="noselect svg_container"
             height="3000" width="3000" font-family="Montserrat" style="position: fixed; background-color: transparent"
             :style="{zIndex: tempElement === null ? -1 : 2}">
            <Component v-if="tempElement" v-show="showTempElement" ref="tempComponent"
                       v-bind:is="tempElement.constructor.name === 'ElementData' ? 'Element' : 'LoopElement'"
                       :data="tempElement" :grid="grid">
            </Component>
            <LoopElement v-if="tempLoopPart" v-show="showTempElement" ref="tempLoopPart"
                         :data="tempLoopPart" :grid="grid">
            </LoopElement>
        </svg>
        <!-- svg to copy current svg to for png export -->
        <svg xmlns="http://www.w3.org/2000/svg"
             preserveAspectRatio="none" viewBox="878 596 3000 3000"
             ref="svgCopy" class="noselect  svg_container_transparent"
             height="3000" width="3000" font-family="Montserrat" style="display: none">
        </svg>
        <!--            </b-col>-->
        <!--        </b-row>-->

        <SaveModal :saveName="saveName" @save="(name) => {saveName = name; save();}"></SaveModal>
        <EditElementModal :editText="editText" :activeElement="activeElement"
                          @edit="(text) => {editText = text; edit();}"></EditElementModal>
        <!-- modal to show if user hasnt saved diagram but wants to leave -->
        <b-modal width="500" id="leave-modal"
                 @ok="$store.getters.user ? $router.push({name: 'List'}) : $router.push({name: 'Home'})" hide-header>
            <template v-slot:modal-cancel>Abbrechen</template>
            <h2>Du hast das Diagramm nicht gespeichert</h2>
            <p class="saveSubtitle">Möchtest du den Editor trotzdem verlassen?</p>
        </b-modal>
        <!-- modal to show login screen when user isnt logged in -->
        <b-modal width="500" id="login-modal" hide-header hide-footer>
            <Login @login="setUser" :cols="12" :sm="12" :md="12" :lg="12" :xl="12"></Login>
        </b-modal>
        <b-modal id="mobile-modal" title="Mobile Nutzung" ok-only @change="setMobile">
            <p class="my-2">Du scheinst ein Smartphone oder Tablet zu verwenden.</p>
            <p class="my-2">Du findest die Element-Übersicht unten auf dem
                <b-icon-plus></b-icon-plus>
                . Pfeile kannst du
                erzeugen, indem du ein Element gedrückt hälst, und den Pfeil dann auf ein anderes Element ziehst.
            </p>
        </b-modal>
        <!-- Alert to show that diagram was saved -->
        <b-alert :show="dismissCountdown" variant="success" dismissible @dismissed="dismissCountdown = 0"
                 @dismiss-count-down="countDownChanged" class="saveAlert">
            Gespeichert!
            <b-progress variant="success" max="5" :value="dismissCountdown" height="4px"></b-progress>
        </b-alert>
    </section>
</template>

<script lang="ts">
    // @ is an alias to /src
    import {Component, Prop} from 'vue-property-decorator'
    import {DiagramMixin} from '../mixins/DiagramMixin';
    import ElementDTO from "../DTO/ElementDTO";
    import ElementData from "../DTO/ElementData";
    import Element from "../components/Element.vue";
    import LineConnection from "../components/LineConnection.vue";
    import LineData from "../DTO/LineData";
    import Process from "../components/Elements/Process.vue";
    import SubProcess from "../components/Elements/SubProcess.vue";
    import Input from "../components/Elements/Input.vue";
    import Output from "../components/Elements/Output.vue";
    import Condition from "../components/Elements/Condition.vue";
    import Start from '../components/Elements/Start.vue';
    import LoopStart from '../components/Elements/LoopStart.vue';
    import LoopEnd from '../components/Elements/LoopEnd.vue';
    import {ZoomService} from "../services/ZoomService";
    import {PanService} from "../services/PanService";
    import {LinePosition} from "../DTO/LinePosition";
    import {Connection} from "../DTO/Connection";
    import {ConnectionDTO} from "../DTO/ConnectionDTO";
    import Login from "./Login.vue";
    import Toolbar from "../components/Toolbar.vue";
    import * as svg from "save-svg-as-png";
    import ElementBar from "../components/ElementBar.vue";
    import SaveModal from "../components/Modals/SaveModal.vue";
    import EditElementModal from "../components/Modals/EditElementModal.vue";
    import {IntersectionService} from "../services/IntersectionService";
    import LoopElementData from "../DTO/LoopElementData";
    import LoopElement from "../components/LoopElement.vue";
    import {MobileService} from "../services/MobileService";
    import Movable from "../components/Movable";

    @Component({
        components: {
            EditElementModal,
            SaveModal,
            ElementBar,
            Toolbar,
            Login,
            LineConnection,
            Element,
            Process,
            SubProcess,
            Input,
            Output,
            Condition,
            Start,
            LoopStart,
            LoopEnd,
            LoopElement
        }
    })
    export default class Diagram extends DiagramMixin {
        @Prop() public isTutorial: boolean;
        private id = "";
        private saved = false;
        private svg = null;
        public elements = [];
        private lines = [];
        private zoomService: ZoomService;
        private panService: PanService;
        private intersectionService: IntersectionService;
        private mobileService: MobileService;
        private viewBox;
        private saveName = "";
        private activeElement = null;
        private editText = "";
        protected pt: SVGPoint;
        private elementBarActive = false;
        private grid = 15;
        private elementDropped;
        private showGrid = true;
        private edited = false;
        private keyMap = {};
        private dismissCountdown = 0;
        private showTempElement = false;
        private tempElement: ElementData = null;
        private tempLoopPart: LoopElementData = null;


        mounted() {
            this.svg = this.$refs.container;
            this.pt = this.svg.createSVGPoint();
            this.lines = [];
            const x = window.screen.width < 576 ? 1080 : 878;
            this.viewBox = {x: x, y: 596, w: this.svg.getAttribute('width'), h: this.svg.getAttribute('height')};
            this.svg.setAttribute('viewBox', this.viewBox.x + ' ' + this.viewBox.y + ' ' + this.viewBox.w + ' ' + this.viewBox.h);
            this.zoomService = new ZoomService(this.elements, this.lines, this.viewBox, this.svg);
            this.panService = new PanService(this.elements, this.lines, this.viewBox, this.svg);
            this.intersectionService = new IntersectionService();
            this.mobileService = new MobileService();

            this.svg.addEventListener("touchstart", this.startZoom);
            this.svg.addEventListener("touchmove", this.zoom);
            document.addEventListener("keydown", this.startZoom);
            document.addEventListener("keyup", this.stopZoom);
            this.svg.addEventListener("touchend", this.stopZoom);
            this.svg.addEventListener("wheel", this.zoom);
            this.svg.addEventListener("mousedown", this.startPanning);
            this.svg.addEventListener("mousemove", this.panning);
            this.svg.addEventListener("mouseup", this.stopPanning);
            this.svg.addEventListener("touchstart", this.startPanning);
            this.svg.addEventListener("touchend", this.stopPanning);
            this.svg.addEventListener("touchmove", this.panning);
            document.addEventListener('keydown', this.handleKeyDown);
            document.addEventListener("keyup", this.handleCtrlSave);

            this.init();
        }

        async createElementByDrag(event, touch, type, x, y, width, height, text, isLoop, loopHeight) {
            event.preventDefault();

            const svg = this.$refs.svgTemp as SVGSVGElement;

            svg.setAttribute('viewBox', this.svg.getAttribute('viewBox'));

            let coords;
            if (touch) {
                coords = this.touchPoint(event, svg);
            } else {
                coords = this.cursorPoint(event, svg);
            }

            const xGrid = this.grid * Math.round((coords.x - width / 2) / this.grid);
            const yGrid = this.grid * Math.round((coords.y - height / 2) / this.grid);

            let element;
            let component;
            if (isLoop) {
                element = new LoopElementData(type, this.elements.length, xGrid, yGrid, width, height, text, svg, isLoop, loopHeight);
                if (type !== 'LoopEnd') {
                    this.tempElement = element;
                } else {
                    this.tempLoopPart = element;
                }
                element.targetMovable = true;
                await this.$nextTick();
                component = this.$refs.tempComponent as LoopElement;

            } else {
                element = new ElementData(type, this.elements.length, xGrid, yGrid, width, height, text, svg);
                this.tempElement = element;
                element.targetMovable = true;
                await this.$nextTick();
                component = this.$refs.tempComponent as Element;
            }

            if (touch) {
                document.addEventListener('touchmove', ($event) => {
                    component.moveTargetTouch($event);
                });
                document.addEventListener('touchend', component.makeTargetUnmovable);
                component.makeTargetMovableTouch(event);
            } else {
                component.makeTargetMovable(event);
            }
            const xTemp = element.x;
            const yTemp = element.y;
            //Call after timer ends
            component.$on('ElementMoved', () => {
                this.elementDropped = this.intersectionService.checkLoop(element, component, this.elements, this.$refs.elements, this.elementDropped);
                const movedDistance = Math.abs(xTemp - element.x) + Math.abs(yTemp - element.y);
                if (movedDistance > 1) {
                    this.showTempElement = true;
                }
            });

            component.$on('ElementDropped', async () => {
                const movedDistance = Math.abs(xTemp - element.x) + Math.abs(yTemp - element.y);
                const elements = this.$refs.elements as Movable[];
                if (movedDistance <= 1) {
                    element.x = x;
                    element.y = y;

                    await this.$nextTick();
                    this.showTempElement = true;
                }
                if (type === "LoopStart") {
                    this.createLoop(element.x, element.y).then(() => {
                        this.elementDropped = this.intersectionService.checkLoop(element, elements.find(e => e.data.id === element.id), this.elements, this.$refs.elements, this.elementDropped);
                    });
                } else if (type !== "LoopEnd") {
                    this.createElement(type, element.x, element.y, width, height, text, isLoop, loopHeight).then(() => {
                        this.elementDropped = this.intersectionService.checkLoop(element, elements.find(e => e.data.id === element.id), this.elements, this.$refs.elements, this.elementDropped);
                    });
                }
                this.tempElement = null;
                this.tempLoopPart = null;
                this.showTempElement = false;
            });
            return element;
        }

        //Create a new Element with given position, size and colour
        async createElement(type, x, y, width, height, text, isLoop, loopHeight) {

            //add new element to array and wait for vue to render it.
            const xGrid = this.grid * Math.round(x / this.grid);
            const yGrid = this.grid * Math.round(y / this.grid);

            let element;
            if (isLoop) {
                element = new LoopElementData(type, this.elements.length, xGrid, yGrid, width, height, text, this.svg, isLoop, loopHeight);
            } else {
                element = new ElementData(type, this.elements.length, xGrid, yGrid, width, height, text, this.svg);

            }
            this.elements.push(element);
            await this.$nextTick();
            this.createElementEventListeners(element);
            if (type !== "LoopStart" && type !== "LoopEnd") {
                this.checkLoopListener(element);
            }
            this.elementBarActive = false;
            this.edited = true;
            return element;
        }

        //If Loop is created, also save the other part of it
        createLoopByDrag(event, touch, x, y) {
            this.createElementByDrag(event, touch, 'LoopStart', x, y, 118, 29, 'Schleife', true, 0).then(loopStart => {
                this.createElementByDrag(event, touch, 'LoopEnd', x, y, 118, 29, '', true, 40).then(loopEnd => {
                    loopStart.loopPart = this.$refs.tempLoopPart;
                    loopEnd.loopPart = this.$refs.tempComponent;
                })
            });
        }

        async createLoop(x, y) {
            await this.createElement('LoopStart', x, y, 118, 29, 'Schleife', true, 0).then(async loopStart => {
                await this.createElement('LoopEnd', x, y, 118, 29, '', true, 150).then(loopEnd => {
                    const elements = this.$refs.elements as Movable[];
                    loopStart.loopPart = elements.find(e => e.data.id === loopEnd.id);
                    loopEnd.loopPart = elements.find(e => e.data.id === loopStart.id);
                    this.checkLoopListener(loopEnd);
                    this.checkLoopListener(loopStart);
                })
            });
        }

        //Create a new Line with given start and end positions and a given start Element to connect to
        async createLine(x1, y1, x2, y2, element, linePosition) {
            let lineTargetPosition;
            switch (linePosition) {
                case LinePosition.Top:
                    lineTargetPosition = LinePosition.Bottom;
                    break;
                case LinePosition.Right:
                    lineTargetPosition = LinePosition.Right;
                    break;
                case LinePosition.Bottom:
                    lineTargetPosition = LinePosition.Top;
                    break;
                case LinePosition.Left:
                    lineTargetPosition = LinePosition.Left;
                    break;
            }
            const elements = this.$refs.elements as Movable[];
            const line = new LineData(this.lines.length, x1, y1, x2, y2, linePosition, lineTargetPosition, this.svg, elements.find(e => e.data.id === element.id));
            this.lines.push(line);
            await this.$nextTick();

            this.checkLineListener(line);

            return line;
        }

        //Create a new line and connect it to the given element
        connectLine(xPos, yPos, element, timer, touchDuration, linePosition) {
            //Position before timer ended
            const x = element.x;
            const y = element.y;
            //Call after timer ends
            timer.time = setTimeout(() => {
                //Check position again to calculate tolerance for moving
                const movedDistance = Math.abs(x - element.x) + Math.abs(y - element.y);
                if (!element.moving || movedDistance < 1) {
                    if ("vibrate" in navigator) {
                        window.navigator.vibrate(50);
                    }
                    //Create new line in center of the element
                    const elements = this.$refs.elements as Movable[];
                    this.createLine(element.x + ((elements.find(e => e.data.id === element.id).getWidth()) / 2),
                        (element.height / 2) + elements.find(e => e.data.id === element.id).getY(),
                        xPos, yPos, element, linePosition)
                        .then(line => {
                            const lines = this.$refs.lines as LineConnection[];
                            elements.find(e => e.data.id === element.id).addLineOrigin(lines.find(l => l.data.id === line.id));
                            element.targetMovable = false;
                            line.targetMovable = true;
                            lines.find(l => l.data.id === line.id).moveTarget(xPos, yPos);
                        });
                }
                element.moving = false;
            }, touchDuration);
        }

        //Check if an element is dragged in or out of a loop, then enlarge or downsize loop
        checkLoopListener(element) {
            let elements = this.$refs.elements as Movable[];
            elements.find(e => e.data.id === element.id).$on('checkLoop', () => {
                elements = this.$refs.elements as Movable[];
                this.elementDropped = this.intersectionService.checkLoop(element, elements.find(e => e.data.id === element.id), this.elements, this.$refs.elements, this.elementDropped);
            });
        }

        //Check if line is dragged over a element, then connect it to it. Or unconnect it, if it was dragged outside of element
        checkLineListener(line) {
            //Event is fired when line is moved, then check if its target position intersects with one of the elements
            const lines = this.$refs.lines as LineConnection[];
            lines.find(l => l.data.id === line.id).$on('checkLine', () => {
                this.edited = this.intersectionService.checkLine(line, this.elements, this.$refs.lines, this.$refs.elements, this.edited);
            });
        }

        handleCreateLine(x, y, linePosition, element){
            const timer = {time: 0};
            this.panService.panningPossible = false;
            this.connectLine(x, y, element, timer, 0, linePosition);
        }

        //Create all necessary Event Listeners for an element
        createElementEventListeners(element) {
            const timer = {time: 0};
            const elements = this.$refs.elements as Movable[];
            //on desktop event is fired, when user clicked the elements line-creators
            elements.find(e => e.data.id === element.id).$on('createLine', (x, y, linePosition) => {
                this.panService.panningPossible = false;
                this.connectLine(x, y, element, timer, 0, linePosition);
            });

            //Long touch. run, after an amount of ms (500) and create a new line for this element. Make new line movable right away and prevent element from moving
            elements.find(e => e.data.id === element.id).$el.addEventListener('touchstart', (e) => {
                e.preventDefault();
                const point = this.touchPoint(e as TouchEvent, this.svg);
                this.connectLine(point.x, point.y, element, timer, 500, LinePosition.Bottom);
            });

            //Reset long touch timer
            elements.find(e => e.data.id === element.id).$el.addEventListener("touchend", () => {
                if (timer.time) {
                    clearTimeout(timer.time);
                }
            });

            //Downsize loop if element was dropped, to shrink it to fitting size
            if (element.type === "LoopEnd") {
                const loopElements = this.$refs.elements as LoopElement[];
                loopElements.find(e => e.data.id === element.id).$on("ElementDropped", (droppedElement) => {
                    element.highlight = false;
                    loopElements.find(e => e.data.id === element.id).downsizeLoop(elements.find(e => e.data.id === droppedElement.id));
                });
            }
        }

        //If Ctrl + S was pressed, save the diagram
        handleCtrlSave(event: KeyboardEvent) {
            this.keyMap[event.key] = event.type == "keydown";
            if ((this.keyMap["Meta"] && this.keyMap["s"]) || (this.keyMap["Control"] && this.keyMap["s"])) {
                event.preventDefault();
                if (!this.saved) {
                    this.$bvModal.show('save-modal');
                } else {
                    this.save();
                }
                this.keyMap["Meta"] = false;
                this.keyMap["Control"] = false;
                this.keyMap["s"] = false;
            }
        }

        //Convert all elements to DTOs, then search for connections and add them too. Finally call function to store data to db
        save() {
            const toolbar = this.$refs.toolbar as Toolbar;
            toolbar.save(this.elements, this.id, this.saveName);
            this.$bvModal.hide('save-modal');
            this.edited = false;
        }

        //Load an existing diagram by id. Create all elements from Json Object, then create all lines from the connections stored in each element
        load($id) {
            this.loadDiagram($id).then(res => {
                this.saveName = res.data.name;
                const data = JSON.parse(res.data.data);
                const dataArray = Object.values(data) as ElementDTO[];
                const elements = [];
                const promises = [];
                //Create elements
                dataArray.forEach(item => {
                    const promise = this.createElement(item.type, item.x, item.y, item.width, item.height, item.text, item.loop, item.loopHeight)
                        .then(async (res) => {
                            const element = res;
                            elements.push(element);
                            element['id'] = item.id;
                            await this.$nextTick();
                            this.createElementEventListeners(element);
                        });
                    promises.push(promise);
                });

                //After all elements were created, create lines from connections.
                Promise.all(promises).then(() => {
                    dataArray.forEach(item => {
                        const refElements = this.$refs.elements as Movable[];

                        const element = elements.find(e => e.id === item.id);
                        item.connections.forEach((con: ConnectionDTO) => {
                            const connection = elements.find(e => e.id === con.id) as ElementData;
                            if (connection !== null) {
                                this.createLine(element.x + (element.width / 2), element.y + (element.height / 2), connection.x + (connection.width / 2), connection.y + (connection.height / 2), element, con.originPosition)
                                    .then((newLine) => {
                                        newLine.targetPosition = con.targetPosition;
                                        newLine.text = con.text;
                                        const lines = this.$refs.lines as LineConnection[];
                                        element.lineOrigins.push(lines.find(l => l.data.id === newLine.id));
                                        const conObj: Connection = {
                                            element: connection,
                                            line: newLine
                                        };
                                        element.connections.push(conObj);
                                        newLine.targetElement = refElements.find(e => e.data.id === connection.id);
                                        lines.find(l => l.data.id === newLine.id).moveTarget(connection.x + (connection.width / 2), connection.y + (connection.height / 2));
                                        connection.lineEnds.push(lines.find(l => l.data.id === newLine.id));
                                    });
                            }
                        });
                        item.loopElements.forEach((loopElement) => {
                            element.loopElements.push(refElements.find(e => e.data.id === loopElement));
                        })

                        if (item.loop) {
                            element.loopPart = refElements.find(e => e.data.id === item.loopPart);
                        }
                    });
                    this.edited = false;
                });
            });
        }

        //Is it a new diagram or an existing one. If existing, load it and update on save
        init() {
            if (this.$route.params.id === "new" || this.$route.params.id === "tutorial") {
                this.saved = false;
                this.saveName = this.$route.params.id === "tutorial" && this.$store.getters.user ? "Level " + (this.$store.getters.user.level + 1) : "Dein PAP";
                const x = window.screen.width < 576 ? 1230 : 1440
                this.createElement('Start', x, 750, 60, 30, 'Start', false, 0).then(() => {
                    this.edited = false;
                });
                this.createElement('Start', x, 950, 60, 30, 'Ende', false, 0).then(() => {
                    this.edited = false;
                });
            } else {
                this.id = this.$route.params.id;
                this.load(this.id);
                this.saved = true;
            }
            this.edited = false;

            if (this.$store.getters.user && !this.$store.getters.user.usedMobile && window.screen.width < 992) {
                this.$bvModal.show('mobile-modal');
            }
        }

        setMobile(){
            this.axios.post('/user/usedMobile', {withCredentials: true})
                .then(() => {
                    this.$store.getters.user.usedMobile = true;
                });
        }

        //Set new text and close Modal
        edit() {
            this.activeElement.text = this.editText;
            if (this.activeElement.type === 'Condition') {
                this.activeElement.lineOrigins.forEach(line => {
                    line.data.text = line.data.validationText;
                });
            }
            this.edited = true;
            this.$bvModal.hide('edit-modal');
        }

        //for the active Element, remove all lines and connections to other elements, then delete this element from the array
        handleDeleteElement() {
            this.deleteElement(this.activeElement);

            if (this.activeElement.type === 'LoopStart') {
                this.deleteElement(this.activeElement.loopPart.data);
                this.activeElement.loopPart.data.loopElements.forEach(element => {
                    this.deleteElement(element.data);
                })
            } else if (this.activeElement.type === 'LoopEnd') {
                this.deleteElement(this.activeElement.loopPart.data);
                this.activeElement.loopElements.forEach(element => {
                    this.deleteElement(element.data);
                })
            }
            this.editText = "";
            this.activeElement = null;
        }

        //Delete an element and adjust id of all other elements in list
        deleteElement(element) {
            this.removeLines(element);
            const elements = this.$refs.elements as Movable[];
            const loopElements = this.$refs.elements as LoopElement[];
            if (element.inLoop !== null && typeof element.inLoop !== "undefined") {
                const loop = element.inLoop.data;
                const index = loop.loopElements.indexOf(elements.find(e => e.data.id === element.id));
                loop.loopElements.splice(index, 1);
                loopElements.find(e => e.data.id === loop.id).downsizeLoop(elements.find(e => e.data.id === element.id));
            }

            const index = this.elements.indexOf(element);
            this.elements.splice(index, 1);
            // for (let i = index; i < this.elements.length; i++) {
            //     this.elements[i].id--;
            // }
            this.edited = true;
            elements.find(e => e.data.id === element.id).destruct();
        }

        //The active Element (the last recently clicked on) is set, so it can be edited or deleted
        handleClickedElement(element) {
            if (this.activeElement !== null && this.activeElement !== element && element.type !== "Start") {
                this.activeElement.colour = this.activeElement.primaryColour;
            }
            if (element.type !== "Start") {
                this.activeElement = element;
                this.editText = this.activeElement.text;
            }
            if (element.type === 'Condition') {
                element.lineOrigins.forEach(line => {
                    line.data.validationText = line.data.text;
                })
            }
            this.elementDropped = false;
        }

        //If element was dropped, check if it is inside loop and remove lines if its necessary
        handleDroppedElement(element) {
            this.elementDropped = true;
            const elements = this.$refs.elements as Movable[];
            elements.find(e => e.data.id === element.id).$emit('checkLoop');
            if (element.deleteLines) {
                this.removeLines(element);
                element.deleteLines = false;
            }
            this.edited = true;
        }

        //If line was dropped and it is not connected to a target, delete id and adjust id of all other lines in list.
        handleDroppedLine(line) {
            if (line.targetElement === null) {
                const origin = line.originElement.data;
                const lines = this.$refs.lines as LineConnection[];
                const originIndex = origin.lineOrigins.indexOf(lines.find(l => l.data.id === line.id));
                origin.lineOrigins.splice(originIndex, 1);

                const lineIndex = this.lines.indexOf(line);
                this.lines.splice(lineIndex, 1);

            }
            this.edited = true;
        }

        //Remove all connected lines of an element and adjust the id of all other lines in list
        public removeLines(element) {
            if (element.lineOrigins !== null && typeof element.lineOrigins !== "undefined") {
                element.lineOrigins.forEach(l => {
                    const line = l.data;

                    const target = line.targetElement.data;
                    const lines = this.$refs.lines as LineConnection[];
                    const targetIndex = target.lineEnds.indexOf(lines.find(l => l.data.id === line.id));
                    target.lineEnds.splice(targetIndex, 1);

                    const lineIndex = this.lines.indexOf(line);
                    this.lines.splice(lineIndex, 1);
                    // for (let i = lineIndex; i < this.lines.length; i++) {
                    //     this.lines[i].id--;
                    // }
                });
            }
            if (element.lineEnds !== null && typeof element.lineEnds !== "undefined") {
                element.lineEnds.forEach(l => {
                    const line = l.data;
                    const end = line.originElement.data;
                    const lines = this.$refs.lines as LineConnection[];
                    const endIndex = end.lineOrigins.indexOf(lines.find(l => l.data.id === line.id));
                    end.lineOrigins.splice(endIndex, 1);
                    const start = end.connections.find(connection => connection.element === element);
                    const startIndex = end.connections.indexOf(start);
                    end.connections.splice(startIndex, 1);
                    const lineIndex = this.lines.indexOf(line);
                    this.lines.splice(lineIndex, 1);
                    // for (let i = (lineIndex+1); i < this.lines.length; i++) {
                    //     this.lines[i].id--;
                    // }
                })
            }
        }

        //Handle if key to delete or to save was pressed
        handleKeyDown(event: KeyboardEvent) {
            if (event.key === "Delete" && this.activeElement !== null) {
                this.handleDeleteElement();
            }
            this.handleCtrlSave(event);
        }

        startZoom(e) {
            this.zoomService.startZoom(e);
        }

        zoom(e) {
            if (this.zoomService.zoomable) {
                const movedViewbox = this.zoomService.zoom(e, this.viewBox);
                this.svg.setAttribute('viewBox',
                    `${movedViewbox.x} ${movedViewbox.y} ${movedViewbox.w} ${movedViewbox.h}`);
                this.viewBox = movedViewbox;
            }
        }

        stopZoom() {
            this.zoomService.stopZoom();
        }

        startPanning(e) {
            const panning = this.panService.startPanning(e);
            if (panning && this.activeElement !== null) {
                this.activeElement.colour = this.activeElement.primaryColour;
                this.activeElement = null;
            }
        }

        panning(e) {
            const movedViewbox = this.panService.panning(e, this.viewBox, this.zoomService.scale);
            if (this.panService.isPanning) {
                this.svg.setAttribute('viewBox',
                    `${movedViewbox.x} ${movedViewbox.y} ${movedViewbox.w} ${movedViewbox.h}`);
            }
        }

        stopPanning(e) {
            if (this.panService.isPanning) {
                this.viewBox = this.panService.stopPanning(e, this.viewBox, this.zoomService.scale);
            }
        }

        //calculate all positions for the grid
        gridSize(size) {
            const array = []
            let count = 0;
            for (let i = 0; i < size; i = i + this.grid) {
                array[count++] = i;
            }
            return array;
        }

        //copy svg, then set viewbox to the borders of all created elements and disable grid, then export it to png
        async exportToPng() {
            const oldViewbox = this.svg.getAttribute('viewBox');
            const localSvg = this.$refs.svgCopy as SVGSVGElement;
            this.showGrid = false;
            await this.$nextTick();
            localSvg.innerHTML = this.svg.innerHTML;
            this.showGrid = true;
            const toolbar = this.$refs.toolbar as Toolbar;
            const pngOptions = toolbar.exportToPng(localSvg, this.$refs.elements, this.elements);
            svg.saveSvgAsPng(localSvg, this.saveName, pngOptions)
                .then(() => {
                    this.showGrid = true;
                    this.svg.setAttribute('viewBox', oldViewbox);
                });
        }


        //set countdown for save alert
        countDownChanged(dismissCountdown) {
            this.dismissCountdown = dismissCountdown;
        }

        //set the user if logged in here
        setUser() {
            this.axios.get(
                '/profile', {withCredentials: true})
                .then((res) => {
                    this.$store.commit('setUser', res.data.user);
                    this.$bvModal.hide('login-modal');
                    this.save();
                })
                .catch(err => {
                    this.$store.commit('unsetUser');
                });
        }
    }
</script>

<style lang="scss" scoped>
    @import "../scss/_variables.scss";

    .home {
        position: relative;
        width: 100%;
        height: 100%;
    }


    .nameInput {
        width: 80%;
        /*max-width: 400px;*/
    }

    .nameInput input {
        border: 0 !important;
    }

    .saveSubtitle {
        text-align: left;
    }

    .fab_bottom_right {
        position: fixed;
        bottom: 20px;
        right: 20px;
        border-radius: 40px !important;
        width: 45px;
        height: 45px;
    }

    .fab_top_right {
        position: fixed;
        top: 20px;
        right: 20px;
        border-radius: 40px !important;
        width: 45px;
        height: 45px;
    }


    .left_bar {
        position: fixed;
        left: 0;
        top: 0;
        height: 100%;
        width: 160px;
        background-color: #e6e7ec;
        z-index: 1;

    }


    .noselect {
        -webkit-touch-callout: none; /* iOS Safari */
        -webkit-user-select: none; /* Safari */
        -moz-user-select: none; /* Old versions of Firefox */
        -ms-user-select: none; /* Internet Explorer/Edge */
        user-select: none;
        /* Non-prefixed version, currently
                                         supported by Chrome, Edge, Opera and Firefox */
    }

    .svg_container {
        background-color: slategrey;
        position: fixed;
        top: 0px;
        left: 0px;
        z-index: 0;
    }

    .svg_container_transparent {
        background-color: transparent;
        position: fixed;
        top: 0px;
        left: 0px;
        z-index: 0;
    }


    .saveAlert {
        position: fixed;
        top: 10px;
        right: 10px;
        width: 230px;
        z-index: 3;
    }

    .download_mobile {
        position: fixed;
        top: 20px;
        right: 10px;
        z-index: 1
    }

    .download_mobile_tutorial {
        position: fixed;
        top: 100px;
        right: 10px;
        z-index: 1
    }

    .next_level_button {
        position: fixed;
        top: 50px;
        right: 10px;
        z-index: 3;
        background-color: $primary;
        color: white;
    }

    .info_button {
        position: fixed;
        top: 60px;
        right: 10px;
        z-index: 3;
    }

    @media (max-width: 992px) {

        .next_level_button {
            position: fixed;
            top: 10px;
            right: 10px;
            z-index: 3;
        }

        .left_bar {
            position: fixed;
            left: 0px;
            height: 230px;
            width: 100%;
            z-index: 2;
        }

        .left_bar_down {
            top: unset;
            bottom: -220px;
            transition: all 0.2s ease-out;
        }

        .left_bar_up {
            top: unset;
            bottom: 0px;
            transition: all 0.2s ease-out;
        }
    }

    @media (max-width: 576px) {
        .left_bar {
            height: 200px;
            font-size: 12px;
        }
    }

</style>
