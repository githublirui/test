<?php
/**
 * Autogenerated by Thrift
 *
 * DO NOT EDIT UNLESS YOU ARE SURE THAT YOU KNOW WHAT YOU ARE DOING
 */
include_once $GLOBALS['THRIFT_ROOT'] . '/Thrift.php';


$GLOBALS['E_EventAction'] = array(
  'kNewContent' => 1,
  'kDelContent' => 2,
  'kUpdateContent' => 4,
);

final class EventAction {
  const kNewContent = 1;
  const kDelContent = 2;
  const kUpdateContent = 4;
  static public $__names = array(
    1 => 'kNewContent',
    2 => 'kDelContent',
    4 => 'kUpdateContent',
  );
}

class OrgEvent {
  static $_TSPEC;

  public $source = null;
  public $act = null;
  public $time_out = null;
  public $id = null;
  public $content = null;
  public $op_timestamp = null;
  public $op_name = null;

  public function __construct($vals=null) {
    if (!isset(self::$_TSPEC)) {
      self::$_TSPEC = array(
        1 => array(
          'var' => 'source',
          'type' => TType::STRING,
          ),
        2 => array(
          'var' => 'act',
          'type' => TType::I32,
          ),
        3 => array(
          'var' => 'time_out',
          'type' => TType::I32,
          ),
        4 => array(
          'var' => 'id',
          'type' => TType::I64,
          ),
        5 => array(
          'var' => 'content',
          'type' => TType::STRING,
          ),
        6 => array(
          'var' => 'op_timestamp',
          'type' => TType::I32,
          ),
        7 => array(
          'var' => 'op_name',
          'type' => TType::STRING,
          ),
        );
    }
    if (is_array($vals)) {
      if (isset($vals['source'])) {
        $this->source = $vals['source'];
      }
      if (isset($vals['act'])) {
        $this->act = $vals['act'];
      }
      if (isset($vals['time_out'])) {
        $this->time_out = $vals['time_out'];
      }
      if (isset($vals['id'])) {
        $this->id = $vals['id'];
      }
      if (isset($vals['content'])) {
        $this->content = $vals['content'];
      }
      if (isset($vals['op_timestamp'])) {
        $this->op_timestamp = $vals['op_timestamp'];
      }
      if (isset($vals['op_name'])) {
        $this->op_name = $vals['op_name'];
      }
    }
  }

  public function getName() {
    return 'OrgEvent';
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
            $xfer += $input->readString($this->source);
          } else {
            $xfer += $input->skip($ftype);
          }
          break;
        case 2:
          if ($ftype == TType::I32) {
            $xfer += $input->readI32($this->act);
          } else {
            $xfer += $input->skip($ftype);
          }
          break;
        case 3:
          if ($ftype == TType::I32) {
            $xfer += $input->readI32($this->time_out);
          } else {
            $xfer += $input->skip($ftype);
          }
          break;
        case 4:
          if ($ftype == TType::I64) {
            $xfer += $input->readI64($this->id);
          } else {
            $xfer += $input->skip($ftype);
          }
          break;
        case 5:
          if ($ftype == TType::STRING) {
            $xfer += $input->readString($this->content);
          } else {
            $xfer += $input->skip($ftype);
          }
          break;
        case 6:
          if ($ftype == TType::I32) {
            $xfer += $input->readI32($this->op_timestamp);
          } else {
            $xfer += $input->skip($ftype);
          }
          break;
        case 7:
          if ($ftype == TType::STRING) {
            $xfer += $input->readString($this->op_name);
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
    $xfer += $output->writeStructBegin('OrgEvent');
    if ($this->source !== null) {
      $xfer += $output->writeFieldBegin('source', TType::STRING, 1);
      $xfer += $output->writeString($this->source);
      $xfer += $output->writeFieldEnd();
    }
    if ($this->act !== null) {
      $xfer += $output->writeFieldBegin('act', TType::I32, 2);
      $xfer += $output->writeI32($this->act);
      $xfer += $output->writeFieldEnd();
    }
    if ($this->time_out !== null) {
      $xfer += $output->writeFieldBegin('time_out', TType::I32, 3);
      $xfer += $output->writeI32($this->time_out);
      $xfer += $output->writeFieldEnd();
    }
    if ($this->id !== null) {
      $xfer += $output->writeFieldBegin('id', TType::I64, 4);
      $xfer += $output->writeI64($this->id);
      $xfer += $output->writeFieldEnd();
    }
    if ($this->content !== null) {
      $xfer += $output->writeFieldBegin('content', TType::STRING, 5);
      $xfer += $output->writeString($this->content);
      $xfer += $output->writeFieldEnd();
    }
    if ($this->op_timestamp !== null) {
      $xfer += $output->writeFieldBegin('op_timestamp', TType::I32, 6);
      $xfer += $output->writeI32($this->op_timestamp);
      $xfer += $output->writeFieldEnd();
    }
    if ($this->op_name !== null) {
      $xfer += $output->writeFieldBegin('op_name', TType::STRING, 7);
      $xfer += $output->writeString($this->op_name);
      $xfer += $output->writeFieldEnd();
    }
    $xfer += $output->writeFieldStop();
    $xfer += $output->writeStructEnd();
    return $xfer;
  }

}

