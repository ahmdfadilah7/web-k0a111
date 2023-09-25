<table style="width: 100%; border: 2px solid #000;">
    <thead>
        <tr>
            <th colspan="12" style="font-size: 13px; font-weight: bold; text-align:center;">Data Pemohon {{ $setting->nama_website }}</th>
        </tr>
        <tr>
            <th colspan="12">Tanggal : {{ date('d M Y') }}</th>
        </tr>
        <tr>
            <th>No</th>
            <th>NIK</th>
            <th>Nama Lengkap</th>
            <th>Email</th>
            <th>No Telepon</th>
            <th>Jenis Kelamin</th>
            <th>Tempat Lahir</th>
            <th>Tanggal Lahir</th>
            <th>Alamat</th>
            <th>Tempat Pendaftaran</th>
            <th>Tanggal Pendaftaran</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($profile as $no => $value)
            <tr>
                <td>{{ ++$no }}</td>
                <td>'{{ $value->nik }}</td>
                <td>{{ $value->nama_lengkap }}</td>
                <td>{{ $value->email }}</td>
                <td>{{ $value->no_telp }}</td>
                <td>
                    @if($value->jns_kelamin == 'L')
                        Laki - Laki
                    @elseif($value->jns_kelamin == 'P')
                        Perempuan
                    @endif    
                </td>
                <td>{{ $value->tmpt_lahir }}</td>
                <td>{{ date('d-m-Y', strtotime($value->tgl_lahir)) }}</td>
                <td>{!! $value->alamat !!}</td>
                <td>{{ $value->nama_polres }}</td>
                <td>{{ date('d-m-Y', strtotime($value->created_at)) }}</td>
                <td>
                    @if($value->status == 0)
                        Belum Diverifikasi
                    @elseif($value->status == 1)
                        Sudah Diverifikasi
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
</table>