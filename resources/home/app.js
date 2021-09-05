import VueRouter from "vue-router";


window.Vue = require("vue");
window.axios = require('axios');

axios.defaults.headers.get['Accepts'] = 'application/json';
axios.defaults.headers.common['Access-Control-Allow-Origin'] = '*';
axios.defaults.headers.common['Access-Control-Allow-Headers'] ='Access-Control-Allow-Headers: Origin, Content-Type, Accept, Authorization, X-Request-With';

import VueToast from 'vue-toast-notification';
// Import one of the available themes
import 'vue-toast-notification/dist/theme-default.css';
//import 'vue-toast-notification/dist/theme-sugar.css';

Vue.use(VueToast);

Vue.use(VueRouter);

Vue.mixin({
    methods: {
        goto: function (route) {
            router.push({path: "/" + route});
        },
        getImageUrl(url){
            return "files/"+url;
        },
        toast:function (text , type="info" , duration = 3000, position = "bottom-left") {
            Vue.$toast.open({
                message: text,
                type: type,
                position : position,
                duration : duration
            });
        },
        toastAll:function (data){
            for (const info of data.info) {
                this.toast(info , 'info');
            }

            for (const info of data.errors) {
                this.toast(info , 'error');
            }

            for (const info of data.warnings) {
                this.toast(info , 'warning');
            }
        },
        redirect(path){
            window.location.replace(path);
        },
        validateEmail(email) {
            const re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
            return re.test(email);
        }
    },
    data(){
        return {
            user : null
        }
    }
})

import home from "./components/home";
import login from "./components/login";
import register from "./components/register";
import aboutus from "./components/aboutus";
import contactus from "./components/contactus";


const routes = [
    {path:"*" , component:home},
    {path:"/register" , component:register},
    {path:"/login" , component:login},
    {path:"/contactus" , component:contactus},
    {path:"/aboutus" , component:aboutus},

]

const router = new VueRouter({
    routes
})

window.app = new Vue({
    router,
    created: function () {
        axios.get("api.php" , {params:{
                a : "me"
            }})
            .then(value => {
                this.user = value.data.payload.values[0]
                window.user = value.data.payload.values[0];
            })

    }
}).$mount("#app");