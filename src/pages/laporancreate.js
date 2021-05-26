import Vue from 'vue';
import LaporanCreate from '../components/pages/laporancreate';

new Vue({
    el: '#vuec',
    render: function (createElement) {
        return createElement(LaporanCreate,  {
            props: {}
        })
    }
})