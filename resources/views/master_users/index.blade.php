<h2>Master User Finger</h2>

<!-- ===================== INSERT ===================== -->
<h3>Tambah User</h3>
<form action="/master-user/store" method="POST">
    @csrf
    <input type="text" name="userid" placeholder="User ID" required>
    <input type="text" name="uid" placeholder="UID" required>
    <input type="text" name="name" placeholder="Nama" required>
    <input type="text" name="card_number" placeholder="No Kartu">
    <input type="text" name="role" placeholder="Role">
    <button type="submit">Simpan</button>
</form>

<hr>

<!-- ===================== TABLE ===================== -->
<h3>Data User</h3>

<a href="/master-user/sync">Sync Dari Mesin</a>

<table border="1" cellpadding="10">
    <tr>
        <th>UserID</th>
        <th>UID</th>
        <th>Nama</th>
        <th>Card</th>
        <th>Role</th>
        <th>Aksi</th>
    </tr>

    @foreach($users as $user)
        <tr>
            <td>{{ $user->userid }}</td>
            <td>{{ $user->uid }}</td>
            <td>{{ $user->name }}</td>
            <td>{{ $user->card_number }}</td>
            <td>{{ $user->role }}</td>
            <td>

                <!-- ===================== UPDATE ===================== -->
                <form action="{{ route('master-user.update', $user->id) }}" method="POST" style="margin-bottom:5px;">
                    @csrf
                    @method('PUT')

                    <!-- userid wajib dikirim -->
                    <input type="hidden" name="userid" value="{{ $user->userid }}">
                    <input type="hidden" name="uid" value="{{ $user->uid }}">

                    <input type="text" name="name" value="{{ $user->name }}" required>
                    <input type="text" name="card_number" value="{{ $user->card_number }}">
                    <input type="number" name="role" value="{{ $user->role }}">

                    <button type="submit">Update</button>
                </form>
                <!-- ===================== DELETE ===================== -->
                <form action="/master-user/delete/{{ $user->id }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" onclick="return confirm('Yakin hapus user ini?')">
                        Hapus
                    </button>
                </form>

            </td>
        </tr>
    @endforeach
</table>