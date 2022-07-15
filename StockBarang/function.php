<?php

session_start();

//Membuat koneksi ke database
$conn = mysqli_connect("localhost","root","","stockbarang");

//menambah barang baru
if(isset($_POST['addnewbarang'])){
    $namabarang = $_POST['namabarang'];
    $deskripsi = $_POST['deskripsi'];
    $stock = $_POST['stock'];

    $addtotable = mysqli_query($conn,"insert into stock (namabarang, deskripsi, stock) values('$namabarang','$deskripsi','$stock')");
    if($addtotable){
        //send to telegram
        $datachatid = mysqli_query($conn, "select * from login");
        while($ambilid = mysqli_fetch_array($datachatid)){

            $message = "<b>Barang Baru!%0A$namabarang</b> telah berhasil ditambah ke dalam <b>Stock Barang</b>!";
            $chatID = $ambilid['idchat'];
            $token = file_get_contents("private/TOKEN.txt");
            $result = "https://api.telegram.org/bot$token/sendmessage?chat_id=$chatID&text=$message&parse_mode=HTML";
            file_get_contents($result, true);     
        }

        header('location:index.php');
    } else {
        echo 'Gagal';
        header('location:index.php');
    }
}

//menambah barang masuk
if(isset($_POST['barangmasuk'])){
    $barangnya = $_POST['barangnya'];
    $penerima = $_POST['penerima'];
    $qty = $_POST['qty'];

    $cekstocksekarang = mysqli_query($conn,"select * from stock where idbarang='$barangnya'");
    $ambildatanya = mysqli_fetch_array($cekstocksekarang);
    $namabarang = $ambildatanya['namabarang'];

    $stocksekarang = $ambildatanya['stock'];
    $tambahkanstocksekarangdenganquantity = $stocksekarang+$qty;

    $addtomasuk = mysqli_query($conn,"insert into masuk (idbarang, keterangan, qty) values('$barangnya','$penerima','$qty' )");
    $updatestockmasuk = mysqli_query($conn,"update stock set stock='$tambahkanstocksekarangdenganquantity' where idbarang='$barangnya'");
    if($addtomasuk&&$updatestockmasuk){
        //send to telegram
        $datachatid = mysqli_query($conn, "select * from login");
        while($ambilid = mysqli_fetch_array($datachatid)){

            $message = "<b>Barang Masuk!</b> %0AAda <b>$qty</b> stock <b>$namabarang</b> telah dikirm oleh $penerima!";
            $chatID = $ambilid['idchat'];
            $token = file_get_contents("private/TOKEN.txt");
            $result = "https://api.telegram.org/bot$token/sendmessage?chat_id=$chatID&text=$message&parse_mode=HTML";
            file_get_contents($result, true);     
        }

        header('location:masuk.php');
    } else {
        echo 'Gagal';
        header('location:masuk.php');
    }
}


//menambah barang keluar
if(isset($_POST['addbarangkeluar'])){
    $barangnya = $_POST['barangnya'];
    $penerima = $_POST['penerima'];
    $qty = $_POST['qty'];

    $cekstocksekarang = mysqli_query($conn,"select * from stock where idbarang='$barangnya'");
    $ambildatanya = mysqli_fetch_array($cekstocksekarang);
    $namabarang = $ambildatanya['namabarang'];

    $stocksekarang = $ambildatanya['stock'];
    $tambahkanstocksekarangdenganquantity = $stocksekarang-$qty;

    $addtokeluar = mysqli_query($conn,"insert into keluar (idbarang, penerima, qty) values('$barangnya','$penerima','$qty' )");
    $updatestockkeluar = mysqli_query($conn,"update stock set stock='$tambahkanstocksekarangdenganquantity' where idbarang='$barangnya'");
    if($addtokeluar&&$updatestockkeluar){
        //send to telegram
        $datachatid = mysqli_query($conn, "select * from login");
        while($ambilid = mysqli_fetch_array($datachatid)){

            $message = "<b>Barang Keluar!</b> %0AAda <b>$qty</b> stock <b>$namabarang</b> telah dikirm ke $penerima!";
            $chatID = $ambilid['idchat'];
            $token = file_get_contents("private/TOKEN.txt");
            $result = "https://api.telegram.org/bot$token/sendmessage?chat_id=$chatID&text=$message&parse_mode=HTML";
            file_get_contents($result, true);     
        }
        
        header('location:keluar.php');
    } else {
        echo 'Gagal';
        header('location:keluar.php');
    }
}


