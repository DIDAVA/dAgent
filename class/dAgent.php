<?php

/*!
 * dAgent v1.0.0
 * (c) 2020 DIDAVA Media
 * Released under the MIT License.
 * https://www.didava.ir
 * https://github.com/DIDAVA/dAgent
 */

class dAgent {
  private $headerKey;
  private $headerStr;

  function __construct() {
    $this->headerKey = null;
    $this->headerStr = null;
    
    $this->isUtility = false;
    $this->isPhone = false;
    $this->isTablet = false;
    $this->isDesktop = false;
    $this->data = (object) [
      'type' => null, 
      'brand' => null, 
      'browser' => null, 
      'os' => null, 
      'arch' => null 
    ];
    
    $this->getHeader();
    if (!empty($this->headerStr)) {
      $this->scanUtils();
      if (!$this->isUtility) $this->scanPhones();
      if (!$this->isPhone) $this->scanTablets();
      if (!$this->isTablet) {
        $this->isDesktop = true;
        $this->data->type = 'Desktop';
      }
      if ($this->isPhone || $this->isTablet) {
        $this->scanMobileBrowsers();
        $this->scanMobileOSs();
      }
      else {
        $this->scanDesktopBrowsers();
        $this->scanDesktopOSs();
      }
      $this->scanArch();
    }
  }

  private function getDB($db) {
    return json_decode( file_get_contents("./db/$db.json") );
  }

  private function scan($pattern) {
    return (bool) preg_match( sprintf('#%s#is', $pattern), $this->headerStr );
  }

  private function getHeader() {
    foreach ($this->getDB('headers') as $header) {
      if (array_key_exists($header, $_SERVER)) {
        $this->headerKey = $header;
        $this->headerStr = $_SERVER[$header];
        break;
      }
    }
  }

  private function scanUtils() {
    foreach ($this->getDB('utilities') as $util => $pattern) {
      if ($this->scan($pattern)) {
        $this->data->type = $util;
        break;
      }
    }
  }

  private function scanPhones() {
    foreach ($this->getDB('phones') as $brand => $pattern) {
      if ($this->scan($pattern)) {
        $this->isPhone = true;
        $this->data->type = 'Phone';
        $this->data->brand = $brand;
        break;
      }
    }
  }

  private function scanTablets() {
    foreach ($this->getDB('tablets') as $brand => $pattern) {
      if ($this->scan($pattern)) {
        $this->isTablet = true;
        $this->data->type = 'Tablet';
        $this->data->brand = str_replace('Tablet', '', $brand);
        break;
      }
    }
  }

  private function scanBrowsers($db) {
    foreach ($db as $brand => $pattern) {
      if ($this->scan($pattern)) {
        $this->data->browser = $brand;
        break;
      }
    }
  }

  private function scanMobileBrowsers() {
    $this->scanBrowsers( $this->getDB('mobilebrowsers') );
  }

  private function scanDesktopBrowsers() {
    $this->scanBrowsers( $this->getDB('desktopbrowsers') );
  }

  private function scanOSs($db) {
    foreach ($db as $os => $pattern) {
      if ($this->scan($pattern)) {
        $this->data->os = $os;
        break;
      }
    }
  }

  private function scanMobileOSs() {
    $this->scanOSs( $this->getDB('mobileos') );
  }

  private function scanDesktopOSs() {
    $this->scanOSs( $this->getDB('desktopos') );
  }

  private function scanArch() {
    foreach ($this->getDB('architecture') as $arch => $pattern) {
      if ($this->scan($pattern)) {
        $this->data->arch = $arch;
        break;
      }
    }
  }

}