<?php

include __DIR__ . "/koneksi.php";

function execute($query)
{
    $koneksi = $GLOBALS['koneksi'];
    try {
        $res = mysqli_query($koneksi, $query);
        if ($res === false) {
            $err = mysqli_error($koneksi);
            // If it's a missing table, try to import the hafidz SQL (dev) or show helpful message
            if (strpos($err, "doesn't exist") !== false) {
                // find missing table name
                if (preg_match("/`?([a-zA-Z0-9_]+)\.`?([a-zA-Z0-9_]+)`? doesn't exist/", $err, $m)) {
                    $dbname = $m[1];
                    $table = $m[2];
                } else if (preg_match("/Table '(.+?)' doesn't exist/", $err, $m2)) {
                    $full = $m2[1];
                    $parts = explode('.', $full);
                    if (count($parts) == 2) {
                        $dbname = $parts[0];
                        $table = $parts[1];
                    } else {
                        $table = $parts[0];
                    }
                } else {
                    $table = null;
                }

                // Only attempt auto import on dev localhost with root/no-pass
                if (isset($host) && isset($user) && isset($pass) && $host === 'localhost' && $user === 'root' && $pass == '') {
                    $sqlFile = realpath(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'sql' . DIRECTORY_SEPARATOR . 'add_hafidz_table.sql');
                    // if the table name is not 'hafidz' but the db is missing some other table, attempt to import the primary xmaqra.sql
                    if (!$sqlFile || !file_exists($sqlFile)) {
                        $sqlFile = realpath(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'xmaqra.sql');
                    }
                    if ($sqlFile && file_exists($sqlFile) && is_readable($sqlFile)) {
                        $sqlContents = file_get_contents($sqlFile);
                        if ($sqlContents !== false) {
                            // Attempt to import SQL dump to create missing table(s)
                            if (mysqli_multi_query($koneksi, $sqlContents)) {
                                // flush results
                                do {
                                    if ($r = mysqli_store_result($koneksi)) mysqli_free_result($r);
                                } while (mysqli_more_results($koneksi) && mysqli_next_result($koneksi));
                                // try original query again
                                $res2 = mysqli_query($koneksi, $query);
                                if ($res2 !== false) return $res2;
                            } else {
                                // fallback: try splitting
                                $parts = preg_split('/;\s*\n/', $sqlContents);
                                foreach ($parts as $p) {
                                    $p = trim($p);
                                    if (!$p) continue;
                                    @mysqli_query($koneksi, $p);
                                }
                                $res2 = mysqli_query($koneksi, $query);
                                if ($res2 !== false) return $res2;
                            }
                        }
                    }
                }
                // If we reach here, show helpful message and die
                $msg = "Database error: $err.\n";
                $msg .= "If this is a local XAMPP dev, import the required SQL migrations: ";
                $msg .= "C:\\xampp2\\mysql\\bin\\mysql.exe -u root < C:\\xampp2\\htdocs\\lomba\\sql\\add_hafidz_table.sql\n OR import xmaqra.sql if needed.\n";
                $msg .= "Missing table: " . ($table ? $table : '(unknown)') . "\n";
                die($msg);
            }
            // other errors: die as before but include message
            die("Database query failed: $err (query: $query)");
        }
        return $res;
    } catch (Throwable $e) {
        die("Database exception: " . $e->getMessage());
    }
}

function getonedata($query)
{
    //getone kolom and one row
    $data = execute($query);
    if (mysqli_num_rows($data) == 0) {
        return "";
    } else {
        $row = mysqli_fetch_array($data);
        return $row[0];
    }
}



function dbescape($str)
{
    return mysqli_real_escape_string($GLOBALS['koneksi'], $str);
}

function savevalue($arr)
{

    for ($i = 0; $i < count($arr); $i++) {
        $arr[$i] = str_replace("'", "\'", $arr[$i]);
        // $arr[$i]=$arr[$i] . " hehehe";
    }
    // $tmp2=implode(",",$arr);
    // $tmp2=str_replace("'","\'",$tmp2);
    // $arr=explode(",",$tmp2);
    return $arr;
}
function dbupdate($tabel, $arrkolom, $arrvalue, $where)
{
    //for sql inject
    $arrvalue = savevalue($arrvalue);

    $tmp = "";
    for ($i = 0; $i < count($arrkolom); $i++) {
        $tmp .= "$arrkolom[$i] = '$arrvalue[$i]',";
    }
    $tmp = substr($tmp, 0, strlen($tmp) - 1);
    $query = "update $tabel set $tmp $where";
    //update aaa set k='',k2='' where id=0;
    // die($query);
    execute($query);
}

function dbinsert($tabel, $arrkolom, $arrvalue)
{
    //for sql inject
    $arrvalue = savevalue($arrvalue);

    $kolom = implode(',', $arrkolom);
    $value = implode("','", $arrvalue);
    $value = "'$value'";
    $query = "insert into $tabel($kolom) values($value)";
    // die($query);
    execute($query);
}

function dbdelete($tabel, $id)
{
    $query = "delete from $tabel where id=$id";
    execute($query);
}

function getdata($query)
{
    return execute($query);
    //sama seperti execute, mungkin dideprecate
}

function getonebaris($query)
{
    $data = execute($query);
    return mysqli_fetch_array($data); //ambil yg pertama
}
