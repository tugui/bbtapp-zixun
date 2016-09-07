<?php

header("Content-type:text/html;charset=utf-8");

$textType = $_POST['textType'];
$data = '';

if (isset($_POST['pageTitle'],
    $_POST['writer'],
    $_POST['nowDate'],
    $_POST['content'],
    $_POST['writerData'],
    $_POST['summary']) &&
    $_POST['textType'] != '' &&
    $_POST['pageTitle'] != '' &&
    $_POST['writer'] != '' &&
    $_POST['nowDate'] != '' &&
    $_POST['content'] != '' &&
    $_POST['writerData'] != '' &&
    $_POST['summary'] != '') {

    $pageTitle = $_POST['pageTitle'];
    $writer = $_POST['writer'];
    $nowDate = $_POST['nowDate'];
    $content = $_POST['content'];
    $writerData = $_POST['writerData'];
    $summary = $_POST['summary'];

    $content = str_replace('"', '\"', $content);

    if ($textType === "dailySoup") {

        $data = '{"date":"' . $nowDate . '","title":"' . $pageTitle . '","author":"' . $writer . '","article":"' . $content . '","summary":"' . $summary . '","authorIntroduce":"' . $writerData . '"}';

    } else if ($textType === "schoolInformation") {

        if (isset($_POST['picture']) && $_POST['picture'] != '') {
            $picture = $_POST['picture'];

        } else {
            echo "填写不全";
            return;
        }

        if (file_exists('http://218.192.166.167/api/protype/schoolInformation/' . iconv('UTF-8', 'GBK', $pageTitle) . '.html')) {
            echo '已存在此文章';
            return;
        }

        $data = '{"date":"' . $nowDate . '","title":"' . $pageTitle . '","author":"' . $writer . '","article":"' . $content . '","summary":"' . $summary . '","picture":"' . $picture . '"}';
    }

    $searchStr = array('.jpg\" title=\"', '.png\" title=\"', '.jpeg\" title=\"', '.gif\" title=\"', '.bmp\" title=\"');
    $replaceStr = array('.jpg?imageView2/0/w/200\" title=\"', '.png?imageView2/0/w/200\" title=\"', '.jpeg?imageView2/0/w/200\" title=\"', '.gif?imageView2/0/w/200\" title=\"', '.bmp?imageView2/0/w/200\" title=\"');

    $dataAfter = str_replace($searchStr, $replaceStr, $data);

    $url = 'http://218.192.166.167/api/protype.php';

    $post_data = array(
        'table' => $textType,
        'method' => "save",
        'data' => $dataAfter,
    );

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
    $return = curl_exec($ch);
    curl_close($ch);
    $array = json_decode($return, true);
    if ($array['status'] == 1) {
        echo '上传成功';
    } else {
        echo '上传失败';
    }

} else {
    echo '填写不全';
}
