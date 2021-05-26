import Vue from 'vue';
import Dashboard from '../components/pages/dashboard';

new Vue({
    el: '#vuec',
    render: function (createElement) {
        return createElement(Dashboard,  {
            props: {}
        })
    }
})