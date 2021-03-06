<?php
/**
 * Autogenerated by Thrift
 *
 * DO NOT EDIT UNLESS YOU ARE SURE THAT YOU KNOW WHAT YOU ARE DOING
 */
include_once $GLOBALS['THRIFT_ROOT'].'/Thrift.php';


class WordAttr {
  static $_TSPEC;

  public $word = null;
  public $tf = null;
  public $idf = null;
  public $attr = null;

  public function __construct($vals=null) {
    if (!isset(self::$_TSPEC)) {
      self::$_TSPEC = array(
        1 => array(
          'var' => 'word',
          'type' => TType::STRING,
          ),
        2 => array(
          'var' => 'tf',
          'type' => TType::DOUBLE,
          ),
        3 => array(
          'var' => 'idf',
          'type' => TType::DOUBLE,
          ),
        4 => array(
          'var' => 'attr',
          'type' => TType::STRING,
          ),
        );
    }
    if (is_array($vals)) {
      if (isset($vals['word'])) {
        $this->word = $vals['word'];
      }
      if (isset($vals['tf'])) {
        $this->tf = $vals['tf'];
      }
      if (isset($vals['idf'])) {
        $this->idf = $vals['idf'];
      }
      if (isset($vals['attr'])) {
        $this->attr = $vals['attr'];
      }
    }
  }

  public function getName() {
    return 'WordAttr';
  }

  public function read($input)
  {
    $xfer = 0;
    $fname = null;
    $ftype = 0;
    $fid = 0;
    $xfer += $input->readStructBegin($fname);
    while (true)
    {
      $xfer += $input->readFieldBegin($fname, $ftype, $fid);
      if ($ftype == TType::STOP) {
        break;
      }
      switch ($fid)
      {
        case 1:
          if ($ftype == TType::STRING) {
            $xfer += $input->readString($this->word);
          } else {
            $xfer += $input->skip($ftype);
          }
          break;
        case 2:
          if ($ftype == TType::DOUBLE) {
            $xfer += $input->readDouble($this->tf);
          } else {
            $xfer += $input->skip($ftype);
          }
          break;
        case 3:
          if ($ftype == TType::DOUBLE) {
            $xfer += $input->readDouble($this->idf);
          } else {
            $xfer += $input->skip($ftype);
          }
          break;
        case 4:
          if ($ftype == TType::STRING) {
            $xfer += $input->readString($this->attr);
          } else {
            $xfer += $input->skip($ftype);
          }
          break;
        default:
          $xfer += $input->skip($ftype);
          break;
      }
      $xfer += $input->readFieldEnd();
    }
    $xfer += $input->readStructEnd();
    return $xfer;
  }

  public function write($output) {
    $xfer = 0;
    $xfer += $output->writeStructBegin('WordAttr');
    if ($this->word !== null) {
      $xfer += $output->writeFieldBegin('word', TType::STRING, 1);
      $xfer += $output->writeString($this->word);
      $xfer += $output->writeFieldEnd();
    }
    if ($this->tf !== null) {
      $xfer += $output->writeFieldBegin('tf', TType::DOUBLE, 2);
      $xfer += $output->writeDouble($this->tf);
      $xfer += $output->writeFieldEnd();
    }
    if ($this->idf !== null) {
      $xfer += $output->writeFieldBegin('idf', TType::DOUBLE, 3);
      $xfer += $output->writeDouble($this->idf);
      $xfer += $output->writeFieldEnd();
    }
    if ($this->attr !== null) {
      $xfer += $output->writeFieldBegin('attr', TType::STRING, 4);
      $xfer += $output->writeString($this->attr);
      $xfer += $output->writeFieldEnd();
    }
    $xfer += $output->writeFieldStop();
    $xfer += $output->writeStructEnd();
    return $xfer;
  }

}

?>

