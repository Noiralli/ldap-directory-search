<?PHP

function sort_ldap_entries($e, $fld, $order) {
    
    for ($i = 0; $i < $e['count']; $i++) { 
        for ($j = $i; $j < $e['count']; $j++) { 
            $d = strcasecmp($e[$i][$fld][0], $e[$j][$fld][0]); 
            switch ($order) { 
            case 'A': 
                if ($d > 0) 
                    swap($e, $i, $j); 
                break; 
            case 'D': 
                if ($d < 0) 
                    swap($e, $i, $j); 
                break; 
            } 
        } 
    } 
    return ($e); 
} 

function swap(&$ary, $i, $j) {
    
    $temp = $ary[$i]; 
    $ary[$i] = $ary[$j]; 
    $ary[$j] = $temp; 
} 
