<?php
function exportRankingCsv($list, $heads, $file_name)
{
    $stream = fopen('php://temp', 'r+b');
    if (!empty($heads)) {
        fputcsv($stream, $heads);
    }
    foreach ($list as $row) {
        fputcsv($stream, $row);
    }
    rewind($stream);
    $csv = str_replace(PHP_EOL, "\r\n", stream_get_contents($stream));
    $csv = mb_convert_encoding($csv, 'SJIS-win', 'UTF-8');
    $headers = array(
        'Content-Type' => 'text/csv',
        'Content-Disposition' => "attachment; filename=$file_name",
    );
    return \Response::make($csv, 200, $headers);
}
