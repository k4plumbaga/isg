<?php
require '../../vendor/autoload.php';
require '../../../connect.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Reader\IReader;
use PhpOffice\PhpSpreadsheet\Writer\IWriter;
if (isset($_POST['addReport_sub'])) {
  $report_name = $_POST['new_report'];
  $company_name = $_POST['company_name'];
  $sorgu = $pdo->prepare("SELECT * FROM `coop_companies` WHERE `name` = '$company_name'");
  $sorgu->execute();
  $companies=$sorgu->fetchAll(PDO::FETCH_OBJ);
  foreach ($companies as $company) {
    $danger_type = $company->danger_type;
    $uzman_id = $company->uzman_id;
    $hekim_id = $company->hekim_id;
  }
  $sorgu2 = $pdo->prepare("SELECT * FROM `users` WHERE `id` = '$uzman_id'");
  $sorgu2->execute();
  $uzmanlar=$sorgu2->fetchAll(PDO::FETCH_OBJ);
  foreach ($uzmanlar as $uzman) {
    $u_first = $uzman->firstname;
    $u_last = $uzman->lastname;
    $u_username = $uzman->username;
  }
  $sorgu3 = $pdo->prepare("SELECT * FROM `users` WHERE `id` = '$hekim_id'");
  $sorgu3->execute();
  $hekimler=$sorgu3->fetchAll(PDO::FETCH_OBJ);
  foreach ($hekimler as $hekim) {
    $h_first = $hekim->firstname;
    $h_last = $hekim->lastname;
    $h_username = $hekim->username;
  }
  $sorgu4 = $pdo->prepare("SELECT * FROM `osgb_workers` WHERE `mail` = '$u_username'");
  $sorgu4->execute();
  $uzmanlar=$sorgu4->fetchAll(PDO::FETCH_OBJ);
  foreach ($uzmanlar as $uzman) {
    $u_tc = $uzman->tc;
  }
  $sorgu5 = $pdo->prepare("SELECT * FROM `osgb_workers` WHERE `mail` = '$h_username'");
  $sorgu5->execute();
  $hekimler=$sorgu5->fetchAll(PDO::FETCH_OBJ);
  foreach ($hekimler as $hekim) {
    $h_tc = $hekim->tc;
  }

  if ($report_name == 'egitim_plan') {
    $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load('../custom_reports/2-YILLIK EĞİTİM PLANI/YILLIK EĞİTİM PLANI.xls');
    $future = new DateTime(date('d-m-Y'));
    if ($danger_type == 3) {
      $future->modify('+3 year');
      $saat = 16;
    }
    elseif ($danger_type == 2) {
      $future->modify('+2 year');
      $saat = 12;
    }
    elseif ($danger_type == 1) {
      $future->modify('+1 year');
      $saat = 8;
    }
    $today = date('d-m-Y');
    $future = $future->format('d-m-Y');
    $worksheet = $spreadsheet->getActiveSheet();
    $worksheet->getCell('E2')->setValue($company_name);
    $worksheet->getCell('O2')->setValue("Hazırlanma Tarihi: $today \n Geçerlilik Tarihi: $future");
    $worksheet->getCell('O4')->setValue("SGK Sicil No\n$company->sgk_sicil");
    $worksheet->getCell('N7')->setValue("$saat saat");
    $worksheet->getCell('B33')->setValue(" $u_first $u_last \n $u_tc");
    $worksheet->getCell('H33')->setValue(" $h_first $h_last \n $h_tc");
    $worksheet->getCell('L33')->setValue(" $company->is_veren");

    $file_name = 'Yıllık Eğitim Planı_'.date('d-m-Y_h:i:sa').'.xls';
    $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xls');
    $writer->save("../$company_name/isletme_raporlari/$file_name");
    header("Location: ../$company_name/index.php?tab=isletme_rapor");
  }

  elseif ($report_name == '') {
    // code...
  }
}

?>