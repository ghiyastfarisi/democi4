import Vue from 'vue';
import LaporanIndex from '../components/pages/laporanindex';

new Vue({
    el: '#vuec',
    render: function (createElement) {
        return createElement(LaporanIndex,  {
            props: {}
        })
    }
})