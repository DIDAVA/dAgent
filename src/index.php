<?php

/*!
 * dAgent v1.0.0
 * (c) 2020 DIDAVA Media
 * Released under the MIT License.
 * https://www.didava.ir
 * https://github.com/DIDAVA/dAgent
 */

class dAgent {
  function __construct() {
    $this->headerKey = null;
    $this->headerStr = null;
    $this->isUtility = false;
    $this->isMobile = false;
    $this->isTablet = false;
    $this->isDesktop = false;
    $this->data = (object) [ 'type' => null, 'brand' => null, 'browser' => null, 'os' => null, 'arch' => null ];
    
    $this->getHeader();
    if (!empty($this->headerStr)) {
      $this->scanUtils();
      if (!$this->isUtility) $this->scanPhones();
      if (!$this->isMobile) $this->scanTablets();
      if (!$this->isTablet) {
        $this->isDesktop = true;
        $this->data->type = 'Desktop';
      }
      if ($this->isMobile || $this->isTablet) {
        $this->scanMobileBrowsers();
        $this->scanMobileOSs();
      }
      else {
        $this->scanDesktopBrowsers();
        $this->scanDesktopOSs();
      }
      $this->scanArch();
    }

    return $this->data;
  }

  function getDB($db) {
    return json_decode( file_get_contents("./db/$db.json") );
  }

  function scan($pattern) {
    return (bool) preg_match( sprintf('#%s#is', $pattern), $this->headerStr );
  }

  function getHeader() {
    foreach ($this->getDB('headers') as $header) {
      if (array_key_exists($header, $_SERVER)) {
        $this->headerKey = $header;
        $this->headerStr = $_SERVER[$header];
        break;
      }
    }
  }

  function scanUtils() {
    foreach ($this->getDB('utilities') as $util => $pattern) {
      if ($this->scan($pattern)) {
        $this->data->type = $util;
        break;
      }
    }
  }

  function scanPhones() {
    foreach ($this->getDB('phones') as $brand => $pattern) {
      if ($this->scan($pattern)) {
        $this->isMobile = true;
        $this->data->type = 'Mobile';
        $this->data->brand = $brand;
        break;
      }
    }
  }

  function scanTablets() {
    foreach ($this->getDB('tablets') as $brand => $pattern) {
      if ($this->scan($pattern)) {
        $this->isTablet = true;
        $this->data->type = 'Tablet';
        $this->data->brand = str_replace('Tablet', '', $brand);
        break;
      }
    }
  }

  function scanBrowsers($db) {
    foreach ($db as $brand => $pattern) {
      if ($this->scan($pattern)) {
        $this->data->browser = $brand;
        break;
      }
    }
  }

  function scanMobileBrowsers() {
    $this->scanBrowsers( $this->getDB('mobilebrowsers') );
  }

  function scanDesktopBrowsers() {
    $this->scanBrowsers( $this->getDB('desktopbrowsers') );
  }

  function scanOSs($db) {
    foreach ($db as $os => $pattern) {
      if ($this->scan($pattern)) {
        $this->data->os = $os;
        break;
      }
    }
  }

  function scanMobileOSs() {
    $this->scanOSs( $this->getDB('mobileos') );
  }

  function scanDesktopOSs() {
    $this->scanOSs( $this->getDB('desktopos') );
  }

  function scanArch() {
    foreach ($this->getDB('architecture') as $arch => $pattern) {
      if ($this->scan($pattern)) {
        $this->data->arch = $arch;
        break;
      }
    }
  }

}