//Update Info Barang
if(isset($_POST['updatebarang'])){
    $idb = $_POST['idb'];
    $namabarang = $_POST['namabarang'];
    $deskripsi = $_POST['deskripsi'];

    $lihatstock = mysqli_query($conn, "select * from stock where idbarang='$idb'");
    $stocknya = mysqli_fetch_array($lihatstock);
    $namabarangskrg = $stocknya['namabarang'];
    $deskripsiskrg = $stocknya['deskripsi'];

    $update = mysqli_query($conn,"update stock set namabarang='$namabarang', deskripsi='$deskripsi' where idbarang ='$idb'");
    if($update){
        //send to telegram
        $datachatid = mysqli_query($conn, "select * from login");
        while($ambilid = mysqli_fetch_array($datachatid)){

            $message = "<b>Revisi Stock Barang!</b> Ada Perubahan pada <b>$namabarangskrg</b>!%0A - $namabarangskrg -> $namabarang%0A - $deskripsiskrg -> $deskripsi";
            $chatID = $ambilid['idchat'];
            $token = file_get_contents("private/TOKEN.txt");
            $result = "https://api.telegram.org/bot$token/sendmessage?chat_id=$chatID&text=$message&parse_mode=HTML";
            file_get_contents($result, true);     
        }
        
        header('location:index.php');
    } else {
        echo 'Gagal';
        header('location:index.php');
    }
}


//Menghapus Barang dari Stock
if(isset($_POST['hapusbarang'])){
    $idb = $_POST['idb'];
    $namabarang = $_POST['namabarang'];

    $hapus = mysqli_query($conn, "delete from stock where idbarang='$idb'");
    if($hapus){
        //send to telegram
        $datachatid = mysqli_query($conn, "select * from login");
        while($ambilid = mysqli_fetch_array($datachatid)){

            $message = "<b>Barang Dihapus! %0A$namabarang</b> telah berhasil dihapus dari <b>Stock Barang</b>!";
            $chatID = $ambilid['idchat'];
            $token = file_get_contents("private/TOKEN.txt");
            $result = "https://api.telegram.org/bot$token/sendmessage?chat_id=$chatID&text=$message&parse_mode=HTML";
            file_get_contents($result, true);     
        }
        
        header('location:index.php');
    } else {
        echo 'Gagal';
        header('location:index.php');
    }
}


//mengubah data barang masuk
if(isset($_POST['updatebarangmasuk'])){
    $idb = $_POST['idb'];
    $idm = $_POST['idm'];
    $keterangan = $_POST['keterangan'];
    $qty = $_POST['qty'];

    $lihatstock = mysqli_query($conn, "select * from stock where idbarang='$idb'");
    $stocknya = mysqli_fetch_array($lihatstock);
    $stockskrg = $stocknya['stock'];
    $namabarang = $stocknya['namabarang'];

    $lihatqty = mysqli_query($conn, "select * from masuk where idmasuk='$idm'");
    $qtynya = mysqli_fetch_array($lihatqty);
    $qtyskrg = $qtynya['qty'];
    $keteranganskrg = $qtynya['keterangan'];

    //send to telegram
    $datachatid = mysqli_query($conn, "select * from login");
    while($ambilid = mysqli_fetch_array($datachatid)){

        $message = "<b>Revisi Barang Masuk!</b> Ada perubahan pada <b>$namabarang</b>!%0A - $qtyskrg -> $qty%0A - $keteranganskrg -> $keterangan";
        $chatID = $ambilid['idchat'];
        $token = file_get_contents("private/TOKEN.txt");
        $result = "https://api.telegram.org/bot$token/sendmessage?chat_id=$chatID&text=$message&parse_mode=HTML";
        file_get_contents($result, true);     
    }
    

    if($qty>$qtyskrg){
        $selisih = $qty - $qtyskrg;
        $tambahin = $stockskrg + $selisih;
        $tambahinstocknya = mysqli_query($conn, "update stock set stock='$tambahin' where idbarang='$idb'");
        $updatenya = mysqli_query($conn, "update masuk set qty='$qty', keterangan='$keterangan' where idmasuk='$idm'");
        if($tambahinstocknya&&$updatenya){
            header('location:masuk.php');
        } else {
            echo 'Gagal';
            header('location:masuk.php');
        }
    } else {
        $selisih = $qtyskrg - $qty;
        $kurangin = $stockskrg - $selisih;
        $kuranginstocknya = mysqli_query($conn, "update stock set stock='$kurangin' where idbarang='$idb'");
        $updatenya = mysqli_query($conn, "update masuk set qty='$qty', keterangan='$keterangan' where idmasuk='$idm'");
        if($kuranginstocknya&&$updatenya){
            header('location:masuk.php');
        } else {
            echo 'Gagal';
            header('location:masuk.php');
        }
    }
}


