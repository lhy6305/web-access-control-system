<?php

require_once(__DIR__."/libutil.php");

function libdb_init() {
    $db=$GLOBALS["db"]=null;

    require(__DIR__."/libdatabase_adapter_sqlite_secret.php");

    if(!is_readable($database_file_path)) {
        $db=$GLOBALS["db"]=new SQLite3($database_file_path, SQLITE3_OPEN_READWRITE|SQLITE3_OPEN_CREATE, $database_key);
        $db->exec(file_get_contents("database_template.sql"));
        if($db->lastErrorCode() != 0) {
            raise_fatal_error("Fatal Error: Database initialization failed: ".$db->lastErrorMsg().". ".basename(__FILE__)."@L".__LINE__."\r\n");
        }
    } else {
        $db=$GLOBALS["db"]=new SQLite3($database_file_path, SQLITE3_OPEN_READWRITE, $database_key);
    }
    unset($database_file_path);
    unset($database_key);
    if(!$db) {
        raise_fatal_error("Fatal Error: Unable to open database file. ".basename(__FILE__)."@L".__LINE__."\r\n");
    }
}

function libdb_search_param($param, $type) {
    global $db;

    $query = '';
    if($type === 'client_id') {
        $query = "SELECT * FROM client_id WHERE client_id = :param";
    } else if($type === 'session_key') {
        $query = "SELECT * FROM client_id WHERE session_key = :param";
    } else if($type === 'client_ip') {
        $query = "SELECT * FROM client_ip WHERE client_ip = :param";
    } else if($type === 'client_hash') {
        $query = "SELECT * FROM client_hash WHERE client_hash = :param";
    } else {
        debug_log("libdb_search_param: \$type (".$type.") is not recognized. ".basename(__FILE__)."@L".__LINE__."\r\n");
        return null;
    }

    $stmt = $db->prepare($query);
    if(!$stmt) {
        debug_log("SQL preparation failed: " . $db->lastErrorMsg().". ".basename(__FILE__)."@L".__LINE__."\r\n");
        return null;
    }
    $stmt->bindValue(':param', $param, SQLITE3_TEXT);
    $result = $stmt->execute();

    $row = $result->fetchArray(SQLITE3_ASSOC);
    return $row;
}

function libdb_get_client($cid) {
    global $db;

    $clientInfo = libdb_search_param($cid, 'client_id');
    if(!$clientInfo) {
        return null;
    }

    $hashQuery = "SELECT * FROM client_hash WHERE client_id = :client_id";
    $stmt = $db->prepare($hashQuery);
    if(!$stmt) {
        debug_log("SQL preparation for client_hash query failed: " . $db->lastErrorMsg().". ".basename(__FILE__)."@L".__LINE__."\r\n");
        return null;
    }
    $stmt->bindValue(':client_id', $cid, SQLITE3_TEXT);
    $hashResult = $stmt->execute();

    if(!$hashResult) {
        debug_log("SQL execution for client_hash query failed: " . $db->lastErrorMsg().". ".basename(__FILE__)."@L".__LINE__."\r\n");
        return null;
    }

    $clientHashes = [];
    while($hashRow = $hashResult->fetchArray(SQLITE3_ASSOC)) {
        $clientHashes[] = $hashRow;
    }

    $ipQuery = "SELECT * FROM client_ip WHERE client_id = :client_id";
    $stmt = $db->prepare($ipQuery);
    if(!$stmt) {
        debug_log("SQL preparation for client_ip query failed: " . $db->lastErrorMsg().". ".basename(__FILE__)."@L".__LINE__."\r\n");
        return null;
    }
    $stmt->bindValue(':client_id', $cid, SQLITE3_TEXT);
    $ipResult = $stmt->execute();

    if(!$ipResult) {
        debug_log("SQL execution for client_ip query failed: " . $db->lastErrorMsg().". ".basename(__FILE__)."@L".__LINE__."\r\n");
        return null;
    }
    $clientIps = [];
    while($ipRow = $ipResult->fetchArray(SQLITE3_ASSOC)) {
        $clientIps[] = $ipRow;
    }

    $clientData = [
                      'client_id' => $cid,
                      'last_visit_time' => (int)$clientInfo['last_visit_time'],
                      'session_key' => $clientInfo['session_key'],
                      'client_hash' => $clientHashes,
                      'client_ip' => $clientIps,
                  ];

    return $clientData;
}

