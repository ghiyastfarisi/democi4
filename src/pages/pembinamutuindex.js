import Vue from 'vue'
import PembinaMutuIndex from '../components/pages/pembinamutuindex'

new Vue({
    el: '#vuec',
    render: function (createElement) {
        return createElement(PembinaMutuIndex,  {
            props: {
                ajaxUri: `v1/pembina-mutu/all?getInstansi=true`
            }
        })
    }
})