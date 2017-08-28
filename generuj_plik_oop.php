<?php
//require_once "connection.php";

class Generuj{
    public $db;
    private $class_db_file;
    private $do_pliku;
    private $nazwa_pliku;
    //private $servername;
    //private $username;
    //private $password;
    //private $dbname;
    //private $conn;
    
    public function __construct() {
        $this->do_pliku = "";
        $this->nazwa_pliku = "kat/hosty_z_bazy.conf";
        //$this->servername = "localhost";
        //$this->username = "root";
        //$this->password = "";
        //$this->dbname = "nowe_hosty";
        //$this->conn = @mysqli_connect($this->servername, $this->username, $this->password, $this->dbname);
	//if(!$this->conn){
        //    die("Błąd połączenia z bazą: ".mysqli_connect_error());
	//}
        $this->class_db_file = 'db.php';

        if(file_exists($this->class_db_file)){
            require_once($this->class_db_file);
            $this->db = new db();
        }else{
            echo "brak pliku z klasą do łączenia z db";
        }
    }
    
    public function generujPlik(){
        $zapytanie_generuj = "SELECT * FROM znane_hosty";
        
        $rezultat_generuj = mysqli_query($this->db->connection, $zapytanie_generuj);
        //$wynik_z_bazy = $this->db->selektuj($zapytanie_generuj);
        
        //var_dump($wynik_z_bazy);
        
//        foreach ($wynik_z_bazy as $value) {
//            echo $value[1]." ".$value[7]." ".$value[2];
//        }
        
        while($row = mysqli_fetch_array($rezultat_generuj)){
            $this->do_pliku .= "host ".$row['nazwa_hosta']." {fixed address ".$row['ip_address'].
            ";hardware ethernet ".$row['mac_address'].";}"."\r\n";
	}
        
        mysqli_free_result($rezultat_generuj);
	
        mysqli_close($this->db->connection);
        file_put_contents($this->nazwa_pliku, $this->do_pliku);
        header('location: index.php');
    }
}
$generuj = new Generuj();
$generuj->generujPlik();
