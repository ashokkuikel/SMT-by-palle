<?php

/**
 * Klasse zum Archivieren der Uptime Daten der SMT Anwedung
 * 
 * @author    Werner Pallentin <werner.pallentin@outlook.de>
 * @package   SMT
 */

class UptimeArchiver {

  public function archive($server_id = null) {
    $db = new Database('SMT-MONITOR');
    $latest_date = new DateTime('-1 week 0:0:0');
    $latest_date_str = $latest_date->format('Y-m-d 00:00:00');

    $sql_where_server = $this->createSQLWhereServer($server_id);

    $records = $db->getQuery(
      "SELECT `server_id`,`date`,`status`,`latency`
          FROM `psm_servers_uptime`
          WHERE {$sql_where_server} `date` < :latest_date", array('latest_date' => $latest_date_str), True);


    if (!empty($records)) {
      // first group all records by day and server_id
      $data_by_day = array();
      foreach ($records as $record) {
        $server_id = (int) $record['server_id'];
        $day = date('Y-m-d', strtotime($record['date']));
        if (!isset($data_by_day[$day][$server_id])) {
          $data_by_day[$day][$server_id] = array();
        }
        $data_by_day[$day][$server_id][] = $record;
      }

      // now get history data day by day
      $histories = array();
      foreach ($data_by_day as $day => $day_records) {
        foreach ($day_records as $server_id => $server_day_records) {
          $histories[] = $this->getHistoryForDay($day, $server_id, $server_day_records);
        }
      }

      // Save all
      $db->insertMultiple('psm_servers_history', $histories);
      // now remove all records from the uptime table
      $db->getQuery(
        "DELETE FROM `psm_servers_uptime` WHERE {$sql_where_server} `date` < :latest_date", array('latest_date' => $latest_date_str), false
      );
    }

    return true;
  }

  /**
   * Build a history array for a day records
   * @param string $day
   * @param int $server_id
   * @param array $day_records
   * @return array
   */
  protected function getHistoryForDay($day, $server_id, $day_records) {
    $latencies = array();
    $checks_failed = 0;

    foreach ($day_records as $day_record) {
      $latencies[] = $day_record['latency'];

      if ($day_record['status'] == 0) {
        $checks_failed++;
      }
    }
    sort($latencies, SORT_NUMERIC);

    $history = array(
      'date' => $day,
      'server_id' => $server_id,
      'latency_min' => min($latencies),
      'latency_avg' => array_sum($latencies) / count($latencies),
      'latency_max' => max($latencies),
      'checks_total' => count($day_records),
      'checks_failed' => $checks_failed,
    );
    return $history;
  }

  protected function createSQLWhereServer($server_id) {
    $sql_where_server = ($server_id !== null)
      // this is obviously not the cleanest way to implement this when using paramter binding.. sorry.
      ? ' `server_id` = ' . intval($server_id) . ' AND ' : '';

    return $sql_where_server;
  }

}

?>