function libdb_update_visit_time($client_id, $client_hash, $client_ip, $session_key) {
    global $db;

    $succ=true;

    $current_time = getsec();

    if(is_string($client_hash)) {
        $client_hash=[$client_hash];
    }

    if(is_string($client_ip)) {
        $client_ip=[$client_ip];
    }

    if(is_string($session_key)) {
        $session_key=[$session_key];
    }

    if($client_hash!==null&&!is_array($client_hash)) {
        debug_log("Warning: \$client_hash.type is not in [Array, string, null]. " . $db->lastErrorMsg().". ".basename(__FILE__)."@L".__LINE__."\r\n");
        $client_hash=null;
    }
    if($client_ip!==null&&!is_array($client_ip)) {
        debug_log("Warning: \$client_ip.type is not in [Array, string, null]. " . $db->lastErrorMsg().". ".basename(__FILE__)."@L".__LINE__."\r\n");
        $client_ip=null;
    }
    if($session_key!==null&&!is_array($session_key)) {
        debug_log("Warning: \$session_key.type is not in [Array, string, null]. " . $db->lastErrorMsg().". ".basename(__FILE__)."@L".__LINE__."\r\n");
        $session_key=null;
    }

    if($client_ip !== null) {
        $updateClientIpQuery = "UPDATE client_ip SET last_visit_time = :last_visit_time WHERE client_id = :client_id AND client_ip = :client_ip";
        $stmt = $db->prepare($updateClientIpQuery);

        if($stmt) {
            foreach($client_ip as $ip) {
                $stmt->bindValue(':last_visit_time', $current_time, SQLITE3_INTEGER);
                $stmt->bindValue(':client_id', $client_id, SQLITE3_TEXT);
                $stmt->bindValue(':client_ip', $ip, SQLITE3_TEXT);

                if(!$stmt->execute()) {
                    $succ=false;
                }
            }
        } else {
            debug_log("SQL preparation for updating client_id failed: " . $db->lastErrorMsg().". ".basename(__FILE__)."@L".__LINE__."\r\n");
            $succ=false;
        }
    }

    if($client_hash !== null) {
        $updateClientHashQuery = "UPDATE client_hash SET last_visit_time = :last_visit_time WHERE client_id = :client_id AND client_hash = :client_hash";
        $stmt = $db->prepare($updateClientHashQuery);

        if($stmt) {
            foreach($client_hash as $hash) {
                $stmt->bindValue(':last_visit_time', $current_time, SQLITE3_INTEGER);
                $stmt->bindValue(':client_id', $client_id, SQLITE3_TEXT);
                $stmt->bindValue(':client_hash', $hash, SQLITE3_TEXT);

                if(!$stmt->execute()) {
                    $succ=false;
                }
            }
        } else {
            debug_log("SQL preparation for updating client_id failed: " . $db->lastErrorMsg().". ".basename(__FILE__)."@L".__LINE__."\r\n");
            $succ=false;
        }
    }

    if($session_key !== null) {
        $updateClientIdQuery = "UPDATE client_id SET last_visit_time = :last_visit_time, session_key = :session_key WHERE client_id = :client_id";
        $stmt = $db->prepare($updateClientIdQuery);

        if(!$stmt) {
            debug_log("SQL preparation for updating client_id failed: " . $db->lastErrorMsg().". ".basename(__FILE__)."@L".__LINE__."\r\n");
            $succ=false;
        }

        $stmt->bindValue(':last_visit_time', $current_time, SQLITE3_INTEGER);
        $stmt->bindValue(':session_key', $session_key, SQLITE3_TEXT);
        $stmt->bindValue(':client_id', $client_id, SQLITE3_TEXT);

        if(!$stmt->execute()) {
            $succ=false;
        }
    }

    return $succ;
}
