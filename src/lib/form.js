module.exports = {
    async HandleGet(url, object) {
        return
    },
    async HandlePatch(url, payload) {
        const result = {
            isError: false,
            message: ''
        }
        await fetch(url, {
            method: 'PATCH',
            body: payload,
        })
        .then(async stream => {
            const resp = await stream.json()

            if (!stream.ok) {
                result.isError = true
                result.message = resp.error.message

                return
            }

            result.message = resp

            if (resp.message) {
                result.message = resp.message
            }

            if (resp.data && resp.data.message) {
                result.message = resp.data.message
            }

            return
        })
        .catch(err => {
            result.isError = true
            result.message = err
        })

        return result
    },
    async HandlePost(url, payload) {
        const result = {
            isError: false,
            message: {}
        }
        await fetch(url, {
            method: 'POST',
            body: payload,
        })
        .then(async stream => {
            const resp = await stream.json()

            if (!stream.ok) {
                result.isError = true
                result.message = resp.error.message

                return
            }

            result.message = resp

            if (resp.message) {
                result.message = resp.message
            }

            if (resp.data && resp.data.message) {
                result.message = resp.data.message
            }

            return
        })
        .catch(err => {
            result.isError = true
            result.message = err
        })

        return result
    },
    async HandleDelete(url) {
        const result = {
            isError: false,
            message: {}
        }
        await fetch(url, {
            method: 'DELETE'
        })
        .then(async stream => {
            const resp = await stream.json()

            if (!stream.ok) {
                result.isError = true
                result.message = resp.error.message

                return
            }

            result.message = resp

            if (resp.message) {
                result.message = resp.message
            }

            if (resp.data && resp.data.message) {
                result.message = resp.data.message
            }

            return
        })
        .catch(err => {
            result.isError = true
            result.message = err
        })

        return result
    },
    ParseError(resp) {
        let msg = ''
        const dataType = typeof resp

        if (dataType === 'array' || dataType === 'object') {
            for (const val in resp) {
                const prefix = dataType === 'object' ? `${val}: ` : ''

                msg += `${prefix}${resp[val]}<br/>`
            }
        } else {
            msg = resp
        }

        return msg
    },
    async HandlePostUpload(url, payload) {
        const result = {
            isError: false,
            message: {},
            origin: {}
        }
        await fetch(url, {
            method: 'POST',
            body: payload,
        })
        .then(async stream => {
            const resp = await stream.json()

            if (!stream.ok) {
                result.isError = true
                result.message = resp.error.message

                return
            }

            result.message = resp

            if (resp.message) {
                result.message = resp.message
            }

            if (resp.data && resp.data.message) {
                result.message = resp.data.message
            }

            result.origin = resp

            return
        })
        .catch(err => {
            result.isError = true
            result.message = err
        })

        return result
    },
}