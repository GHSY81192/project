<?php
Class DB
{

    private $link_id;
    private $handle;
    private $is_log;
    private $time;

    //构造函数
    public function __construct()
    {
        $this->time = $this->microtime_float();
        $config_path = dirname(dirname(__FILE__)).'/data/common.inc.php';
        require_once($config_path);
        $this->connect('123.56.179.66', 'bbs', 'Hbyzh,2016,bbs', 'bbs', 0);
        $this->is_log = true;
        if($this->is_log)
        {
            $handle = fopen("dblog.txt", "a+");
            $this->handle = $handle;
        }
    }

    //数据库连接
    public function connect($dbhost, $dbuser, $dbpw, $dbname, $pconnect = 0, $charset = 'utf8')
    {
        if($pconnect == 0)
        {
            $this->link_id = @mysql_connect($dbhost, $dbuser, $dbpw, true);
            if(!$this->link_id)
            {
                $this->halt("数据库连接失败");
            }
        }
        else
        {
            $this->link_id = @mysql_pconnect($dbhost, $dbuser, $dbpw);
            if(!$this->link_id)
            {
                $this->halt("数据库持久连接失败");
            }
        }
        if(!@mysql_select_db($dbname, $this->link_id))
        {
            $this->halt('数据库选择失败');
        }
        @mysql_query("set names " . $charset);
    }

    //查询 
    public function query($sql)
    {
        $this->write_log("查询 " . $sql);
        $query = mysql_query($sql, $this->link_id);
        if(!$query) $this->halt('Query Error: ' . $sql);
        return $query;
    }

    //获取一条记录（MYSQL_ASSOC，MYSQL_NUM，MYSQL_BOTH）              
    public function get_one($sql, $result_type = MYSQL_ASSOC)
    {
        $query = $this->query($sql);
        $rt =& mysql_fetch_array($query, $result_type);
        $this->write_log("获取一条记录 " . $sql);
        return $rt;
    }

    //获取全部记录
    public function get_all($sql, $result_type = MYSQL_ASSOC)
    {
        $query = $this->query($sql);
        $i = 0;
        $rt = array();
        while ($row =& mysql_fetch_array($query, $result_type))
        {
            $rt[$i] = $row;
            $i++;
        }
        $this->write_log("获取全部记录 " . $sql);
        return $rt;
    }

    //插入
    public function insert($table, $dataArray)
    {
        $field = "";
        $value = "";
        if(!is_array($dataArray) || count($dataArray) <= 0)
        {
            $this->halt('没有要插入的数据');
            return false;
        }
        while (list($key, $val) = each($dataArray))
        {
            $field .= "$key,";
            $value .= "'$val',";
        }
        $field = substr($field, 0, -1);
        $value = substr($value, 0, -1);
        $sql = "insert into $table($field) values($value)";
        $this->write_log("插入 " . $sql);
        if(!$this->query($sql)) return false;
        return true;
    }

    //更新
    public function update($table, $dataArray, $condition = "")
    {
        if(!is_array($dataArray) || count($dataArray) <= 0)
        {
            $this->halt('没有要更新的数据');
            return false;
        }
        $value = "";
        while (list($key, $val) = each($dataArray)) $value .= "$key = '$val',";
        $value .= substr($value, 0, -1);
        $sql = "update $table set $value where $condition";
        $this->write_log("更新 " . $sql);
        if(!$this->query($sql)) return false;
        return true;
    }

    //删除
    public function delete($table, $condition = "")
    {
        if(empty($condition))
        {
            $this->halt('没有设置删除的条件');
            return false;
        }
        $sql = "delete from $table where 1=1 and $condition";
        $this->write_log("删除 " . $sql);
        if(!$this->query($sql)) return false;
        return true;
    }

    //返回结果集
    public function fetch_array($query, $result_type = MYSQL_ASSOC)
    {
        $this->write_log("返回结果集");
        return mysql_fetch_array($query, $result_type);
    }

    //获取记录条数
    public function num_rows($results)
    {
        if(!is_bool($results))
        {
            $num = mysql_num_rows($results);
            $this->write_log("获取的记录条数为" . $num);
            return $num;
        }
        else
        {
            return 0;
        }
    }

    //释放结果集
    public function free_result()
    {
        $void = func_get_args();
        foreach($void as $query)
        {
            if(is_resource($query) && get_resource_type($query) === 'mysql result')
            {
                return mysql_free_result($query);
            }
        }
        $this->write_log("释放结果集");
    }

    //获取最后插入的id
    public function insert_id()
    {
        $id = mysql_insert_id($this->link_id);
        $this->write_log("最后插入的id为" . $id);
        return $id;
    }

    //关闭数据库连接
    protected function close()
    {
        $this->write_log("已关闭数据库连接");
        return @mysql_close($this->link_id);
    }

    //错误提示
    private function halt($msg = '')
    {
        $msg .= "\r\n" . mysql_error();
        $this->write_log($msg);
        die($msg);
    }

    //析构函数
    public function __destruct()
    {
        $this->free_result();
        $use_time = ($this->microtime_float()) - ($this->time);
        $this->write_log("完成整个查询任务,所用时间为" . $use_time);
        if($this->is_log)
        {
            fclose($this->handle);
        }
    }

    //写入日志文件
    public function write_log($msg = '')
    {
        if($this->is_log)
        {
            $text = date("Y-m-d H:i:s") . " " . $msg . "\r\n";
            fwrite($this->handle, $text);
        }
    }

    //获取毫秒数
    public function microtime_float()
    {
        list($usec, $sec) = explode(" ", microtime());
        return ((float)$usec + (float)$sec);
    }
}