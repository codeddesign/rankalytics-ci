<?php

class Crawled_sites_Model extends CI_Model
{
    function __construct()
    {
        $this->pgsql = $this->load->database('pgsql', true);
    }

    /**
     * @param array $where
     * @param string $what
     * @return bool / array
     */
    public function getCrawledInfo( $where = array( 'keyword_id' => "", "host" => "", "crawled_date" => '' ), $what = '*' )
    {
        $query['select'] = 'SELECT ' . $what;
        $query['from']   = 'FROM "crawled_sites"';

        $condition = array();

        if (isset( $where['crawled_date'] ) AND $where['crawled_date'] != '') {
            $temp = array(
                'where_1' => '"crawled_date" <= \'' . $where['crawled_date'] . ' 23:59:59\'',
                'where_2' => '"crawled_date" >= \'' . $where['crawled_date'] . ' 00:00:00\'',
            );

            $condition['crawled_date'] = implode( ' AND ', $temp );
        }

        if (isset( $where['keyword_id'] ) AND $where['keyword_id'] != '') {
            $condition['keyword'] = '"keyword_id" = \'' . $where['keyword_id'] . '\'';
        }

        if (isset( $where['host'] ) AND $where['host'] != '') {
            $condition['host'] = '("host" = \'' . $where['host'] . '\'';
            $host_alternative  = ( stripos( $where['host'], 'www.' ) === false ) ? ( 'www.' . $where['host'] ) : ( str_ireplace( 'www.', '', $where['host'] ) );
            $condition['host'] .= ' OR "host" =\'' . $host_alternative . '\')';
        }

        if (count( $condition ) > 0) {
            $query['condition'] = 'WHERE ' . implode( ' AND ', $condition );
        }

        $query['order'] = 'ORDER BY "crawled_date" desc';
        $query['limit'] = 'LIMIT 1';
        $q              = implode( ' ', $query );

        $rows = $this->pgsql->query( $q )->result_array();

        if ($rows == false) {
            return false;
        }
        
        return $rows['0'];
    }

    /**
     * @param $where
     * @return bool / array
     */
    public function getRank7Days($where) {
        $where['crawled_date'] = date('Y-m-d', strtotime('-7 days'));

        $temp = $this->getCrawledInfo($where, 'rank');
        if (isset( $temp['rank'] )) {
            return $temp['rank'];
        }

        return '';
    }

    /**
     * @param array $where
     * @return bool / array
     */
    public function getRank28Days($where = array('keyword_id' => '', 'host' => '')) {
        $where['crawled_date'] = date('Y-m-d', strtotime('-28 days'));

        $temp = $this->getCrawledInfo($where, '*');
        if (isset( $temp['rank'] )) {
            return $temp['rank'];
        }

        return '';
    }

    public static function getRankPercentage($rank, $volume) {
        $defaultReturn = 'Low Rank';

        switch ($rank) {
            case 1:
                $percent = 0.30;
                break;
            case 2:
                $percent = 0.16;
                break;
            case 3:
                $percent = 0.10;
                break;
            case 4:
                $percent = 0.08;
                break;
            case 5:
                $percent = 0.06;
                break;
            case 6:
                $percent = 0.04;
                break;
            case 7:
                $percent = 0.03;
                break;
            case 8:
                $percent = 0.03;
                break;
            case 9:
                $percent = 0.02;
                break;
            case 10:
                $percent = 0.02;
                break;
            case 11:
                $percent = 0.01;
                break;
            case 12:
                $percent = 0.007;
                break;
            case 13:
                $percent = 0.007;
                break;
            case 14:
                $percent = 0.006;
                break;
            case 15:
                $percent = 0.004;
                break;
            case 16:
                $percent = 0.0035;
                break;
            case 17:
                $percent = 0.0033;
                break;
            case 18:
                $percent = 0.0027;
                break;
            case 19:
                $percent = 0.0027;
                break;
            case 20:
                $percent = 0.0029;
                break;
            case 21:
                $percent = 0.001;
                break;
            case 22:
                $percent = 0.001;
                break;
            case 23:
                $percent = 0.0008;
                break;
            case 24:
                $percent = 0.0006;
                break;
            case 25:
                $percent = 0.0006;
                break;
            case 26:
                $percent = 0.0005;
                break;
            case 27:
                $percent = 0.0005;
                break;
            case 28:
                $percent = 0.0005;
                break;
            case 29:
                $percent = 0.0004;
                break;
            case 30:
                $percent = 0.0006;
                break;
            default:
                $percent = 0;
                break;
        }

        return ($percent == 0) ? $defaultReturn : $volume * $percent;
    }

    public function findOne( array $condition ){
        $result = $this->pgsql->select('*')->from('crawled_sites')->where($condition)->limit(1)->get()->result_array();
        if(count($result)) {
            return $result[0];
        }

        return false;
    }
}