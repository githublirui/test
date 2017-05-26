<?php
include_once  $GLOBALS['THRIFT_ROOT'] . '/Thrift.php';
include_once CODE_BASE2 . '/util/event_platform/inc/event_proxy/event_proxy_types.php';

interface TSReportEventIf {
  public function ReportEvent($event);
  public function ReportEventFinish($event_id);
}

class TSReportEventClient implements TSReportEventIf {
  protected $input_ = null;
  protected $output_ = null;

  protected $seqid_ = 0;

  public function __construct($input, $output=null) {
    $this->input_ = $input;
    $this->output_ = $output ? $output : $input;
  }

  public function ReportEvent($event)
  {
    $this->send_ReportEvent($event);
  }

  public function send_ReportEvent($event)
  {
    $args = new TSReportEvent_ReportEvent_args();
    $args->event = $event;
    $bin_accel = ($this->output_ instanceof TProtocol::$TBINARYPROTOCOLACCELERATED) && function_exists('thrift_protocol_write_binary');
    if ($bin_accel)
    {
      thrift_protocol_write_binary($this->output_, 'ReportEvent', TMessageType::CALL, $args, $this->seqid_, $this->output_->isStrictWrite());
    }
    else
    {
      $this->output_->writeMessageBegin('ReportEvent', TMessageType::CALL, $this->seqid_);
      $args->write($this->output_);
      $this->output_->writeMessageEnd();
      $this->output_->getTransport()->flush();
    }
  }
  public function ReportEventFinish($event_id)
  {
    $this->send_ReportEventFinish($event_id);
  }

  public function send_ReportEventFinish($event_id)
  {
    $args = new TSReportEvent_ReportEventFinish_args();
    $args->event_id = $event_id;
    $bin_accel = ($this->output_ instanceof TProtocol::$TBINARYPROTOCOLACCELERATED) && function_exists('thrift_protocol_write_binary');
    if ($bin_accel)
    {
      thrift_protocol_write_binary($this->output_, 'ReportEventFinish', TMessageType::CALL, $args, $this->seqid_, $this->output_->isStrictWrite());
    }
    else
    {
      $this->output_->writeMessageBegin('ReportEventFinish', TMessageType::CALL, $this->seqid_);
      $args->write($this->output_);
      $this->output_->writeMessageEnd();
      $this->output_->getTransport()->flush();
    }
  }
}

// HELPER FUNCTIONS AND STRUCTURES

class TSReportEvent_ReportEvent_args {
  static $_TSPEC;

  public $event = null;

  public function __construct($vals=null) {
    if (!isset(self::$_TSPEC)) {
      self::$_TSPEC = array(
        1 => array(
          'var' => 'event',
          'type' => TType::STRUCT,
          'class' => 'OrgEvent',
          ),
        );
    }
    if (is_array($vals)) {
      if (isset($vals['event'])) {
        $this->event = $vals['event'];
      }
    }
  }

  public function getName() {
    return 'TSReportEvent_ReportEvent_args';
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
          if ($ftype == TType::STRUCT) {
            $this->event = new OrgEvent();
            $xfer += $this->event->read($input);
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
    $xfer += $output->writeStructBegin('TSReportEvent_ReportEvent_args');
    if ($this->event !== null) {
      if (!is_object($this->event)) {
        throw new TProtocolException('Bad type in structure.', TProtocolException::INVALID_DATA);
      }
      $xfer += $output->writeFieldBegin('event', TType::STRUCT, 1);
      $xfer += $this->event->write($output);
      $xfer += $output->writeFieldEnd();
    }
    $xfer += $output->writeFieldStop();
    $xfer += $output->writeStructEnd();
    return $xfer;
  }

}

class TSReportEvent_ReportEventFinish_args {
  static $_TSPEC;

  public $event_id = null;

  public function __construct($vals=null) {
    if (!isset(self::$_TSPEC)) {
      self::$_TSPEC = array(
        1 => array(
          'var' => 'event_id',
          'type' => TType::I64,
          ),
        );
    }
    if (is_array($vals)) {
      if (isset($vals['event_id'])) {
        $this->event_id = $vals['event_id'];
      }
    }
  }

  public function getName() {
    return 'TSReportEvent_ReportEventFinish_args';
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
          if ($ftype == TType::I64) {
            $xfer += $input->readI64($this->event_id);
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
    $xfer += $output->writeStructBegin('TSReportEvent_ReportEventFinish_args');
    if ($this->event_id !== null) {
      $xfer += $output->writeFieldBegin('event_id', TType::I64, 1);
      $xfer += $output->writeI64($this->event_id);
      $xfer += $output->writeFieldEnd();
    }
    $xfer += $output->writeFieldStop();
    $xfer += $output->writeStructEnd();
    return $xfer;
  }

}
