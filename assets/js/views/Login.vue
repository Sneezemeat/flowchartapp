<template>
    <section>
        <b-container class="overflow-scroll" v-if="!register">
            <validation-observer ref="observer" v-slot="{ handleSubmit }">
                <b-form @submit.stop.prevent="handleSubmit(login)">

                    <b-row class="text-center mt-5 justify-content-center">
                        <b-col cols="12">
                            <h1 class="mb-5">Anmelden</h1>
                        </b-col>
                        <b-col :xl="xl ? xl : 5" :lg="lg ? lg : 5" :md="md ? md : 5" :sm="sm ? sm : 9"
                               :cols="cols ? cols : 12">
                            <validation-provider name="E-Mail" :rules="{required: true, email: true}"
                                                 v-slot="validationContext">
                                <b-form-group label="E-Mail" label-for="email-input" class="mt-5">
                                    <b-form-input v-model="email" id="email-input" class="input-outlined"
                                                  :state="getValidationState(validationContext)"
                                                  aria-describedby="input-1-live-feedback"></b-form-input>
                                    <b-form-invalid-feedback id="input-1-live-feedback"> {{validationContext.errors[0]}}
                                    </b-form-invalid-feedback>
                                </b-form-group>
                            </validation-provider>
                        </b-col>
                    </b-row>
                    <b-row class="text-center justify-content-center">
                        <b-col :xl="xl ? xl : 5" :lg="lg ? lg : 5" :md="md ? md : 5" :sm="sm ? sm : 9"
                               :cols="cols ? cols : 12">
                            <validation-provider name="Passwort" :rules="{required: true}"
                                                 v-slot="validationContext">
                                <b-form-group label="Passwort" label-for="password-input">
                                    <b-form-input type="password" v-model="password" id="password-input"
                                                  :state="getValidationState(validationContext)"
                                                  class="input-outlined"
                                                  aria-describedby="input-1-live-feedback"></b-form-input>
                                    <b-form-invalid-feedback id="input-1-live-feedback"> {{validationContext.errors[0]}}
                                    </b-form-invalid-feedback>
                                </b-form-group>
                            </validation-provider>
                            Noch kein Konto?
                            <a class="link" @click="register = true">Registrieren</a>
                        </b-col>
                    </b-row>
                    <b-row class="text-center justify-content-center" v-if="error">
                        <b-col xl="5" lg="5" md="5" sm="9" xs="12" cols="12">
                            <span style="color:red">Die Login Daten sind nicht korrekt</span>
                        </b-col>
                    </b-row>
                    <b-row class="text-center justify-content-center mt-5">
                        <b-col xl="7" lg="7" md="7" sm="12" xs="12" cols="12">
                            <b-button type="submit" class="button_outlined">
                                Anmelden
                            </b-button>
                        </b-col>
                    </b-row>
                </b-form>
            </validation-observer>
        </b-container>
        <Register v-if="register" @login="(email, password) => loginAfterRegister(email, password)"
                  :cols="cols" :sm="sm" :md="md" :lg="lg" :xl="xl"></Register>
        <b-alert :show="dismissCountdown" variant="success" dismissible @dismissed="dismissCountdown = 0"
                 @dismiss-count-down="countDownChanged" class="saveAlert">
            {{alertText}}
            <b-progress variant="success" max="5" :value="dismissCountdown" height="4px"></b-progress>
        </b-alert>
        <b-modal width="500" id="sync-modal"
                 @ok="handleSyncDiagrams" @cancel="$emit('login');" hide-header>
            <template v-slot:modal-cancel>Nein</template>
            <template v-slot:modal-ok>Ja</template>
            <h2>Du hast lokal gespeicherte PAPs.</h2>
            <p class="saveSubtitle">Möchtest du deine PAPs mit deinem Konto synchronisieren?</p>
        </b-modal>
    </section>
</template>

<script lang="ts">
    // @ is an alias to /src
    import {Component, Prop, Vue, Watch} from 'vue-property-decorator';
    import VueRouter from "vue-router";
    import axios from 'axios'
    import VueAxios from 'vue-axios'
    import Register from "./Register.vue";
    import {DiagramMixin} from "../mixins/DiagramMixin";

    Vue.use(VueAxios, axios, VueRouter);
    @Component({
        components: {Register}
    })
    export default class Login extends DiagramMixin {
        private email = "";
        private password = "";
        private error = false;
        private register = false;
        private dismissCountdown = 0;
        private alertText = "Registriert";
        @Prop() public cols;
        @Prop() public sm;
        @Prop() public md;
        @Prop() public lg;
        @Prop() public xl;
        @Prop() public reg;

        constructor() {
            super();
        }

        mounted(){
            this.register = JSON.parse(this.reg);
        }

        getValidationState({dirty, validated, valid = null}) {
            return dirty || validated ? valid : null;
        }

        loginAfterRegister(email, password){
            this.alertText = 'Registriert';
            this.dismissCountdown = 5;
            this.email = email;
            this.password = password;
            this.login();
        }

        login() {
            const formData = new FormData();
            formData.append('email', this.email);
            formData.append('password', this.password);
            this.axios.post('/login', formData, {withCredentials: true})
                .then(() => {
                    if (typeof localStorage.local !== "undefined" && JSON.parse(localStorage.local) && typeof localStorage.diagrams !== "undefined" && JSON.parse(localStorage.diagrams).length > 0) {
                        this.$bvModal.show('sync-modal');
                    } else {
                        this.$emit("login");
                    }
                    localStorage.local = JSON.stringify(false);
                })
                .catch((err) => {
                    this.error = true;
                });
        }

        handleSyncDiagrams() {
            this.syncDiagrams(JSON.parse(localStorage.diagrams)).then(() => {
                this.alertText = "Synchronisiert";
                this.$emit("login");
            });
        }

        //set countdown for save alert
        countDownChanged(dismissCountdown) {
            this.dismissCountdown = dismissCountdown;
        }

    }
</script>

<style lang="scss">
    @import "../scss/_variables.scss";

    .link {
        color: $primary !important;
        cursor: pointer;
    }

    .saveAlert {
        position: fixed;
        top: 10px;
        right: 10px;
        width: 230px;
        z-index: 3;
    }
</style>
