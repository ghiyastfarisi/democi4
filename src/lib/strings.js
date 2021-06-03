module.exports = {
    CleanupFileName: (input) => input.replace(/[^a-z0-9]/gi, '_').toLowerCase()
}