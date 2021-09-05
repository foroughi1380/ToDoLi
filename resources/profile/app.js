import VueRouter from "vue-router";
import Vue2OrgTree from 'vue2-org-tree'


window.Vue = require("vue");
window.axios = require('axios');

axios.defaults.headers.get['Accepts'] = 'application/json';
axios.defaults.headers.common['Access-Control-Allow-Origin'] = '*';
axios.defaults.headers.common['Access-Control-Allow-Headers'] ='Access-Control-Allow-Headers: Origin, Content-Type, Accept, Authorization, X-Request-With';

import VueToast from 'vue-toast-notification';

import 'vue-toast-notification/dist/theme-default.css';
//import 'vue-toast-notification/dist/theme-sugar.css';

Vue.use(Vue2OrgTree)

Vue.use(VueToast);

Vue.use(VueRouter);

Vue.mixin({
    methods: {
        goto: function (route) {
            router.push({path: "/" + route});
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
        },
        getImageUrl(url){
            return "files/"+url;
        },
        persian: function (input) {
            if (input === undefined || input == null) return "";
            let str1 = input.toString().trim();
            if (str1 === '') return '';
            str1 = str1.replace(/0/img, '۰');
            str1 = str1.replace(/1/img, '۱');
            str1 = str1.replace(/2/img, '۲');
            str1 = str1.replace(/3/img, '۳');
            str1 = str1.replace(/4/img, '۴');
            str1 = str1.replace(/5/img, '۵');
            str1 = str1.replace(/6/img, '۶');
            str1 = str1.replace(/7/img, '۷');
            str1 = str1.replace(/8/img, '۸');
            str1 = str1.replace(/9/img, '۹');
            return str1;
        },
        getTime: function (input) {
            try {
                let time = input.split(" ")[1].split(":");
                return time[0] + ":" + time[1];
            } catch (e) {
                return input;
            }
        },
        jalali: function (input) {
            try {
                let moment = require('moment-jalaali');
                let date = moment(input, 'YYYY/MM/DD');
                return date.format('jYYYY/jM/jD');
            } catch (e) {
                return input;
            }
        }
    },
    data(){
        return {
            user : null
        }
    }
})

import home from "./components/home";
import GroupTasks from "./components/GroupTasks";
import CreateGroup from "./components/CreateGroup";
import Chart from "./components/Chart";


const routes = [
    {path:"*" , component:home},
    {path:"/group/:id" , component: GroupTasks , props:true},
    {path:"/chart/:id" , component: Chart , props:true},
    {path:"/createGroup" , component: CreateGroup}
]

const router = new VueRouter({
    routes
})

window.app = new Vue({
    router,
    created: function () {
        this.getUserInformation();
        this.$on("infoupdate" , function () {
            this.getUserInformation()
        }.bind(this))
        setInterval(function(){ this.loadNotifications(); this.loadJoins();}.bind(this), 2000);
    },
    methods :{
        getUserInformation(){
            axios.get("api.php" , {params:{
                    a : "me"
                }})
                .then(value => {
                    this.user = value.data.payload.values[0];
                    window.user = value.data.payload.values[0];
                }).catch(reason => {
                this.redirect("index.html");
            })
            this.getGroups();

            this.$on("groupUpdate" , function(){
                this.getGroups();
            });
        },
        logout(){
            axios.get("api.php" , {params:{
                    a : "logout"
                }}).finally(() => this.redirect("index.html"));
        },
        openGroup(g){
            this.goto("group/" + g.id);
        },
        getGroups(){
            axios.get("api.php" , {params:{
                    a : "groups"
                }})
                .then(value => {
                    this.groups = value.data.payload.values[0];
                })
        },
        loadNotifications(){
            axios.get("api.php?a=notifications")
                .then(value => {
                    this.toastAll(value.data.payload);
                    this.notifications = value.data.payload.values[0]
                }).catch(reason => {
                this.toastAll(reason.response.data.payload);
            })
        },
        loadJoins(){
            axios.get("api.php?a=joinlist")
                .then(value => {
                    this.toastAll(value.data.payload);
                    this.joins = value.data.payload.values[0]
                }).catch(reason => {
                this.toastAll(reason.response.data.payload);
            })
        },
        joinAccept(accept , j){
            axios.get("api.php?a=joinrequestaccept"  , {
                params:{
                    accept : accept ,
                    id : j.id
                }
            })
                .then(value => {
                    this.toastAll(value.data.payload);
                    if (accept){
                        this.getGroups()
                    }
                }).catch(reason => {
                this.toastAll(reason.response.data.payload);
            })
        }
    },
    data : {
        groups:[],
        notifications : [],
        joins : [],
    }
}).$mount("#app")