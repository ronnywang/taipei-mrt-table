<?php

// php get-pdf-list.php > pdf.csv
// 產生各車站方向以及對應的 pdf

$stations = array(
'018' => '木柵',
'017' => '萬芳社區',
'016' => '萬芳醫院',
'015' => '辛亥',
'014' => '麟光',
'013' => '六張犁',
'012' => '科技大樓',
'011' => '大安',
'010' => '忠孝復興',
'009' => '南京復興',
'008' => '中山國中',
'007' => '松山機場',
'021' => '大直',
'022' => '劍南路',
'023' => '西湖',
'024' => '港墘',
'025' => '文德',
'026' => '內湖',
'027' => '大湖公園',
'028' => '葫洲',
'029' => '東湖',
'030' => '南港軟體園區',
'031' => '南港展覽館',
'070' => '紅樹林',
'069' => '竹圍',
'068' => '關渡',
'067' => '忠義',
'066' => '復興崗',
'065' => '新北投',
'064' => '北投',
'063' => '奇岩',
'062' => '唭哩岸',
'061' => '石牌',
'060' => '明德',
'059' => '芝山',
'058' => '士林',
'057' => '劍潭',
'056' => '圓山',
'055' => '民權西路',
'054' => '雙連',
'053' => '中山',
'051' => '台北車站',
'050' => '台大醫院',
'042' => '中正紀念堂',
'134' => '東門',
'103' => '大安森林公園',
'011' => '大安',
'101' => '信義安和',
'100' => '台北101/世貿',
'099' => '象山',
'110' => '南京三民',
'109' => '台北小巨蛋',
'009' => '南京復興',
'132' => '松江南京',
'053' => '中山',
'105' => '北門',
'086' => '西門',
'043' => '小南門',
'042' => '中正紀念堂',
'041' => '古亭',
'040' => '台電大樓',
'039' => '公館',
'038' => '萬隆',
'037' => '景美',
'036' => '大坪林',
'035' => '七張',
'032' => '小碧潭',
'034' => '新店區公所',
'033' => '新店',
'047' => '景安',
'046' => '永安市場',
'045' => '頂溪',
'041' => '古亭',
'134' => '東門',
'089' => '忠孝新生',
'132' => '松江南京',
'131' => '行天宮',
'130' => '中山國小',
'055' => '民權西路',
'128' => '大橋頭',
'127' => '台北橋',
'126' => '菜寮',
'125' => '三重',
'124' => '先嗇宮',
'123' => '頭前庄',
'122' => '新莊',
'121' => '輔大',
'180' => '丹鳳',
'179' => '迴龍',
'178' => '三重國小',
'177' => '三和國中',
'176' => '徐匯中學',
'175' => '三民高中',
'174' => '蘆洲',
'077' => '永寧',
'078' => '土城',
'079' => '海山',
'080' => '亞東醫院',
'081' => '府中',
'082' => '板橋',
'083' => '新埔',
'084' => '江子翠',
'085' => '龍山寺',
'086' => '西門',
'051' => '台北車站',
'088' => '善導寺',
'089' => '忠孝新生',
'010' => '忠孝復興',
'091' => '忠孝敦化',
'092' => '國父紀念館',
'093' => '市政府',
'094' => '永春',
'095' => '後山埤',
'096' => '昆陽',
'097' => '南港',
'031' => '南港展覽館',
);

$output = fopen('php://output', 'w');
fputcsv($output, array("車站代碼", "車站名稱", "檔案", "方向名稱"));
foreach ($stations as $station_id => $name) {
    $content = file_get_contents("http://web.metro.taipei/c/timetables.asp?id={$station_id}");
    preg_match_all('#<a href="../img/ALL/timetables/([^.]*)\.PDF" title="(.*)" alt="(.*)" target="_blank">(.*)</a>#', $content, $matches);
    foreach ($matches[1] as $id => $file) {
        fputcsv($output, array(
            $station_id,
            $name,
            $file,
            $matches[4][$id],
        ));
    }
}
