<?PHP

include 'function.php';

$name = $_POST['user'];

// user/host info
$host = "ldap://10.110.1.**";
$user = "web.site@****.lan";
$pass = "*************";

$stack = array();

// form post
$username = $name;

// connect 
$ldapconn = ldap_connect($host)
    or die("Could not connect to LDAP server.");

// set options
ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);
ldap_set_option($ldapconn, LDAP_OPT_REFERRALS, 0);

// bind
if ($ldapconn) {
    $ldapbind = ldap_bind($ldapconn, $user, $pass);
    if ($ldapbind) {//echo "LDAP bind successful...";
    } 
    else {//echo "LDAP bind failed...";
    }
}

// try search
if ($ldapbind) {

    // set filter and org unit
    $base = "ou=Users,ou=HARTMED - Accounts,dc=HARTMED,dc=LAN";
    $filter="(&(objectcategory=person)(objectclass=user)(!(userAccountControl:1.2.840.113556.1.4.803:=2))(|(sN=*$username*)(givenName=*$username*)))";

    // get results
    $result = ldap_search($ldapconn,$base,$filter);

    // sort and store
    ldap_sort($ldapconn,$result,"givenName");
    $info = ldap_get_entries($ldapconn, $result);

    //echo "user count: ".$info['count'];

    // user variables
    $fullname ="";
    $phone ="";
    $email ="";
    $location ="";

    // record data
    for ($i=0; $i<$info["count"]; $i++) {

        //check if email is blank
        if (isset($info[$i]["mail"][0])) {

            //check if email string contains admin or test
            $emailtest = $info[$i]["mail"][0];
            if(stristr($emailtest, 'admin') === FALSE && stristr($emailtest, 'test') === FALSE && stristr($emailtest, 'setup') === FALSE) {

                if (isset($info[$i]["givenname"][0])) {
                    $first = $info[$i]["givenname"][0];
                } else {
                    $first = "N/A";
                }
                if (isset($info[$i]["sn"][0])) {
                    $last = $info[$i]["sn"][0];
                } else {
                    $last = "N/A";
                }
                if (isset($info[$i]["ipphone"][0])) {
                    $phone = $info[$i]["ipphone"][0];
                } else {
                    $phone = "N/A";
                }
                if (isset($info[$i]["mail"][0])) {
                    $email = $info[$i]["mail"][0];
                } else {
                    $email = "N/A";
                }
                if (isset($info[$i]["physicaldeliveryofficename"][0])) {
                    $location = $info[$i]["physicaldeliveryofficename"][0];
                } else {
                    $location = "N/A";
                }
                $fullname = $first . " " . $last;
                
                $stack[] = array($fullname,$phone,$location,$email);
            }
        }
    }
    @ldap_close($ldapconn);
    
    echo json_encode($stack);
} 
