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
    ],
    upi: [
        {
            name: 'Nama',
            origin: 'nama_perusahaan',
            detail_link: true
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
    ],
    riwayat_pembinaan: [
        {
            name: 'Kegiatan',
            origin: 'kegiatan',
            detail_link: true
        },
        {
            name: 'Nama Pembina Mutu',
            origin: 'nama_pembina_mutu'
        },
        {
            name: 'Unit Kerja',
            origin: 'unit_kerja_terakhir'
        },
        {
            name: 'Tanggal',
            origin: 'tanggal_kunjungan'
        },
        {
            name: 'Setting',
            origin: 'setting',
            decorator: true
        }
    ],
    pembina_mutu: [
        {
            name: 'Nama',
            origin: 'nama_lengkap',
            detail_link: true
        },
        {
            name: 'Instansi',
            origin: 'instansi'
        },
        {
            name: 'Setting',
            origin: 'setting',
            decorator: true
        }
    ],
    riwayat_kunjungan: [
        {
            name: 'Kegiatan',
            origin: 'kegiatan',
            detail_link: true
        },
        {
            name: 'UPI',
            origin: 'nama_upi'
        },
        {
            name: 'Provinsi',
            origin: 'nama_provinsi'
        },
        {
            name: 'Tanggal',
            origin: 'tanggal_kunjungan'
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