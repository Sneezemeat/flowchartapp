<template>
    <b-container class="home">

        <b-row class="text-center mb-3 mt-5 justify-content-center" align-h="center">
            <b-col cols="12" class="justify-content-center" align-self="center">
                <img :src="logo" alt="Logo" class="d-none d-sm-none d-md-block d-xl-block mr-auto ml-auto">
                <img :src="logo_mobile" alt="Logo"
                     class="logo_mobile d-block d-sm-block d-md-none d-xl-none mr-auto ml-auto">
            </b-col>
            <b-col xl="5" lg="5" md="5" sm="8" cols="8">
                <b-button :to="{name: 'Login', params: {reg: 'false'}}" class="button_outlined mt-5">
                    Anmelden
                </b-button>
                <br class="pb-5">
            </b-col>
        </b-row>
        <b-row class="text-center justify-content-center mb-3">
            <b-col xl="5" lg="5" md="5" sm="8" cols="8">
                <b-button class="button_outlined" @click="register">
                    Registrieren
                </b-button>
            </b-col>
        </b-row>
        <b-row class="text-center justify-content-center">
            <!--offset-xl="3" offset-lg="3" offset-md="3" offset-sm="1"-->
            <b-col cols="1">
            </b-col>
            <b-col xl="5" lg="5" md="5" sm="8" cols="8">
                <b-button class="button_outlined" @click="startLocal">
                    Offline nutzen
                </b-button>
            </b-col>
            <b-col cols="1" class="info"
                   v-b-popover.hover.top="'Hier kannst du die App verwenden ohne dich anzumelden und deine Diagramme lokal speichern.'" v-b-popover.click.top="'Hier kannst du die App verwenden ohne dich anzumelden und deine Diagramme lokal speichern.'">
                <b-icon-info-circle scale="2"
                                    class="d-none d-sm-block d-md-block d-lg-block d-xl-block"></b-icon-info-circle>
                <b-icon-info-circle scale="1.5"
                                    class="d-block d-sm-none d-md-none d-lg-none d-xl-none"></b-icon-info-circle>
            </b-col>
        </b-row>

        <b-link :to="{name: 'Impressum'}" class="impressum">Impressum</b-link>
    </b-container>

</template>

<script lang="ts">
    // @ is an alias to /src
    import {Component, Vue} from 'vue-property-decorator';
    import VueRouter from 'vue-router';
    import Login from "./Login.vue";

    Vue.use(VueRouter, Login);
    @Component({
        components: {}
    })
    export default class Home extends Vue {
        private logo = require('../assets/fAppLogo.png?name="fAppLogo.png"').default;
        private logo_mobile = require('../assets/fAppLogoMobile.png?name="fAppLogoMobile.png"').default;

        constructor() {
            super();
        }

        register() {
            this.$router.push({name: 'Login', params: {reg: 'true'}});
        }

        startLocal() {
            const user = (localStorage.user !== "undefined" && typeof localStorage.user !== "undefined") && JSON.parse(localStorage.user).name === "Lokal" ? JSON.parse(localStorage.user) : {
                id: 1,
                email: null,
                name: "Lokal",
                level: 0
            };
            this.$store.commit('setUser', user);
            localStorage.user = JSON.stringify(user);
            localStorage.diagrams = localStorage.diagrams === "undefined" || typeof localStorage.diagrams === "undefined" ? JSON.stringify([]) : localStorage.diagrams;
            localStorage.local = true;
            this.$router.push({name: 'List'});
        }

    }
</script>

<style scoped>
    .info {
        width:            100%;
        height:           6vh;
        display:          flex;
        align-items:      center;
        justify-content:  center;
        background-color: white;
        box-shadow:       0 0 black;
    }

    .impressum{
        position: absolute;
        right: 5px;
        bottom: 5px;
    }

    @media (max-width: 430px) {
        .logo_mobile {
            width: 100%;
        }

        .btn {
            font-size: 18px !important;
        }
    }

</style>
