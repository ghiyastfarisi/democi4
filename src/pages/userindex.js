import Vue from 'vue';
import UserIndex from '../components/pages/userindex';

new Vue({
    el: '#vuec',
    render: function (createElement) {
        return createElement(UserIndex,  {
            props: {}
        })
    }
})