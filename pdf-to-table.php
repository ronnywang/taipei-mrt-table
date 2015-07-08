<?php

// php pdf-to-table.php > time-table.csv
// 把 pdf 裡面的時刻表取出來轉成 CSV
// 需要 xpdf 的 pdftotext
class PDFtoTable
{

    public function main()
    {
        $fp = fopen("pdf.csv", "r");
        $output = fopen('php://output', 'w');
        $columns = fgetcsv($fp);
        fputcsv($output, array("車站代碼","車站名稱","方向代碼","方向名稱","中文班次別","英文班次別","時間"));
        while ($rows = fgetcsv($fp)) {
            $content = `pdftotext -layout pdfs/{$rows[2]}.PDF /dev/stdout`;
            $infos = self::getInfoFromTXT($content);
            foreach ($infos as $info) {
                fputcsv($output, array(
                    $rows[0],
                    $rows[1],
                    $rows[2],
                    $rows[3],
                    $info[0],
                    $info[1],
                    $info[2],
                ));
            }
        }
    }

    public function getInfoFromTXT($content)
    {
        $lines = explode("\n", $content);
        $line_no = 0;

        // 先找到哪一行是 Hour 開頭，並用 Hour 找到每一格的寬度
        for (; $line_no < count($lines); $line_no ++) {
            $line = $lines[$line_no];
            if (strpos($line, 'Hour') === 0) {
                break;
            }
        }
        // 找到每一格的寬度，用 Hour 這個字當作分隔
        $positions = array();
        $tables = array();
        $table_name = array();
        $table_ename = array();
        $offset = 0;
        while (false !== strpos($line, 'Hour', $offset)) {
            $positions[] = strpos($line, 'Hour', $offset);
            $tables[] = array();
            $offset = 4 + strpos($line, 'Hour', $offset);
        }

        $name_line = $lines[$line_no - 3];
        $ename_line = $lines[$line_no - 2];
        // 把前幾行的中文描述和英文描述抓出來
        for ($i = 0; $i < count($positions); $i ++) {
            $start = $positions[$i] - 3;
            $length = ($i + 1 == count($positions)) ? 10000: $positions[$i + 1] - $start;
            $table_ename[] = trim(substr($ename_line, $start, $length));
        }
        $table_name = preg_split('#\s+#', trim($name_line));

        // 開始爬時刻表，時刻表只會是數字開頭或者是空白開頭接數字，非以上情況就表示時刻表結束
        $hour = null;
        $line_no ++;
        for (; $line_no < count($lines); $line_no ++) {
            $line = $lines[$line_no];
            if (preg_match('#^([0-9]+)#', $line, $matches)) {
                // 數字開頭，表示進入新的小時
                $hour = $matches[1];
                for ($i = 0; $i < count($positions); $i ++) {
                    $start = $positions[$i];
                    $length = ($i + 1 == count($positions)) ? strlen($line) - $start : $positions[$i + 1] - $start;
                    $parts = preg_split('#\s+#', trim(substr($line, $start, $length)));;
                    // 第一個數字一定要等於 $hour
                    if ($parts[0] != $hour) {
                        throw new Exception("第一個數字一定要等於 $hour");
                    }
                    array_shift($parts);
                    $tables[$i] = array_merge($tables[$i], array_map(function($p) use ($hour) { return "{$hour}:{$p}"; }, $parts));
                }
            } elseif (preg_match('#^\s+[0-9]+#', $line)) {
                // 空白開頭，表示是這個小時的下一行
                for ($i = 0; $i < count($positions); $i ++) {
                    $start = $positions[$i];
                    $length = ($i + 1 == count($positions)) ? strlen($line) - $start : $positions[$i + 1] - $start;
                    $parts = preg_split('#\s+#', trim(substr($line, $start, $length)));;
                    if ($parts[0] == '') {
                        continue;
                    }
                    $tables[$i] = array_merge($tables[$i], array_map(function($p) use ($hour) { return "{$hour}:{$p}"; }, $parts));
                }
            } else {
                break;
            }
        }

        $ret = array();
        foreach ($tables as $i => $times) {
            $ret[] = array(
                $table_name[$i],
                $table_ename[$i],
                implode(' ', $times),
            );
        }
        return $ret;
    }
}

$p = new PDFtoTable;
$p->main();
