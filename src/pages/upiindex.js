import Vue from 'vue'
import UpiIndex from '../components/pages/upiindex'

new Vue({
    el: '#vuec',
    render: function (createElement) {
        return createElement(UpiIndex,  {
            props: {
                ajaxUri: `v1/upi/all`
            }
        })
    }
})