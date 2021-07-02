import Vue from 'vue';
import UserSetting from '../components/pages/usersetting';

const auth = window.COOKIE_OBJECT

new Vue({
    el: '#vuec',
    render: function (createElement) {
        return createElement(UserSetting,  {
            props: {
                ajaxUri: `v1/user/${auth.uid}`,
                uid: parseInt(auth.uid)
            }
        })
    }
})