import Vue from 'vue'
import PembinaMutuDetail from '../components/pages/pembinamutudetail'
import UrlParse from 'url-parse'

const currentUri = new UrlParse(window.location.href, true)
const parsePath = currentUri.pathname.split('/')
const pid = parseInt(parsePath[parsePath.length -1])

new Vue({
    el: '#vuec',
    render: function (createElement) {
        return createElement(PembinaMutuDetail,  {
            props: {
                ajaxUri: `v1/pembina-mutu/${pid}`,
                uid: pid
            }
        })
    }
})