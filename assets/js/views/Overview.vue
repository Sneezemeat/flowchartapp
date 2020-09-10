<template>
    <section>
        <canvas id="confetti" ref="confetti" class="confettiCanvas"></canvas>
        <div class="main_bar">
            <b-row align-v="center">
                <b-col cols="6" sm="6" md="1" xl="1" class="main_bar_left"><img :src="logo" alt="Logo" width="50px">
                </b-col>
                <b-col cols="0" sm="0" md="5" xl="5" class="main_bar_left d-none d-md-block">FlowchartApp</b-col>
                <b-col cols="6" class="main_bar_right">
                    <div @click="logout">
                        <i class="fas fa-sign-out-alt logout fa-lg icon"></i>
                    </div>
                </b-col>
            </b-row>
        </div>
        <a @click="endTutorial(false)" class="skip_tutorial_button"
           v-if="$store.getters.user && $store.getters.user.level <= 5">Tutorial überspringen</a>
        <b-container>
            <section class="mt-5">
                <b-row class="pt-5" v-if="$store.getters.user && $store.getters.user.level <= 5">
                    <b-col cols="12" v-if="$store.getters.user">
                        <h1 v-if="$store.getters.user.level === 0">Tutorial</h1>
                        <h1 v-else-if="$store.getters.user.level !== 0">Level:
                            {{$store.getters.user.level}}</h1>
                        <b-progress height="2rem" :value="$store.getters.user.level"
                                    class="level_progress mb-2 mt-5 position-relative">
                            <b-progress-bar :value="$store.getters.user.level" max="5">
                            </b-progress-bar>
                            <p :class="$store.getters.user.level <= 2 ? '' : 'text-white'" class="level_progress_text">
                                {{$store.getters.user.level}}/5</p>
                        </b-progress>
                    </b-col>
                    <b-col cols="12" class="mb-5 mt-5">
                        <b-button class="level_start_button" size="lg"
                                  v-if="$store.getters.user && $store.getters.user.level === 0" @click="loadLevel">
                            Tutorial starten
                        </b-button>
                        <b-button class="level_start_button" size="lg"
                                  v-else-if="$store.getters.user && $store.getters.user.level === 5"
                                  @click="endTutorial(true)">
                            Tutorial abschließen
                        </b-button>
                        <b-button class="level_start_button" size="lg"
                                  v-else-if="$store.getters.user && $store.getters.user.level > 0" @click="loadLevel">
                            Weiter mit Level {{ $store.getters.user.level + 1 }}
                        </b-button>

                    </b-col>
                </b-row>
                <b-row class="pt-5" v-if="$store.getters.user && $store.getters.user.level > 5">
                    <b-col xl="4" md="4" sm="4" cols="6" class="mb-5 diagram_col">
                        <router-link :to="{name: 'Diagram', params: {id: 'new'}}">
                            <b-card class="diagram_card">
                                <b-container>
                                    <b-row align-v="center">
                                        <b-col>
                                            <i class="fas fa-plus fa-4x diagram_plus"></i>
                                        </b-col>
                                    </b-row>
                                </b-container>
                            </b-card>
                        </router-link>
                    </b-col>
                    <b-col xl="4" md="4" sm="4" cols="6" v-bind:key="diagram.id" v-for="diagram in diagrams"
                           class="mb-5">
                        <b-card class="diagram_card" @click="openDiagram(diagram.id)">
                            {{diagram.name}}
                            <b-button pill class="edit_btn" size="sm"
                                      @click="handleEditDiagram(diagram)">
                                <b-icon-pencil scale="0.7"></b-icon-pencil>
                            </b-button>
                        </b-card>
                    </b-col>
                    <b-col cols="12" v-if="max > limit">
                        <b-pagination pills size="md" v-if="max" v-model="page" :page="getDiagrams"
                                      :number-of-pages="max/limit"
                                      :per-page="limit" :total-rows="max"
                                      align="center" @input="getDiagrams" class="diagram_pagination"></b-pagination>
                    </b-col>
                </b-row>


            </section>
        </b-container>

        <b-modal width="500" @ok="deleteDiagram" modal-cancel="Abbrechen" @cancel="handleCancelDelete" id="delete-modal"
                 hide-header>
            <template v-slot:modal-cancel>Abbrechen</template>
            <h2>Diagramm löschen</h2>
            <p class="saveSubtitle" v-if="toDeleteDiagram !== null">Möchtest du das Diagramm {{toDeleteDiagram.name}}
                wirklich löschen?</p>
        </b-modal>
        <b-modal width="500" @ok="deleteDiagram" id="edit-modal" hide-header hide-footer>
            <validation-observer ref="observer" v-slot="{ handleSubmit }">
                <b-form @submit.stop.prevent="handleSubmit(editDiagram)">
                    <validation-provider name="Name" :rules="{min: 2, max: 30}"
                                         v-slot="validationContext">
                        <b-form-group label="Name" label-for="name-input">
                            <b-form-input class="nameInput" id="name-input" label="Name" outlined v-model="editName"
                                          placeholder="Name" :state="getValidationState(validationContext)"
                                          aria-describedby="input-1-live-feedback"></b-form-input>
                            <b-form-invalid-feedback id="input-1-live-feedback"> {{validationContext.errors[0]}}
                            </b-form-invalid-feedback>
                        </b-form-group>
                    </validation-provider>
                    <b-row>
                        <b-col cols="4" sm="2" md="2" lg="2" xl="2">
                            <b-button @click="handleDeleteDiagram" variant="outline-danger" class="icon_button">
                                <b-icon-trash></b-icon-trash>
                            </b-button>
                        </b-col>
                        <b-col cols="12" sm="5" md="5" lg="5" xl="5" order="3" order-sm="2" order-md="2" order-lg="2"
                               order-xl="2" class="mt-2 mt-sm-0 mt-md-0 mt-lg-0 mt-xl-0">
                            <b-button class="button_outlined" @click="handleCancelEdit">
                                Abbrechen
                            </b-button>
                        </b-col>
                        <b-col cols="8" sm="5" md="5" lg="5" xl="5" order="2" order-sm="3" order-md="3" order-lg="3"
                               order-xl="3">
                            <b-button class="button_outlined" type="submit">
                                OK
                            </b-button>
                        </b-col>
                    </b-row>
                </b-form>
            </validation-observer>
        </b-modal>
        <b-modal id="end-tutorial-modal" title="Tutorial abgeschlossen!" ok-only @ok="confettiGenerator.clear()" @blur="confettiGenerator.clear()" @hide="confettiGenerator.clear()">
            <p class="my-2">Herzlichen Glückwunsch, du hast das Tutorial erfolgreich abgeschlossen.<br><br>
                Wie geht es nun weiter? Du bekommst hier eine Übersicht über all deine erstellten PAPs. <br><br>
                Du kannst natürlich auch neue PAPs erstellen oder vorhandene bearbeiten und löschen.</p>
        </b-modal>
        <b-modal id="skip-tutorial-modal" title="Wie geht es weiter?" ok-only>
            <p class="my-2">
                Du bekommst hier eine Übersicht über all deine erstellten PAPs. <br><br>
                Du kannst natürlich auch neue PAPs erstellen oder vorhandene bearbeiten und löschen.</p>
        </b-modal>
    </section>
