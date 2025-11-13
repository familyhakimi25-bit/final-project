<?php
header('Content-Type: application/json');

// Matikan error HTML panjang dari mysqli supaya kita kawal sendiri response
mysqli_report(MYSQLI_REPORT_OFF);
error_reporting(0);

// SAMBUNG DB TERUS SINI – JANGAN include file yang ada session check
$conn = mysqli_connect('127.0.0.1', 'root', '', 'dbbarber', 3307);
if (!$conn) {
    http_response_code(500);
    echo json_encode(["error" => "DB connection failed"]);
    exit;
}

function clean($conn, $str) { return mysqli_real_escape_string($conn, $str); }

$method = $_SERVER['REQUEST_METHOD'];

// ---------- GET ----------
if ($method === 'GET') {
    if (isset($_GET['id'])) {
        $id = (int) $_GET['id'];
        $res = mysqli_query($conn, "SELECT * FROM receipt WHERE receipt_id = $id");
        if ($res && mysqli_num_rows($res) > 0) {
            echo json_encode(mysqli_fetch_assoc($res));
        } else {
            http_response_code(404);
            echo json_encode(["error" => "Booking not found"]);
        }
    } else {
        $out = [];
        $res = mysqli_query($conn, "SELECT * FROM receipt ORDER BY receipt_id DESC");
        while ($res && $row = mysqli_fetch_assoc($res)) { $out[] = $row; }
        echo json_encode($out);
    }
    exit;
}

// Baca body utk POST/PUT (kalau tiada, $input = [])
$raw   = file_get_contents('php://input');
$input = json_decode($raw, true);
if (!is_array($input)) { $input = []; }

// ---------- POST (Create) ----------
if ($method === 'POST') {
    if (empty($input['cust_id']) || empty($input['date']) || empty($input['time'])) {
        http_response_code(400);
        echo json_encode(["error" => "cust_id, date, time required"]);
        exit;
    }

    $cust_id = (int)$input['cust_id'];
    $date  = clean($conn, $input['date']);
    $time  = clean($conn, $input['time']);
    $notes = isset($input['notes']) ? clean($conn, $input['notes']) : '';
    $name  = isset($input['name'])  ? clean($conn, $input['name'])  : '';

    // ✅ Semak foreign key: customer mesti wujud
    $exists = mysqli_query($conn, "SELECT 1 FROM customer WHERE cust_id = $cust_id LIMIT 1");
    if (!$exists || mysqli_num_rows($exists) == 0) {
        http_response_code(400);
        echo json_encode(["error" => "cust_id not found in customer table"]);
        exit;
    }

    // Elak slot clash
    $check = mysqli_query($conn, "SELECT 1 FROM receipt WHERE date='$date' AND time='$time' LIMIT 1");
    if ($check && mysqli_num_rows($check) > 0) {
        http_response_code(409);
        echo json_encode(["error" => "Slot already taken"]);
        exit;
    }

    $sql = "INSERT INTO receipt (cust_id, date, time, notes, name)
            VALUES ($cust_id, '$date', '$time', '$notes', '$name')";
    if (mysqli_query($conn, $sql)) {
        echo json_encode(["message" => "created", "receipt_id" => mysqli_insert_id($conn)]);
    } else {
        http_response_code(500);
        echo json_encode(["error" => mysqli_error($conn)]);
    }
    exit;
}

// ---------- PUT (Update) ----------
if ($method === 'PUT') {
    if (!isset($_GET['id'])) {
        http_response_code(400);
        echo json_encode(["error" => "id is required"]);
        exit;
    }
    $id = (int)$_GET['id'];

    $old = mysqli_query($conn, "SELECT * FROM receipt WHERE receipt_id = $id");
    if (!$old || mysqli_num_rows($old) == 0) {
        http_response_code(404);
        echo json_encode(["error" => "Booking not found"]);
        exit;
    }
    $old = mysqli_fetch_assoc($old);

    // Nilai baru jika diberi, else kekalkan lama
    $date   = isset($input['date'])   ? clean($conn, $input['date'])   : $old['date'];
    $time   = isset($input['time'])   ? clean($conn, $input['time'])   : $old['time'];
    $notes  = isset($input['notes'])  ? clean($conn, $input['notes'])  : $old['notes'];
    $name   = isset($input['name'])   ? clean($conn, $input['name'])   : $old['name'];
    $custIdNew = isset($input['cust_id']) ? (int)$input['cust_id']     : (int)$old['cust_id'];

    // ✅ Jika cust_id nak ditukar/ditetapkan, sahkan wujud
    if ($custIdNew !== (int)$old['cust_id'] || isset($input['cust_id'])) {
        $exists = mysqli_query($conn, "SELECT 1 FROM customer WHERE cust_id = $custIdNew LIMIT 1");
        if (!$exists || mysqli_num_rows($exists) == 0) {
            http_response_code(400);
            echo json_encode(["error" => "cust_id not found in customer table"]);
            exit;
        }
    }

    $sql = "UPDATE receipt 
            SET cust_id=$custIdNew, date='$date', time='$time', notes='$notes', name='$name'
            WHERE receipt_id = $id";
    if (mysqli_query($conn, $sql)) {
        echo json_encode(["message" => "updated"]);
    } else {
        http_response_code(500);
        echo json_encode(["error" => mysqli_error($conn)]);
    }
    exit;
}

// ---------- DELETE ----------
if ($method === 'DELETE') {
    if (!isset($_GET['id'])) {
        http_response_code(400);
        echo json_encode(["error" => "id is required"]);
        exit;
    }
    $id = (int)$_GET['id'];
    $sql = "DELETE FROM receipt WHERE receipt_id = $id";
    if (mysqli_query($conn, $sql)) {
        echo json_encode(["message" => "deleted"]);
    } else {
        http_response_code(500);
        echo json_encode(["error" => mysqli_error($conn)]);
    }
    exit;
}

// ---------- Fallback ----------
http_response_code(405);
echo json_encode(["error" => "Method not allowed"]);
