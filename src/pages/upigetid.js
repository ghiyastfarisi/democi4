import Vue from 'vue'
import UpiDetail from '../components/pages/upidetail'
import UrlParse from 'url-parse'

const currentUri = new UrlParse(window.location.href, true)
const parsePath = currentUri.pathname.split('/')
const upiId = parseInt(parsePath[parsePath.length -1])

new Vue({
    el: '#vuec',
    render: function (createElement) {
        return createElement(UpiDetail,  {
            props: {
                ajaxUri: `v1/upi/${upiId}`,
                uid: upiId
            }
        })
    }
})