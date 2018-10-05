<?php

$server = "localhost";
$username = "root";
$password = "";
$database = "appujian";

// Koneksi dan memilih database di server
$conn = mysqli_connect($server,$username,$password, $database) or die("Koneksi gagal");


// buat helper
if ( !function_exists('get_file_extensions') )
{
	function get_file_extensions( $string = "" ) {
		$array 	= explode(".", $string);
		$ext 	= end($array);

		return $ext;
	}
}

if ( !function_exists('kelamin') )
{
	function kelamin($kelamin){
		if($kelamin ==  'L'){
			$kelamin = 'Laki-Laki';
		}else{
			$kelamin = 'Perempuan';
		}

		return $kelamin;
	}
}

if ( !function_exists('trace') )
{
	function trace($data, $die = true){
		echo "<pre>";
		print_r($data);
		if($die) {
			die();
		}
	}
}

if ( !function_exists('simpan') )
{
	function simpan($tabel, $kolom, $nilai, $conn){
		$hasil['status'] = false;
		$hasil['message'] = 'gagal disimpan, $conn';
		$data = mysqli_query($conn, "INSERT INTO $tabel $kolom values $nilai") OR die(mysqli_error());
		if($data){
			$hasil['status'] = true;
			$hasil['message'] = 'berhasil disimpan';
		}
		return $hasil;
	}
}

if ( !function_exists('edit') )
{
	function edit($tabel, $kolom, $where, $conn){
		$hasil['status'] = false;
		$hasil['message'] = 'gagal edit, $conn';
		$data = mysqli_query($conn, "UPDATE $tabel SET $kolom WHERE $where") OR die(mysqli_error());
		if($data){
			$hasil['status'] = true;
			$hasil['message'] = 'berhasil edit';
		}
		return $hasil;
	}
}