</template>

<script lang="ts">
    // @ is an alias to /src
    import Component from 'vue-class-component';
    import {DiagramMixin} from "../mixins/DiagramMixin";
    import axios from "axios";
    import Diagram from "./Diagram.vue";
    import ConfettiGenerator from "confetti-js";
    import Vue from 'vue'


    @Component
    export default class Home extends DiagramMixin {

        public diagrams = [];
        private page: number;
        private max = 0;
        private limit = 0;
        private logo = require('../assets/pLogoWhite.svg?name="pLogoWhite.svg"').default;
        private toDeleteDiagram = null;
        private editName = "";
        private confettiGenerator;

        mounted() {
            this.page = 1;
            this.max = 0;
            this.limit = 0;
            this.getDiagrams();
        }

        getDiagrams() {
            this.getAllDiagrams(this.page).then(res => {
                this.diagrams = res.data.diagrams;
                this.max = res.data.max;
                this.limit = res.data.limit;
            });
        }

        logout() {
            this.axios.get(
                "/logout", {withCredentials: true})
                .then(() => {
                    this.$store.commit('unsetUser');
                    this.$router.push('/');
                });
            localStorage.setItem('local', 'false');
        }

        openDiagram(id) {
            if (this.toDeleteDiagram === null && this.editName === "") {
                this.$router.push({name: 'Diagram', params: {id: id}});
            }
        }

        loadLevel() {
            const levels = this.diagrams.filter(diagram => diagram.name === "Level " + (this.$store.getters.user.level + 1));
            if (levels.length > 0) {
                this.$router.push({name: 'Level', params: {id: levels[0].id}});
            } else {
                this.$router.push({name: 'Level', params: {id: 'tutorial'}});
            }
        }

        endTutorial(congrats: boolean) {
            if (congrats) {
                var confettiSettings = {
                    target: 'confetti',
                    max: 150,
                    size: 1.8,
                    clock: 35,
                    props: ['circle', 'square', 'triangle']
                };
                this.confettiGenerator = new (ConfettiGenerator as any)(confettiSettings);
                this.confettiGenerator.render();
            }
            if (!JSON.parse(localStorage.local)) {
                const formdata = new FormData();
                formdata.append('level', '6');
                axios.post("/user/level/set", formdata, {withCredentials: true})
                    .then(() => {
                        this.$store.getters.user.level = 6;
                        if(congrats) {
                            this.$bvModal.show('end-tutorial-modal');
                        }
                        else{
                            this.$bvModal.show('skip-tutorial-modal');
                        }
                    });
            } else {
                const $user = this.$store.getters.user;
                $user.level = 6;
                this.$store.commit('setUser', $user);
                localStorage.user = JSON.stringify($user);
                if(congrats) {
                    this.$bvModal.show('end-tutorial-modal');
                }
                else{
                    this.$bvModal.show('skip-tutorial-modal');
                }
            }
        }

        handleDeleteDiagram() {
            this.$bvModal.show('delete-modal');
        }

        handleEditDiagram(diagram) {
            this.toDeleteDiagram = diagram;
            this.editName = diagram.name;
            this.$bvModal.show('edit-modal');
        }

        handleCancelDelete() {
            this.toDeleteDiagram = null
            this.$bvModal.hide('delete-modal');
        }

        handleCancelEdit() {
            this.toDeleteDiagram = null;
            this.editName = "";
            this.$bvModal.hide('edit-modal');
        }

        deleteDiagram() {
            const id = this.toDeleteDiagram.id;
            this.deleteOneDiagram(this.toDeleteDiagram.id)
                .then(() => {
                    const diagram = this.diagrams.find(diagram => diagram.id === id);
                    const index = this.diagrams.indexOf(diagram);
                    this.diagrams.splice(index, 1);
                    this.$bvModal.hide('edit-modal');
                })
            this.toDeleteDiagram = null;
        }

        editDiagram() {
            const diagram = this.toDeleteDiagram;
            this.updateDiagram(this.toDeleteDiagram.id, this.editName, this.toDeleteDiagram.data)
                .then((res) => {
                    diagram.name = res.data.name;
                    this.$bvModal.hide('edit-modal');
                })
            this.toDeleteDiagram = null;
            this.editName = "";
        }


        getValidationState({dirty, validated, valid = null}) {
            return dirty || validated ? valid : null;
        }

    }
