<?php 

class Flasher {
    public static function setFlash($pesan, $aksi, $tipe)
    {
        $_SESSION['flash'] = [
            'pesan' => $pesan,
            'aksi'  => $aksi,
            'tipe'  => $tipe
        ];
    }

    public static function flash()
    {
        if( isset($_SESSION['flash']) ) {
            $tipe = $_SESSION['flash']['tipe'];
            $colorClass = 'bg-blue-100 text-blue-800'; // default
            
            if ($tipe == 'success') {
                $colorClass = 'bg-green-100 text-green-800';
            } else if ($tipe == 'danger') {
                $colorClass = 'bg-red-100 text-red-800';
            } else if ($tipe == 'warning') {
                $colorClass = 'bg-yellow-100 text-yellow-800';
            }

            echo '<div class="p-4 text-sm rounded-lg ' . $colorClass . '" role="alert">
                    Data <strong>' . $_SESSION['flash']['pesan'] . '</strong> ' . $_SESSION['flash']['aksi'] . '
                  </div>';
            unset($_SESSION['flash']);
        }
    }
}
