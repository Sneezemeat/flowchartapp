<template>
    <div id="app">
        <meta name="viewport" content="width=device-width, user-scalable=no">
        <router-view v-on:login="getUser"></router-view>
    </div>
</template>

<script lang="ts">
    import {Component, Vue} from 'vue-property-decorator';
    import VueRouter from "vue-router";
    import axios from 'axios'
    import VueAxios from 'vue-axios'

    Vue.use(VueAxios, axios, VueRouter);
    @Component({
        components: {},
    })
    export default class App extends Vue {

        constructor() {
            super();
            this.getUser();
        }


        getUser() {
            if (typeof localStorage.local !== "undefined" && JSON.parse(localStorage.local)) {
                this.$store.commit('setUser', JSON.parse(localStorage.user));
                this.$router.push({name: "List"});
            } else {
                this.axios.get(
                    '/profile', {withCredentials: true})
                    .then((res) => {
                        this.$store.commit('setUser', res.data);
                        if (this.$router.currentRoute.name === "Login") {
                            this.$router.push({name: "List"});
                        }
                    })
                    .catch(() => {
                        this.$store.commit('unsetUser');
                        this.$router.push({name: "Home"});

                    });
            }
        }
    }
</script>

<style lang="scss" scoped>

</style>

<style lang="scss">
    @import "./scss/_variables.scss";

    body, html {
        height: 100%;
        margin: 0;
        font-family: $primary-font;
        overscroll-behavior: none;
        overflow: hidden;
    }


    a, a:hover, a:hover > * {
        text-decoration: none;
        color: inherit;
    }

    #app {
        height: 100%;
        font-family: $primary-font;
        letter-spacing: 0.22em;
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
        text-align: center;
        color: #000000;
        margin-top: 0;
    }

    .button_outlined {
        width: 100%;
        color: $primary;
        border: 1px solid $primary;
        background-color: transparent;
        font-size: 1.3rem !important;
    }

    .button_outlined:hover {
        background-color: $primary;
        color: white;
        border-color: $primary;
    }

    .icon_button {
        width: 100%;
        background-color: transparent;
        font-size: 1.3rem !important;
    }

    .secondary_color {
        background-color: $secondary;
    }

    .input-outlined:focus {
        border-color: $primary;
        border: 2px solid $primary;
        box-shadow: none;
    }

    .main_bar {
        height: 40px;
        background-color: $primary;
        width: 100%;
        position: absolute;
        top: 0px;
        left: 0px;
        color: white;
        z-index: 3;
    }

    .main_bar .row {
        height: 100%
    }

    .main_bar .main_bar_right {
        text-align: right;
        padding-right: 40px;
    }

    .main_bar .main_bar_left {
        text-align: left;
        padding-left: 40px;
    }

    .icon {
        cursor: pointer;
    }

    .overflow-scroll {
        overflow: scroll;
        height: 100%;
        padding-bottom: 20px;
    }

</style>
