<template>
    <div class="main_bar">
        <b-row align-v="center">
            <b-col cols="2" sm="2" md="2" xl="2"
                   class="main_bar_left d-none d-sm-none d-md-none d-lg-block d-xl-block">
                <img :src="logo" alt="Logo" width="50px">
            </b-col>
            <b-col md="12" lg="3" xl="3" sm="12" cols="12">
                <!-- Main Navigation Bar with Icons -->
                <b-row class="justify_main_bar">
                    <b-col cols="2" class="tool_icon d-block" order="1" order-sm="1" order-md="1" order-lg="1"
                           order-xl="1" @click="home">
                        <i class="fas fa-home"></i>
                    </b-col>
                    <b-col cols="2" class="tool_icon" v-if="!saved && edited"
                           @click="handleSave"
                           order="2"
                           order-sm="2"
                           order-md="2" order-lg="2"
                           order-xl="2">
                        <i class="fas fa-save"></i>
                    </b-col>
                    <b-col cols="2" class="tool_icon" v-else-if="saved && edited" @click="$emit('save')" order="2"
                           order-sm="2" order-md="2"
                           order-lg="2"
                           order-xl="2">
                        <i class="fas fa-save"></i>
                    </b-col>
                    <b-col cols="2" class="tool_icon_disabled" v-else-if="!saved && !edited"
                           order="2" order-sm="2"
                           order-md="2" order-lg="2"
                           order-xl="2">
                        <i class="fas fa-save"></i>
                    </b-col>
                    <b-col cols="2" class="tool_icon_disabled" v-else-if="saved && !edited" order="2"
                           order-sm="2" order-md="2"
                           order-lg="2"
                           order-xl="2">
                        <i class="fas fa-save"></i>
                    </b-col>
                    <b-col cols="2"
                           class="tool_icon pl-lg-3 d-none d-sm-none d-md-none d-lg-block d-xl-block"
                           order="3" @click="$emit('export')">
                        <i class="fas fa-download fa-sm"></i>
                    </b-col>
                    <b-col cols="2" v-if="activeElement !== null"
                           class="tool_icon"
                           order="5" order-sm="5" order-md="5"
                           order-lg="4" order-xl="4" @click="$emit('deleteElement')">
                        <i class="fas fa-trash fa-sm"></i>
                    </b-col>
                    <b-col cols="2" v-else
                           class="tool_icon_disabled"
                           order="5" order-sm="5" order-md="5"
                           order-lg="4" order-xl="4">
                        <i class="fas fa-trash fa-sm"></i>
                    </b-col>
                    <b-col cols="2" v-if="activeElement !== null"
                           class="tool_icon pl-lg-3"
                           order="4" order-sm="4" order-md="4"
                           order-lg="5" order-xl="5" v-b-modal.edit-modal>
                        <i class="fas fa-edit fa-sm"></i>
                    </b-col>
                    <b-col cols="2" v-else
                           class="tool_icon_disabled pl-lg-3"
                           order="4" order-sm="4" order-md="4"
                           order-lg="5" order-xl="5">
                        <i class="fas fa-edit fa-sm"></i>
                    </b-col>

                    <b-col cols="2" class="tool_icon d-sm-block d-block d-md-block d-lg-none d-xl-none" order="3"
                           order-sm="3"
                           order-md="3"
                           @click="$emit('toggleElementBar')">
                        <i class="fas fa-plus fa-sm"></i>
                    </b-col>
                </b-row>
            </b-col>
            <b-col cols="4" class="d-none d-sm-none d-md-none d-lg-block d-xl-block">{{saveName}}</b-col>
            <b-col v-if="isTutorial" cols="3" class="main_bar_right d-none d-sm-none d-md-none d-lg-block d-xl-block">
                <div @click="$bvModal.show('tutorial-modal')" style="width: 25px; float: right">
                    <i class="fas fa-info-circle fa-lg icon"></i>
                </div>
            </b-col>
        </b-row>
    </div>
</template>

