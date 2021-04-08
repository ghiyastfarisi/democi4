const TableFieldTypes = [ 'user' ]
const TableFieldData = {
    user: [
        {
            name: 'Username',
            origin: 'username'
        },
        {
            name: 'Status',
            origin: 'login_status'
        },
        {
            name: 'Setting',
            origin: 'setting',
            decorator: true
        }
    ],
    pm_pendidikan: [
        {
            name: 'Jenjang',
            origin: 'jenjang'
        },
        {
            name: 'Institusi Pendidikan',
            origin: 'institusi_pendidikan'
        },
        {
            name: 'Program Studi',
            origin: 'program_studi'
        },
        {
            name: 'Tahun Lulus',
            origin: 'tahun_lulus'
        },
        {
            name: 'Setting',
            origin: 'setting',
            decorator: true
        }
    ],
    pm_jabatan: [
        {
            name: 'Jabatan',
            origin: 'jabatan'
        },
        {
            name: 'Unit Kerja',
            origin: 'unit_kerja'
        },
        {
            name: 'Instansi',
            origin: 'instansi'
        },
        {
            name: 'Tahun Mulai',
            origin: 'tahun_mulai'
        },
        {
            name: 'Tahun Selesai',
            origin: 'tahun_selesai'
        },
        {
            name: 'Masih Menjabat',
            origin: 'masih_menjabat'
        },
        {
            name: 'Setting',
            origin: 'setting',
            decorator: true
        }
    ],
    pm_pelatihan: [
        {
            name: 'Nama Pelatihan',
            origin: 'nama_pelatihan'
        },
        {
            name: 'Penyelenggara',
            origin: 'penyelenggara'
        },
        {
            name: 'Tahun Pelaksanaan',
            origin: 'tahun_pelaksanaan'
        },
        {
            name: 'Setting',
            origin: 'setting',
            decorator: true
        }
    ]
}

module.exports = {
    TableFieldTypes,
    TableFieldData,
}