</script>

<style lang="scss">
    @import "../scss/_variables.scss";

    .diagram_card {
        cursor: pointer;
        width: 300px;
        height: 150px;
        position: relative;
        margin-left: auto;
        margin-right: auto;
        background-color: $primary;
        color: white;
        font-size: 25px;
    }

    .diagram_card .card-footer {
        background-color: $primary;
        color: white;
    }

    .diagram_card .card-body {
        align-items: center;
        justify-content: center;
        display: flex;
        word-break: break-word;
    }

    .diagram_card:hover {
        border: 1px solid $primary !important;
        color: $secondary;
    }

    .diagram_card .container, .diagram_card .row {
        height: 100%;
    }

    .diagram_pagination .page-item .page-link {
        color: $secondary;
    }

    .diagram_pagination .page-item.active .page-link {
        background-color: $secondary;
        color: white;
        border: 0;
    }

    .logout {
        cursor: pointer;
    }

    .edit_btn {
        position: absolute;
        top: -10px;
        right: -10px;
        background-color: $secondary;
        border: 0;
    }

    .level_start_button {
        background-color: $primary;
        color: white;
        border: 0;
    }

    .level_progress .progress-bar {
        background-color: $secondary;
    }

    .level_progress_text {
        position: absolute;
        width: 100%;
        text-align: center;
        line-height: 2rem;
    }

    .skip_tutorial_button {
        position: absolute;
        top: 50px;
        right: 10px;
        font-size: 12px;
        cursor: pointer;
    }

    .confettiCanvas {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
    }

    @media (max-width: 992px) {
        .diagram_card {
            width: 200px;
            height: 100px;
            font-size: 16px;
        }
        .diagram_plus {
            font-size: 50px;
        }
    }

    @media(max-width: 768px) {
        .diagram_card {
            width: 150px;
            height: 75px;
            font-size: 15px;
        }
        .diagram_plus {
            font-size: 35px;
        }
    }

    @media (max-width: 576px) {
        .diagram_card {
            width: 150px;
            height: 75px;
            font-size: 15px;
        }
        .diagram_plus {
            font-size: 35px;
        }
    }

    @media (max-width: 350px) {
        .diagram_card {
            width: 130px;
            height: 65px;
            font-size: 10px;
        }
        .diagram_plus {
            font-size: 25px;
        }

    }

</style>