class ThriftCallback {
  static $_TSPEC;

  public $host = null;
  public $port = null;

  public function __construct($vals=null) {
    if (!isset(self::$_TSPEC)) {
      self::$_TSPEC = array(
        1 => array(
          'var' => 'host',
          'type' => TType::STRING,
          ),
        2 => array(
          'var' => 'port',
          'type' => TType::I32,
          ),
        );
    }
    if (is_array($vals)) {
      if (isset($vals['host'])) {
        $this->host = $vals['host'];
      }
      if (isset($vals['port'])) {
        $this->port = $vals['port'];
      }
    }
  }

  public function getName() {
    return 'ThriftCallback';
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
            $xfer += $input->readString($this->host);
          } else {
            $xfer += $input->skip($ftype);
          }
          break;
        case 2:
          if ($ftype == TType::I32) {
            $xfer += $input->readI32($this->port);
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
    $xfer += $output->writeStructBegin('ThriftCallback');
    if ($this->host !== null) {
      $xfer += $output->writeFieldBegin('host', TType::STRING, 1);
      $xfer += $output->writeString($this->host);
      $xfer += $output->writeFieldEnd();
    }
    if ($this->port !== null) {
      $xfer += $output->writeFieldBegin('port', TType::I32, 2);
      $xfer += $output->writeI32($this->port);
      $xfer += $output->writeFieldEnd();
    }
    $xfer += $output->writeFieldStop();
    $xfer += $output->writeStructEnd();
    return $xfer;
  }

}

class EventConcern {
  static $_TSPEC;

  public $source = null;
  public $action_mask = null;

  public function __construct($vals=null) {
    if (!isset(self::$_TSPEC)) {
      self::$_TSPEC = array(
        1 => array(
          'var' => 'source',
          'type' => TType::STRING,
          ),
        2 => array(
          'var' => 'action_mask',
          'type' => TType::I32,
          ),
        );
    }
    if (is_array($vals)) {
      if (isset($vals['source'])) {
        $this->source = $vals['source'];
      }
      if (isset($vals['action_mask'])) {
        $this->action_mask = $vals['action_mask'];
      }
    }
  }

  public function getName() {
    return 'EventConcern';
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
            $xfer += $input->readString($this->source);
          } else {
            $xfer += $input->skip($ftype);
          }
          break;
        case 2:
          if ($ftype == TType::I32) {
            $xfer += $input->readI32($this->action_mask);
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
    $xfer += $output->writeStructBegin('EventConcern');
    if ($this->source !== null) {
      $xfer += $output->writeFieldBegin('source', TType::STRING, 1);
      $xfer += $output->writeString($this->source);
      $xfer += $output->writeFieldEnd();
    }
    if ($this->action_mask !== null) {
      $xfer += $output->writeFieldBegin('action_mask', TType::I32, 2);
      $xfer += $output->writeI32($this->action_mask);
      $xfer += $output->writeFieldEnd();
    }
    $xfer += $output->writeFieldStop();
    $xfer += $output->writeStructEnd();
    return $xfer;
  }

}
