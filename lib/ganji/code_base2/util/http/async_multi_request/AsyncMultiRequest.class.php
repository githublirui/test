<?PHP

require_once dirname(__FILE__) . '/Handler.php';
require_once dirname(__FILE__) . '/Request.php';

class AsyncMultiRequest {
    public $urls = array();
    public $headers = array();
    public $options = array();
    public $callbacks = array();
    public $connectionsLimit = 8;

    public function execute() {
        if (empty($this->urls)) {
            return false;
        }
        
        $mrHandler = new MultiRequest_Handler();
        $mrHandler->setConnectionsLimit($this->connectionsLimit);
        if (!empty($this->callbacks)) {
            if (is_array($this->callbacks)) {
                foreach ($this->callbacks as $callback) {
                    $mrHandler->onRequestComplete($callback);
                }
            } else {
                $mrHandler->onRequestComplete($this->callbacks);
            }
        }

        if (is_array($this->headers) && count($this->headers) > 0) {
            $mrHandler->requestsDefaults()->addHeaders($this->headers);
        }

        if (is_array($this->options) && count($this->options) > 0) {
            $mrHandler->requestsDefaults()->addCurlOptions($this->options);
        }

        if (is_array($this->urls)) {
            foreach($this->urls as $url) {
                if (!empty($url)) {
                    $request = new MultiRequest_Request($url);
                    $mrHandler->pushRequestToQueue($request);
                }
            }
        } else {
            $request = new MultiRequest_Request($this->urls);
            $mrHandler->pushRequestToQueue($request);
        }

        $mrHandler->start();
    }
}
