module.exports = {
    Sanitize: (struct, source) => {
        const sanitized = {}

        for (const [key, value] of Object.entries(struct)) {
            if (source[key]) {
                sanitized[key] = source[key]
            } else {
                sanitized[key] = value
            }
        }

        return sanitized
    }
}