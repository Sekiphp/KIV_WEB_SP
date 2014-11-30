<?php
  error_reporting(E_ALL);
  date_default_timezone_set('Europe/Prague');
  header("Content-Type: text/html; charset=UTF-8");
  require_once 'functions.inc.php';
  
  if(session_id() == ''){
    session_start();
  }
  
  # ---------- Definice vsech tabulek z SRBD ----------
  define("TABLE_FIRMY", "vr_firmy");
  define("TABLE_NAVSTEVY", "vr_navstevy");
  define("TABLE_NAVSTOS", "vr_navstevy_osoby");
  define("TABLE_OSOBY", "vr_osoby");
  define("TABLE_POZNAMKY", "vr_poznamky");
  define("TABLE_ZAMESTNANCI", "zamestnanci");
  