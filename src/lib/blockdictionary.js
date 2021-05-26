const FieldData = {
    upi: [
        {
            name: 'Nama',
            origin: 'nama_perusahaan',
        },
        {
            name: 'NIB',
            origin: 'nib'
        },
        {
            name: 'Kecamatan',
            origin: 'location_district_name'
        },
        {
            name: 'Kota Kabupaten',
            origin: 'location_regency_name'
        },
        {
            name: 'Setting',
            origin: 'setting',
            decorator: true
        }
    ]
}

module.exports = {
    FieldData
}