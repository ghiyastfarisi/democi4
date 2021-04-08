import Vue from 'vue'
import UserDetail from '../components/userdetail'
import UrlParse from 'url-parse'

const currentUri = new UrlParse(window.location.href, true)
const parsePath = currentUri.pathname.split('/')
const userId = parseInt(parsePath[parsePath.length -1])

new Vue({
    el: '#vuec',
    render: function (createElement) {
        return createElement(UserDetail,  {
            props: {
                ajaxUri: `v1/user/${userId}`,
                uid: userId
            }
        })
    }
})