<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>El-Gato - Edit Identitas</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="flex items-center justify-center min-h-screen bg-gray-100 font-sans">
    <?php
        session_start();

        // Memeriksa apakah pengguna sudah login
        if (!isset($_SESSION['user_id'])) {
            echo '<script>
            Swal.fire({
                title: "Terjadi Kesalahan",
                text: "Login gagal, silahkan coba lagi.",
                icon: "warning",
                confirmButtonText: "Kembali",
            }).then(function() {
                window.location = "../Login/";
            });
            </script>';
            exit();
        }

        include "../../connection.php";

        // Mendapatkan UserID dari session
        $id = $_SESSION['user_id'];

        // Query untuk mengambil data pengguna
        $data = mysqli_query($connect, "SELECT * FROM user WHERE UserID='$id'");

        // Memeriksa apakah data pengguna ditemukan
        if(mysqli_num_rows($data) > 0){
            $output = mysqli_fetch_assoc($data);
            // Mendapatkan path foto profil
            $profile_picture = "../ProfilePicture/" . $output['ProfilePicture'];

            // Validasi apakah file ada, jika tidak gunakan gambar default
            if (empty($output['ProfilePicture']) || !file_exists($profile_picture)) {
                $profile_picture = '../../Asset/user.png';
            }
        } else {
            echo '<script>
            Swal.fire({
                title: "Terjadi Kesalahan",
                text: "Data pengguna tidak ditemukan.",
                icon: "error",
                confirmButtonText: "Kembali",
            }).then(function() {
                window.location = "../Login/";
            });
            </script>';
            exit();
        }
    ?>
    <div class="bg-white p-8 rounded-bl-3xl rounded-tr-3xl shadow-lg w-full max-w-2xl">
        <h1 class="text-4xl font-bold text-gray-800 text-center">Identitas Anda</h1>
        <hr class="my-4 border-gray-300">
        
        <div class="flex items-center space-x-8">
            <!-- Foto Profil -->
            <div class="w-32 aspect-square">
                <img src="<?php echo $profile_picture; ?>" alt="Foto Profil" class="w-full h-full object-cover rounded-full">
            </div>
            
            <!-- Data Pengguna dalam Tabel -->
            <div class="w-full space-y-4">
                <table class="min-w-full text-sm text-left text-gray-500">
                    <tbody>
                        <tr class="border-b">
                            <td class="py-2 px-4 font-semibold">Nama Lengkap</td>
                            <td class="py-2 px-4" id="namalengkap"><?php echo htmlspecialchars($output['NamaLengkap']); ?></td>
                            <td class="py-2 px-4 text-blue-500 hover:underline cursor-pointer" onclick="editData('Nama Lengkap', 'namalengkap', '<?php echo $id; ?>')">Edit</td>
                        </tr>
                        <tr class="border-b">
                            <td class="py-2 px-4 font-semibold">Username</td>
                            <td class="py-2 px-4" id="username"><?php echo htmlspecialchars($output['Username']); ?></td>
                            <td class="py-2 px-4 text-blue-500 hover:underline cursor-pointer" onclick="editData('Username', 'username', '<?php echo $id; ?>')">Edit</td>
                        </tr>
                        <tr class="border-b">
                            <td class="py-2 px-4 font-semibold">Email</td>
                            <td class="py-2 px-4" id="email"><?php echo htmlspecialchars($output['Email']); ?></td>
                            <td class="py-2 px-4 text-blue-500 hover:underline cursor-pointer" onclick="editData('Email', 'email', '<?php echo $id; ?>')">Edit</td>
                        </tr>
                        <tr class="border-b">
                            <td class="py-2 px-4 font-semibold">Alamat</td>
                            <td class="py-2 px-4" id="alamat"><?php echo htmlspecialchars($output['Alamat']); ?></td>
                            <td class="py-2 px-4 text-blue-500 hover:underline cursor-pointer" onclick="editData('Alamat', 'alamat', '<?php echo $id; ?>')">Edit</td>
                        </tr>
                        <tr class="border-b">
                            <td class="py-2 px-4 font-semibold">Password</td>
                            <td class="py-2 px-4">********</td>
                            <td class="py-2 px-4 text-blue-500 hover:underline cursor-pointer" onclick="editPassword('<?php echo $id; ?>')">Edit</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Tombol Kembali -->
        <div class="mt-6 text-center">
            <a href="../" class="text-white bg-blue-500 hover:bg-blue-600 px-4 py-2 rounded-md">Kembali</a>
        </div>
    </div>

    <script>
        function editData(fieldName, elementId, userId) {
            const currentValue = document.getElementById(elementId).innerText;

            Swal.fire({
                title: `Edit ${fieldName}`,
                input: 'text',
                inputLabel: `Masukkan ${fieldName} baru`,
                inputValue: currentValue,
                showCancelButton: true,
                confirmButtonText: 'Simpan',
                cancelButtonText: 'Batal',
                inputValidator: (value) => {
                    if (!value) {
                        return 'Data tidak boleh kosong!';
                    }

                    // Validasi khusus untuk email
                    if (fieldName === 'Email') {
                        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                        if (!emailPattern.test(value)) {
                            return 'Format email tidak valid!';
                        }
                    }
                },
                didOpen: () => {
                    const inputElement = Swal.getInput();
                    if (inputElement) {
                        inputElement.setAttribute('autocomplete', 'off');
                    }
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    const newValue = result.value;

                    // Kirim data ke server untuk update
                    fetch('process.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            userId: userId,
                            field: elementId,
                            value: newValue,
                        }),
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            document.getElementById(elementId).innerText = newValue;
                            Swal.fire('Berhasil!', `${fieldName} berhasil diperbarui.`, 'success');
                        } else {
                            Swal.fire('Gagal!', data.message || 'Terjadi kesalahan saat memperbarui data.', 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire('Gagal!', 'Terjadi kesalahan saat memperbarui data.', 'error');
                    });
                }
            });
        }

        function editPassword(userId) {
            Swal.fire({
                title: 'Edit Password',
                html: `
                    <input type="password" id="new-password" class="swal2-input" placeholder="Masukkan password baru" autocomplete="off">
                    <input type="password" id="confirm-password" class="swal2-input" placeholder="Konfirmasi password" autocomplete="off">
                `,
                showCancelButton: true,
                confirmButtonText: 'Simpan',
                cancelButtonText: 'Batal',
                preConfirm: () => {
                    const newPassword = document.getElementById('new-password').value;
                    const confirmPassword = document.getElementById('confirm-password').value;

                    if (!newPassword || !confirmPassword) {
                        Swal.showValidationMessage('Password tidak boleh kosong!');
                        return false;
                    }

                    if (newPassword !== confirmPassword) {
                        Swal.showValidationMessage('Password dan konfirmasi tidak cocok!');
                        return false;
                    }

                    return { newPassword: newPassword };
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    const newPassword = result.value.newPassword;

                    // Kirim data ke server untuk update password
                    fetch('process_password.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            userId: userId,
                            newPassword: newPassword,
                        }),
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire('Berhasil!', 'Password berhasil diperbarui.', 'success');
                        } else {
                            Swal.fire('Gagal!', data.message || 'Terjadi kesalahan saat memperbarui password.', 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire('Gagal!', 'Terjadi kesalahan saat memperbarui password.', 'error');
                    });
                }
            });
        }
    </script>
</body>
</html>
