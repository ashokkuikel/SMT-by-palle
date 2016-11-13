<?php

/**
 * Archivier Klasse für die SMT Anwedung
 * 
 * @author    Werner Pallentin <werner.pallentin@outlook.de>
 * @package   SMT
 */

class Archive {

  /**
   * Available archiver utils.
   * @var array $archivers
   */
  protected $archivers = array();

  /*
   * Retention period
   * @var \DateInterval $retention_period
   * @see setRetentionPeriod()
   */
  protected $retention_period;

  public function __construct() {
    
  }

  /**
   * Archive one or more servers.
   * @param int $server_id
   * @return boolean
   */
  public function archive($server_id = null) {
    $result = true;
    foreach ($this->archivers as $archiver) {
      if (!$archiver->archive($server_id)) {
        $result = false;
      }
    }
    return $result;
  }

  /**
   * Set retention period for this archive run.
   *
   * Set period to 0 to disable cleanup altogether.
   * @param \DateInterval|int $period \DateInterval object or number of days (int)
   * @return \psm\Util\Server\ArchiveManager
   */
  public function setRetentionPeriod($period) {
    if (is_object($period) && $period instanceof \DateInterval) {
      $this->retention_period = $period;
    } elseif (intval($period) == 0) {
      // cleanup disabled
      $this->retention_period = false;
    } else {
      $this->retention_period = new \DateInterval('P' . intval($period) . 'D');
    }
    return $this;
  }

  //ToDo: Aufräumen der DB implememtieren
}
