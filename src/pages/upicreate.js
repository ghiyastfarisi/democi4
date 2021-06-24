import Vue from 'vue'
import UpiCreate from '../components/pages/upicreate'

new Vue({
    el: '#vuec',
    render: function (createElement) {
        return createElement(UpiCreate,  {})
    }
})