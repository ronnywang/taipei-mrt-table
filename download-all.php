<?php

// 將 pdf.csv 裡面的全部 pdf 下載到 pdfs/ 資料夾下
$fp = fopen("pdf.csv", 'r');
$columns = fgetcsv($fp);
mkdir("pdfs");
chdir("pdfs");
while ($rows = fgetcsv($fp)) {
    system("wget http://web.metro.taipei/img/ALL/timetables/{$rows[2]}.PDF");
}
