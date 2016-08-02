<?php
//    require_once(DIR_SYSTEM.'library/db.php');
//    require_once(DIR_SYSTEM.'library/db/mpdo.php');
//    require_once(DIR_SYSTEM.'library/db/mssql.php');
//    require_once(DIR_SYSTEM.'library/db/mysql.php');
//    require_once(DIR_SYSTEM.'library/db/mysqli.php');
//    require_once(DIR_SYSTEM.'library/db/postgre.php');
    function getTable($table,$prefix){
        if(!$prefix || isset($prefix)){
            $prefix = DB_PREFIX;
        }
        return "`" . $prefix . $table."`";
    }
    class MYDB extends DB{
        public function getAll($sql){
            $res = $this->query($sql);
            return $res->rows;
        }
		public function update($table, $params, $filter)
		{
			$sql = "UPDATE ".$table." SET";
			foreach ($params as $key => $value)
			{
				if ($key == '')
				{
					continue;
				}
				$sql = $sql." ".$key." = '".$value."',";
			}
			$sql = substr($sql, 0 , -1);
			$sql = $sql." WHERE";
			foreach ($filter as $key => $value)
			{
				if ($key == '')
				{
					continue;
				}
				$sql = $sql." ".$key." = '".$value."' AND";
			}
			$sql = substr($sql, 0 , -3);
			return $this->query($sql);
		}
		
		public function insert($table, $params)
		{
			$sql = "INSERT INTO ".$table." SET";
			foreach ($params as $key => $value)
			{
				if ($key == '')
				{
					continue;
				}
				$sql = $sql." ".$key." = '".$value."',";
			}
			$sql = substr($sql, 0 , -1);
			return $this->query($sql);
		}


        public function executeSql($sql){
            return $this->query($sql);
        }

        public function startTransaction(){
            $this->getLink()->autocommit(FALSE);
        }
        public function commitTransaction(){
            $this->getLink()->commit();
        }
        public function rollbackTransaction(){
            $this->getLink()->rollback();
        }


        public function queryRows($sql){
            $query = $this->query($sql);
            return $query->rows;
        }

        public function queryCount($sql){
            $query = $this->query($sql);
            return $query->row['count'];
        }

        public function querySingleRow($sql){
            $query = $this->query($sql);
            return $query->row;
        }
    }
    //$db = new MYDB(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);