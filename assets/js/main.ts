import Vue from 'vue'
import App from './App.vue'
import './registerServiceWorker'
import Diagram from "./views/Diagram.vue";
import Home from "./views/Home.vue";
import VueRouter from 'vue-router'
import Overview from "./views/Overview.vue";
import { BootstrapVue, BootstrapVueIcons} from "bootstrap-vue";
import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'
import Login from "./views/Login.vue";
import store from "./store";
import '@fortawesome/fontawesome-free/css/all.css'
import '@fortawesome/fontawesome-free/js/all.js'
import Register from "./views/Register.vue";
import {ValidationProvider, ValidationObserver, extend ,localize} from 'vee-validate';
import * as rules from "vee-validate/dist/rules";
import de from 'vee-validate/dist/locale/de';
import Level from "./views/Level.vue";
import Impressum from "./views/Impressum.vue";

Vue.config.productionTip = false;

const routes = [
  { path: '/', name:'Home',component: Home, meta: {title: 'FlowchartApp'}},
  { path: '/login', name: 'Login', component: Login, props: true, meta: {title: 'Anmelden'}},
  { path: '/register', name: 'Register', component: Register, meta: {title: 'Registrieren'}},
  { path: '/diagram/:id', name: 'Diagram', component: Diagram, meta: {title: 'FlowchartApp'}},
  { path: '/list', name: 'List', component: Overview, meta: {title: 'Deine Diagramme'}},
  { path: '/level/:id', name: 'Level', component: Level, meta: {title: 'FlowchartApp'}},
  { path: '/impressum', name: 'Impressum', component: Impressum},
];

const router = new VueRouter({
  routes
});

// Install VeeValidate rules and localization
Object.keys(rules).forEach(rule => {
  extend(rule, rules[rule]);
});

localize('de', de);

Vue.use(BootstrapVue)
Vue.use(BootstrapVueIcons)
Vue.component("ValidationObserver", ValidationObserver);
Vue.component("ValidationProvider", ValidationProvider);
new Vue({
  router,
  store,
  render: h => h(App)
}).$mount('#app')

