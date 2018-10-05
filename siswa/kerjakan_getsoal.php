<?php
include '../config/koneksi.php';
include '../config/datetime.php';
include '../config/url.php';

/*$sql = "SELECT * FROM ujian_kerjakan WHERE kerjakan_userid = '{$_SESSION['siswa_id']}'";
$cek = mysqli_query($conn, $sql);*/

session_start();
error_reporting(0);
$get_data = mysqli_query($conn, "SELECT * FROM ujian_kerjakan WHERE kerjakan_userid = '$_SESSION[siswa_id]'
			AND YEAR(kerjakan_createdate) = '$y' AND MONTH(kerjakan_createdate) = '$m' AND DAY(kerjakan_createdate) = '$d'
			AND kerjakan_status = 'mulai' AND kerjakan_info = 'mengerjakan'
			AND kerjakan_soalaktifid = '$_GET[sai]' AND kerjakan_materisoalid = '$_GET[msi]'");

	if ($get_data->num_rows >0) {
		$row=mysqli_fetch_assoc($get_data);
		$kerjakan = json_decode($row['kerjakan_data']);

		$index = $_GET['nomor'];
		$getsoal = $kerjakan->soal[$index];

?>

<h4><span class="badge btn-twitter"><?php echo $no.'</span> <span class="pilihan-ep">'.$getsoal->soal; ?></span></h4>
<div class='row'>
	<div class='col-md-12'>
		<span class="badge bg-teal">A</span> <input <?php if(is_pilih($getsoal->soal_id.'a',$getsoal->soal_aktif_id,$getsoal->materi_soal_id, $conn) == '1') echo 'checked'; ?> type='radio' value='a' name='pilih[<?php echo $getsoal->soal_id; ?>]' onclick="update_ganda(<?php echo $getsoal->soal_id ?>,'a',<?php echo $getsoal->soal_aktif_id ?>,<?php echo $getsoal->materi_soal_id ?>)"><span class="pilihan-ep"><?php echo $getsoal->a ?></span><br>
		<span class="badge bg-teal">B</span> <input <?php if(is_pilih($getsoal->soal_id.'b',$getsoal->soal_aktif_id,$getsoal->materi_soal_id, $conn) == '1') echo 'checked'; ?> type='radio' value='b' name='pilih[<?php echo $getsoal->soal_id; ?>]' onclick="update_ganda(<?php echo $getsoal->soal_id ?>,'b',<?php echo $getsoal->soal_aktif_id ?>,<?php echo $getsoal->materi_soal_id ?>)"><span class="pilihan-ep"><?php echo $getsoal->b ?></span><br>
		<span class="badge bg-teal">C</span> <input <?php if(is_pilih($getsoal->soal_id.'c',$getsoal->soal_aktif_id,$getsoal->materi_soal_id, $conn) == '1') echo 'checked'; ?> type='radio' value='c' name='pilih[<?php echo $getsoal->soal_id; ?>]' onclick="update_ganda(<?php echo $getsoal->soal_id ?>,'c',<?php echo $getsoal->soal_aktif_id ?>,<?php echo $getsoal->materi_soal_id ?>)"><span class="pilihan-ep"><?php echo $getsoal->c ?></span><br>
		<span class="badge bg-teal">D</span> <input <?php if(is_pilih($getsoal->soal_id.'d',$getsoal->soal_aktif_id,$getsoal->materi_soal_id, $conn) == '1') echo 'checked'; ?> type='radio' value='d' name='pilih[<?php echo $getsoal->soal_id; ?>]' onclick="update_ganda(<?php echo $getsoal->soal_id ?>,'d',<?php echo $getsoal->soal_aktif_id ?>,<?php echo $getsoal->materi_soal_id ?>)"><span class="pilihan-ep"><?php echo $getsoal->d ?></span><br>
		<span class="badge bg-teal">E</span> <input <?php if(is_pilih($getsoal->soal_id.'e',$getsoal->soal_aktif_id,$getsoal->materi_soal_id, $conn) == '1') echo 'checked'; ?> type='radio' value='e' name='pilih[<?php echo $getsoal->soal_id; ?>]' onclick="update_ganda(<?php echo $getsoal->soal_id ?>,'e',<?php echo $getsoal->soal_aktif_id ?>,<?php echo $getsoal->materi_soal_id ?>)"><span class="pilihan-ep"><?php echo $getsoal->e ?></span><br><br>
	</div>
</div>
<?php } else {
	echo "<div class='callout callout-warning'>
        <h4>Perhatian!</h4>
        <p>Anda telah mengerjakan soal ini ! Klik di <span class='badge btn-twitter'> <a href='pages.php?q=pilih-soal'>sini</a> </span> untuk kembali.</p>
        </div>";
} ?>
<script type="text/javascript">
    function update_ganda(soal_id,soal_pilihan_id,sai,msi) {
        $.ajax({
            type : "POST",
            url  : "<?php echo $base_url; ?>/siswa/pages/kerjakansoal/update.php",
            data : "soal_id=" + soal_id + "&soal_pilihan_id=" + soal_pilihan_id + "&sai=" + sai  + "&msi=" + msi
        });

    }
</script>