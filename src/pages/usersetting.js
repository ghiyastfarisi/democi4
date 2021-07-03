import Vue from 'vue';
import UserSetting from '../components/pages/usersetting';
import { UserSession } from '../lib/auth'

new Vue({
    el: '#vuec',
    render: function (createElement) {
        return createElement(UserSetting,  {
            props: {
                ajaxUri: `v1/user/${UserSession.uid}`,
                uid: parseInt(UserSession.uid)
            }
        })
    }
})