<script lang="ts">
    import {Component, Prop, Vue} from 'vue-property-decorator'
    import ElementDTO from "../DTO/ElementDTO";
    import {Connection} from "../DTO/Connection";
    import {ConnectionDTO} from "../DTO/ConnectionDTO";
    import {DiagramMixin} from "../mixins/DiagramMixin";

    @Component
    export default class Toolbar extends DiagramMixin {
        @Prop() public saved;
        @Prop() public edited;
        @Prop() public activeElement;
        @Prop() public saveName;
        @Prop() public isTutorial;
        private logo = require('../assets/pLogoWhite.svg?name="pLogoWhite.svg"').default;

        created() {
            window.addEventListener('beforeunload', this.handleWindowClose);
        }

        save(elements, id, name) {
            const elementDTOs = {};
            elements.forEach(element => {
                const elementDTO = new ElementDTO(element.type, element.id, element.x, element.y, element.width, element.height, element.text, element.isLoop, element.loopHeight);
                elementDTOs[elementDTO.id] = elementDTO;
                if (element.isLoop && element.loopPart !== null) {
                    elementDTO.loopPart = element.loopPart.data.id;
                }
            });
            elements.forEach(element => {
                const connections = Object.values(element.connections) as Connection[];
                if (connections !== null && typeof connections !== "undefined") {
                    connections.forEach((connection) => {
                        const connectionDTO: ConnectionDTO = {
                            id: connection.element.id,
                            originPosition: connection.line.originPosition,
                            targetPosition: connection.line.targetPosition,
                            text: connection.line.text,
                        };
                        elementDTOs[element.id].connections.push(connectionDTO);
                    });
                }
                if (element.loopElements !== null && typeof element.loopElements !== "undefined") {
                    element.loopElements.forEach(loopElement => {
                        elementDTOs[element.id].loopElements.push(loopElement.data.id);
                    });
                }
            });
            const data = JSON.stringify(elementDTOs);
            if (this.saved) {
                this.updateDiagram(id, name, data).then(() => {
                    this.$emit('diagramSaved');
                });
            } else {
                this.addDiagram(name, data).then((res) => {
                    this.$emit('diagramSaved', res);
                });

            }
        }

        //copy svg, then set viewbox to the borders of all created elements and disable grid, then export it to png
        exportToPng(svg, refElements, elements) {
            let minX = Infinity, maxX = Number.NEGATIVE_INFINITY,
                minY = Infinity, maxY = Number.NEGATIVE_INFINITY;
            elements.forEach(element => {
                if (element.x < minX) {
                    minX = element.x;
                }
                if (element.y < minY) {
                    minY = element.y;
                }
                if (element.x + refElements.find(e => e.data.id === element.id).getWidth() > maxX) {
                    maxX = element.x + refElements.find(e => e.data.id === element.id).getWidth()
                }
                if (element.y + refElements.find(e => e.data.id === element.id).getHeight() > maxY) {
                    maxY = element.y + refElements.find(e => e.data.id === element.id).getHeight();
                }
            });

            svg.setAttribute('viewBox', minX + ' ' + minY + ' ' + (maxX - minX) + ' ' + (maxY - minY));

            const pngOptions = {
                fonts: [
                    {
                        text: "@font-face {font-family: 'Montserrat'; font-style: normal; font-weight: 600; src: local('Montserrat SemiBold'), local('Montserrat-SemiBold'), url(https://fonts.gstatic.com/s/montserrat/v14/JTURjIg1_i6t8kCHKm45_bZF3gnD_vx3rCs.woff2) format('woff2'); unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;}",
                        url: 'https://fonts.gstatic.com/s/montserrat/v14/JTURjIg1_i6t8kCHKm45_bZF3gnD_vx3rCs.woff2',
                        format: 'application/font-woff2'
                    },
                ],
                left: minX,
                top: minY,
            }

            return pngOptions;
        }

        handleSave() {
            // if (this.$store.getters.user) {
            if (this.isTutorial) {
                this.$emit('save');
            } else {
                this.$bvModal.show('save-modal');
            }
            // } else {
            //     this.$bvModal.show('login-modal');
            // }
        }


        //check if diagram was saved and user is logged in. Show modal if not saved and redirect to home if not logged in.
        home() {
            if (this.edited) {
                this.$bvModal.show('leave-modal');
            } else {
                if (this.$store.getters.user) {
                    this.$router.push({name: 'List'});
                } else {
                    this.$router.push({name: 'Home'});
                }
            }
        }

        handleWindowClose(event) {
            event.preventDefault();
            event.returnValue = 'Du hast das Diagramm nicht gespeichert. Möchtest du den Editor trotzdem verlassen?';
        }
    }
</script>

<style scoped>
    .justify_main_bar {
        justify-content: left;
    }

    .tool_icon {
        border-left: 1px solid white;
        cursor:      pointer;
    }

    .tool_icon:focus {
        outline: none;
    }

    .tool_icon_disabled {
        border-left: 1px solid white;
        cursor:      default;
        color:       lightgrey;
    }

    .tool_icon_disabled:focus {
        outline: none;
    }

    @media (max-width: 992px) {
        .main_bar {
            bottom: 0px;
            top:    auto;
        }

        .justify_main_bar {
            justify-content: center;
        }

        .tool_icon {
            border-left: none;
        }

        .tool_icon_disabled {
            border-left: none;
        }

    }

    @media (min-width: 992px) {
        .main_bar {
            top: 0px;
        }
    }
</style>