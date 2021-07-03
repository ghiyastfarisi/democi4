const getSessionCookie = () =>
    decodeURIComponent(document.cookie).split('auth=')

const parseJson = (c) => {
    try {
        return JSON.parse(c)
    } catch (error) {
        return {}
    }
}

const parseSessionCookie = () => {
    const c = getSessionCookie()

    if (c.length<1) {
        return {}
    } else if (c.length>1) {
        let sess;

        c.forEach(el => {
            const s = parseJson(el)

            if (s && s.login) {
                sess = s
            }
        })

        return sess
    } else {
        return JSON.parse(c)
    }
}

const UserSession = parseSessionCookie()

module.exports = {
    UserSession
}