//menghapus barang masuk
if(isset($_POST['hapusbarangmasuk'])){
    $idb = $_POST['idb'];
    $qty = $_POST['kty'];
    $idm = $_POST['idm'];
    $namabarang = $_POST['namabarang'];

    $getdatastock = mysqli_query($conn, "select * from stock where idbarang='$idb'");
    $data = mysqli_fetch_array($getdatastock);
    $stok = $data['stock'];

    $selisih = $stok - $qty;

    $update = mysqli_query($conn, "update stock set stock='$selisih' where idbarang='$idb'");
    $hapusdata = mysqli_query($conn, "delete from masuk where idmasuk='$idm'");

    if($update&&$hapusdata){
        //send to telegram
        $datachatid = mysqli_query($conn, "select * from login");
        while($ambilid = mysqli_fetch_array($datachatid)){

            $message = "<b>Barang Dihapus! %0A$qty</b> stock <b>$namabarang</b> telah berhasil dihapus dari <b>Barang Masuk</b>!";
            $chatID = $ambilid['idchat'];
            $token = file_get_contents("private/TOKEN.txt");
            $result = "https://api.telegram.org/bot$token/sendmessage?chat_id=$chatID&text=$message&parse_mode=HTML";
            file_get_contents($result, true);     
        }
        
        header('location:masuk.php');
    } else {
        echo 'Gagal';
        header('location:masuk.php');
    }
}


//Mengubah data barang keluar
if(isset($_POST['updatebarangkeluar'])){
    $idb = $_POST['idb'];
    $idk = $_POST['idk'];
    $penerima = $_POST['penerima'];
    $qty = $_POST['qty'];

    $lihatstock = mysqli_query($conn, "select * from stock where idbarang='$idb'");
    $stocknya = mysqli_fetch_array($lihatstock);
    $stockskrg = $stocknya['stock'];
    $namabarang = $stocknya['namabarang'];

    $lihatqty = mysqli_query($conn, "select * from keluar where idkeluar='$idk'");
    $qtynya = mysqli_fetch_array($lihatqty);
    $qtyskrg = $qtynya['qty'];
    $penerimaskrg = $qtynya['penerima'];

    //send to telegram
    $datachatid = mysqli_query($conn, "select * from login");
    while($ambilid = mysqli_fetch_array($datachatid)){

        $message = "<b>Revisi Barang Keluar!</b> Ada perubahan pada <b>$namabarang</b>!%0A - $qtyskrg -> $qty%0A - $penerimaskrg -> $penerima";
        $chatID = $ambilid['idchat'];
        $token = file_get_contents("private/TOKEN.txt");
        $result = "https://api.telegram.org/bot$token/sendmessage?chat_id=$chatID&text=$message&parse_mode=HTML";
        file_get_contents($result, true);     
    }

    if($qty>$qtyskrg){
        $selisih = $qty - $qtyskrg;
        $kurangin = $stockskrg - $selisih;
        $kuranginstocknya = mysqli_query($conn, "update stock set stock='$kurangin' where idbarang='$idb'");
        $updatenya = mysqli_query($conn, "update keluar set qty='$qty', penerima='$penerima' where idkeluar='$idk'");
        if($kuranginstocknya&&$updatenya){
            header('location:keluar.php');
        } else {
            echo 'Gagal';
            header('location:keluar.php');
        }
    } else {
        $selisih = $qtyskrg - $qty;
        $tambahin = $stockskrg + $selisih;
        $tambahinstocknya = mysqli_query($conn, "update stock set stock='$tambahin' where idbarang='$idb'");
        $updatenya = mysqli_query($conn, "update keluar set qty='$qty', penerima='$penerima' where idkeluar='$idk'");
        if($tambahinstocknya&&$updatenya){
            header('location:keluar.php');
        } else {
            echo 'Gagal';
            header('location:keluar.php');
        }
    }
}


