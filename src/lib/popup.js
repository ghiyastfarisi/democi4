const Swal = require('sweetalert2')

module.exports = {
    AutoClosePopup: ({
        title, body, timeout, icon = ''
    }) => {
        let timerInterval

        Swal.fire({
            ...(icon !== '' ? { icon } : {} ),
            title,
            html: body,
            timer: timeout,
            timerProgressBar: true,
            didOpen: () => {
                Swal.showLoading()
                timerInterval = setInterval(() => {
                    const content = Swal.getContent()
                    if (content) {
                        const b = content.querySelector('b')
                        if (b) {
                            b.textContent = Swal.getTimerLeft()
                        }
                    }
                }, 100)
            },
            willClose: () => {
                clearInterval(timerInterval)
            }
        })
    },
    Loading: ({
        title, body, icon = ''
    }) => {
        return Swal.fire({
            ...(icon !== '' ? { icon } : {} ),
            title,
            html: body,
            timerProgressBar: false,
            didOpen: () => {
                Swal.showLoading()
            }
        })
    },
}