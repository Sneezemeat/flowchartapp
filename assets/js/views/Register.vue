<template>
    <b-container class="overflow-scroll">
        <validation-observer ref="observer" v-slot="{ handleSubmit }">
            <b-form @submit.stop.prevent="handleSubmit(register)">


                <b-row class="text-center mt-5 justify-content-center">
                    <b-col cols="12">
                        <h1 class="mb-0">Registrieren</h1>
                    </b-col>
                    <b-col :xl="xl ? xl : 5" :lg="lg ? lg : 5" :md="md ? md : 5" :sm="sm ? sm : 9"
                           :cols="cols ? cols : 12">
                        <validation-provider name="E-Mail" :rules="{required: true, email: true}"
                                             v-slot="validationContext">
                            <b-form-group label="E-Mail*" label-for="email-input" class="mt-5">
                                <b-form-input required v-model="email" id="email-input"
                                              class="input-outlined"
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
                        <validation-provider name="Passwort" :rules="{required: true, min: 8}"
                                             v-slot="validationContext" vid="password">
                            <b-form-group label="Passwort*" label-for="password-input">
                                <b-form-input required type="password" v-model="password" id="password-input"
                                              class="input-outlined"
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
                        <validation-provider name="Passwort Wiederholung" rules="required|confirmed:password"
                                             v-slot="validationContext">
                            <b-form-group label="Passwort Wiederholung*" label-for="password-repeat">
                                <b-form-input required type="password" v-model="passwordRepeat" id="password-repeat"
                                              class="input-outlined"
                                              :state="getValidationState(validationContext)"
                                              aria-describedby="input-1-live-feedback"></b-form-input>
                                <b-form-invalid-feedback id="input-1-live-feedback"> {{validationContext.errors[0]}}
                                </b-form-invalid-feedback>
                            </b-form-group>
                        </validation-provider>
                    </b-col>
                </b-row>
                <b-row class="text-center justify-content-center" v-if="error">
                    <b-col :xl="xl ? xl : 5" :lg="lg ? lg : 5" :md="md ? md : 5" :sm="sm ? sm : 9"
                           :cols="cols ? cols : 12">
                        <span style="color:red">{{errorMsg}}</span>
                    </b-col>
                </b-row>
                <b-row class="text-center justify-content-center mt-5">
                    <b-col xl="7" md="7" sm="12" xs="12">
                        <b-button class="button_outlined" type="submit">
                            Registrieren
                        </b-button>
                    </b-col>
                </b-row>

            </b-form>
        </validation-observer>
    </b-container>

</template>

<script lang="ts">
    // @ is an alias to /src
    import {Component, Prop} from 'vue-property-decorator';
    import Vue from 'vue';
    import VueRouter from "vue-router";
    import axios from 'axios'
    import VueAxios from 'vue-axios'


    Vue.use(VueAxios, axios, VueRouter);
    @Component
    export default class Register extends Vue {
        private email = "";
        private password = "";
        private passwordRepeat = "";
        private error = false;
        private errorMsg = "";
        @Prop() public cols;
        @Prop() public sm;
        @Prop() public md;
        @Prop() public lg;
        @Prop() public xl;

        constructor() {
            super();
        }

        getValidationState({dirty, validated, valid = null}) {
            return dirty || validated ? valid : null;
        }


        register() {
            const dataObject = {
                email: this.email,
                password: this.password,
                // eslint-disable-next-line @typescript-eslint/camelcase
                passwordConfirmation: this.passwordRepeat,
                // eslint-disable-next-line @typescript-eslint/camelcase
                isAdmin: false,
                level: 0
            };
            this.axios.post('/register', dataObject, {
                headers: {'Content-Type': 'application/json'},
                withCredentials: true
            })
                .then(() => {
                        this.error = false;
                        this.$emit("login", this.email, this.password);
                })
                .catch((error) => {
                    this.error = true;
                    if (error.response.data.error) {
                        this.errorMsg = error.response.data.error;
                    } else {
                        this.errorMsg = "Es gab einen Fehler bei der Registrierung, versuch es später erneut.";
                    }
                });
        }

    }
</script>

<style>

</style>