//menghapus barang keluar
if(isset($_POST['hapusbarangkeluar'])){
    $idb = $_POST['idb'];
    $qty = $_POST['kty'];
    $idk = $_POST['idk'];
    $namabarang = $_POST['namabarang'];

    $getdatastock = mysqli_query($conn, "select * from stock where idbarang='$idb'");
    $data = mysqli_fetch_array($getdatastock);
    $stok = $data['stock'];

    $selisih = $stok + $qty;

    $update = mysqli_query($conn, "update stock set stock='$selisih' where idbarang='$idb'");
    $hapusdata = mysqli_query($conn, "delete from keluar where idkeluar='$idk'");

    if($update&&$hapusdata){
        //send to telegram
        $datachatid = mysqli_query($conn, "select * from login");
        while($ambilid = mysqli_fetch_array($datachatid)){

            $message = "<b>Barang Dihapus! %0A$qty</b> stock <b>$namabarang</b> telah berhasil dihapus dari <b>Barang Keluar</b>!";
            $chatID = $ambilid['idchat'];
            $token = file_get_contents("private/TOKEN.txt");
            $result = "https://api.telegram.org/bot$token/sendmessage?chat_id=$chatID&text=$message&parse_mode=HTML";
            file_get_contents($result, true);     
        }
        
        header('location:keluar.php');
    } else {
        echo 'Gagal';
        header('location:keluar.php');
    }
}

//menambah akun baru
if(isset($_POST['addnewakun'])){
    $email = $_POST['email'];
    $password = $_POST['password'];
    $idchat = $_POST['idchat'];

    $addtotable = mysqli_query($conn,"insert into login (email, password, idchat) values('$email','$password','$idchat')");
    if($addtotable){
        //send to telegram
        $datachatid = mysqli_query($conn, "select * from login");
        while($ambilid = mysqli_fetch_array($datachatid)){

            $message = "<b>Akun baru ditambahkan! %0A$email</b> telah berhasil ditambah ke dalam <b>Pusat Akun</b>!";
            $chatID = $ambilid['idchat'];
            $token = file_get_contents("private/TOKEN.txt");
            $result = "https://api.telegram.org/bot$token/sendmessage?chat_id=$chatID&text=$message&parse_mode=HTML";
            file_get_contents($result, true);     
        }

        header('location:akun.php');
    } else {
        echo 'Gagal';
        header('location:akun.php');
    }
}

//Update Info akun
if(isset($_POST['updateakun'])){
    $iduser = $_POST['iduser'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $idchat = $_POST['idchat'];

    $lihatstock = mysqli_query($conn, "select * from login where iduser='$iduser'");
    $stocknya = mysqli_fetch_array($lihatstock);
    $emailskrg = $stocknya['email'];
    $passwordskrg = $stocknya['password'];
    $idchatskrg = $stocknya['idchat'];

    $update = mysqli_query($conn,"update login set email='$email', password='$password', idchat='$idchat' where iduser ='$iduser'");
    if($update){
        //send to telegram
        $datachatid = mysqli_query($conn, "select * from login");
        while($ambilid = mysqli_fetch_array($datachatid)){

            $message = "<b>Revisi Pusat Akun!</b> Ada Perubahan pada <b>$emailskrg</b>!%0A - $emailskrg -> $email%0A - $passwordskrg -> $password%0A - $idchatskrg -> $idchat";
            $chatID = $ambilid['idchat'];
            $token = file_get_contents("private/TOKEN.txt");
            $result = "https://api.telegram.org/bot$token/sendmessage?chat_id=$chatID&text=$message&parse_mode=HTML";
            file_get_contents($result, true);     
        }
        
        header('location:akun.php');
    } else {
        echo 'Gagal';
        header('location:akun.php');
    }
}

//Menghapus akun dari pusat akun
if(isset($_POST['hapusakun'])){
    $iduser = $_POST['iduser'];
    $email = $_POST['email'];

    $hapus = mysqli_query($conn, "delete from login where iduser='$iduser'");
    if($hapus){
        //send to telegram
        $datachatid = mysqli_query($conn, "select * from login");
        while($ambilid = mysqli_fetch_array($datachatid)){

            $message = "<b>Akun Dihapus! %0A$email</b> telah berhasil dihapus dari <b>Pusat Akun</b>!";
            $chatID = $ambilid['idchat'];
            $token = file_get_contents("private/TOKEN.txt");
            $result = "https://api.telegram.org/bot$token/sendmessage?chat_id=$chatID&text=$message&parse_mode=HTML";
            file_get_contents($result, true);     
        }
        
        header('location:akun.php');
    } else {
        echo 'Gagal';
        header('location:akun.php');
    }
}

?>