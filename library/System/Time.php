<?php

  /**
  * Time Class
  * 
  * @author    Werner Pallentin <werner.pallentin@outlook.de>
  * @package   System
  */

  class Time {
    public function pluralize( $count, $text )
    {
        $v = $count . ( ( $count == 1 ) ? ( " $text" ) : ( " ${text}en" ) );
        // Aufgrund der deutschen Anpassung
        return str_replace("een", "en", $v);
    }

    public function ago( $datetime )
    {
        $interval = date_create('now')->diff( $datetime );
        $suffix = ( $interval->invert ? 'vor ' : '' );
        
        if ( $v = $interval->y >= 1 ) return $suffix . $this->pluralize( $interval->y, 'Jahr' );
        if ( $v = $interval->m >= 1 ) return $suffix . $this->pluralize( $interval->m, 'Monat' );
        if ( $v = $interval->d >= 1 ) return $suffix . $this->pluralize( $interval->d, 'Tag' );
        if ( $v = $interval->h >= 1 ) return $suffix . $this->pluralize( $interval->h, 'Stunde' );
        if ( $v = $interval->i >= 1 ) return $suffix . $this->pluralize( $interval->i, 'Minute' );
        
        return $suffix . $this->pluralize( $interval->s, 'Sekunde' );
    }
  }
