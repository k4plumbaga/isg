<?php
require '../../../connect.php';
date_default_timezone_set('Europe/Istanbul');
if (isset($_FILES['ziyaret_dosyası']) && isset($_POST['ziyaret_dosyası_yukle'])) {
  $company_name = $_POST['company_name'];
  $dir =  dirname(__DIR__);
  $rapor_adi = $_POST['visit_report_name'];
  $rapor_tarihi = date('d-m-Y', strtotime($_POST["visit_report_date"]));

  $file= $_FILES['ziyaret_dosyası'];
  $fileName = $_FILES['ziyaret_dosyası']['name'];
  $fileTmp = $_FILES['ziyaret_dosyası']['tmp_name'];
  $fileSize = $_FILES['ziyaret_dosyası']['size'];
  $filesError = $_FILES['ziyaret_dosyası']['error'];
  $fileType = $_FILES['ziyaret_dosyası']['type'];

  $fileExt = explode('.',$_FILES['ziyaret_dosyası']['name']);
  $fileActualExt = strtolower(end($fileExt));
  $allowed = array('xlsx','xls','odt','odf','ods','pdf','docx','doc','txt','jpg','jpeg','png','ppt','pptx');
  if(in_array($fileActualExt,$allowed)){
      if($_FILES['ziyaret_dosyası']['error'] ===  0){
        $fileNameNew = $rapor_adi."_".$rapor_tarihi."_".date('G:i:s')."_";
        $fileDestination = $dir.'/'.$company_name.'/ziyaret_raporlari/'.$fileNameNew.".".$fileActualExt;

        move_uploaded_file($_FILES['ziyaret_dosyası']['tmp_name'],$fileDestination);
        header("Location: ../$company_name/index.php?tab=ziyaret_rapor");
      }else{
        ?>
        <div class='alert alert-danger alert-dismissible fade show' style='margin-bottom: 0 !important;' role='alert'>
          <strong>Dosya yüklenirken bir hata ile karşılaşıldı!Lütfen daha sonra tekrar deneiyiniz! Sorun devam ederse sistem yöneticinizle görüşün.</strong>
          <button type='button' class='close' data-dismiss='alert' aria-label='Close' padding='auto'>
            <span aria-hidden='true'>&times;</span>
          </button>
        </div>
        <?php
      }
  }else{
    ?>
    <div class='alert alert-danger alert-dismissible fade show' style='margin-bottom: 0 !important;' role='alert'>
      Dosya türü uygun değil. Lütfen <b>'xlsx','xls','odt','odf','ods','pdf','docx','doc','txt','jpg','jpeg','png','ppt','pptx'</b> türündeki dosyaları yükleyin!
      <button type='button' class='close' data-dismiss='alert' aria-label='Close' padding='auto'>
        <span aria-hidden='true'>&times;</span>
      </button>
    </div>
    <?php
  }
}
?>
