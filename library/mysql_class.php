<?php
if (!defined('ROOT')) exit('Can\'t Access !'); 
class mysql_class{
	
 private $dbhost; //数据库主机
 private $dbuser; //数据库用户名
 private $dbpassword; //数据库用户名密码
 private $dbname; //数据库名 
 private $dbcharset; //数据库编码，GBK,UTF8,gb2312
 private $conn; //数据库连接标识;
 private $pconn; //是否永久连接
 
 //构造函数
 function __construct($module){
   $this->dbhost = config::get("dbhost");
   $this->dbuser = config::get("dbuser");
   $this->dbpassword = config::get("dbpassword");
   $this->dbname = config::get("dbname");
   $this->conn = config::get("dbconn");
   $this->pconn = config::get("pconnect");
   $this->dbcharset = config::get("charset");
   $this->module = $module;
   $this->pre = config::get("tablepre");
   $this->connect();
 }
 
 //连接数据库
 private function connect(){
   if($this->pconn){
      $this->conn=@mysql_pconnect($this->dbhost,$this->dbuser,$this->dbpassword) or die($this->error());
   }else{
      $this->conn=@mysql_connect($this->dbhost,$this->dbuser,$this->dbpassword) or die($this->error());
   }
   @mysql_select_db($this->dbname, $this->conn) or die ($this->error());
   @mysql_query("SET NAMES $this->dbcharset") or die ($this->error());
   @mysql_query("set sql_mode=''") or die ($this->error());
 }
 
 
 //执行一条SQL语句
 function query($sql) {
   $query = @mysql_query($sql,$this->conn) or die($this->error($sql));
   return $query;
 }
 
 //创建新的数据库
 function create_database($database) {
   $sql = 'create database ' . $database;
   return $this->query($sql);
 } 
 
 //信息生成数组(双方式)
 function arr($query){
   return mysql_fetch_array($query);
 }
 
 //获取一条数据。
 function select_one($sql){
   $query=$this->query($sql);
   return $this->arr($query);
 }
 
 //获取一条数据。
 function one($sql){
   $query=$this->query($sql);
   return $this->assoc($query);
 }

 //信息生成数组(字段名方式)
 function assoc($query) {
   return mysql_fetch_assoc($query);
 }

 //信息生成数组(数字索引方式)
 function row($query) {
   return mysql_fetch_row($query);
 }

 //信息生成对象
 function obj($query) {
   return mysql_fetch_object($query);
 }
 
 //返回数据条数
 function num($table,$where="") {
   $where = $where!="" ?  "where $where" : "";
   return $this->counts("select * from $table $where");
 }
 
 function sum($table,$row,$where=""){
   $arr = $this->select_one("select sum({$row}) as sinbegin from {$table} $where");
   return $arr['sinbegin'] ? $arr['sinbegin'] : 0;
 }
 
 function getrecord($table,$row,$where=""){
   foreach($row as $key=>$val){
	 $field[] = "sum({$val}) as {$key}";
   }
   $rs = $this->select_one("select ".implode(",",$field)." from {$table} {$where}");
   foreach($row as $key=>$val){
	 $value[$key] = $rs[$key] ? $rs[$key] : '0.0000';
   }
   return $value;
 }
 
 //返回数据条数
 function counts($sql) {
   return mysql_num_rows($this->query($sql));
 }
 
 function getid($sql,$id='uid'){
  $query = $this->query($sql); 
  while($rs=$this->assoc($query)){
    $arr[] = $rs[$id];
  }
  return is_array($arr) ? implode(",",$arr) : "";
 }
 
 function affected(){
   return mysql_affected_rows();
 }
 
  //返回数据
 function value($table,$row,$where="") {
   $sql="select * from $table where $where";
   $rs=$this->select_one($sql);
   return $rs[$row];
 }
 
 //插入数据
 function insert($table,$row,$many='') {
   $this->query($this->sql_insert($table,$row,$many));
   return $this->insertid();
 }
 
 //更新数据
 function update($table,$row,$where) {
   $sql = $this->sql_update($table, $row, $where);
   return $this->query($sql);
 } 
 
 function sql_insert($table,$row,$many){
	$fields = '';
	$values = '';
	if(is_array($many)){
	  foreach ($row as $key=>$value) {
	    $fields .= "`".$key."`,";
	    if(!is_array($value)) $values .= "'".$value."',";
	  }
	  return "insert into `".$table."` (".substr($fields, 0, -1).") values (".$values.implode("),(".$values,$many).")";
	}else{
	  foreach ($row as $key=>$value) {
	    $fields .= "`".$key."`,";
	    $values .= "'".$value."',";
	  }
	  return "insert into `".$table."` (".substr($fields, 0, -1).") values (".substr($values, 0, -1).")";
	}
 }
 function sql_update($tbname, $row, $where){
    $sqlud='';
    if(is_string($row)){
	  $sqlud=$row.' ';
	}else{
	  foreach ($row as $key=>$value) {
		$sqlud .= "`$key`"."= '".$value."',";
	  }
	}
	return "update `".$tbname."` set ".substr($sqlud, 0, -1)." where ".$where;
 }
 
 function getarr($sql){
	$query = $this->query($sql); 
    while($rs=$this->assoc($query)){
  	    $getarr[] = $rs;
	}
	return $getarr;
 }
 
function db_create_in($item_list, $field_name = '')
{
    if (empty($item_list))
    {
        return "";
    }
    else
    {
        if (!is_array($item_list))
        {
            $item_list = explode(',', $item_list);
        }
        $item_list = array_unique($item_list);
        $item_list_tmp = '';
        foreach ($item_list as $item)
        {
            if ($item != '')
            {
                $item_list_tmp .= $item_list_tmp ? " or {$field_name}='{$item}'" : "{$field_name}='{$item}'";
            }
        }
        return "({$item_list_tmp})";
    }
}
 
 
  //获取数据总和
 function getsum($table,$row,$where=""){
   $where = $where!="" ?  "where $where" : "";
   $sum = $this->select_one("select sum(".$row.") as sinbegin from $table $where");
   return $sum['sinbegin'] ? $sum['sinbegin'] : 0;
 }
 
 //删除数据
 function delete($table,$where) {
   return $this->query("delete from $table where $where");
 }
 
 function insertid(){
   return mysql_insert_id($this->conn);	 
 }
 
 //错误信息提示
 private function error($sql=""){
   $error['error'] = "Mysql_error:".mysql_error()."(".mysql_errno().")";
   $error['error'] .= $sql == "" ? "" : " Sql:'{$sql}'";
   return json($error);
 } 
 
 //析构函数关闭数据库
 function __destruct(){	 
	 @mysql_close($this->conn);
 }
}
?>