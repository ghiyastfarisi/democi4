<template>
    <form class="form-name-container" @submit.prevent="submitData">
        <section class="modal-card-body">
            <div class="field">
                <label class="label">Username</label>
                <div class="control">
                    <input class="input" name="username" type="email" placeholder="use email format" v-model="iuname" :disabled="formDep.isEdit">
                </div>
            </div>
            <div class="field">
                <label class="label">Password</label>
                <div class="control">
                    <input class="input" name="password" type="password" placeholder="minimum 8 characters" v-model="ipass">
                </div>
            </div>
            <div v-if="formDep.isEdit" class="field">
                <label class="label">Login Status</label>
                <div class="control is-expanded">
                    <div class="select is-fullwidth">
                        <select name="login_status" id="login_status" v-model="iloginStatus">
                            <option v-for="(list, index) in loginStatusArray" :value="list" :key="index">
                                {{ list }}
                            </option>
                        </select>
                    </div>
                </div>
            </div>
        </section>
        <footer class="modal-card-foot">
            <button type="submit" class="button is-success">{{ formDep.submit }}</button>
            <button type="cancel" @click="Close" class="button">Cancel</button>
        </footer>
    </form>
</template>

<script>
const { AutoClosePopup } = require('../../lib/popup')

module.exports = {
    data: function() {
        return {
            iuname: '',
            ipass: '',
            iloginStatus: '',
            loginStatusArray: [ 'active', 'inactive' ]
        }
    },
    props: {
        formDep: {
            submit: String,
            isEdit: Boolean,
            fetchEditUrl: String,
            updateUrl: String,
            extra: {
                userId: Number
            }
        }
    },
    created() {
        let fd = this.formDep

        console.log(fd)

        if (fd.isEdit) {
            alert('used for edit')
        }
    },
    methods: {
        submitData() {
            const url = BASE_API_URL + 'v1/user/create'
            const payload = JSON.stringify({
                username: this.iuname,
                password: this.ipass
            })
            fetch(url, {
                method: 'POST',
                body: payload,
            })
            .then(resp => {
                if (!resp.ok) {
                    return resp.json()
                        .then(errResp => {
                            if (errResp && errResp.error && errResp.error.message) {
                                console.log(errResp.error.message)
                            }
                        })
                        .catch(err => {
                            console.error(err)
                        })
                }

                return resp.json()
            })
            .catch(err => {
                console.error(err)
            })

            this.Close()

            AutoClosePopup({
                title: 'Create Data Succeeded',
                body: '',
                timeout:1500
            })

            this.$emit('update-table')
        },
        Close() {
            this.$emit('toggle-close')
        },
    }
};
</script>

<style scoped>
</style>