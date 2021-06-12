import Vue from 'vue';
import LaporanDetail from '../components/pages/laporandetail';
import UrlParse from 'url-parse'

const currentUri = new UrlParse(window.location.href, true)
const parsePath = currentUri.pathname.split('/')
const kunjunganId = parseInt(parsePath[parsePath.length -1])

new Vue({
    el: '#vuec',
    render: function (createElement) {
        return createElement(LaporanDetail,  {
            props: {
                kunjunganId
            }
        })
    }
})