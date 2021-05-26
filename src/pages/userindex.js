import Vue from 'vue';
import Ajaxtable from '../components/ajaxtable';

new Vue({
    el: '#vuec',
    render: function (createElement) {
        return createElement(Ajaxtable,  {
            props: {
                fieldType: 'user',
                ajaxUri: 'v1/user/all',
                showLimit: 10
            }
        })
    }
})