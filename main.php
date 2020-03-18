<?
// session_start();
class DB {
    protected static $instance = null;

    public function __construct() {}
    public function __clone() {}

    public static function instance()
    {
        if (self::$instance === null)
        {
            $opt = array(
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, PDO::ATTR_EMULATE_PREPARES => TRUE,
            );
            $config = require_once 'config.php';
            $dsn = 'mysql:host='.$config['host'].';dbname='.$config['db_name'].';charset='.$config['charset'];
            self::$instance = new PDO($dsn, $config['username'],$config['password'],$opt);
        }
        return self::$instance;
    }

    public static function __callStatic($method, $args)
    {
        return call_user_func_array(array(self::instance(), $method), $args);
    }

    public static function run($sql, $args = [])
    {
        $stmt = self::instance()->prepare($sql);
        $stmt->execute($args);
        return $stmt;
    }
 }


class Articles{
    public function allrecords()
    {
        $sql = 'SELECT * FROM test1';
        return $res = DB::query($sql)->fetchAll();
    }
    public function adding($values)
    {
        $sql = 'INSERT INTO test1(name,surname) VALUES(?,?)';
        return $res = DB::run($sql,[$values['name'],$values['surname']]);
    }
    public function deleting($values)
    {
        $sql = 'DELETE FROM test1 WHERE id = ?';
        return $res = DB::run($sql,[$values['id']]);
    }
    public function showing($values)
    {
        $sql = 'SELECT name,surname FROM test1 WHERE id = ?';
        return $res = DB::run($sql,[$values['id']])->fetch();
    }
    public function updating($values)
    {
        $sql = 'UPDATE test1 SET name = ?,surname =? WHERE id = ?';
        return $res = DB::run($sql,[$values['name'],$values['surname'],$values['id']]);
    }



    public function count2()
    {
        $sql = 'SELECT COUNT(*) AS count FROM test1';
        return $res = DB::query($sql)->fetch();
    }
    public function count()
    {
        $sql = 'SELECT COUNT(*) AS count FROM news';
        return $res = DB::query($sql)->fetch();
    }
    public function view($id)
    {
        $sql = 'SELECT title, content, image FROM news WHERE id = ?';
        return $res = DB::run($sql,[$id])->fetch();
    }
    public function delete($id)
    {
        $sql = 'DELETE FROM news WHERE id = ?';
        return $res = DB::run($sql,[$id]);
    }
    public function add($d,$l,$a,$c,$i)
    {
        $sql = 'INSERT INTO news(idate,title,announce,content,image) VALUES(?,?,?,?,?)';
        return $res = DB::run($sql,[$d,$l,$a,$c,$i]);
    }
    public function update($title,$content,$image,$id)
    {
        $sql = 'UPDATE news SET title = ?,content = ?, image =? WHERE id = ?';
        return $res = DB::run($sql,[$title,$content,$image,$id]);
    }
    public function allnews1($start, $num)
    {
        $sql = 'SELECT * FROM news ORDER BY idate DESC LIMIT :start,:num';
        $sth = DB::prepare($sql);
        $sth->bindValue(':start',$start,PDO::PARAM_INT);
        $sth->bindValue(':num',$num,PDO::PARAM_INT);
        $sth->execute();
        return $result = $sth->fetchAll();
    }
    public function allnews2($start, $num)
    {
        $sql = 'SELECT * FROM news ORDER BY idate ASC LIMIT :start,:num';
        $sth = DB::prepare($sql);
        $sth->bindValue(':start',$start,PDO::PARAM_INT);
        $sth->bindValue(':num',$num,PDO::PARAM_INT);
        $sth->execute();
        return $result = $sth->fetchAll();
    }
    public function search($text)
    {
        $sql = 'SELECT * FROM news WHERE content LIKE :val';
        $sth = DB::prepare($sql);
        $sth->bindParam(':val',$text,PDO::PARAM_STR);
        $text = '%'.$text.'%';
        $sth->execute();
        return $result = $sth->fetchAll();
    }
    public function filter($date)
    {
        $sql = "SELECT * FROM news WHERE idate BETWEEN (SELECT UNIX_TIMESTAMP(STR_TO_DATE(:d1, '%Y-%m-%d'))) AND (SELECT UNIX_TIMESTAMP(STR_TO_DATE(:d2, '%Y-%m-%d')))";
        $sth = DB::prepare($sql);

        $sth->bindParam(':d1',$date1,PDO::PARAM_STR);
        $sth->bindParam(':d2',$date2,PDO::PARAM_STR);
        $date1 = "2019-12-".$date;
        $date3 = (int)$date+1;
        $date2 = "2019-12-".$date3;
        $sth->execute();
        // return $date2;
        return $result = $sth->fetchAll();
    }
}

class Users{
    public function registration($login,$email,$pass)
    {
        $sql = 'INSERT INTO users(login,email,pass) VALUES(?,?,?)';
        return $res = DB::run($sql,[$login,$email,$pass]);
    }
    public function login($login)
    {
        $sql = 'SELECT * FROM users WHERE login = ?';
        return $res = DB::run($sql,[$login])->fetch(PDO::FETCH_OBJ);
    }
    public function check_user($login,$email)
    {
        $sql = 'SELECT COUNT(login) AS count FROM users WHERE login = ? OR email = ?';
        return $res = DB::run($sql,[$login,$email])->fetch(PDO::FETCH_OBJ);
    }
}

$articles = new Articles();
$users = new Users();


// class DB
// {
//
//     protected static $instance = null;
//     final private function __construct() {}
//     final private function __clone() {}
//     public static function instance()
//     {
//         if (self::$instance === null)
//         {
// 			$opt  = array(
// 				PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
// 				PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
// 				PDO::ATTR_EMULATE_PREPARES   => TRUE,
// 				PDO::ATTR_STATEMENT_CLASS    => array('myPDOStatement'),
// 			);
//             $config = require_once 'config.php';
//             $dsn = 'mysql:host='.$config['host'].';dbname='.$config['db_name'].';charset='.$config['charset'];
//             self::$instance = new PDO($dsn, $config['username'],$config['password'], $opt);
//         }
//         return self::$instance;
//     }
//     public static function __callStatic($method, $args) {
//         return call_user_func_array(array(self::instance(), $method), $args);
//     }
// }
// class myPDOStatement extends PDOStatement
// {
// 	function execute($data = array())
// 	{
// 		parent::execute($data);
// 		return $this;
// 	}
// }
