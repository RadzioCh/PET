<?php
namespace App\Database;


class Query
{


    public static function insert($query, ?array $dataToBlind = null)
    {
        return Query::uidQuery($query, $dataToBlind);
    }

    public static function update($query, ?array $dataToBlind = null)
    {
        return Query::uidQuery($query, $dataToBlind);
    }

    public static function delete($query, ?array $dataToBlind = null)
    {
        return Query::uidQuery($query, $dataToBlind);
    }

    /**
     * 
     * @param type $query
     * @param array $dataToBlind
     * @return boolean
     */
    public static function uidQuery($query, ?array $dataToBlind = null)
    {
        try {
            $db = Connect::connectDB();
            $stmt = $db->prepare($query);

            if(!empty($dataToBlind) && is_array($dataToBlind)) {

                $toData = [];
                foreach($dataToBlind as $index => $value) {
                    $toData[":".$index] = $value;
                }

                foreach($toData as $i => $v) {
                    if(strpos($query, $i) !== false) {
                        //sprawdzanie czy wartosc istnieje w zapytaniu
                        $stmt->bindValue($i, $v);
                    }
                }
                
            }

            $stmt->execute();
            return true;

        }catch(\Exception $e){
                 exit('Wystapil blad w zapytaniu: <br /> ' . $query . '<br /> MYSQL_ERROR: <br /> ' . $e->getMessage());
        }
    }

    public static function select($query, ?array $dataToBlind = null, $oneRows = null)
    {
         try {
            $db = Connect::connectDB();
            $stmt = $db->prepare($query);

            if(!empty($dataToBlind) && is_array($dataToBlind)) {

                $toData = [];
                foreach($dataToBlind as $index => $value) {
                    $toData[":".$index] = $value;
                }

                foreach($toData as $i => $v) {
                    if(strpos($query, $i) !== false) {
                        //sprawdzanie czy wartosc istnieje w zapytaniu
                        $stmt->bindValue($i, $v);
                    }
                }
            }

            $stmt->execute();
            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            $stmt->closeCursor();
            unset($stmt);
        
        } catch(\Exception $e){
                 exit('Wystapil blad w zapytaniu: <br /> ' . $query . '<br /> MYSQL_ERROR: <br /> ' . $e->getMessage());

            $results = [];
        }

        if($oneRows == 1) {
            return $results[0];
        }
        return $results;
    }

    /**
     * Uzywac po insercie zeby pobrac id ostatniego insterta w sesji.
     *
     * @return mixed ostatnie id (PRIMARY KEY) wsadzone w bazie dla danej sesji
     */
    public static function selectLastId()
    {
        $row = self::select('SELECT LAST_INSERT_ID() AS id');

        return $row[0]['id'];
    }

    /**
     * @param $table
     * @return bool
     */
    public static function truncate($table) {
        return Query::uidQuery("TRUNCATE TABLE {$table}");
    }


}