if ( !function_exists('is_pilih') )
{
	function is_pilih($pilihan,$sai,$msi, $conn) {

		$y = date("Y");
		$m = date("m");
		$d = date("d");

		$data = mysqli_query($conn, "SELECT * FROM ujian_kerjakan WHERE kerjakan_userid = '$_SESSION[siswa_id]'
			AND YEAR(kerjakan_createdate) = '$y' AND MONTH(kerjakan_createdate) = '$m' AND DAY(kerjakan_createdate) = '$d'
			AND kerjakan_status = 'mulai' AND kerjakan_soalaktifid = '$sai' AND kerjakan_materisoalid = '$msi'");
		$row=mysqli_fetch_assoc($data);
        $kerjakan = json_decode($row['kerjakan_data']);

        $id = [];
        foreach ($kerjakan->jawaban as $key => $value) {
        	$id['pilihan'][$key] = $key.$value;
        }

	    if (in_array($pilihan, $id['pilihan'])) {
		    return true;
	    }

	    return false;
	}
}

if ( !function_exists('app_count_tugas_aktif') )
{
	function app_count_tugas_aktif($id, $conn){
		$data = mysqli_query($conn, "SELECT *
				FROM
				tb_soal_aktif
				INNER JOIN tb_materi_soal ON tb_soal_aktif.materi_soal_id = tb_materi_soal.materi_soal_id
				INNER JOIN tb_pilih_mapel ON tb_materi_soal.pilih_mapel_id = tb_pilih_mapel.pilih_mapel_id
				INNER JOIN tb_mapel ON tb_pilih_mapel.mapel_id = tb_mapel.mapel_id
				INNER JOIN tb_guru ON tb_materi_soal.guru_id = tb_guru.guru_id
				INNER JOIN tb_siswa ON tb_siswa.kelas_sub_id = tb_soal_aktif.kelas_sub_id
				WHERE
				tb_soal_aktif.aktif = 'aktif' AND tb_siswa.siswa_id = $id");
		return mysqli_num_rows($data);
	}
}

if ( !function_exists('app_count_tugas_selesai') )
{
	function app_count_tugas_selesai($id, $conn){
		$data = mysqli_query($conn, "SELECT
				ujian_kerjakan.kerjakan_id,
				ujian_kerjakan.kerjakan_userid,
				ujian_kerjakan.kerjakan_soalaktifid
				FROM ujian_kerjakan
				WHERE ujian_kerjakan.kerjakan_status = 'selesai' AND ujian_kerjakan.kerjakan_userid = '$id' GROUP BY ujian_kerjakan.kerjakan_soalaktifid");
		return mysqli_num_rows($data);
	}
}

if ( !function_exists('app_count_nilai') )
{
	function app_count_nilai($id, $conn){
		$data = mysqli_query($conn, "SELECT *
				FROM
				tb_soal_aktif
				INNER JOIN tb_materi_soal ON tb_soal_aktif.materi_soal_id = tb_materi_soal.materi_soal_id
				INNER JOIN tb_kelas_sub ON tb_soal_aktif.kelas_sub_id = tb_kelas_sub.kelas_sub_id
				INNER JOIN tb_nilai_siswa ON tb_soal_aktif.materi_soal_id = tb_nilai_siswa.materi_soal_id
				INNER JOIN tb_pilih_mapel ON tb_materi_soal.pilih_mapel_id = tb_pilih_mapel.pilih_mapel_id
				INNER JOIN tb_mapel ON tb_pilih_mapel.mapel_id = tb_mapel.mapel_id
				INNER JOIN tb_siswa ON tb_nilai_siswa.siswa_id = tb_siswa.siswa_id AND tb_siswa.kelas_sub_id = tb_soal_aktif.kelas_sub_id
				WHERE tb_nilai_siswa.siswa_id = '$id'
				GROUP BY tb_nilai_siswa.materi_soal_id, tb_nilai_siswa.tgl");
		return mysqli_num_rows($data);
	}
}

if ( !function_exists('app_count_guru') )
{
	function app_count_guru($conn){
		$data = mysqli_query($conn, "SELECT *
				FROM
				tb_guru
				WHERE blokir = 'n'");
		return mysqli_num_rows($data);
	}
}

if ( !function_exists('app_count_siswa') )
{
	function app_count_siswa($conn){
		$data = mysqli_query($conn, "SELECT *
				FROM
				tb_siswa");
		return mysqli_num_rows($data);
	}
}

if ( !function_exists('app_count_tugas') )
{
	function app_count_tugas($id, $conn){
		$data = mysqli_query($conn, "SELECT
				tb_materi_soal.materi_soal_id,
				tb_materi_soal.guru_id
				FROM
				tb_materi_soal
				INNER JOIN tb_pilih_mapel ON tb_materi_soal.pilih_mapel_id = tb_pilih_mapel.pilih_mapel_id
				INNER JOIN tb_mapel ON tb_pilih_mapel.mapel_id = tb_mapel.mapel_id
				WHERE tb_materi_soal.guru_id= '$id'");
		return mysqli_num_rows($data);
	}
}

if ( !function_exists('app_count_soal') )
{
	function app_count_soal($id, $conn){
		$data = mysqli_query($conn, "SELECT
				tb_materi_soal.materi_soal_id,
				tb_materi_soal.guru_id,
				tb_materi_soal.materi
				FROM
				tb_materi_soal
				INNER JOIN tb_soal ON tb_soal.materi_soal_id = tb_materi_soal.materi_soal_id
				WHERE tb_materi_soal.guru_id= '$id' AND tb_soal.blokir = 'n'");
		return mysqli_num_rows($data);
	}
}

if ( !function_exists('app_count_soal') )
{
	function app_count_soal($id, $conn){
		$data = mysqli_query($conn, "SELECT
				tb_materi_soal.materi_soal_id,
				tb_materi_soal.guru_id,
				tb_materi_soal.materi
				FROM
				tb_materi_soal
				INNER JOIN tb_soal ON tb_soal.materi_soal_id = tb_materi_soal.materi_soal_id
				WHERE tb_materi_soal.guru_id= '$id' AND tb_soal.blokir = 'n'");
		return mysqli_num_rows($data);
	}
}

if ( !function_exists('app_count_mapel') )
{
	function app_count_mapel($conn){
		$data = mysqli_query($conn, "SELECT *
				FROM
				tb_mapel WHERE blokir = 'n'");
		return mysqli_num_rows($data);
	}
}

if ( !function_exists('app_count_kelas') )
{
	function app_count_kelas($conn){
		$data = mysqli_query($conn, "SELECT *
				FROM
				tb_kelas WHERE blokir = 'n'");
		return mysqli_num_rows($data);
	}
}


if ( !function_exists('app_setting') )
{
	function app_setting($set, $conn){
		$data = mysqli_query($conn, "SELECT *
				FROM
				tb_set WHERE `set` = '$set'");
		return mysqli_fetch_assoc($data);
	}
}

if( !function_exists('auto_nomor'))
{
	function auto_nomor($conn)
	{
		$no = date("ym");
		$cek = mysqli_query($conn, "SELECT * FROM tb_siswa where id is not null order by id desc limit 1");
		$is_exist = mysqli_num_rows($cek);
		$getdata = mysqli_fetch_assoc($cek);
		$is_null = $getdata['id'];
		$order_no = $getdata['id'];
		$nomor_urut = substr($order_no,4,2);
		if($is_exist) {
			$no = $order_no+1;
		}
		else
		{
			$no = $no.date('d');
		}

		return $no;
	}
}

if( !function_exists('get_versi'))
{
	function get_versi()
	{
		
		$versi = "2.0";

		return $versi;
	}
}


if( !function_exists('timeout'))
{
	function timeout($detik = 1000)
	{
		session_start();
        $_SESSION['timeout'] = time()+$detik;
        $_SESSION['detik']   = $detik;

		return time()+$detik;
	}
}


if( !function_exists('cek_timeout'))
{
	function cek_timeout($timeout)
	{
		$time = time();
		if ($timeout < $time) {
			return 0;
        } else {
			return 1;
        }
	}
}


if( !function_exists('logout'))
{
	function logout($base_url = null)
	{
		session_start();
		session_destroy();
		echo "<script>window.location.replace('$base_url/siswa/logout.php')</script>";
	}
}

?>