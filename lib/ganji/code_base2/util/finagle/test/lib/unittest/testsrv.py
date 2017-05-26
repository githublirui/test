# a test server writen in python
# implements HTTP/THRIFT protocol

#Copyright Jon Berg , turtlemeat.com

import string,cgi,time,json
from BaseHTTPServer import BaseHTTPRequestHandler, HTTPServer

class MyHandler(BaseHTTPRequestHandler):

    def do_POST(self):
        #print self.path
        if self.path != '/ok':
            self.send_response(404) # raise Exception('404')
            self.end_headers()
            return

        ctype, pdict = cgi.parse_header(self.headers.getheader('content-type'))
        length = int(self.headers.getheader('content-length'))
        postvars = self.rfile.read(length)
        #
        #print postvars
        self.send_response(200)
        self.end_headers()
        #params = json.loads(postvars.decode('utf-8'))
        self.wfile.write( postvars )
        #print self.path
        return
        #    ctype, pdict = cgi.parse_header(self.headers.getheader('content-type'))

    def do_GET(self):
        #print self.path
        if not self.path.startswith( '/ok'):
            self.send_response(404) # raise Exception('404')
            self.end_headers()
            return
        self.send_response(200)
        self.end_headers()
        self.wfile.write('{"a":1,"b":2}')

def main():
    try:
        server = HTTPServer(('', 43298), MyHandler)
        #print 'started httpserver...'
        server.serve_forever()
    except KeyboardInterrupt:
        #print '^C received, shutting down server'
        server.socket.close()

if __name__ == '__main__':
    main()

