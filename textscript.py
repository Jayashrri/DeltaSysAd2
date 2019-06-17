#!/usr/bin/env python3

import requests
import random
import webbrowser

idno = random.randint(1,9999)
cpu = random.randint(1,30)
memory = random.randint(1,400)
timereq = random.randint(0,3600)

url = "http://192.168.0.103:85/cernserver.php"
values = {}
values['ID'] = idno
values['CPURequired'] = cpu
values['MemoryRequired'] = memory
values['TimeRequired'] = timereq

r = requests.post(url,values)
with open("results.html", "wb") as f:
    f.write(r.content)
webbrowser.open("results.html")