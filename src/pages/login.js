import Vue from 'vue';
import LoginPage from '../components/pages/login';

new Vue({
    el: '#vuec',
    render: function (createElement) {
        return createElement(LoginPage,  {})
    }
})